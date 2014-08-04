<?php
/**
 * Default helper
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Guidance
 * @package     Setup
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Price_Helper_Data extends Mage_Core_Helper_Abstract
{
    const AD_CODE_PARAM           = 'code';
    const FREE_SHIPPING_RULE_NAME = 'Free shipping by catalog code';
    const SHIPPING_RULE_PREFIX    = '$';
    const SHIPPING_RULE_SUFFIX    = ' Flat rate shipping for customer groups';

    //prefix prepended to adcode to create group name
    const GROUP_CODE_PREFIX       = 'CC-';
    const DEFAULT_CUSTOMER_GROUP_ID = 1;

    /**
     * DB connection.
     *
     * @var Varien_Adapter_Interface
     */
    protected $_connection;

    /**
     * Cached customer group ids
     */
    private $_customerGroupIds = array();

    private $_customerGroupTable;
    private $_tgcFlatRateTable;

    public function __construct()
    {
        /** @var _connection Magento_Db_Adapter_Pdo_Mysql */
        $this->_connection         = Mage::getSingleton('core/resource')->getConnection('write');
        /** @var _customerGroupTable `customer_group` */
        $this->_customerGroupTable = Mage::getResourceModel('customer/group')->getMainTable();
        /** @var _tgcFlatRateTable tgc_shipping_flat_rate */
        $this->_tgcFlatRateTable = Mage::getResourceModel('tgc_shipping/flatRate')->getMainTable();
    }

    /**
     * Load free shipping rule for catalog code pricing
     *
     * @throws DomainException If cannot load rule
     * @return Mage_SalesRule_Model_Rule
     */
    public function getFreeShippingRule()
    {
        $rule = Mage::getModel('salesrule/rule')->load(self::FREE_SHIPPING_RULE_NAME, 'name');
        if ($rule->isObjectNew()) {
            throw new DomainException('Unable to load free shipping rule.');
        }

        return $rule;
    }

    /**
     * Returns ad code processor
     *
     * @return Tgc_Price_Model_AdCode_Processor
     */
    public function getAdCodeProcessor()
    {
        return Mage::getSingleton('tgc_price/adCode_processor');
    }

    /**
     * Returns URL of price update action
     *
     * @param string $adCode If ad code definde it will be added as URL param
     * @return string
     */
    public function getPriceUpdateUrl($adCode = null)
    {
        $params = array();
        if ($adCode) {
            $params[self::AD_CODE_PARAM] = $adCode;
        }

        return Mage::getModel('core/url')->getUrl('price/adcode/update', $params);
    }

    /**
     * Returns custom pricing reset URL
     *
     * @return string
     */
    public function getPriceResetUrl()
    {
        return Mage::getModel('core/url')->getUrl('price/adcode/reset');
    }

    /**
     * Get or create customer group id for catalog code
     *
     * @param string $code the customer group code
     * @param int|bool $allowCoupons whether this group is allowed to use coupons
     * @return int group id
     * @throws InvalidArgumentException If code is 0 or empty
     */
    public function getCustomerGroupIdByCatalogCode($code, $allowCoupons = null)
    {
        $code = (int)$code;
        if (!$code) {
            throw new InvalidArgumentException('Invalid catalog code');
        }

        //return cached version if available
        if (isset($this->_customerGroupIds[$code])) {
            if (!is_null($allowCoupons) && $allowCoupons != '') {
                $data = array(
                    'customer_group_id' => $this->_customerGroupIds[$code],
                    'allow_coupons'     => intval($allowCoupons),
                );
                $this->_connection->insertOnDuplicate($this->_customerGroupTable, $data);
            }
            return $this->_customerGroupIds[$code];
        }

        $select = $this->_connection->select()
            ->from($this->_customerGroupTable)
            ->where('catalog_code = ?', $code);

        //try to get existing
        $customerGroupId = $this->_connection->fetchOne($select);

        //create new
        if ($customerGroupId === false) {
            $customerGroupId = self::DEFAULT_CUSTOMER_GROUP_ID;
        } else {
            if (!is_null($allowCoupons) && $allowCoupons != '') {
                $data = array(
                    'customer_group_id' => $customerGroupId,
                    'allow_coupons'     => intval($allowCoupons),
                );
                $this->_connection->insertOnDuplicate($this->_customerGroupTable, $data);
            }
        }

        //cache it
        $this->_customerGroupIds[$code] = $customerGroupId;

        return $customerGroupId;
    }

    /**
     * Add the promo rate to the flat table
     *
     * @param int $groupId
     * @param int $websiteId
     * @param float $shippingPrice
     */
    public function addShippingPriceToTgcFlatRateTable($groupId, $websiteId, $shippingPrice)
    {
        if (empty($shippingPrice) || $shippingPrice <  0.1) {
            return;
        }

        $data = array(
            'customer_group_id' => $groupId,
            'website_id'        => $websiteId,
            'shipping_price'    => $shippingPrice,
        );

        $this->_connection->insertOnDuplicate($this->_tgcFlatRateTable, $data);
    }

    public function getAppliedPriorityCode()
    {
        $code = Mage::getModel('core/cookie')->get(Tgc_CookieNinja_Model_Ninja::COOKIE_AD_CODE);

        if (Mage::helper('ninja/ninja')->shouldDisplayPriorityCode($code) || Mage::getSingleton('customer/session')->getHasSubmittedDefaultAdcode()) {
            return $code;
        }

        return '';
    }
}
