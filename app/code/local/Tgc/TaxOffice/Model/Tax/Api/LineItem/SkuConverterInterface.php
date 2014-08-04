<?php
/**
 * Interface for converting products into TaxOffice webservice's SKUs.
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Tgc_TaxOffice
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
interface Tgc_TaxOffice_Model_Tax_Api_LineItem_SkuConverterInterface
{
    /**
     * Returns TaxOffice webservice's SKU for item
     *
     * @param Mage_Sales_Model_Quote_Item $item
     * @return string|null
     */
    public function getSku($item);
}
