<?php
/**
 * Base price import entity
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Dax
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 * PREVIOUSLY USED CHECKSUM INTERFACE
 */
abstract class Tgc_Dax_Model_Import_Entity_Price_Abstract extends Tgc_Dax_Model_Import_Entity_Checksum_Base
{
    const COL_SKU       = 'sku';
    const COL_PRICE     = 'price';
    const COL_CURRENCY  = 'currency';

    const ERROR_INVALID_CURRENCY = 1;
    const ERROR_INVALID_SKU      = 2;

    const TAX_CLASS_ID = 3;
    const DEBUG_FLAG = 'tgc_dax/debug';

    private $_skuToId = array();

    private $_debug = false;
    private $_logFileName = 'import.log';

    /**
     * Returns target table name for inserting (updating)
     *
     * @return string
     */
    abstract protected function _getTable();

    /**
     * Maps row from data source to rows for inserting (updatating) to target table
     *
     * @param array $row
     * @param bool $check whether this is check or import
     * @return array<array>
     */
    abstract protected function _map(array $row, $check = false);

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->_debug = Mage::getStoreConfigFlag(self::DEBUG_FLAG);
        $this->_dataSourceModel = Mage_ImportExport_Model_Import::getDataSourceModel();
        $this->_dataSourceModel->setEntityTypeCode($this->getEntityTypeCode());
        $this->_connection      = Mage::getSingleton('core/resource')->getConnection('write');
        $this->_permanentAttributes = $this->_getPermanentAttributes();

        $this->_initErrorMessages();
    }

    /**
     * Returns permanent attributes
     *
     * @return array<string>
     */
    protected function _getPermanentAttributes()
    {
        return array(
            self::COL_SKU,
            self::COL_PRICE,
            self::COL_CURRENCY,
        );
    }

    /**
     * Initializes error messages
     */
    protected function _initErrorMessages()
    {
        $this->addMessageTemplate(self::ERROR_INVALID_CURRENCY, 'Invalid currency code');
        $this->addMessageTemplate(self::ERROR_INVALID_SKU, 'Invalid product SKU');
    }

    /**
     *
     * @return Varien_Db_Adapter_Interface
     */
    protected function _getConnection()
    {
        return $this->_connection;
    }

    /**
     * Validates row
     *
     * @see Mage_ImportExport_Model_Import_Entity_Abstract::validateRow($rowData, $rowNum)
     */
    final public function validateRow(array $rowData, $rowNum)
    {
        try {
            $this->_map($rowData, true);
            return true;
        } catch (InvalidArgumentException $e) {
            $this->addRowError($e->getCode(), $rowNum);
            return false;
        }
    }

    /**
     * Imports data
     *
     * @see Mage_ImportExport_Model_Import_Entity_Abstract::_importData()
     */
    final protected function _importData()
    {
        $s = time();
        $this->_log("Import {$this->getEntityTypeCode()}...");
        $this->_log('Bunches?');

        $result = true;
        $rowsImported = 0;
        while ($bunch = $this->_dataSourceModel->getNextBunch()) {
            $rowsImported += $this->_processBunch($bunch, $result);
        }

        $this->_log('Bunches ' . (time() - $s) . '.');

        $this->_reindexPrices();
        $this->_updatePricesOfConfigurables();

        $this->_log('.');

        return $result;
    }

    private function _processBunch(array $bunch, &$result)
    {
        $rowsImported = 0;
        foreach ($bunch as $rowNum => $rowData) {
            try {
                $this->_processRow($rowData);
                $rowsImported++;
            } catch (InvalidArgumentException $e) {
                $this->addRowError($e->getCode(), $rowNum);
                $result = false;
            }
        }

        return $rowsImported;
    }

    private function _processRow($rowData)
    {
        $updated = array();
        foreach ($this->_map($rowData, false) as $data) {
            $this->_connection->insertOnDuplicate($this->_getTable(), $data);
        }
    }

    /**
     * Returns product ID from data source row
     *
     * @param array $row
     * @throws InvalidArgumentException
     * @return integer
     */
    protected function _getProductId(array $row)
    {
        $sku = $row[self::COL_SKU];
        if (isset($this->_skuToId[$sku])) {
            return $this->_skuToId[$sku];
        }

        $id = Mage::getResourceModel('catalog/product')->getIdBySku($sku);
        if (!$id) {
            throw new InvalidArgumentException('Product with this SKU does not exist', self::ERROR_INVALID_SKU);
        }

        return $this->_skuToId[$sku] = $id;
    }

    private function _reindexPrices()
    {
        $s = time();
        $this->_log('Prices?');

        $eavConfig = Mage::getSingleton('eav/config');
        $priceAttrId = $eavConfig->getAttribute(Mage_Catalog_Model_Product::ENTITY, 'price')->getId();
        $specialPriceAttrId = $eavConfig->getAttribute(Mage_Catalog_Model_Product::ENTITY, 'special_price')->getId();
        $priceIf = new Zend_Db_Expr('IF(sp.value IS NOT NULL, LEAST(sp.value, pp.value), pp.value)');
        $taxClass = new Zend_Db_Expr(self::TAX_CLASS_ID);

        $prices = $this->_getConnection()
            ->select()
            ->from(
                array('g' => 'customer_group'),
                array ('p.entity_id', 'g.customer_group_id', 'w.website_id', $taxClass, $priceIf, $priceIf, $priceIf, $priceIf)
            )
            ->join(
                array('p' => 'catalog_product_entity'),
                null,
                array()
            )
            ->joinLeft(
                array('pp' => 'catalog_product_entity_decimal'),
                "pp.attribute_id = $priceAttrId AND pp.entity_id = p.entity_id",
                array()
            )
            ->joinLeft(
                array('sp' => 'catalog_product_entity_decimal'),
                "sp.attribute_id = $specialPriceAttrId AND sp.entity_id = p.entity_id AND pp.store_id = sp.store_id",
                array()
            )
            ->join(
                array('sg' => 'core_store_group'),
                'sg.default_store_id = pp.store_id',
                array()
            )
            ->join(
                array('w' => 'core_website'),
                'w.website_id = sg.website_id',
                array()
            )
            ->where('w.website_id <> 0')
            ->where('g.catalog_code IS NULL');

        $insertPrices = $this->_getConnection()->insertFromSelect(
            $prices,
            'catalog_product_index_price',
            array('entity_id', 'customer_group_id', 'website_id', 'tax_class_id', 'price', 'min_price', 'max_price', 'final_price'),
            Varien_Db_Adapter_Interface::INSERT_ON_DUPLICATE
        );

        if ($this->_debug) {
            Mage::log("$prices");
        }

        $this->_getConnection()->query($insertPrices);

        $this->_log('Prices ' . (time() - $s) . '.');
    }

    private function _updatePricesOfConfigurables()
    {
        $s = time();
        $this->_log('Configurables?');

        $eav = Mage::getSingleton('eav/config');
        $mediaFormatAttr = $eav->getAttribute(Mage_Catalog_Model_Product::ENTITY, 'media_format');

        $prices = $this->_getConnection()
            ->select()
            ->from(
                array('p' => 'catalog_product_entity'),
                array(
                    'p.entity_id', 'i.customer_group_id', 'i.website_id', new Zend_Db_Expr('MIN(i.price)'),
                    new Zend_Db_Expr('MIN(i.final_price)'), new Zend_Db_Expr('MIN(i.min_price)'),
                    new Zend_Db_Expr('MAX(i.max_price)')
                )
            )
            ->join(
                array('sl' => 'catalog_product_super_link'),
                'sl.parent_id = p.entity_id',
                array()
            )
            ->join(
                array('i' => 'catalog_product_index_price'),
                'sl.product_id = i.entity_id',
                array()
            )
            ->join(
                array('media_format' => 'catalog_product_entity_int'),
                "media_format.entity_id = sl.product_id AND media_format.attribute_id = {$mediaFormatAttr->getId()} AND media_format.store_id = 0",
                array()
            )
            ->where('media_format.value NOT IN (?)', $this->_helperDax()->getTranscriptOptionIds())
            ->group('p.entity_id')
            ->group('customer_group_id')
            ->group('website_id');

        $insertPrices = $this->_getConnection()
            ->insertFromSelect(
                $prices,
                'catalog_product_index_price',
                array('entity_id', 'customer_group_id', 'website_id', 'price', 'final_price', 'min_price', 'max_price'),
                Varien_Db_Adapter_Interface::INSERT_ON_DUPLICATE
            );

        if ($this->_debug) {
            Mage::log("$prices");
        }

        $this->_getConnection()->query($insertPrices);
        $this->_log('Configurables ' . (time() - $s) . '.');
    }

    protected function _helperDax()
    {
        return Mage::helper('tgc_dax');
    }

    private function _log($message)
    {
        if ($this->_debug) {
            Mage::log($message, null, $this->_logFileName);
        }
    }
}
