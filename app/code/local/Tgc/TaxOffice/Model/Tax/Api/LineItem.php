<?php
/**
 * API LineItem wrapper object
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Tgc_TaxOffice
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_TaxOffice_Model_Tax_Api_LineItem
{
    /**
     * Module's config object
     *
     * @var Tgc_TaxOffice_Model_Config
     */
    private $_config;

    /**
     * Quote Item
     *
     * @var Mage_Sales_Model_Quote_Item
     */
    private $_item;

    /**
     * SKU converter
     *
     * @var Tgc_TaxOffice_Model_Tax_Api_LineItem_SkuConverterInterface
     */
    private $_skuConverter;

    /**
     * Constructor
     *
     * @param array $args 'config' is required argument, 'item' is required argument
     * @throws InvalidArgumentException
     */
    public function __construct($args = array())
    {
        if (!isset($args['config'])) {
            throw new InvalidArgumentException("'config' argument is required");
        } elseif (!($args['config'] instanceof Tgc_TaxOffice_Model_Config)) {
            throw new InvalidArgumentException("'config' must be an instance of Tgc_TaxOffice_Model_Config");
        }

        if (!isset($args['item'])) {
            throw new InvalidArgumentException("'item' argument is required");
        }

        if (isset($args['sku_converter'])) {
            if (!($args['sku_converter'] instanceof Tgc_TaxOffice_Model_Tax_Api_LineItem_SkuConverterInterface)) {
                throw new InvalidArgumentException(
                    "'sku_converter' must be an instance of Tgc_TaxOffice_Model_Tax_Api_LineItem_SkuConverterInterface"
                );
            }
            $this->_skuConverter = $args['sku_converter'];
        } else {
            $this->_skuConverter = Mage::getSingleton('tgc_taxOffice/tax_api_lineItem_skuConverter');
        }

        $this->_config = $args['config'];
        $this->_item = $args['item'];
    }

    /**
     * Returns assigned quote item
     *
     * @return Mage_Sales_Model_Quote_Item
     */
    public function getItem()
    {
        return $this->_item;
    }

    /**
     * Returns SKU converter
     *
     * @return Tgc_TaxOffice_Model_Tax_Api_LineItem_SkuConverterInterface
     */
    protected function _getSkuConverter()
    {
        return $this->_skuConverter;
    }

    /**
     * Converts the Quote Item into API request LineItem object
     *
     * @param bool $useBaseAmounts
     * @return Tgc_TaxOffice_LineItem
     */
    public function convertIntoRequest($useBaseAmounts = false)
    {
        $requestLineItem = new Tgc_TaxOffice_LineItem();
        $requestLineItem->Amount = ($useBaseAmounts ?
            $this->getItem()->getBaseRowTotal() - $this->getItem()->getBaseDiscountAmount() :
            $this->getItem()->getRowTotal() - $this->getItem()->getDiscountAmount());
        $requestLineItem->AvgUnitPrice = ($useBaseAmounts ? $this->getItem()->getBasePrice() : $this->getItem()->getPrice());
        $requestLineItem->ID = $this->getItem()->getId();
        $requestLineItem->Quantity = $this->getItem()->getQty();
        $requestLineItem->SKU = $this->_getSkuConverter()->getSku($this->getItem());

        return $requestLineItem;
    }
}
