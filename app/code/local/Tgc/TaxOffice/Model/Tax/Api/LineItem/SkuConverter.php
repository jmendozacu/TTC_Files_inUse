<?php
/**
 * Model for converting products into TaxOffice webservice's SKUs.
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Tgc_TaxOffice
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_TaxOffice_Model_Tax_Api_LineItem_SkuConverter
    implements Tgc_TaxOffice_Model_Tax_Api_LineItem_SkuConverterInterface
{
    /**
     * MediaFormat attribute option names association with webservice's SKUs.
     *
     * @var array
     */
    protected $_skus = array(
        'DVD' => 'IN-PD',
        'CD' => 'IN-PC',
        'Audio Download' => 'IN-DA',
        'Video Download' => 'IN-DV',
        'CD Soundtrack' => 'IN-PC',
        'Soundtrack Download' => 'IN-DT'
    );

    const GIFTCARD_SKU = 'GC-US';

    /**
     * Returns TaxOffice webservice's SKU for product
     *
     * @param Mage_Sales_Model_Quote_Item $item
     * @return string|null
     */
    public function getSku($item)
    {
        if ($item->getProductType() == Enterprise_GiftCard_Model_Catalog_Product_Type_Giftcard::TYPE_GIFTCARD) {
            return self::GIFTCARD_SKU;
        }

        $product = null;
        if ($item->getProductType() == Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE) {
            foreach ($item->getChildren() as $childItem) {
                /* @var $childItem Mage_Sales_Model_Quote_Item */
                $product = $childItem->getProduct();
            }
        } else {
            $product = $item->getProduct();
        }

        if ($product->getMediaFormat()) {
            $mediaFormatText = $this->_getProductMediaFormatOptionText($product);
            if (isset($this->_skus[$mediaFormatText])) {
                return $this->_skus[$mediaFormatText];
            }
        }

        return null;
    }

    /**
     * Gets MediaFormat attribute's option text by option ID from the product.
     *
     * @param Mage_Catalog_Model_Product $product
     * @return mixed
     */
    protected function _getProductMediaFormatOptionText($product)
    {
        return $product->getResource()->getAttribute('media_format')->getSource()->getOptionText(
            $product->getMediaFormat()
        );
    }
}
