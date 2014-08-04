<?php
/**
 * Abstract class for TaxOffice operations
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Tgc_TaxOffice
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_TaxOffice_Model_Tax_Api_Operation_Abstract
{
    /**
     * Config object of the module
     *
     * @var Tgc_TaxOffice_Model_Config
     */
    private $_config;

    /**
     * SOAP Client object for the webservice
     *
     * @var Tgc_TaxOffice_STOWebServices
     */
    private $_client;

    /**
     * Operation's debug info
     *
     * @var array
     */
    protected $_debugData = array();

    /**
     * Constructor
     *
     * @param array $args 'config' is required argument, 'client' is required argument
     * @throws InvalidArgumentException
     */
    public function __construct($args = array())
    {
        if (!isset($args['config'])) {
            throw new InvalidArgumentException("'config' argument is required");
        } elseif (!($args['config'] instanceof Tgc_TaxOffice_Model_Config)) {
            throw new InvalidArgumentException("'config' must be an instance of Tgc_TaxOffice_Model_Config");
        }

        if (!isset($args['client'])) {
            throw new InvalidArgumentException("'client' argument is required");
        }

        $this->_config = $args['config'];
        $this->_client = $args['client'];
    }

    /**
     * Returns module's config object
     *
     * @return Tgc_TaxOffice_Model_Config
     */
    public function getConfig()
    {
        return $this->_config;
    }

    /**
     * Returns SOAP client object
     *
     * @return Tgc_TaxOffice_STOWebServices
     */
    public function getClient()
    {
        return $this->_client;
    }

    /**
     * Prepares default configuration values for the SOAP request
     *
     * @param stdClass $request
     */
    protected function _prepareRequestDefaults($request)
    {
        $request->EntityID = $this->getConfig()->getEntityId();
        $request->DivisionID = $this->getConfig()->getDivisionId();
    }

    /**
     * Logs debug data into the file
     */
    protected function _log()
    {
        $debugLine = '';
        foreach ($this->_debugData as $key => $data) {
            $debugLine .= "{$key}: ".print_r($data, true)."\n\n";
        }

        Mage::log($debugLine, null, 'sales_tax_office.log');
    }
}
