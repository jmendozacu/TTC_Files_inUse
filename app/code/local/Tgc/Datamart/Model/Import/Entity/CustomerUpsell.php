<?php
/**
 * DataMart integration
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DataMart
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Datamart_Model_Import_Entity_CustomerUpsell extends Tgc_Dax_Model_Import_Entity_Checksum_Base
{
    //the real attribute names
    const SEGMENT_GROUP     = 'segment_group';
    const COURSE_ID         = 'course_id';
    const SORT_ORDER        = 'sort_order';

    //the names used in the import file
    const COL_SEGMENT_GROUP = 'segmentgroup';
    const COL_COURSE_ID     = 'course_id';
    const COL_SORT_ORDER    = 'displayorder';

    private $_entityTable;
    private $_requiredAttributes;

    /**
     * Constructor.
     *
     */
    public function __construct()
    {
        /** @var _dataSourceModel Mage_ImportExport_Model_Resource_Import_Data */
        $this->_dataSourceModel = Mage_ImportExport_Model_Import::getDataSourceModel();
        $this->_dataSourceModel->setEntityTypeCode($this->getEntityTypeCode());
        /** @var _connection Magento_Db_Adapter_Pdo_Mysql */
        $this->_connection      = Mage::getSingleton('core/resource')->getConnection('write');
        /** @var _entityTable `tgc_datamart_email_landing` */
        $this->_entityTable     = Mage::getResourceModel('tgc_datamart/customerUpsell')->getMainTable();

        $this->_permanentAttributes = array(
            self::COL_SEGMENT_GROUP,
            self::COL_COURSE_ID,
            self::COL_SORT_ORDER,
        );

        $this->_particularAttributes = array(
            self::COL_COURSE_ID,
            self::COL_SORT_ORDER,
        );

        $this->_requiredAttributes = array(
            self::COL_SEGMENT_GROUP,
            self::COL_COURSE_ID,
        );
    }

    public function getEntityTypeCode()
    {
        return 'customer_upsell';
    }

    /**
     * Validate a row's data
     *
     * @param array $rowData
     * @param int   $rowNum
     * @throws InvalidArgumentException
     * @return bool whether row is valid or not
     */
    public function validateRow(array $rowData, $rowNum)
    {
        try {
            $this->_validateRequiredAttributes($rowData);

            if (Mage_ImportExport_Model_Import::BEHAVIOR_DELETE == $this->getBehavior()
                || Mage_ImportExport_Model_Import::BEHAVIOR_REPLACE == $this->getBehavior()) {
                return true;
            }

            if ($this->_rowExists($rowData)) {
                $message = Mage::helper('tgc_datamart')->__(
                    'A row with segment group: %s and course ID: %s already exists',
                    $rowData[self::COL_SEGMENT_GROUP],
                    $rowData[self::COL_COURSE_ID]
                );
                throw new InvalidArgumentException($message);
            }
            return true;
        } catch (InvalidArgumentException $e) {
            $this->addRowError($e->getMessage(), $rowNum);
            return false;
        }
    }

    protected function _importData()
    {
        if (Mage_ImportExport_Model_Import::BEHAVIOR_DELETE == $this->getBehavior()) {
            $this->_deleteCustomerUpsell();
        } else if (Mage_ImportExport_Model_Import::BEHAVIOR_REPLACE == $this->getBehavior()) {
            $this->_updateCustomerUpsell();
        } else {
            $this->_saveCustomerUpsell();
        }

        try {
            Mage::app()->getCacheInstance()->invalidateType(Mage_Core_Block_Abstract::CACHE_GROUP);
        } catch (Exception $e) {
            Mage::logException($e);
        }

        return true;
    }

    /**
     * Update existing customer upsell
     */
    private function _updateCustomerUpsell()
    {
        while ($bunch = $this->_dataSourceModel->getNextBunch()) {
            foreach ($bunch as $rowNum => $rowData) {
                try {
                    $this->_connection->insertOnDuplicate($this->_entityTable, $this->_map($rowData));
                } catch (InvalidArgumentException $e) {
                    $this->addRowError($e->getCode(), $rowNum);
                }
            }
        }
    }

    /**
     * Save new customer upsell
     */
    private function _saveCustomerUpsell()
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
        }
    }

    /**
     * Delete customer upsell
     */
    private function _deleteCustomerUpsell()
    {
        while ($bunch = $this->_dataSourceModel->getNextBunch()) {
            $idsToDelete = array();
            /** @var $resource Tgc_Datamart_Model_Resource_CustomerUpsell */
            $resource = Mage::getResourceSingleton('tgc_datamart/customerUpsell');

            foreach ($bunch as $rowData) {
                $idsToDelete[] = $resource->getIdBySegmentAndCourse($rowData[self::COL_SEGMENT_GROUP], $rowData[self::COL_COURSE_ID]);
            }
            if ($idsToDelete) {
                $resource->deleteRowsByIds($idsToDelete);
            }
        }
    }

    protected function _map(array $row)
    {
        return array(
            self::SEGMENT_GROUP => $row[self::COL_SEGMENT_GROUP],
            self::COURSE_ID     => $row[self::COL_COURSE_ID],
            self::SORT_ORDER    => $row[self::COL_SORT_ORDER],
        );
    }

    private function _rowExists(array $row)
    {
        $select = $this->_connection->select()
            ->from($this->_entityTable, 'entity_id')
            ->where('segment_group = :segment')
            ->where('course_id = :course_id');

        $bind = array(
            ':segment'   => (string)$row[self::COL_SEGMENT_GROUP],
            ':course_id' => (int)$row[self::COL_COURSE_ID],
        );

        return (bool)$this->_connection->fetchOne($select, $bind);
    }

    private function _validateRequiredAttributes(array $row)
    {
        foreach ($this->_requiredAttributes as $attribute) {
            if (empty($row[$attribute])) {
                $message = Mage::helper('tgc_datamart')->__(
                    'Column: %s is required and cannot be empty',
                    $attribute
                );
                throw new InvalidArgumentException($message);
            }
        }
    }
}
