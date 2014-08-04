<?php
/**
 * API processor interface
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Tgc_StrongMail
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
interface Tgc_TaxOffice_Model_Tax_Api_ProcessorInterface
{
    /**
     * Calculates tax amounts and returns the result.
     *
     * @param Mage_Sales_Model_Quote_Address $address
     * @param array $items array of QuoteItems
     * @param float $shippingAmount
     * @return Tgc_TaxOffice_Model_Tax_Api_Operation_Calculate_Result
     */
    public function calculate($address, $items, $shippingAmount);
}
