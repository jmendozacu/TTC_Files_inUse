<?php
/**
 * Dax priority price entity for importexport
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Dax
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Dax_Model_Import_Entity_Price_Priority extends Tgc_Dax_Model_Import_Entity_Price_Abstract
{
    const COL_CATALOG_CODE     = 'catalog_code';
    const COL_SHIPPING_PRICE   = 'shipping_price';
    const COL_ALLOW_COUPONS    = 'allow_coupons';

    const ERROR_INVALID_CATALOG_CODE = 3;

    private $_currencyToWebsite;
    private $_table;
    private $_taxClassId;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->_table = Mage::getResourceModel('catalog/product_attribute_backend_groupprice')->getMainTable();
        $this->_taxClassId = Mage::getModel('tax/class')
                                 ->load(Mage_Tax_Model_Class::TAX_CLASS_TYPE_CUSTOMER, 'class_type')
                                 ->getClassId();

        $this->_initCurrencyToWebsite();
    }

    protected function _initErrorMessages()
    {
        parent::_initErrorMessages();

        $this->addMessageTemplate(self::ERROR_INVALID_CATALOG_CODE, 'Empty catalog code');
    }

    /**
     * Returns entity code
     *
     * @see Mage_ImportExport_Model_Import_Entity_Abstract::getEntityTypeCode()
     */
    public function getEntityTypeCode()
    {
        return 'priority_price';
    }

    /**
     * Adds permanent priority pricing attributes to permanent
     *
     * @see Tgc_Dax_Model_Import_Entity_Price_Abstract::_getPermanentAttributes()
     */
    protected function _getPermanentAttributes()
    {
        $attributes = parent::_getPermanentAttributes();

        $attributes[] = self::COL_CATALOG_CODE;
        $attributes[] = self::COL_SHIPPING_PRICE;
        $attributes[] = self::COL_ALLOW_COUPONS;

        return $attributes;
    }

    /**
     * Returns group price table name
     *
     * @see Tgc_Dax_Model_Import_Entity_Price_Abstract::_getTable()
     */
    protected function _getTable()
    {
        return $this->_table;
    }

    /**
     * Maps priority pricing rows to group pricing rows
     *
     * @see Tgc_Dax_Model_Import_Entity_Price_Abstract::_map()
     */
    protected function _map(array $row, $check = false)
    {
        $data = array();

        try {
            $groupId   = $this->_getGroupId($row);
        } catch (InvalidArgumentException $e) {
            throw new InvalidArgumentException($e->getMessage(), self::ERROR_INVALID_CATALOG_CODE, $e);
        }

        $productId = $this->_getProductId($row);
        $price     = (float)$row[self::COL_PRICE];

        foreach ($this->_getWebsiteIds($row) as $websiteId) {
            if (!$check) {
                $shippingPrice = $row[self::COL_SHIPPING_PRICE];
                Mage::helper('tgc_price')->addShippingPriceToTgcFlatRateTable($groupId, $websiteId, $shippingPrice);
            }

            $data[] = array(
                'entity_id'         => $productId,
                'all_groups'        => 0,
                'customer_group_id' => $groupId,
                'value'             => $price,
                'website_id'        => $websiteId,
            );
        }

        return $data;
    }

    private function _initCurrencyToWebsite()
    {
        $this->_currencyToWebsite = array();
        $websites = Mage::getResourceModel('core/website_collection');

        foreach ($websites as $website) {
            $currency = $website->getConfig(Mage_Directory_Model_Currency::XML_PATH_CURRENCY_BASE);
            $this->_currencyToWebsite[$currency][] = $website->getId();
        }
    }

    private function _getWebsiteIds(array $row)
    {
        $currency = $row[self::COL_CURRENCY];
        if (!isset($this->_currencyToWebsite[$currency])) {
            throw new InvalidArgumentException('Row contains unsupported currency', self::ERROR_INVALID_CURRENCY);
        }

        return $this->_currencyToWebsite[$currency];
    }

    private function _getGroupId(array $row)
    {
        return Mage::helper('tgc_price')->getCustomerGroupIdByCatalogCode($row[self::COL_CATALOG_CODE], $row[self::COL_ALLOW_COUPONS]);
    }
}
