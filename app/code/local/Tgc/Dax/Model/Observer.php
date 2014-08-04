<?php
/**
 * Dax observer
 *
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Dax
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Dax_Model_Observer
{
    protected $_connection;
    private   $_ruleTable;
    private   $_rateMapping;

    const RULE_METHOD_SUFFIX = '(Promotional Rate)  ';
    const METHOD_DESCRIPTION = 'Promotional Shipping Price';

    /**
     *
     */
    public function __construct()
    {
        /** @var _connection Magento_Db_Adapter_Pdo_Mysql */
        $this->_connection  = Mage::getSingleton('core/resource')->getConnection('write');
        $this->_ruleTable   = Mage::getResourceModel('salesrule/rule')->getMainTable();
        $this->_rateMapping = Mage::helper('tgc_dax')->getFlatRateMethodsForRules();
    }

    /**
     * Observe after shipping rates are collected
     * and update the shipping rate price if necessary
     *
     * @param $observer
     */
    public function updateShippingPrice($observer)
    {
        //return if not shipping address or no info set yet
        $address = $observer->getEvent()->getQuoteAddress();
        if ($address->getAddressType() != 'shipping') {
            return;
        }
        if (!$address->getRegion() && !$address->getRegionId() && !$address->getPostcode() && !$address->getCountryId()) {
            return;
        }

        $checkout = Mage::getSingleton('checkout/session');
        if (!$checkout->hasQuote()) {
            return;
        }
        $quote = $checkout->getQuote();

        //return if already updated
        if (Mage::registry('shipping_updated_quote_' . $quote->getId())) {
            return;
        }
        Mage::register('shipping_updated_quote_' . $quote->getId(), true, true);

        //return if no rules applied to quote
        if (!$quote->getAppliedRuleIds()) {
            return;
        }

        //check if any applied rules have special shipping price
        $appliedIds = explode(',', $quote->getAppliedRuleIds());

        //get the lowest applicable special ship price
        $specialShipData = array_filter($this->_getSpecialShipData($appliedIds));

        //return if not
        if (empty($specialShipData)) {
            return;
        }

        $rulePrice = isset($specialShipData['shipping_amount']) ? $specialShipData['shipping_amount'] : null;
        $shipType  = isset($specialShipData['shipping_type']) ? strtolower($specialShipData['shipping_type']) : 'not_selected';

        if (!$this->_rateExists($shipType)) {
            return;
        }

        //update the free shipping rate to reflect the promotional rate
        $wasApplied = false;
        $rates = $address->getShippingRatesCollection();
        foreach ($rates as $rate) {
            $wasApplied = $this->_updateRate($rate, $rulePrice, $shipType);
            if ($wasApplied) {
                break(1);
            }
        }
        if (!$wasApplied) {
            //if we were going to add promo rates in we would do it here
            return;
        }

        $rates = $address->getGroupedAllShippingRates();
        foreach ($rates as $carrier) {
            foreach ($carrier as $rate) {
                $wasApplied = $this->_updateRate($rate, $rulePrice, $shipType);
                if ($wasApplied) {
                    break(2);
                }
            }
        }

        //recalculate totals, skipping this observer since it is registered as updated
        $quote->setTotalsCollectedFlag(false)
            ->collectTotals();
    }

    /**
     * Update the shipping rate with new price and title
     *
     * @param $rate
     * @param float $rulePrice
     * @param string $shipType
     * @return bool
     */
    private function _updateRate($rate, $rulePrice, $shipType)
    {
        if ($this->_rateMatchesShipType($rate, $shipType)) {
            $rate->setPrice($rulePrice);
            $rate->setMethodTitle($rate->getMethodTitle() . ' ' . self::RULE_METHOD_SUFFIX);
            $rate->setMethodDescription(self::METHOD_DESCRIPTION);
            $rate->save();
            return true;
        }

        return false;
    }

    private function _rateExists($name)
    {
        return isset($this->_rateMapping[$name]);
    }

    /**
     * Check if the rate ship type matches the promotional ship type
     *
     * @param $rate
     * @param $type
     * @return bool
     */
    private function _rateMatchesShipType($rate, $type)
    {
        $code = $rate->getCode();

        if ($this->_rateMapping[$type] == $code) {
            return true;
        }

        return false;
    }

    private function _getSpecialShipData(array $appliedIds)
    {
        $select = $this->_connection->select()
            ->from($this->_ruleTable, array('shipping_amount', 'shipping_type'))
            ->where('rule_id IN(?)', $appliedIds)
            ->where('shipping_amount IS NOT NULL')
            ->where('is_imported = 1')
            ->order('shipping_amount asc')
            ->limit(1);

        return (array)$this->_connection->fetchRow($select);
    }

    /**
     * Adds the extra form fields into the admin coupon form
     *
     * @param $observer
     */
    public function addCouponFormFields($observer)
    {
        $form  = $observer->getEvent()->getForm();
        $model = Mage::registry('current_promo_quote_rule');

        $fieldset = $form->addFieldset('additional_fieldset',
            array('legend' => Mage::helper('salesrule')->__('Fixed Rate Shipping Information'))
        );

        if ($model->getIsImported()) {
            $fieldset->addField('is_imported', 'hidden', array(
                'name' => 'is_imported',
            ));

            $fieldset->addField('shipping_amount', 'text', array(
                'name'     => 'shipping_amount',
                'required' => false,
                'class'    => 'validate-not-negative-number',
                'label'    => Mage::helper('tgc_dax')->__('Shipping Amount'),
                'note'     => Mage::helper('tgc_dax')->__('Enter a fixed rate shipping charge for this coupon'),
            ));

            $fieldset->addField('shipping_type', 'text', array(
                'name'     => 'shipping_type',
                'required' => false,
                'label'    => Mage::helper('tgc_dax')->__('Shipping Type'),
                'note'     => Mage::helper('tgc_dax')->__('Enter a fixed rate shipping method for this coupon'),
            ));
        }

        $form->setValues($model->getData());
    }

    /**
     * Validation for extra form fields for _beforeSave event
     *
     * @param $observer
     */
    public function validateCouponFormFields($observer)
    {
        $rule = $observer->getEvent()->getRule();
        if (!$rule->getIsImported()) {
            return;
        }

        $shipMethods = Mage::helper('tgc_dax')->getFlatRateMethodsForRules();
        $type = strtolower($rule->getShippingType());
        $price = $rule->getShippingAmount();
        if (!empty($type) && !isset($shipMethods[$type])) {
            $message = Mage::helper('tgc_dax')->__(
                'The shipping type: %s is not mapped to any shipping method',
                $type
            );
            Mage::throwException($message);
        }

        if (trim($price) == '') {
            $rule->setShippingAmount(null);
        } else if (!empty($price) && $price < 0.0001 && empty($type)) {
            $message = Mage::helper('tgc_dax')->__(
                'If you want to set free shipping without specifying a shipping method, use the \'Free Shipping\' dropdown in the Actions tab'
            );
            Mage::throwException($message);
        }
    }

    public function addColumnsToGrid($observer)
    {
        $fullActionName = Mage::app()->getFrontController()->getAction()->getFullActionName();
        if(in_array($fullActionName, array('adminhtml_sales_order_index','adminhtml_sales_order_grid'))) {
            $collection = $observer->getEvent()->getOrderGridCollection();
            $resource   = $collection->getResource();
            $collection->getSelect()->joinLeft(
                array('orders' => $resource->getTable('sales/order')),
                'main_table.entity_id = orders.entity_id',
                array('is_exported', 'dax_received', 'dax_order_id')
            );
        }
    }

    public function checkNewsletterStatus($observer)
    {
        $subscriber = $observer->getEvent()->getSubscriber();
        $statusChanged = (bool)$subscriber->getIsStatusChanged();

        if ($statusChanged) {
            $subscriber->setNeedsExport(1);
            $subscriber->setChangeStatusAt(now());
        }
    }

    public function subscribeNewUser($observer)
    {
        $customer = $observer->getEvent()->getCustomer();
        if ($customer->isObjectNew()) {
            if (Mage::app()->getFrontController()->getRequest()->getParam('opt_out') == 1) {
                return;
            }
            $customer->setIsSubscribed(1);
        }
    }

    public function checkStatusAtLogin($observer)
    {
        if (Mage::app()->getFrontController()->getRequest()->getParam('opt_out') == 1) {
            $customer = $observer->getEvent()->getCustomer();
            $customer->setIsSubscribed(0);
            $customer->save();
        }
    }

    public function addAffiliateIdToOrder(Varien_Event_Observer $observer)
    {
        if ($affiliateId = Mage::getModel('core/cookie')->get(Tgc_CookieNinja_Model_Ninja::COOKIE_AFFILIATE_ID)) {
            $order = $observer->getEvent()->getOrder();
            $order->setAffiliateId($affiliateId);
        }
    }
}
