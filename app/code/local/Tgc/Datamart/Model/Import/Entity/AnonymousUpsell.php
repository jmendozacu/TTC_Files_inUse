<?php
/**
 * DataMart integration
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DataMart
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 * PREVIOUSLY USED CHECKSUM INTERFACE
 */
class Tgc_Datamart_Model_Import_Entity_AnonymousUpsell extends Tgc_Dax_Model_Import_Entity_Checksum_Base
{
    //the real attribute names
    const SUBJECT_ID          = 'subject_id';
    const COURSE_ID           = 'course_id';
    const SORT_ORDER          = 'sort_order';

    //the names used in the import file
    const COL_SUBJECT_ID      = 'subject_id';
    const COL_COURSE_ID       = 'course_id';
    const COL_SORT_ORDER      = 'displayorder';

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
        $this->_entityTable     = Mage::getResourceModel('tgc_datamart/anonymousUpsell')->getMainTable();

        $this->_permanentAttributes = array(
            self::COL_SUBJECT_ID,
            self::COL_COURSE_ID,
            self::COL_SORT_ORDER,
        );

        $this->_particularAttributes = array(
            self::COL_COURSE_ID,
        );

        $this->_requiredAttributes = array(
            self::COL_SUBJECT_ID,
            self::COL_COURSE_ID,
        );
    }

    public function getEntityTypeCode()
    {
        return 'anonymous_upsell';
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
                    'A row with subject id: %s and course ID: %s already exists',
                    $rowData[self::COL_SUBJECT_ID],
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
            $this->_deleteAnonymousUpsell();
        } else if (Mage_ImportExport_Model_Import::BEHAVIOR_REPLACE == $this->getBehavior()) {
            $this->_updateAnonymousUpsell();
        } else {
            $this->_saveAnonymousUpsell();
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
    private function _updateAnonymousUpsell()
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
     * Save anonymous upsell
     */
    private function _saveAnonymousUpsell()
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
     * Delete anonymous upsell
     */
    private function _deleteAnonymousUpsell()
    {
        while ($bunch = $this->_dataSourceModel->getNextBunch()) {
            $idsToDelete = array();
            /** @var $resource Tgc_Datamart_Model_Resource_AnonymousUpsell */
            $resource = Mage::getResourceSingleton('tgc_datamart/anonymousUpsell');

            foreach ($bunch as $rowData) {
                $idsToDelete[] = $resource->getIdBySubjectAndCourse($rowData[self::COL_SUBJECT_ID], $rowData[self::COL_COURSE_ID]);
            }
            if ($idsToDelete) {
                $resource->deleteRowsByIds($idsToDelete);
            }
        }
    }

    protected function _map(array $row)
    {
        return array(
            self::SUBJECT_ID => $row[self::COL_SUBJECT_ID],
            self::COURSE_ID  => $row[self::COL_COURSE_ID],
            self::SORT_ORDER => $row[self::COL_SORT_ORDER],
        );
    }

    private function _rowExists(array $row)
    {
        $select = $this->_connection->select()
            ->from($this->_entityTable, 'entity_id')
            ->where('subject_id = :subjectId')
            ->where('course_id = :courseId');

        $bind = array(
            ':subjectId' => (int)$row[self::COL_SUBJECT_ID],
            ':courseId'  => (int)$row[self::COL_COURSE_ID],
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
