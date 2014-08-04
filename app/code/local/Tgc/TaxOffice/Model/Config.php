<?php
/**
 * Module's config
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Tgc_TaxOffice
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_TaxOffice_Model_Config
{
    const XML_IS_ENABLED = 'tgc_taxoffice/api/enable';
    const XML_API_WSDL = 'tgc_taxoffice/api/wsdl';
    const XML_API_ENTITY_ID = 'tgc_taxoffice/api/entity_id';
    const XML_API_DIVISION_ID = 'tgc_taxoffice/api/division_id';
    const XML_API_CUSTOMER_TYPE = 'tgc_taxoffice/api/customer_type';
    const XML_API_PROVIDE_TYPE = 'tgc_taxoffice/api/provide_type';
    const XML_API_TEST = 'tgc_taxoffice/api/test';
    const XML_API_DEBUG = 'tgc_taxoffice/api/debug';
    const XML_API_SHIP_FROM_ZIP_CODE = 'tgc_taxoffice/api/ship_from_zip_code';
    const XML_API_SHIP_FROM_COUNTRY = 'tgc_taxoffice/api/ship_from_country';
    const XML_API_SPECIFICCOUNTRY = 'tgc_taxoffice/api/specificcountry';

    /**
     * StoreId for getting config values
     *
     * @var int
     */
    private $_storeId;

    /**
     * Constructor
     *
     * @param array $args 'store_id' is required.
     */
    public function __construct($args = array())
    {
        if (isset($args['store_id'])) {
            $this->_storeId = $args['store_id'];
        }
    }

    /**
     * Get Store ID.
     *
     * @return int
     */
    public function getStoreId()
    {
        return $this->_storeId;
    }

    /**
     * Set Store ID.
     *
     * @param int $storeId
     * @return Tgc_TaxOffice_Model_Config
     */
    public function setStoreId($storeId)
    {
        $this->_storeId = $storeId;
        return $this;
    }

    /**
     * Is module enabled or not
     *
     * @return bool
     */
    public function isEnabled()
    {
        return Mage::getStoreConfig(self::XML_IS_ENABLED, $this->getStoreId());
    }

    /**
     * Returns SOAP WSDL url
     *
     * @return string
     */
    public function getWsdl()
    {
        return Mage::getStoreConfig(self::XML_API_WSDL, $this->getStoreId());
    }

    /**
     * TaxOffice EntityId
     *
     * @return string
     */
    public function getEntityId()
    {
        return Mage::getStoreConfig(self::XML_API_ENTITY_ID, $this->getStoreId());
    }

    /**
     * TaxOffice DivisionId
     *
     * @return string
     */
    public function getDivisionId()
    {
        return Mage::getStoreConfig(self::XML_API_DIVISION_ID, $this->getStoreId());
    }

    /**
     * TaxOffice CustomerType
     *
     * @return string
     */
    public function getCustomerType()
    {
        return Mage::getStoreConfig(self::XML_API_CUSTOMER_TYPE, $this->getStoreId());
    }

    /**
     * TaxOffice providerType
     *
     * @return string
     */
    public function getProvideType()
    {
        return Mage::getStoreConfig(self::XML_API_PROVIDE_TYPE, $this->getStoreId());
    }

    /**
     * Test mode or not
     *
     * @return bool
     */
    public function getTest()
    {
        return Mage::getStoreConfig(self::XML_API_TEST, $this->getStoreId());
    }

    /**
     * Debug mode or not
     *
     * @return bool
     */
    public function getDebugMode()
    {
        return Mage::getStoreConfig(self::XML_API_DEBUG, $this->getStoreId());
    }

    /**
     * ShipFrom Zip code
     *
     * @return string
     */
    public function getShipFromZipCode()
    {
        return Mage::getStoreConfig(self::XML_API_SHIP_FROM_ZIP_CODE, $this->getStoreId());
    }

    public function getShipFromCountryCode()
    {
        return Mage::getStoreConfig(self::XML_API_SHIP_FROM_COUNTRY, $this->getStoreId());
    }

    /**
     * Is tax retrieving allowed for country
     *
     * @param string $countryId
     * @return bool
     */
    public function isAllowedForCountry($countryId)
    {
        return in_array(
            $countryId,
            explode(',', Mage::getStoreConfig(self::XML_API_SPECIFICCOUNTRY, $this->getStoreId()))
        );
    }
}
