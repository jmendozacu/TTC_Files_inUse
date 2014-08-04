<?php
/**
 * Catalog Codes import entity
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Dax
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Dax_Model_Import_Entity_CatalogCode extends Tgc_Dax_Model_Import_Entity_Checksum_Base
{
    const COL_CATALOG_CODE           = 'catalogcode';
    const COL_NAME                   = 'name';
    const COL_DESCRIPTION            = 'description';
    const COL_START_DATE             = 'start_date';
    const COL_STOP_DATE              = 'stop_date';
    const COL_APPLY_SPECIAL_SHIPPING = 'apply_special_shipping';
    const COL_SPECIAL_SHIPPING_PRICE = 'special_shipping_price';
    const COL_CURRENCY               = 'currency';
    const COL_ALLOW_COUPONS          = 'allow_coupons';
    const DEFAULT_TAX_CLASS_ID       = 3;
    const DEFAULT_WEBSITE_CODE       = 0;

    private $_entityTable;
    private $_currencyToWebsite;
    private $_tgcFlatRateTable;
    private $_groupIds = array();

    public function __construct()
    {
        $this->_dataSourceModel  = Mage_ImportExport_Model_Import::getDataSourceModel();
        $this->_dataSourceModel->setEntityTypeCode($this->getEntityTypeCode());
        $this->_connection       = Mage::getSingleton('core/resource')->getConnection('write');
        $this->_entityTable      = Mage::getResourceModel('customer/group')->getMainTable();
        $this->_tgcFlatRateTable = Mage::getResourceModel('tgc_shipping/flatRate')->getMainTable();

        $this->_permanentAttributes = array(
            self::COL_CATALOG_CODE,
        );

        $this->_initCurrencyToWebsite();
    }

    public function getEntityTypeCode()
    {
        return 'catalog_code';
    }

    public function validateRow(array $rowData, $rowNum)
    {
        if (Mage_ImportExport_Model_Import::BEHAVIOR_DELETE == $this->getBehavior()) {
            return true;
        }

        try {
            // Do not create new catalog codes (customer groups), the price importer will create customer group if it's necessary
            // and this import will then update the customer group with catalog code information
            // return false without error
            if (!$this->_catalogCodeExists($rowData)) {
                return false;
            }
            $this->_map($rowData);
            return true;
        } catch (InvalidArgumentException $e) {
            $this->addRowError($e->getMessage(), $rowNum);
            return false;
        }
    }

    protected function _importData()
    {
        if (Mage_ImportExport_Model_Import::BEHAVIOR_DELETE == $this->getBehavior()) {
            $this->_deleteCatalogCodes();
        } else if (Mage_ImportExport_Model_Import::BEHAVIOR_REPLACE == $this->getBehavior()) {
            $this->_updateCatalogCodes();
        } else {
            $this->_saveCatalogCodes();
        }
    }

    private function _updateCatalogCodes()
    {
        while ($bunch = $this->_dataSourceModel->getNextBunch()) {
            foreach ($bunch as $rowNum => $rowData) {
                try {
                    $this->_connection->insertOnDuplicate($this->_entityTable, $this->_map($rowData));
                    $this->_addShippingPriceToTgcFlatRateTable($rowData);
                } catch (InvalidArgumentException $e) {
                    $this->addRowError($e->getCode(), $rowNum);
                }
            }
        }
    }

    private function _saveCatalogCodes()
    {
        while ($bunch = $this->_dataSourceModel->getNextBunch()) {
            $data = array();
            foreach ($bunch as $rowData) {
                $data[] = $this->_map($rowData);
            }

            try {
                $this->_connection->insertMultiple($this->_entityTable, $data);
            } catch (Exception $e) {
                Mage::logException($e);
            }

            foreach ($bunch as $rowData) {
                try {
                    $this->_addShippingPriceToTgcFlatRateTable($rowData);
                } catch (Exception $e) {
                    Mage::logException($e);
                }
            }
        }
    }

    private function _deleteCatalogCodes()
    {
        while ($bunch = $this->_dataSourceModel->getNextBunch()) {
            $codesToDelete = array();

            foreach ($bunch as $rowData) {
                try {
                    $codesToDelete[] = $rowData[self::COL_CATALOG_CODE];
                    $this->_connection->delete(
                        $this->_tgcFlatRateTable,
                        $this->_connection->quoteInto('customer_group_id =(?)', $this->_getGroupId($rowData))
                    );
                } catch (Exception $e) {
                    Mage::logException($e);
                }
            }
            if ($codesToDelete) {
                try {
                    $this->_connection->delete(
                        $this->_entityTable,
                        $this->_connection->quoteInto('catalog_code IN(?)', $codesToDelete)
                    );
                } catch (Exception $e) {
                    Mage::logException($e);
                }
            }
        }
    }

    protected function _map(array $row)
    {
        return array(
            'customer_group_id'      => $this->_getGroupId($row),
            'customer_group_code'    => Tgc_Price_Helper_Data::GROUP_CODE_PREFIX . $row[self::COL_CATALOG_CODE],
            'tax_class_id'           => self::DEFAULT_TAX_CLASS_ID,
            'allow_coupons'          => empty($row[self::COL_ALLOW_COUPONS]) ? 0 : 1,
            'catalog_code'           => $this->_validateCatalogCode($row),
            'special_shipping_price' => empty($row[self::COL_SPECIAL_SHIPPING_PRICE]) ? 0 : $row[self::COL_SPECIAL_SHIPPING_PRICE],
            'website_id'             => empty($row[self::COL_CURRENCY]) ? self::DEFAULT_WEBSITE_CODE : $this->_getWebsiteId($row),
            'start_date'             => empty($row[self::COL_START_DATE]) ?
                date(Varien_Date::DATETIME_PHP_FORMAT, Mage::getModel('core/date')->timestamp(time())) :
                date(Varien_Date::DATETIME_PHP_FORMAT, Mage::getModel('core/date')->timestamp(strtotime($row[self::COL_START_DATE]))),
            'stop_date'              => empty($row[self::COL_STOP_DATE]) ? '2099-12-31 00:00:00' :
                date(Varien_Date::DATETIME_PHP_FORMAT, Mage::getModel('core/date')->timestamp(strtotime($row[self::COL_STOP_DATE]))),
            'name'                   => $row[self::COL_NAME],
            'description'            => $row[self::COL_DESCRIPTION],
        );
    }

    private function _validateCatalogCode(array $row)
    {
        if (empty($row[self::COL_CATALOG_CODE])) {
            $message = Mage::helper('tgc_dax')->__(
                'Catalog Code cannot be empty'
            );
            throw new InvalidArgumentException($message);
        }

        return $row[self::COL_CATALOG_CODE];
    }

    private function _catalogCodeExists(array $row)
    {
        $select = $this->_connection->select()
            ->from($this->_entityTable, 'catalog_code')
            ->where('catalog_code = :catalogCode');

        $bind = array(
            ':catalogCode' => (int)$row[self::COL_CATALOG_CODE],
        );

        return (bool)$this->_connection->fetchOne($select, $bind);
    }

    private function _initCurrencyToWebsite()
    {
        $this->_currencyToWebsite = array();
        $websites = Mage::getResourceModel('core/website_collection')
            ->addFieldToFilter('code', array('neq' => Mage_Core_Model_Store::ADMIN_CODE));

        foreach ($websites as $website) {
            $currency = $website->getConfig(Mage_Directory_Model_Currency::XML_PATH_CURRENCY_BASE);
            $this->_currencyToWebsite[$currency] = $website->getId();
        }
    }

    private function _getWebsiteId(array $row)
    {
        $currency = $row[self::COL_CURRENCY];
        if (!isset($this->_currencyToWebsite[$currency])) {
            throw new InvalidArgumentException('Row contains unsupported currency');
        }

        return $this->_currencyToWebsite[$currency];
    }

    private function _getGroupId(array $row, $required = false)
    {
        if (isset($this->_groupIds[$row[self::COL_CATALOG_CODE]])) {
            return $this->_groupIds[$row[self::COL_CATALOG_CODE]];
        }

        $select = $this->_connection->select()
            ->from($this->_entityTable, 'customer_group_id')
            ->where('catalog_code = :catalogCode');

        $bind = array(
            ':catalogCode' => (int)$row[self::COL_CATALOG_CODE],
        );

        $groupId = $this->_connection->fetchOne($select, $bind);
        if ($required && !$groupId) {
            throw new InvalidArgumentException('Group ID does not exist for Catalog Code');
        }

        $result = $groupId ? $groupId : null;
        if (!empty($result)) {
            $this->_groupIds[$row[self::COL_CATALOG_CODE]] = $result;
        }

        return $result;
    }

    private function _addShippingPriceToTgcFlatRateTable(array $row)
    {
        if (empty($row[self::COL_APPLY_SPECIAL_SHIPPING])) {
            $this->_connection->delete(
                $this->_tgcFlatRateTable,
                $this->_connection->quoteInto('customer_group_id =(?)', $this->_getGroupId($row))
            );
        }

        $data = array(
            'customer_group_id' => $this->_getGroupId($row, true),
            'website_id'        => $this->_getWebsiteId($row),
            'shipping_price'    => empty($row[self::COL_SPECIAL_SHIPPING_PRICE]) ? 0 : $row[self::COL_SPECIAL_SHIPPING_PRICE],
        );

        $this->_connection->insertOnDuplicate($this->_tgcFlatRateTable, $data);
    }
}
