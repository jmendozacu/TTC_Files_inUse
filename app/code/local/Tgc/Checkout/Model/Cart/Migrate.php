<?php

/**
* @author      Guidance Magento Team <magento@guidance.com>
* @category    Tgc
* @package     Cart
* @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
*/

class Tgc_Checkout_Model_Cart_Migrate extends Mage_Core_Model_Abstract
{
    const CART_MIGRATE_COOKIE_NAME = 'cart_migrate';

    protected $_product;

    protected $_media_formats = array(
        'DA',
        'DT',
        'DV',
        'PC',
        'PD',
        'PT'
    );
    const MEDIA_FORMAT_ATTR = 'media_format';

    protected function _construct()
    {
        $this->_init('tgc_checkout/cart_migrate');
    }

    public function doMigration($quote, $store_cookie)
    {
        $web_user_id = $store_cookie['userID'];

        // Pull unclaimed cart items matching web_user_id
        $collection = $this->getCollection();
        $collection->getSelect()
            ->where('web_user_id = ?', $web_user_id)
            ->where('is_claimed = 0');

        if($collection->getSize())
        {
            // Save the quote to generate a quote_id
            $quote->save();
            $quote_id = $quote->getId();
            $cart_product = Mage::getModel('checkout/cart_product_api_v2');
            $salesrule = '';

            foreach($collection as $migrate_item)
            {
                // Ad Code is repeated for every cart item so when loop finishes we will have a singular ad code
                $ad_code = $migrate_item->getAdcode();

                // Check if it's a coupon record, if so, save and continue to next row
                if($coupon = $this->_isCoupon($migrate_item->getSku()))
                {
                    $salesrule = $coupon;
                    continue;
                }

                // If it's not a sku or a coupon, skip and continue
                if(!$product = $this->_isSku($migrate_item->getSku()))
                {
                    continue;
                }

                // If we can't match the sku to the parent course id, skip and continue
                if($product->getCourseId() != substr($migrate_item->getSku(), 2))
                {
                    continue;
                }

                // If the sku prefix isn't in our media formats array, skip and continue
                if(!in_array(strtoupper(substr($migrate_item->getSku(), 0, 2)), $this->_media_formats))
                {
                    continue;
                }

                // Get media format attribute for cart submission
                $attribute = Mage::getSingleton('eav/config')->getAttribute(Mage_Catalog_Model_Product::ENTITY, self::MEDIA_FORMAT_ATTR);

                $option_id = $product->getMediaFormat();

                if($option_id)
                {
                    // i.e. array('media_format' => 13)
                    $cart_product_options = array($attribute->getAttributeId() => $option_id);
                }
                else
                {
                    $cart_product_options = array();
                }

                $data[] = array(
                    'sku' => $product->getCourseId(),
                    'qty' => $migrate_item->getQuantity(),
                    'super_attribute' => $cart_product_options
                );

            }

            // Add to cart
            if (isset($data)) {
                $cart_product->add($quote_id, $data);
            }

            // Set Ad Code
            $this->_addAdCode($ad_code);

            // Process coupon
            if($salesrule != '')
            {
                $this->_addCoupon($quote_id, $salesrule->getCode());
            }

            // Save quote and recalculate
            $quote->getShippingAddress()->setCollectShippingRates(true);
            $quote->collectTotals()
                ->save();
            // Set quote to session and set quote id
            Mage::getSingleton('checkout/session')->setQuoteId($quote_id);

            // Mark items as claimed
            foreach($collection as $migrate_item)
            {
                $migrate_item->setIsClaimed(1)
                    ->setClaimDate(Mage::getModel('core/date')->date())
                    ->save()
                ;
            }
        }
        return true;
    }

    protected function _addCoupon($quote_id, $coupon)
    {
        try {
            Mage::getModel('checkout/cart_coupon_api')->add($quote_id, $coupon);
        }
        catch(Exception $e)
        {
            // Since we are automatically building a cart based on cookie value from a  visit
            // to the previous tgc site, we will suppress error messages so as not to alarm the user.
            $file = self::getStoreConfig('dev/log/exception_file');
            Mage::log("\n" . $e->__toString(), Zend_Log::ERR, $file, true);
        }
    }

    protected function _addAdCode($code)
    {
        Mage::helper('tgc_price')->getAdCodeProcessor()
            ->changePrices($code);
        return $this;
    }

    protected function _isCoupon($coupon_code)
    {
        $return = false;
        if(strtoupper(substr($coupon_code, 0, 4)) == 'CPN-' || strtoupper(substr($coupon_code, 0, 2)) == 'CP')
        {
            $collection = Mage::getModel('salesrule/rule')->getCollection();
            $collection->getSelect()
                ->where('name like ?', '%'.$coupon_code.'%')
                ->where('is_active = ?', 1);
            $rule = $collection->getFirstItem();
            if($rule->getId())
            {
                $return = $rule;
            }
        }
        return $return;
    }

    protected function _isSku($sku)
    {
        if (is_null($this->_product)) {
            $this->_product = Mage::helper('catalog/product')->getProduct($sku, Mage::app()->getStore()->getId(), 'sku');
        }
        if($this->_product->getId()) {
            return $this->_product;
        } else {
            return false;
        }
    }
}
