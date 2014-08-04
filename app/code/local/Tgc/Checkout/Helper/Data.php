<?php
/**
 * Checkout
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Checkout
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Checkout_Helper_Data extends Mage_Core_Helper_Data
{
    private $_shippingRatesTableDisplay = 0;
    private $_TaxesTableDisplay = 0;
    private $_country_code = "";
    public $_currentItem = null;

    // Constants to use like Filter Country Code and obtain the data of International Rates
    const INTERNATIONAL_MEXICO_CANADA_CODE = "MX";
    const INTERNATIONAL_EUROPE_FAR_EAST_CODE = "FR";
    const INTERNATIONAL_OTHERS_CODE = "0";
    const TRANSCRIPT_TYPE_PHYSICAL = 'physical';
    const TRANSCRIPT_TYPE_DIGITAL = 'digital';
    const TRANSCRIPT_PHYSICAL_PREFIX = 'PT';
    const TRANSCRIPT_DIGITAL_PREFIX = 'DT';
    const TRANSCRIPT_OPTION_REGISTRY_PREFIX = 'transcript_option_sku_';

    public function getDisplayRates()
    {
        return $this->_shippingRatesTableDisplay;
    }

    public function setDisplayRates($arg)
    {
        $this->_shippingRatesTableDisplay = intval($arg);
    }

    public function getDisplayTaxes()
    {
        return $this->_TaxesTableDisplay;
    }

    public function setDisplayTaxes($arg)
    {
        $this->_TaxesTableDisplay = intval($arg);
    }

    public function getCurrentItem()
    {
        return $this->_currentItem;
    }

    public function setCurrentItem($item)
    {
        $this->_currentItem = $item;
    }

    public function getShippingRatesCollection($websiteId = null, $deliveryType = 'Standard')
    {
        if (is_null($websiteId) || !is_numeric($websiteId)) {
            $websiteId = Mage::app()->getStore()->getWebsiteId();
        }

        return Mage::getResourceModel('premiumrate_shipping/carrier_premiumrate_collection')
            ->setWebsiteFilter($websiteId)
            ->addFieldToFilter('delivery_type', array('eq' => $deliveryType))
            ->setOrder('price_from_value', 'ASC');
    }

    public function getShippingRatesByCountryCode($country_code = null)
    {
        $shippingRates = $this->getShippingRatesCollection();
        if (is_null($country_code)) {
            $shippingRates->setCountryFilter($this->getCountryCode());
        } else {
            $shippingRates->setCountryFilter($country_code);
        }
        return $shippingRates;
    }

    public function getCountryCode()
    {
        switch (Mage::app()->getWebsite()->getName()) {
            case "US":
                $this->_country_code = "US";
                break;
            case "UK":
                $this->_country_code = "GB";
                break;
            case "Australia":
                $this->_country_code = "AU";
                break;
            default:
                $this->_country_code = "US";
                break;
        }
        return $this->_country_code;
    }

    public function getMexAndCanInternationalShippingRates()
    {
        return $this->getShippingRatesByCountryCode(Tgc_Checkout_Helper_Data::INTERNATIONAL_MEXICO_CANADA_CODE);
    }

    public function getEurAndFEaInternationalShippingRates()
    {
        return $this->getShippingRatesByCountryCode(Tgc_Checkout_Helper_Data::INTERNATIONAL_EUROPE_FAR_EAST_CODE);
    }

    public function getOthersInternationalShippingRates()
    {
        return $this->getShippingRatesByCountryCode(Tgc_Checkout_Helper_Data::INTERNATIONAL_OTHERS_CODE);
    }

    public function getInternationalShippingRates()
    {
        $mexAndCanShippingRates = $this->getMexAndCanInternationalShippingRates();
        $eurAndFarEastShippingRates = $this->getEurAndFEaInternationalShippingRates();
        $othersShippingRates = $this->getOthersInternationalShippingRates();

        $shippingRates = new Varien_Data_Collection();
        foreach ($mexAndCanShippingRates as $mexAndCanShippingRate) {
            $shippingRates->addItem($mexAndCanShippingRate);
        }
        foreach ($eurAndFarEastShippingRates as $eurAndFarEastShippingRate) {
            $shippingRates->addItem($eurAndFarEastShippingRate);
        }
        foreach ($othersShippingRates as $othersShippingRate) {
            $shippingRates->addItem($othersShippingRate);
        }

        return $shippingRates;
    }

    public function getEstimateShippingByPrice($price = null)
    {
        /** @var $quote Mage_Sales_Model_Quote */
        $quote = Mage::getSingleton('checkout/session')->getQuote();

        if ($quote->isVirtual()) {
            return false;
        }
        if (is_null($price) || !is_numeric($price)) {
            $price = $quote->getSubtotal() - $quote->getSubtotalForDigitalItem();
        }
        $collection = $this->getShippingRatesCollection()
            ->addFieldToFilter('price_from_value', array('lteq' => $price))
            ->addFieldToFilter('price_to_value', array('gteq' => $price))
            ->addFieldToFilter('dest_country_id', $quote->getShippingAddress()->getCountryId());

        if (count($collection) == 0) {
            $collection = $this->getShippingRatesCollection()
                ->addFieldToFilter('price_from_value', array('lteq' => $price))
                ->addFieldToFilter('price_to_value', array('gteq' => $price));
        }

        $estimatedShipping = $collection->getFirstItem();

        if ($estimatedShipping && $estimatedShipping->getPrice()) {
            return number_format($estimatedShipping->getPrice(), 2);
        } else {
            return false;
        }
    }

    public function getEstimatedTax()
    {
        $quote = Mage::getSingleton('checkout/session')->getQuote();
        $quote->collectTotals();
        $tax = 0;
        if ($quote->isVirtual()) {
            $tax = $quote->getBillingAddress()->getTaxAmount();
        } else {
            $tax = $quote->getShippingAddress()->getTaxAmount();
        }
        if ($tax > 0) {
            return number_format($tax, 2);
        }
        return false;
    }

    public function getEstimatedTotal($incTax = true)
    {
        $sum = Mage::getSingleton('checkout/session')->getQuote()->getGrandTotal();
        $sum = ($shipping = Mage::helper('tgc_checkout')->getEstimateShippingByPrice()) ? $sum + $shipping : $sum;
        if ($incTax) {
            $sum = ($tax = $this->getEstimatedTax()) ? $sum + $tax : $sum;
        }
        return number_format($sum, 2);
    }

    public function getOrderGrandTotal()
    {
        $quoteId = Mage::getSingleton('checkout/session')->getLastQuoteId();
        $quote = Mage::getModel('sales/quote')->load($quoteId);
        $sum = $quote->getGrandTotal();

        return number_format($sum, 2);
    }

    /**
     * Returns url that will add a transcript product to the cart.
     *
     * @param $sku
     * @param $itemId
     * @return bool|string
     */
    public function getAddTranscriptUrl($sku, $itemId, $mediaFormat)
    {
        $url = false;
        $productTranscriptType = $this->determineProductsTranscriptType($mediaFormat);

        if($productTranscriptType) {
            $product = $this->loadProductBySku($sku);
            if($product) {
                if($product->getId()) {
                    $currentUrl = Mage::helper('core/url')->getCurrentUrl();
                    $routeParams = array(
                        'uenc' => Mage::helper('core')->urlEncode($currentUrl),
                        'product' => $product->getEntityId(),
                        Mage_Core_Model_Url::FORM_KEY => Mage::getSingleton('core/session')->getFormKey(),
                        'is_transcript_item' => 1,
                        'product_sku' => $sku,
                        'transcript_parent_item_id' => $itemId,
                        'transcript_type' => $productTranscriptType,
                    );

                    $url = Mage::getUrl('checkout/cart/addtranscriptitem', $routeParams);
                }
            }
        }

        return $url;
    }

    public function hasTranscriptProductBeenAddedCart($quoteId, $currentItemId, $mediaFormat)
    {
        $transcriptType = $this->determineProductsTranscriptType($mediaFormat);
        $hasTranscriptProductBeenAddedCart = false;

        if($transcriptType) {
            $connection = Mage::getSingleton('core/resource')->getConnection('write');

            $selectTranscript = $connection->select()
                ->from('sales_flat_quote_item')
                ->where('quote_id = ?', $quoteId)
                ->where('transcript_parent_item_id = ?', $currentItemId)
                ->where('transcript_type = ?', $transcriptType);

            $stmt = $connection->query($selectTranscript);

            if($stmt->rowCount() == 1) {
                $hasTranscriptProductBeenAddedCart = true;
            }
        }

        return $hasTranscriptProductBeenAddedCart;
    }

    public function findAssociatedTranscriptProduct($currentItemId)
    {
        $transcriptItemId = null;
        $connection = Mage::getSingleton('core/resource')->getConnection('write');

        $selectTranscript = $connection->select()
            ->from('sales_flat_quote_item',array('item_id'))
            ->where('transcript_parent_item_id = ?', $currentItemId);

        $stmt = $connection->query($selectTranscript);

        if($stmt->rowCount() == 1) {
            $row = $stmt->fetch(Zend_Db::FETCH_NUM);
            $transcriptItemId = $row[0];
        }

        return $transcriptItemId;
    }

    public function getTextTranscriptProductBeenAddedToCart($associatedTranscriptProduct)
    {
        $associatedTranscriptOption = Mage::helper('tgc_checkout')->getAssociatedTranscriptOption($associatedTranscriptProduct->getSku());
        $price = $this->getTranscriptFinalPrice($associatedTranscriptProduct);

        return str_replace(
            'Include',
            '',
            sprintf($this->__('The %s for this course has been added to your cart.(%s)'),$associatedTranscriptOption->getTitle(), Mage::helper('checkout')->formatPrice(number_format($price,2)))
        );
    }

    public function getTextTranscriptProductAddToCart($associatedTranscriptProduct)
    {
        $associatedTranscriptOption = Mage::helper('tgc_checkout')->getAssociatedTranscriptOption($associatedTranscriptProduct->getSku());
        $price = $this->getTranscriptFinalPrice($associatedTranscriptProduct);

        return str_replace(
            'Include',
            '',
            sprintf($this->__('Add a %s of this course to your order for only %s'),$associatedTranscriptOption->getTitle(), Mage::helper('checkout')->formatPrice(number_format($price,2)))
        );
    }

    public function getTranscriptFinalPrice($associatedTranscriptProduct = '')
    {
        $price = null;
        if($associatedTranscriptProduct instanceof Mage_Catalog_Model_Product) {
            $quote = Mage::helper('checkout/cart')->getQuote();
            $transcriptItem = $quote->getItemByProduct($associatedTranscriptProduct);
            if($transcriptItem instanceof Mage_Sales_Model_Quote_Item) {
                $price = $transcriptItem->getPrice(); //price for items is the same as getFinalPrice
            } else { //if it can't load the item, then take final price from the product.
                $price = $associatedTranscriptProduct->getFinalPrice();
            }
        }

        return $price;
    }

    /**
     * Load product by specified sku
     *
     * @param string $sku
     * @return bool|Mage_Catalog_Model_Product
     */
    protected function loadProductBySku($sku)
    {
        /** @var $product Mage_Catalog_Model_Product */
        $product = Mage::getModel('catalog/product')
            ->setStore(Mage::app()->getStore())
            ->loadByAttribute('sku', $sku);
        if ($product && $product->getId()) {
            Mage::getModel('cataloginventory/stock_item')->assignProduct($product);
        }

        return $product;
    }

    public function getRemoveTranscriptUrl($itemId)
    {
        $url = Mage::getUrl('checkout/cart/removeoption', array("item_id" => $itemId));

        return $url;
    }

    public function getTranscriptOptions($course)
    {
        $transcriptOptions = array();
        if ($options = $course->getOptions()) {
            foreach ($options as $option) {
                if ($values = $option->getValues()) {
                    foreach ($values as $value) {
                        $transcriptOptions[] = $value;
                    }
                }
            }
        }
        return $transcriptOptions;
    }

    /**
     * Retrieves transcript product associated with a course
     *
     * @param Mage_Catalog_Model_Product $course
     * @param $mediaFormat
     * @return bool|Mage_Catalog_Model_Product
     */
    public function getAssociatedTranscriptProduct(Mage_Catalog_Model_Product $course, $mediaFormat)
    {
        $associatedTranscriptProduct = false;

        $productTranscriptType = $this->determineProductsTranscriptType($mediaFormat);
        if ($productTranscriptType) {
            $transcriptOption = $this->getTranscriptOptionBySku($course, $productTranscriptType);
            $associatedTranscriptProductSku = $transcriptOption->getSku();
            if ($associatedTranscriptProductSku) {
                $transcriptProduct = $this->loadProductBySku($associatedTranscriptProductSku);
                if ($transcriptProduct && $transcriptProduct->getId()) {
                    $associatedTranscriptProduct = $transcriptProduct;
                }
            }
        }

        return $associatedTranscriptProduct;
    }

    public function getAssociatedTranscriptOption($sku)
    {
        $associatedTranscript = false;

        $registryValue = self::TRANSCRIPT_OPTION_REGISTRY_PREFIX . $sku;
        if (Mage::registry($registryValue)) {
            return Mage::registry($registryValue);
        }

        return $associatedTranscript;
    }

    /**
     * Loads the transcript item associated with the specified transcript type.
     *
     * @param $course
     * @param string $transcriptType
     * @return bool
     */
    public function getTranscriptOptionBySku($course, $transcriptType = '')
    {
        $transcriptOption = false;
        $transcriptPrefix = $this->getTranscriptPrefixByType($transcriptType);

        if($transcriptPrefix) {
            if ($options = $course->getOptions()) {
                foreach ($options as $option) {
                    if($values = $option->getValues()) {
                        $transcriptOptions = array();
                        foreach ($values as $value) {
                            $transcriptOptions[] = $value;
                        }

                        foreach($transcriptOptions as $transcriptOption) {
                            $transcriptOptionValues = $transcriptOption->getData();
                            if(substr($transcriptOptionValues['sku'],0,2) == $transcriptPrefix) {
                                $transcriptOption = new Varien_Object($transcriptOptionValues);
                                $this->registerTranscriptOption($transcriptOptionValues['sku'], $transcriptOption);
                                return $transcriptOption;
                            }
                        }
                    }
                }
            }
        }

        return $transcriptOption;
    }

    public function registerTranscriptOption($transcriptSku = '', $transcriptOption = '')
    {
        if($transcriptSku && $transcriptOption) {
            $transcriptRegistryName = self::TRANSCRIPT_OPTION_REGISTRY_PREFIX . $transcriptSku;
            if(!Mage::registry($transcriptRegistryName)) {
                Mage::register($transcriptRegistryName, $transcriptOption);
            }
        }
    }

    /**
     * Returns the transcript prefix
     *
     * @param string $transcriptType
     * @return bool|string
     */
    public function getTranscriptPrefixByType($transcriptType = '')
    {
        $transcriptPrefix = false;
        if($transcriptType) {
            if($transcriptType) {
                if($transcriptType == self::TRANSCRIPT_TYPE_PHYSICAL) {
                    $transcriptPrefix = self::TRANSCRIPT_PHYSICAL_PREFIX;
                } elseif($transcriptType == self::TRANSCRIPT_TYPE_DIGITAL) {
                    $transcriptPrefix = self::TRANSCRIPT_DIGITAL_PREFIX;
                }
            }
        }

        return $transcriptPrefix;
    }

    public function isTranscript($itemOptions, $course)
    {

        if ($this->isTranscriptBuyed($itemOptions, $course)) {
            return true;
        }

        if ($options = $course->getOptions()) {
            return true;
        }

        return false;
    }

    public function getTranscriptOptionBuyed($itemOptions, $course)
    {
        $transcriptOptions = array();
        if (count($itemOptions)) {
            foreach ($itemOptions as $itemOption) {
                if ($options = $course->getOptions()) {
                    foreach ($options as $option) {
                        if ($itemOption['label'] == $option->getTitle()) {
                            $transcriptOptions[] = $option;
                        }
                    }
                }
            }
        }
        return $transcriptOptions;
    }

    public function isTranscriptBuyed($itemOptions, $course)
    {
        if (count($itemOptions) && is_array($itemOptions)) {
            foreach ($itemOptions as $itemOption) {
                if ($options = $course->getOptions()) {
                    foreach ($options as $option) {
                        if ($itemOption['label'] == $option->getTitle()) {
                            return true;
                        }
                    }
                }
            }
        }
        return false;
    }

    public function getTableTotals(Mage_Core_Model_Layout $layout, $collectTotals = true)
    {
        if ($collectTotals) {
            $quote = Mage::getSingleton('checkout/session')->getQuote();
            $quote->collectTotals();
            $quote->save();
        }
        return $layout->createBlock('checkout/cart_totals')->setTemplate('checkout/cart/totals.phtml')->toHtml();
    }

    public function getTableReviewTotals(Mage_Core_Model_Layout $layout, $collectTotals = true)
    {
        if ($collectTotals) {
            $quote = Mage::getSingleton('checkout/session')->getQuote();
            $quote->collectTotals();
            $quote->save();
        }
        return $layout->createBlock('checkout/cart_totals')->setTemplate('checkout/onepage/review/totals.phtml')->toHtml();
    }

    public function deriveMediaFormatFromSku($sku)
    {
        $mediaFormat = false;

        $mediaPrefix = substr($sku, 0, 2);
        switch ($mediaPrefix) {
            case 'PT':
                $mediaFormat = "Transcript Book";
                break;
            case 'DT':
                $mediaFormat = "Digital Transcript";
                break;
            case 'DA':
                $mediaFormat = "Audio Download";
                break;
            case 'PD':
                $mediaFormat = "DVD";
                break;
            case 'PC':
                $mediaFormat = "CD";
                break;
            case 'DV':
                $mediaFormat = "Video Download";
                break;
        }

        return $mediaFormat;
    }

    public function determineProductsTranscriptType($mediaFormat)
    {
        $transcriptType = false;
        $mediaFormat = strtolower($mediaFormat);

        if(in_array($mediaFormat, array("transcript book","dvd","cd","cd soundtrack"))) {
            $transcriptType = self::TRANSCRIPT_TYPE_PHYSICAL;
        } elseif(in_array($mediaFormat, array("video download","audio download","digital transcript","soundtrack download"))) {
            $transcriptType = self::TRANSCRIPT_TYPE_DIGITAL;
        }

        return $transcriptType;
    }

    public function determineTranscriptOptionsType($sku)
    {
        $transcriptType = false;
        if ($mediaFormat = $this->deriveMediaFormatFromSku($sku)) {
            $transcriptType = $this->determineProductsTranscriptType($mediaFormat);
        }

        return $transcriptType;
    }

    public function getTableItemsCart(Mage_Core_Model_Layout $layout)
    {
        /** @var $blockCart Mage_Checkout_Block_Cart */
        $blockCart = $layout->createBlock('checkout/cart');
        $blockCart->setCartTemplate("checkout/cart.phtml")
            ->setEmptyTemplate("checkout/noItems.phtml")
            ->chooseTemplate();
        $blockCart->addItemRender("simple", "checkout/cart_item_renderer", "checkout/cart/item/default.phtml")
            ->addItemRender("grouped", "checkout/cart_item_renderer_grouped", "checkout/cart/item/default.phtml")
            ->addItemRender("configurable", "checkout/cart_item_renderer_configurable", "checkout/cart/item/default.phtml");

        $items = $blockCart->getItems();
        $html = "";
        foreach ($items as $item) {
            $html .= $blockCart->getItemHtml($item);
        }
        return $html;
    }

    public function getCurrentItemMediaFormat()
    {
        return $this->deriveMediaFormatFromSku($this->getCurrentItem()->getSku());
    }

    public function isCurrentItemDigital()
    {
        return $this->determineProductsTranscriptType($this->getCurrentItemMediaFormat()) == Tgc_Checkout_Helper_Data::TRANSCRIPT_TYPE_DIGITAL? true : false;
    }

    public function getIsQtyTextBoxReadonly()
    {
        return $this->isCurrentItemDigital() ? 'readonly': '';
    }

    public function getIsQtyTextBoxReadonlyCss()
    {
        return $this->isCurrentItemDigital() ?  ' checkoutQtyReadonly' : '';
    }

    public function isPaymentBridgeContext()
    {
        return 'enterprise_pbridge' == Mage::app()->getRequest()->getRouteName();
    }
}
