<?php
/**
 * API main processor model
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Tgc_TaxOffice
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_TaxOffice_Model_Tax_Api_Processor implements Tgc_TaxOffice_Model_Tax_Api_ProcessorInterface
{
    /**
     * Module's config
     *
     * @var Tgc_TaxOffice_Model_Config
     */
    private $_config;

    /**
     * SOAP client object
     *
     * @var Tgc_TaxOffice_STOWebServices
     */
    private $_client;

    /**
     * Constructor
     *
     * @param array $args 'config' is required argument
     * @throws InvalidArgumentException
     */
    public function __construct($args = array())
    {
        if (!isset($args['config'])) {
            throw new InvalidArgumentException("'config' argument is required");
        } elseif (!($args['config'] instanceof Tgc_TaxOffice_Model_Config)) {
            throw new InvalidArgumentException("'config' must be an instance of Tgc_TaxOffice_Model_Config");
        }

        $this->_config = $args['config'];

        Mage::helper('tgc_taxOffice')->includeLibraryClasses();

        $this->_client = new Tgc_TaxOffice_STOService(
            $this->_config->getWsdl(),
            array('trace' => ($this->_config->getDebugMode() ? 1 : 0),
                'exceptions' => 1,
                'features' => SOAP_SINGLE_ELEMENT_ARRAYS
            )
        );
    }

    /**
     * Returns module's config
     *
     * @return Tgc_TaxOffice_Model_Config
     */
    public function getConfig()
    {
        return $this->_config;
    }

    /**
     * Returns SOAP client
     *
     * @return Tgc_TaxOffice_STOWebServices
     */
    public function getClient()
    {
        return $this->_client;
    }

    /**
     * Calculates tax amounts and returns the result.
     *
     * @param Mage_Sales_Model_Quote_Address $address
     * @param array $items array of QuoteItems
     * @param float $shippingAmount
     * @return Tgc_TaxOffice_Model_Tax_Api_Operation_Calculate_Result
     */
    public function calculate($address, $items, $shippingAmount)
    {
        return Mage::getSingleton('tgc_taxOffice/tax_api_operation_calculate', array(
                'config' => $this->getConfig(),
                'client' => $this->getClient()
            ))
            ->execute($address, $items, $shippingAmount);

    }
}