<?php
/**
 * Mailer configuration object
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Tgc_StrongMail
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_StrongMail_Model_Api_Mailer_Config
{
    const XML_API_WSDL = 'tgc_strongmail/api/wsdl';
    const XML_API_USER = 'tgc_strongmail/api/username';
    const XML_API_PASSWORD = 'tgc_strongmail/api/password';
    const XML_API_COMPANY = 'tgc_strongmail/api/company';

    /**
     * StoreId for getting config values
     *
     * @var int
     */
    private $_storeId;

    /**
     * Constructor
     *
     * @param array $args 'store_id' is required argument
     * @throws InvalidArgumentException
     */
    public function __construct($args)
    {
        if (!isset($args['store_id'])) {
            throw new InvalidArgumentException("'store_id' is required argument");
        }

        $this->_storeId = $args['store_id'];
    }

    /**
     * Get Store ID.
     *
     * @return int
     */
    protected function _getStoreId()
    {
        return $this->_storeId;
    }

    /**
     * Returns WSDL URL
     *
     * @return string
     */
    public function getWsdl()
    {
        return Mage::getStoreConfig(self::XML_API_WSDL, $this->_getStoreId());
    }

    /**
     * Returns API username
     *
     * @return string
     */
    public function getUsername()
    {
        return Mage::getStoreConfig(self::XML_API_USER, $this->_getStoreId());
    }

    /**
     * Returns API password
     *
     * @return string
     */
    public function getPassword()
    {
        return Mage::getStoreConfig(self::XML_API_PASSWORD, $this->_getStoreId());
    }

    /**
     * Returns API company
     *
     * @return string
     */
    public function getCompany()
    {
        return Mage::getStoreConfig(self::XML_API_COMPANY, $this->_getStoreId());
    }
}
