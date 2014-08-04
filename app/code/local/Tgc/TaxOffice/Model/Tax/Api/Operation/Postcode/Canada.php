<?php
/**
 * Canada postcodes formatter for API request
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Tgc_TaxOffice
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_TaxOffice_Model_Tax_Api_Operation_Postcode_Canada
    implements Tgc_TaxOffice_Model_Tax_Api_Operation_Postcode_Interface
{
    /**
     * Formats postcode for the tax API request.
     *
     * @param string $postcode
     * @return string
     */
    public function format($postcode)
    {
        return trim(preg_replace('/[\s\r\n\t]+/is', ' ', $postcode));
    }
}
