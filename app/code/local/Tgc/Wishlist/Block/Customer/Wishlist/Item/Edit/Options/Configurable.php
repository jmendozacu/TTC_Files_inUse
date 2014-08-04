<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Wishlist
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_Wishlist_Block_Customer_Wishlist_Item_Edit_Options_Configurable
    extends Ayasoftware_SimpleProductPricing_Catalog_Block_Product_View_Type_Configurable
{
    /**
     * Products cache
     *
     * @var array
     */
    protected $_product;

    /**
     * Item product
     *
     * @return Mage_Catalog_Model_Product
     */
    public function getProduct()
    {
        if (is_null($this->_product)) {
            $product = $this->getItem()->getProduct();
            $selectedAttributeOptions = $this->getItem()->getOptionByCode('attributes');
            if ($selectedAttributeOptions) {
                $selectedAttributeOptions = $selectedAttributeOptions->getValue();
                if ($selectedAttributeOptions && is_string($selectedAttributeOptions)) {
                    $selectedAttributeOptions = @unserialize($selectedAttributeOptions);
                    if (is_array($selectedAttributeOptions)) {
                        $defaultValues = array('super_attribute' => array());
                        foreach ($selectedAttributeOptions as $attributeId => $optionId) {
                            $defaultValues['super_attribute'][$attributeId] = $optionId;
                        }
                        $product->setPreconfiguredValues(new Varien_Object($defaultValues));
                    }
                }
            }
            $this->_product = $product;
        }
        return $this->_product;
    }

    /**
     * Returns additional values for js config, con be overriden by descedants
     *
     * @return array
     */
    protected function _getAdditionalConfig()
    {
        return array('containerId' => 'configurable_options_' . $this->getItem()->getId());
    }

    /**
     * Get JSON encoded configuration array which can be used for JS dynamic
     * price calculation depending on product options
     *
     * @return string
     */
    public function getPriceJsonConfig()
    {
        $config = array();
        if (!$this->hasOptions()) {
            return Mage::helper('core')->jsonEncode($config);
        }

        $_request = Mage::getSingleton('tax/calculation')->getRateRequest(false, false, false);
        /* @var $product Mage_Catalog_Model_Product */
        $product = $this->getProduct();
        $_request->setProductClassId($product->getTaxClassId());
        $defaultTax = Mage::getSingleton('tax/calculation')->getRate($_request);

        $_request = Mage::getSingleton('tax/calculation')->getRateRequest();
        $_request->setProductClassId($product->getTaxClassId());
        $currentTax = Mage::getSingleton('tax/calculation')->getRate($_request);

        $_regularPrice = $product->getPrice();
        $_finalPrice = $product->getFinalPrice();
        if ($product->getTypeId() == Mage_Catalog_Model_Product_Type::TYPE_BUNDLE) {
            $_priceInclTax = Mage::helper('tax')->getPrice($product, $_finalPrice, true,
                null, null, null, null, null, false);
            $_priceExclTax = Mage::helper('tax')->getPrice($product, $_finalPrice, false,
                null, null, null, null, null, false);
        } else {
            $_priceInclTax = Mage::helper('tax')->getPrice($product, $_finalPrice, true);
            $_priceExclTax = Mage::helper('tax')->getPrice($product, $_finalPrice);
        }
        $_tierPrices = array();
        $_tierPricesInclTax = array();
        foreach ($product->getTierPrice() as $tierPrice) {
            $_tierPrices[] = Mage::helper('core')->currency($tierPrice['website_price'], false, false);
            $_tierPricesInclTax[] = Mage::helper('core')->currency(
                Mage::helper('tax')->getPrice($product, (int)$tierPrice['website_price'], true),
                false, false);
        }
        $config = array(
            'productId'           => $product->getId(),
            'priceFormat'         => Mage::app()->getLocale()->getJsPriceFormat(),
            'includeTax'          => Mage::helper('tax')->priceIncludesTax() ? 'true' : 'false',
            'showIncludeTax'      => Mage::helper('tax')->displayPriceIncludingTax(),
            'showBothPrices'      => Mage::helper('tax')->displayBothPrices(),
            'productPrice'        => Mage::helper('core')->currency($_finalPrice, false, false),
            'productOldPrice'     => Mage::helper('core')->currency($_regularPrice, false, false),
            'priceInclTax'        => Mage::helper('core')->currency($_priceInclTax, false, false),
            'priceExclTax'        => Mage::helper('core')->currency($_priceExclTax, false, false),
            /**
             * @var skipCalculate
             * @deprecated after 1.5.1.0
             */
            'skipCalculate'       => ($_priceExclTax != $_priceInclTax ? 0 : 1),
            'defaultTax'          => $defaultTax,
            'currentTax'          => $currentTax,
            'idSuffix'            => '_clone',
            'oldPlusDisposition'  => 0,
            'plusDisposition'     => 0,
            'plusDispositionTax'  => 0,
            'oldMinusDisposition' => 0,
            'minusDisposition'    => 0,
            'tierPrices'          => $_tierPrices,
            'tierPricesInclTax'   => $_tierPricesInclTax,
        );

        $responseObject = new Varien_Object();
        Mage::dispatchEvent('catalog_product_view_config', array('response_object' => $responseObject));
        if (is_array($responseObject->getAdditionalOptions())) {
            foreach ($responseObject->getAdditionalOptions() as $option => $value) {
                $config[$option] = $value;
            }
        }

        return Mage::helper('core')->jsonEncode($config);
    }

    /**
     * Set item
     *
     * @return \Tgc_Wishlist_Block_Customer_Wishlist_Item_Edit_Options_Configurable
     */
    public function setItem($item)
    {
        $this->_product = null;
        $this->unsetData('allow_products');
        $this->setData('item', $item);
        return $this;
    }
}
