<?php
/**
 * Attribute price import entity
 *
 * Updates product price attribute
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Dax
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
abstract class Tgc_Dax_Model_Import_Entity_Price_Attribute extends Tgc_Dax_Model_Import_Entity_Price_Abstract
{
    /**
     * Price attribute
     *
     * @var Mage_Eav_Model_Entity_Attribute
     */
    private $_priceAttribute;

    private $_currencyToStores;

    /**
     * Returns code of price attribute that will be updated
     * for product
     *
     * @return string
     */
    abstract protected function _getPriceAttributeCode();

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->_initCurrencyToStores();
        $this->_initPriceAttribute();
    }

    /**
     * Returns backend table of attribute to update
     *
     * @see Tgc_Dax_Model_Import_Entity_Price_Abstract::_getTable()
     */
    final protected function _getTable()
    {
        return $this->_priceAttribute->getBackendTable();
    }

    /**
     * Maps source row to price backend table row
     *
     * @see Tgc_Dax_Model_Import_Entity_Price_Abstract::_map()
     */
    final protected function _map(array $row, $check = false)
    {
        $data = array();
        foreach ($this->_getStoreIds($row) as $storeId) {
            $data[] = array(
                'entity_type_id' => $this->_priceAttribute->getEntityTypeId(),
                'attribute_id'   => $this->_priceAttribute->getAttributeId(),
                'store_id'       => $storeId,
                'entity_id'      => $this->_getProductId($row),
                'value'          => (float)$row[self::COL_PRICE],
            );
        }

        return $data;
    }

    private function _initCurrencyToStores()
    {
        $this->_currencyToStores = array();
        $stores = Mage::getResourceModel('core/store_collection');

        foreach ($stores as $store) {
            $currency = $store->getConfig(Mage_Directory_Model_Currency::XML_PATH_CURRENCY_BASE);
            $this->_currencyToStores[$currency][] = $store->getId();
        }
    }

    private function _initPriceAttribute()
    {
        $this->_priceAttribute = Mage::getModel('eav/entity_attribute')->loadByCode(
            Mage_Catalog_Model_Product::ENTITY,
            $this->_getPriceAttributeCode()
        );
        if (!$this->_priceAttribute) {
            throw new DomainException('Unable to load price attribute by code ' . $this->_getPriceAttributeCode());
        }
    }

    private function _getStoreIds(array $row)
    {
        $currency = $row[self::COL_CURRENCY];
        if (!isset($this->_currencyToStores[$currency])) {
            throw new InvalidArgumentException("Row contains unsupported currency: $currency", self::ERROR_INVALID_CURRENCY);
        }

        return $this->_currencyToStores[$currency];
    }
}
