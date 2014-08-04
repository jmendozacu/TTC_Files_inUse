<?php
/**
 * Digital Library Purchase History Import
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DigitalLibrary
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 * PREVIOUSLY USED CHECKSUM INTERFACE
 */
class Tgc_DigitalLibrary_Model_Import_Entity_PurchaseHistory extends Tgc_Dax_Model_Import_Entity_Checksum_Base
{
    //attribute names
    const ENTITY_ID           = 'entity_id';
    const DAX_CUSTOMER_ID     = 'dax_customer_id';
    const PRODUCT_ID          = 'product_id';

    //the fields in the import file
    const COL_DAX_CUSTOMER_ID = 'CustAccount';
    const COL_COURSE_ID       = 'courselist';

    const DELIMITER           = ',';

    private $_entityTable;
    private $_productTable;
    private $_eavAttributeTable;
    private $_varcharTable;
    private $_customerTable;
    private $_courseIdsToProductIds = array();

    /**
     * Constructor.
     *
     */
    public function __construct()
    {
        /** @var _dataSourceModel Mage_ImportExport_Model_Resource_Import_Data */
        $this->_dataSourceModel   = Mage_ImportExport_Model_Import::getDataSourceModel();
        $this->_dataSourceModel->setEntityTypeCode($this->getEntityTypeCode());
        /** @var _connection Magento_Db_Adapter_Pdo_Mysql */
        $this->_connection        = Mage::getSingleton('core/resource')->getConnection('write');
        /** @var _entityTable `digital_library_cross_platform_resume` */
        $this->_entityTable       = Mage::getResourceModel('tgc_dl/purchaseHistory')->getMainTable();
        $this->_productTable      = 'catalog_product_entity';
        $this->_eavAttributeTable = 'eav_attribute';
        $this->_varcharTable      = 'catalog_product_entity_varchar';
        $this->_customerTable     = 'customer_entity';

        $this->_permanentAttributes = array(
            self::COL_DAX_CUSTOMER_ID,
            self::COL_COURSE_ID,
        );

        $this->_particularAttributes = array(
            self::COL_DAX_CUSTOMER_ID,
        );

        $this->_initCourseIdsToProductIds();
    }

    public function getEntityTypeCode()
    {
        return 'purchase_history';
    }

    private function _initCourseIdsToProductIds()
    {
        $collection = Mage::getModel('catalog/product')
            ->getCollection()
            ->addAttributeToSelect('course_id')
            ->addAttributeToFilter('type_id', array('eq' => 'configurable'));

        $ids       = $collection->getColumnValues('entity_id');
        $courseIds = $collection->getColumnValues('course_id');

        $this->_courseIdsToProductIds = array_combine($courseIds, $ids);
    }

    public function validateRow(array $rowData, $rowNum)
    {
        try {
            if (Mage_ImportExport_Model_Import::BEHAVIOR_DELETE == $this->getBehavior()) {
                $courseIds = explode(self::DELIMITER, $rowData[self::COL_COURSE_ID]);
                foreach ($courseIds as $courseId) {
                    $data = array(
                        self::COL_DAX_CUSTOMER_ID => $rowData[self::COL_DAX_CUSTOMER_ID],
                        self::COL_COURSE_ID       => $courseId,
                    );
                    $this->_rowExists($data);
                }
            }
            if (Mage_ImportExport_Model_Import::BEHAVIOR_APPEND == $this->getBehavior()) {
                $courseIds = explode(self::DELIMITER, $rowData[self::COL_COURSE_ID]);
                foreach ($courseIds as $courseId) {
                    $data = array(
                        self::COL_DAX_CUSTOMER_ID => $rowData[self::COL_DAX_CUSTOMER_ID],
                        self::COL_COURSE_ID       => $courseId,
                    );
                    if ($this->_rowExists($data)) {
                        $message = Mage::helper('tgc_dl')->__(
                            'A row with these values already exists'
                        );
                        throw new InvalidArgumentException($message);
                    }
                }
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
            $this->_deletePurchaseHistoryData();

        } else if (Mage_ImportExport_Model_Import::BEHAVIOR_REPLACE == $this->getBehavior()) {
            $this->_updatePurchaseHistoryData();

        } else {
            $this->_savePurchaseHistoryData();
        }

        return true;
    }

    /**
     * Update existing purchase history data
     */
    private function _updatePurchaseHistoryData()
    {
        $bind = array('is_prospect' => 0);

        while ($bunch = $this->_dataSourceModel->getNextBunch()) {
            $daxIds = array();
            foreach ($bunch as $rowNum => $rowData) {
                if ($rowNum == 0) {
                    continue;
                }
                try {
                    foreach ($this->_map($rowData) as $data) {
                        $this->_connection->insertOnDuplicate($this->_entityTable, $data);
                        $daxIds[] = $rowData[self::COL_DAX_CUSTOMER_ID];
                    }
                } catch (InvalidArgumentException $e) {
                    $this->addRowError($e->getCode(), $rowNum);
                }
            }

            if (!empty($daxIds)) {
                $this->_connection->update(
                    $this->_customerTable,
                    $bind,
                    array($this->_connection->quoteInto("`dax_customer_id` IN(?) ", $daxIds))
                );
            }
        }
    }

    /**
     * Save purchase history data
     */
    private function _savePurchaseHistoryData()
    {
        $bind = array('is_prospect' => 0);

        while ($bunch = $this->_dataSourceModel->getNextBunch()) {
            $daxIds = array();
            $data = array();
            foreach ($bunch as $rowNum => $rowData) {
                if ($rowNum == 0) {
                    continue;
                }
                foreach ($this->_map($rowData) as $line) {
                    $data[] = $line;
                    $daxIds[] = $rowData[self::COL_DAX_CUSTOMER_ID];
                }
            }
            try {
                $this->_connection->insertMultiple($this->_entityTable, $data);
            } catch (Exception $e) {
                Mage::logException($e);
            }

            if (!empty($daxIds)) {
                $this->_connection->update(
                    $this->_customerTable,
                    $bind,
                    array($this->_connection->quoteInto("`dax_customer_id` IN(?) ", $daxIds))
                );
            }
        }
    }

    /**
     * Delete purchase history data
     */
    private function _deletePurchaseHistoryData()
    {
        while ($bunch = $this->_dataSourceModel->getNextBunch()) {
            $idsToDelete = array();
            /** @var $resource Tgc_DigitalLibrary_Model_Resource_PurchaseHistory */
            $resource = Mage::getResourceModel('tgc_dl/purchaseHistory');

            foreach ($bunch as $rowNum => $rowData) {
                if ($rowNum == 0) {
                    continue;
                }
                foreach ($this->_map($rowData) as $line) {
                    $idsToDelete[] = $line[self::ENTITY_ID];
                }
            }
            $idsToDelete = array_filter($idsToDelete);
            if ($idsToDelete) {
                $resource->deleteRowsByIds($idsToDelete);
            }
        }
    }

    protected function _map(array $row)
    {
        if (empty($row[self::COL_DAX_CUSTOMER_ID])) {
            $message = Mage::helper('tgc_dl')->__(
                'DAX Customer ID column cannot be empty'
            );
            throw new InvalidArgumentException($message);
        }
        if (empty($row[self::COL_COURSE_ID])) {
            $message = Mage::helper('tgc_dl')->__(
                'Course ID column cannot be empty'
            );
            throw new InvalidArgumentException($message);
        }

        $courseIds = explode(self::DELIMITER, $row[self::COL_COURSE_ID]);
        $mappings  = array();
        foreach ($courseIds as $courseId) {
            $data = array(
                self::COL_DAX_CUSTOMER_ID => $row[self::COL_DAX_CUSTOMER_ID],
                self::COL_COURSE_ID       => $courseId,
            );
            $mappings[] = array(
                self::ENTITY_ID       => $this->_rowExists($data),
                self::DAX_CUSTOMER_ID => $this->_validateDaxCustomerId($row[self::COL_DAX_CUSTOMER_ID]),
                self::PRODUCT_ID      => $this->_getProductIdFromCourseId($courseId),
            );
        }

        return $mappings;
    }

    private function _rowExists(array $row)
    {
        $select = $this->_connection->select()
            ->from($this->_entityTable, 'entity_id')
            ->where('dax_customer_id = :daxCustomerId')
            ->where('product_id = :productId');
        $bind = array(
            ':daxCustomerId' => (string)$row[self::COL_DAX_CUSTOMER_ID],
            ':productId' => (int)$this->_getProductIdFromCourseId($row[self::COL_COURSE_ID]),
        );

        return $this->_connection->fetchOne($select, $bind);
    }

    private function _validateDaxCustomerId($daxCustomerId)
    {
        $select = $this->_connection->select()
            ->from($this->_customerTable, 'entity_id')
            ->where('dax_customer_id = :daxCustomerId');
        $bind = array(
            ':daxCustomerId' => (string)$daxCustomerId,
        );

        $exists = (bool)$this->_connection->fetchOne($select, $bind);
        if (!$exists) {
            throw new InvalidArgumentException(
                Mage::helper('tgc_dl')->__(
                    'Invalid DAX Customer ID: %s supplied. There is no corresponding customer.',
                    $daxCustomerId
                )
            );
        }

        return $daxCustomerId;
    }

    private function _getProductIdFromCourseId($courseId)
    {
        if (isset($this->_courseIdsToProductIds[$courseId])) {
            return $this->_courseIdsToProductIds[$courseId];
        }

        throw new InvalidArgumentException(
            Mage::helper('tgc_dl')->__(
                'Invalid Course ID: %s supplied. There is no corresponding product.',
                $courseId
            )
        );
    }
}
