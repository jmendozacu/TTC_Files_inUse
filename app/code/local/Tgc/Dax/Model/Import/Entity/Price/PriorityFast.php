<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category
 * @package
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Dax_Model_Import_Entity_Price_PriorityFast extends Tgc_Dax_Model_Import_Entity_Checksum_Base
{
    private $_debug = false;
    private $_logFileName = 'import.log';

    private $_inputTable;
    private $_priceTable;
    private $_currencyToWebsite;
    private $_taxClassId;

    const COL_SKU       = 'sku';
    const COL_PRICE     = 'price';
    const COL_CURRENCY  = 'currency';

    const ERROR_INVALID_CURRENCY = 1;
    const ERROR_INVALID_SKU      = 2;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->_debug = Mage::getStoreConfigFlag(Tgc_Dax_Model_Import_Entity_Price_Abstract::DEBUG_FLAG);
        $this->_dataSourceModel = Mage_ImportExport_Model_Import::getDataSourceModel();
        $this->_dataSourceModel->setEntityTypeCode($this->getEntityTypeCode());
        $this->_connection = Mage::getSingleton('core/resource')->getConnection('write');
        $this->_permanentAttributes = array(self::COL_SKU, self::COL_PRICE, self::COL_CURRENCY);
        $this->_taxClassId = Mage::getModel('tax/class')
            ->load(Mage_Tax_Model_Class::TAX_CLASS_TYPE_CUSTOMER, 'class_type')
            ->getClassId();

        $this->_initErrorMessages();
        $this->_initCurrencyToWebsite();
        $this->_createInputTable();
        $this->_createPriceTable();
    }

    public function __destruct()
    {
        $this->_dropInputTable();
        $this->_dropPriceTable();
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

    protected function _getWebsiteIds(array $row)
    {
        $currency = $row[self::COL_CURRENCY];
        if (!isset($this->_currencyToWebsite[$currency])) {
            throw new InvalidArgumentException('Row contains unsupported currency', self::ERROR_INVALID_CURRENCY);
        }

        return $this->_currencyToWebsite[$currency];
    }

    protected function _map(array $row)
    {
        $data = array();

        foreach ($this->_getWebsiteIds($row) as $websiteId) {
            $mapped = $row;
            $mapped['website_id'] = $websiteId;
            $mapped['tax_class_id'] = $this->_taxClassId;
            unset($mapped['currency']);
            $data[] = $mapped;
        }

        return $data;
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

    private function _createInputTable()
    {
        $name = '0_priority_price_input_' . uniqid();
        $table = $this->_getConnection()->newTable($name);
        $table->addColumn('catalog_code',   $table::TYPE_INTEGER)
              ->addColumn('sku',            $table::TYPE_TEXT, 60)
              ->addColumn('price',          $table::TYPE_DECIMAL, array(12, 4))
              ->addColumn('shipping_price', $table::TYPE_DECIMAL, array(12, 4))
              ->addColumn('allow_coupons',  $table::TYPE_BOOLEAN)
              ->addColumn('tax_class_id',   $table::TYPE_INTEGER)
              ->addColumn('apply_special_shipping', $table::TYPE_BOOLEAN)
              ->addColumn('website_id', $table::TYPE_INTEGER)
              ->addIndex('IDX_CATALOG_CODE_WEBSITE_ID_SKU', array('catalog_code', 'website_id', 'sku'));

        $createTable = $this->_debug ? 'createTable' : 'createTemporaryTable';
        $this->_getConnection()->$createTable($table);
        $this->_inputTable = $name;
    }

    private function _createPriceTable()
    {
        $name = '0_conf_price_update_' . uniqid();
        $table = $this->_getConnection()->newTable($name);
        $table->addColumn('entity_id',         $table::TYPE_INTEGER)
              ->addColumn('website_id',        $table::TYPE_INTEGER)
              ->addColumn('customer_group_id', $table::TYPE_INTEGER)
              ->addColumn('min_price',         $table::TYPE_DECIMAL, array(12, 4))
              ->addColumn('max_price',         $table::TYPE_DECIMAL, array(12, 4))
              ->addColumn('new_min_price',     $table::TYPE_DECIMAL, array(12, 4))
              ->addColumn('new_max_price',     $table::TYPE_DECIMAL, array(12, 4));

        $createTable = $this->_debug ? 'createTable' : 'createTemporaryTable';
        $this->_connection->$createTable($table);
        $this->_priceTable = $name;
    }

    private function _dropPriceTable()
    {
        if (!$this->_debug) {
            $this->_getConnection()->dropTemporaryTable($this->_priceTable);
        }
        $this->_priceTable = null;
    }


    private function _addInputData(array $data)
    {
        $this->_getConnection()->insertMultiple($this->_inputTable, $data);
    }

    private function _dropInputTable()
    {
        if (!$this->_debug) {
            $this->_getConnection()->dropTemporaryTable($this->_inputTable);
        }
        $this->_inputTable = null;
    }

    private function _cleanupInputTable()
    {
        $this->_log('Clean up?');
        $this->_getConnection()->delete($this->_inputTable);
        $this->_log('Clean up.');
    }

    /**
     * Initializes error messages
     */
    protected function _initErrorMessages()
    {
        $this->addMessageTemplate(self::ERROR_INVALID_CURRENCY, 'Invalid currency code');
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
    public function validateRow(array $rowData, $rowNum)
    {
        return true;
    }

    /**
     * Imports data
     *
     * @see Mage_ImportExport_Model_Import_Entity_Abstract::_importData()
     */
    protected function _importData()
    {
        try{
            $this->_log('Fast priority_price...');
            $this->_cleanupInputTable();
            $this->_processBunches();
            $this->_addNewGroups();
            $this->_deleteExpiredCustomerGroups();
            $this->_updatePriceIndex();
            $this->_updateGroupPrices();
            $this->_updatePricesOfConfigurables();
            $this->_updateShippingPrices();
            $this->_invalidateSearchIndex();
            $this->_log('.');
        } catch (Exception $e) {
            Mage::logException($e);
            throw $e;
        }

        return true;
    }

    private function _processBunches()
    {
        $s = time();
        $this->_log('Bunches?');
        while ($bunch = $this->_dataSourceModel->getNextBunch()) {
            $this->_processBunch($bunch, $result);
        }
        $this->_log('Bunches ' . (time() - $s) . '.');
    }

    private function _processBunch(array $bunch, &$result)
    {
        $rowsImported = 0;
        $data = array();

        foreach ($bunch as $rowNum => $rowData) {
            try {
                $data = array_merge($data, $this->_map($rowData));
            } catch (Exception $e) {
                Mage::logException($e);
            }
        }

        $this->_addInputData($data);

        return count($data);
    }

    private function _addNewGroups()
    {
        $s = time();
        $this->_log('Groups?');
        $groupPrefix = Tgc_Price_Helper_Data::GROUP_CODE_PREFIX;
        $groups = $this->_getConnection()
            ->select()
            ->from(
                array('i' => $this->_inputTable),
                array(
                    'g.customer_group_id',
                    new Zend_Db_Expr("CONCAT('$groupPrefix', i.catalog_code)"),
                    'tax_class_id',
                    'allow_coupons',
                    'catalog_code',
                    'shipping_price',
                    'website_id',
                    new Zend_Db_Expr("'0000-00-00 00:00:00'"),
                    new Zend_Db_Expr("'2099-12-31 00:00:00'")
                )
            )
            ->joinLeft(
                array('g' => 'customer_group'),
                'g.catalog_code = i.catalog_code',
                array()
            )
            ->group('i.catalog_code');

        $insert = $this->_getConnection()->insertFromSelect(
            $groups,
            'customer_group',
            array(
                'customer_group_id',
                'customer_group_code',
                'tax_class_id',
                'allow_coupons',
                'catalog_code',
                'special_shipping_price',
                'website_id',
                'start_date',
                'stop_date'
            ),
            Varien_Db_Adapter_Interface::INSERT_ON_DUPLICATE
        );

        $this->_getConnection()->query($insert);

        $this->_log("Groups " . (time() - $s) . '.');
    }

    private function _updatePriceIndex()
    {
        $s = time();
        $this->_log('Prices?');

        $eavConfig = Mage::getSingleton('eav/config');
        $priceAttrId = $eavConfig->getAttribute(Mage_Catalog_Model_Product::ENTITY, 'price')->getId();
        $specialPriceAttrId = $eavConfig->getAttribute(Mage_Catalog_Model_Product::ENTITY, 'special_price')->getId();
        $taxClass = new Zend_Db_Expr(Tgc_Dax_Model_Import_Entity_Price_Abstract::TAX_CLASS_ID);
        $priceExpr = new Zend_Db_Expr('IF(i.price IS NOT NULL, LEAST(i.price, IF(sp.value IS NOT NULL, LEAST(sp.value, pp.value), pp.value)), IF(sp.value IS NOT NULL, LEAST(sp.value, pp.value), pp.value))');

        $prices = $this->_getConnection()
            ->select()
            ->from(
                array('g' => 'customer_group'),
                array ('p.entity_id', 'g.customer_group_id', 'g.website_id', $taxClass, $priceExpr, $priceExpr, $priceExpr, $priceExpr)
            )
            ->join(
                array('w' => 'core_website'),
                'w.website_id = g.website_id',
                array()
            )
            ->join(
                array('sg' => 'core_store_group'),
                'sg.website_id = w.website_id',
                array()
            )
            ->join(
                array('p' => 'catalog_product_entity'),
                null,
                array()
            )
            ->joinLeft(
                array('i' => $this->_inputTable),
                'i.website_id = g.website_id AND g.catalog_code = i.catalog_code AND i.sku = p.sku',
                array()
            )
            ->joinLeft(
                array('pp' => 'catalog_product_entity_decimal'),
                "pp.attribute_id = $priceAttrId AND pp.entity_id = p.entity_id AND sg.default_store_id = pp.store_id",
                array()
            )
            ->joinLeft(
                array('sp' => 'catalog_product_entity_decimal'),
                "sp.attribute_id = $specialPriceAttrId AND sp.entity_id = p.entity_id AND sg.default_store_id = sp.store_id",
                array()
            )
            ->joinLeft(
                array('po' => 'catalog_product_index_price'),
                'po.entity_id = p.entity_id AND po.customer_group_id = g.customer_group_id AND po.website_id = g.website_id',
                array()
            )
            ->where('g.catalog_code IS NOT NULL')
            ->where("po.price IS NULL OR po.price <> $priceExpr")
            ->where("$priceExpr IS NOT NULL");

        $insert = $this->_getConnection()->insertFromSelect(
            $prices,
            'catalog_product_index_price',
            array('entity_id', 'customer_group_id', 'website_id', 'tax_class_id', 'price', 'min_price', 'max_price', 'final_price'),
            Varien_Db_Adapter_Interface::INSERT_ON_DUPLICATE
        );

        if ($this->_debug) {
            Mage::log("$prices");
        }

        $this->_getConnection()->query($insert);

        $this->_log('Prices ' . (time() - $s) . '.');
    }

    private function _updateGroupPrices()
    {
        $s = time();
        $this->_log('Group prices?');

        $taxClass = new Zend_Db_Expr(Tgc_Dax_Model_Import_Entity_Price_Abstract::TAX_CLASS_ID);

        $prices = $this->_getConnection()
            ->select()
            ->from(
                array('g' => 'customer_group'),
                array ('p.entity_id', new Zend_Db_Expr('0'), 'g.customer_group_id', 'i.price', 'g.website_id')
            )
            ->join(
                array('w' => 'core_website'),
                'w.website_id = g.website_id',
                array()
            )
            ->join(
                array('p' => 'catalog_product_entity'),
                null,
                array()
            )
            ->join(
                array('i' => $this->_inputTable),
                'i.website_id = g.website_id AND g.catalog_code = i.catalog_code AND i.sku = p.sku',
                array()
            )
            ->joinLeft(
                array('po' => 'catalog_product_entity_group_price'),
                'po.entity_id = p.entity_id AND po.customer_group_id = g.customer_group_id AND po.website_id = g.website_id',
                array()
            )
            ->where('g.catalog_code IS NOT NULL')
            ->where("po.value IS NULL OR po.value <> i.price")
            ->where("i.price IS NOT NULL");

            $insert = $this->_getConnection()->insertFromSelect(
                $prices,
                'catalog_product_entity_group_price',
                array('entity_id', 'all_groups', 'customer_group_id', 'value', 'website_id'),
                Varien_Db_Adapter_Interface::INSERT_ON_DUPLICATE
            );

        if ($this->_debug) {
            Mage::log("$prices");
        }

        $this->_getConnection()->query($insert);

        $this->_log('Group prices ' . (time() - $s) . '.');
    }

    private function _updatePricesOfConfigurables()
    {
        $s = time();
        $this->_log('Configurables?');

        $this->_getConnection()->delete($this->_priceTable);
        $taxClass = new Zend_Db_Expr(Tgc_Dax_Model_Import_Entity_Price_Abstract::TAX_CLASS_ID);
        $eav = Mage::getSingleton('eav/config');
        $mediaFormatAttr = $eav->getAttribute(Mage_Catalog_Model_Product::ENTITY, 'media_format');

        $prices = $this->_getConnection()
            ->select()
            ->from(array('p' => 'catalog_product_entity'), array())
            ->join(array('sl' => 'catalog_product_super_link'), 'sl.parent_id = p.entity_id', array())
            ->join(
                array('pi' => 'catalog_product_index_price'),
                'pi.entity_id = sl.product_id',
                array()
            )
            ->joinLeft(
                array('po' => 'catalog_product_index_price'),
                'po.entity_id = p.entity_id AND pi.website_id = po.website_id AND pi.customer_group_id = po.customer_group_id',
                array()
            )
            ->join(
                array('media_format' => 'catalog_product_entity_int'),
                "media_format.entity_id = sl.product_id AND media_format.attribute_id = {$mediaFormatAttr->getId()} AND media_format.store_id = 0",
                array()
            )
            ->where('media_format.value NOT IN (?)', Mage::helper('tgc_dax')->getTranscriptOptionIds())
            ->group('p.entity_id')
            ->group('pi.website_id')
            ->group('pi.customer_group_id')
            ->columns(array(
                'p.entity_id',
                'pi.website_id',
                'pi.customer_group_id',
                'po.min_price',
                'po.max_price',
                new Zend_Db_Expr('MIN(pi.final_price)'),
                new Zend_Db_Expr('MAX(pi.final_price)'),
            ));

        $insertPrices = $this->_getConnection()->insertFromSelect($prices, $this->_priceTable);

        if ($this->_debug) {
            Mage::log((string)$insertPrices);
        }

        $this->_getConnection()->query($insertPrices);

        $updatedPrices = $this->_getConnection()
            ->select()
            ->from(
                $this->_priceTable,
                array('entity_id', 'website_id', 'customer_group_id', $taxClass, 'new_min_price', 'new_min_price', 'new_min_price', 'new_max_price')
            )
            ->where('min_price IS NULL OR max_price IS NULL OR min_price <> new_min_price OR max_price <> new_max_price');

        $insertUpdatedPrices = $this->_getConnection()
            ->insertFromSelect(
                $updatedPrices,
                'catalog_product_index_price',
                array('entity_id', 'website_id', 'customer_group_id', 'tax_class_id', 'price', 'final_price', 'min_price', 'max_price'),
                Varien_Db_Adapter_Interface::INSERT_ON_DUPLICATE
            );

        if ($this->_debug) {
            Mage::log((string)$insertUpdatedPrices);
        }

        $this->_getConnection()->query($insertUpdatedPrices);
        $this->_log('Configurables ' . (time() - $s) . '.');
    }

    private function _deleteExpiredCustomerGroups()
    {
        $s = time();
        $this->_log('Groups clean up?');
        $defCustGrpId = $this->_getConnection()->quote(Tgc_Price_Helper_Data::DEFAULT_CUSTOMER_GROUP_ID);
        $inputTable = $this->_getConnection()->quoteIdentifier($this->_inputTable);
        $this->_getConnection()->query(<<<SQL
            UPDATE ad_code
              INNER JOIN customer_group g ON g.customer_group_id = ad_code.customer_group_id
              LEFT JOIN $inputTable i ON i.catalog_code = g.catalog_code
              SET ad_code.customer_group_id = $defCustGrpId
              WHERE i.catalog_code IS NULL AND g.catalog_code IS NOT NULL
SQL
        );
        $this->_getConnection()->query(<<<SQL
            DELETE customer_group
              FROM customer_group
              LEFT JOIN $inputTable i ON i.catalog_code = customer_group.catalog_code AND i.website_id = customer_group.website_id
              WHERE i.catalog_code IS NULL AND customer_group.catalog_code IS NOT NULL
SQL
        );
        $this->_log('Groups clean up ' . (time() - $s) . '.');
    }

    private function _updateShippingPrices()
    {
        $s = time();
        $this->_log('Shipping prices?');

        $prices = $this->_getConnection()
            ->select()
            ->from(
                array('i' => $this->_inputTable),
                array('g.customer_group_id', 'i.website_id', 'shipping_price')
            )
            ->join(array('g' => 'customer_group'), 'g.catalog_code = i.catalog_code', array())
            ->where('i.shipping_price')
            ->group('i.catalog_code');

        $shippingRateTable = Mage::getResourceModel('tgc_shipping/flatRate')->getMainTable();
        $this->_getConnection()->truncateTable($shippingRateTable);
        $insertPrices = $this->_getConnection()->insertFromSelect(
            $prices,
            $shippingRateTable,
            array('customer_group_id', 'website_id', 'shipping_price')
        );
        $this->_getConnection()->query($insertPrices);

        $this->_log('Shipping prices ' . (time() - $s) . '.');
    }

    private function _log($message)
    {
        if ($this->_debug) {
            Mage::log($message, null, $this->_logFileName);
        }
    }

    /**
     * Invalidates Solr indexer: we need to reindex all entities in Solr,
     * because we are importing prices for almost all products and
     * we won't win in performance here if we would save all changed product IDs into changelog.
     */
    private function _invalidateSearchIndex()
    {
        /* @var $metadata Enterprise_Mview_Model_Metadata */
        $metadata = Mage::getModel('enterprise_mview/metadata')
            ->load($this->_getConnection()->getTableName('catalogsearch_fulltext'), 'table_name');
        $metadata->setGroupCode('catalogsearch_fulltext')
            ->setInvalidStatus()
            ->save();
    }
}
