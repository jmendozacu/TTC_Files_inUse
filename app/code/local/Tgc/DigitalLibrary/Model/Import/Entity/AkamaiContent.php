E<?php
/**
 * Digital Library Customer Merged Accounts Import
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DigitalLibrary
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_DigitalLibrary_Model_Import_Entity_AkamaiContent extends Tgc_Dax_Model_Import_Entity_Checksum_Base
{
    const COURSE_ID             = 'course_id';
    const GUIDEBOOK_FILE_NAME   = 'guidebook_file_name';
    const GUIDEBOOK_URL_PREFIX  = 'guidebook_url_prefix';
    const TRANSCRIPT_FILE_NAME  = 'transcript_file_name';
    const TRANSCRIPT_URL_PREFIX = 'transcript_url_prefix';

    private $_entityTable;
    private $_courseIds = array();

    public function __construct()
    {
        /** @var _dataSourceModel Mage_ImportExport_Model_Resource_Import_Data */
        $this->_dataSourceModel = Mage_ImportExport_Model_Import::getDataSourceModel();
        $this->_dataSourceModel->setEntityTypeCode($this->getEntityTypeCode());
        /** @var _connection Magento_Db_Adapter_Pdo_Mysql */
        $this->_connection      = Mage::getSingleton('core/resource')->getConnection('write');
        /** @var _entityTable `digital_library_cross_platform_resume` */
        $this->_entityTable     = Mage::getResourceModel('tgc_dl/akamaiContent')->getMainTable();

        $this->_permanentAttributes = array(
            self::COURSE_ID,
            self::GUIDEBOOK_URL_PREFIX,
            self::TRANSCRIPT_URL_PREFIX,
        );

        $this->_initCourseIds();
    }

    private function _initCourseIds()
    {
        $this->_courseIds = (array)Mage::getModel('catalog/product')
            ->getCollection()
            ->addAttributeToSelect('course_id')
            ->getColumnValues('course_id');
    }

    public function getEntityTypeCode()
    {
        return 'akamai_content';
    }

    public function validateRow(array $rowData, $rowNum)
    {
        try {
            if (Mage_ImportExport_Model_Import::BEHAVIOR_APPEND == $this->getBehavior() && $this->_rowExists($rowData)) {
                throw new InvalidArgumentException(
                    Mage::helper('tgc_dl')->__(
                        'A row with Course ID %s already exists',
                        $rowData[self::COURSE_ID]
                    )
                );
            }
            if (!$this->_courseExists($rowData)) {
                throw new InvalidArgumentException(
                    Mage::helper('tgc_dl')->__(
                        'A product with Course ID %s does not exist',
                        $rowData[self::COURSE_ID]
                    )
                );
            }
            $this->_map($rowData);
            return true;
        } catch (InvalidArgumentException $e) {
            $this->addRowError($e->getMessage(), $rowNum);
            return false;
        }
    }

    private function _rowExists(array $row)
    {
        $select = $this->_connection->select()
            ->from($this->_entityTable, 'entity_id')
            ->where('course_id = :courseId');
        $bind = array(
            ':courseId' => (string)$row[self::COURSE_ID],
        );

        return $this->_connection->fetchOne($select, $bind);
    }

    private function _courseExists(array $row)
    {
        return in_array($row[self::COURSE_ID], $this->_courseIds);
    }

    protected function _importData()
    {
        if (Mage_ImportExport_Model_Import::BEHAVIOR_DELETE == $this->getBehavior()) {
            $this->_deleteAkamaiContent();

        } else if (Mage_ImportExport_Model_Import::BEHAVIOR_REPLACE == $this->getBehavior()) {
            $this->_updateAkamaiContent();

        } else {
            $this->_saveAkamaiContent();
        }

        return true;
    }

    private function _updateAkamaiContent()
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

    private function _saveAkamaiContent()
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

    private function _deleteAkamaiContent()
    {
        while ($bunch = $this->_dataSourceModel->getNextBunch()) {
            $idsToDelete = array();
            $resource = Mage::getResourceModel('tgc_dl/akamaiContent');

            foreach ($bunch as $rowData) {
                $idsToDelete[] = $this->_rowExists($rowData);
            }
            $idsToDelete = array_filter($idsToDelete);
            if ($idsToDelete) {
                $resource->deleteRowsByIds($idsToDelete);
            }
        }
    }

    protected function _map(array $row)
    {
        return array(
            self::COURSE_ID             => $row[self::COURSE_ID],
            self::GUIDEBOOK_FILE_NAME   => $row[self::GUIDEBOOK_FILE_NAME],
            self::GUIDEBOOK_URL_PREFIX  => $row[self::GUIDEBOOK_URL_PREFIX],
            self::TRANSCRIPT_FILE_NAME  => $row[self::TRANSCRIPT_FILE_NAME],
            self::TRANSCRIPT_URL_PREFIX => $row[self::TRANSCRIPT_URL_PREFIX],
        );
    }
}
