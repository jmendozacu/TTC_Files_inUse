<?php
/**
 * User: mhidalgo
 * Date: 25/02/14
 * Time: 16:42
 */
class Tgc_Checkout_Model_Cart extends Mage_Checkout_Model_Cart
{
    /** @var int */
    protected $_digitalProductQty = 1;

    /**
     * Update cart items information
     *
     * @param   array $data
     * @return  Mage_Checkout_Model_Cart
     */
    public function updateItems($data)
    {
        Mage::dispatchEvent('checkout_cart_update_items_before', array('cart' => $this, 'info' => $data));

        /* @var $messageFactory Mage_Core_Model_Message */
        $messageFactory = Mage::getSingleton('core/message');
        $session = $this->getCheckoutSession();
        $qtyRecalculatedFlag = false;
        $digitalItemInvalid = false;

        $mediaAttributeIds = Mage::helper('tgc_catalog')->getDigitalMediaAttributeId();

        foreach ($data as $itemId => $itemInfo) {
            $item = $this->getQuote()->getItemById($itemId);
            if (!$item) {
                continue;
            }

            if (!empty($itemInfo['remove']) || (isset($itemInfo['qty']) && $itemInfo['qty'] == '0')) {
                $this->removeItem($itemId);
                continue;
            }

            $qty = isset($itemInfo['qty']) ? (float)$itemInfo['qty'] : false;

            $mediaFormat = Mage::getModel('catalog/product')->getCollection()
                ->addAttributeToSelect('media_format')
                ->addAttributeToFilter('entity_id', $itemInfo['product_selected'])
                ->getFirstItem()
                ->getMediaFormat();

            if (in_array($mediaFormat, $mediaAttributeIds) && $qty > $this->_digitalProductQty) {
                $itemInfo['before_suggest_qty'] = $qty;
                $qty = $this->_digitalProductQty;
                $qtyRecalculatedFlag = true;
                $digitalItemInvalid = true;
            }

            // This condition determine if the user changed your product configuration
            $children = $item->getChildren();
            if (!empty($itemInfo['product_selected']) &&
                is_array($children) &&
                isset($children[0]) &&
                $children[0]->getProductId() != $itemInfo['product_selected']
            ) {

                $params = array(
                    'product' => $item->getProductId(),
                    'super_attribute' => array(
                        Mage::getResourceModel('catalog/product')->getAttribute('media_format')->getAttributeId()
                        =>
                        $mediaFormat
                    ),
                    'qty' => $qty
                );

                $this->updateItem($itemId, $params);

                if($associatedTranscriptProductId = $this->_helperTgcCheckout()->findAssociatedTranscriptProduct($itemId)) {
                    $this->removeItem($associatedTranscriptProductId); //this removes the transcript product from the cart.
                }

                continue;
            }

            if ($qty > 0) {
                $item->setQty($qty);

                $itemInQuote = $this->getQuote()->getItemById($item->getId());

                if (!$itemInQuote && $item->getHasError()) {
                    Mage::throwException($item->getMessage());
                }

                if (isset($itemInfo['before_suggest_qty']) && ($itemInfo['before_suggest_qty'] != $qty)) {
                    $qtyRecalculatedFlag = true;
                    $message = $messageFactory->notice(Mage::helper('checkout')->__('Quantity was recalculated from %d to %d', $itemInfo['before_suggest_qty'], $qty));
                    $session->addQuoteItemMessage($item->getId(), $message);
                }
            }
        }

        if($digitalItemInvalid) {
            $session->addNotice(
                Mage::helper('checkout')->__('One or more items could not be updated,<br />because the maximum quantity of a donwnloadable product is 1')
            );
        } elseif ($qtyRecalculatedFlag) {
            $session->addNotice(
                Mage::helper('checkout')->__('Some products quantities were recalculated because of quantity increment mismatch')
            );
        }

        Mage::dispatchEvent('checkout_cart_update_items_after', array('cart' => $this, 'info' => $data));
        return $this;
    }

    public function _helperTgcCheckout()
    {
        return Mage::helper('tgc_checkout');
    }

    /**
     * Initialize cart quote state to be able use it on cart page
     *
     * @return Mage_Checkout_Model_Cart
     */
    public function init()
    {
        parent::init();
        if (Mage::getSingleton('customer/session')->isLoggedIn()) {
            $quote = $this->getQuote();
            $customer = Mage::getSingleton('customer/session')->getCustomer();
            $customerShippingAddress = $customer->getDefaultShippingAddress();
            if ($customerShippingAddress) {
                $shippingAddress = $quote->getShippingAddress();
                if (!$shippingAddress->getPostcode() ||
                    $shippingAddress->getCustomerAddressId() == $customerShippingAddress->getId()
                ) {
                    $shippingAddress->importCustomerAddress($customerShippingAddress);
                }
            }
            $customerBillingAddress = $customer->getDefaultBillingAddress();
            if ($customerBillingAddress) {
                $billingAddress = $quote->getBillingAddress();
                if (!$billingAddress->getPostcode() ||
                    $billingAddress->getCustomerAddressId() == $customerBillingAddress->getId()
                ) {
                    $billingAddress->importCustomerAddress($customerBillingAddress);
                }
            }
        }


        return $this;
    }
}