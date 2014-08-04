<?php
/**
 * Postcode country formatter factory
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Tgc_TaxOffice
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_TaxOffice_Model_Tax_Api_Operation_Postcode_Factory
{
    /**
     * List of formatters for countries. Country codes should be uppercase.
     *
     * @var array
     */
    protected $_formatters = array(
        'US' => 'tgc_taxOffice/tax_api_operation_postcode_numericDefault',
        'CA' => 'tgc_taxOffice/tax_api_operation_postcode_canada'
    );

    /**
     * Cached formatter models.
     *
     * @var array
     */
    private $_formattersCache = array();

    /**
     * Returns postcode formatter for country
     *
     * @param string $countryCode
     * @throws DomainException
     *
     * @return Tgc_TaxOffice_Model_Tax_Api_Operation_Postcode_Interface|false
     */
    public function getFormatter($countryCode)
    {
        $countryCode = strtoupper($countryCode);
        if (!isset($this->_formattersCache[$countryCode])) {
            if (isset($this->_formatters[$countryCode])) {
                $this->_formattersCache[$countryCode] = Mage::getModel($this->_formatters[$countryCode]);
                if (!($this->_formattersCache[$countryCode] instanceof Tgc_TaxOffice_Model_Tax_Api_Operation_Postcode_Interface)) {
                    throw new DomainException(
                        'Postcode formatter for '.$countryCode.' country does not implement Tgc_TaxOffice_Model_Tax_Api_Operation_Postcode_Interface interface'
                    );
                }
            } else {
                $this->_formattersCache[$countryCode] = false;
            }
        }
        return $this->_formattersCache[$countryCode];
    }
}
