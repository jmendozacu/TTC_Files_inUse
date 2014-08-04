E<?php
/**
 * Digital Library Access Rights Import
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DigitalLibrary
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 * PREVIOUSLY USED CHECKSUM INTERFACE
 */
class Tgc_DigitalLibrary_Model_Import_Entity_AccessRights extends Tgc_Dax_Model_Import_Entity_Checksum_Base
{
    //attribute names in digital_library_access_rights table
    const ENTITY_ID          = 'entity_id';
    const COURSE_ID          = 'course_id';
    const FORMAT             = 'format';
    const WEB_USER_ID        = 'web_user_id';
    const DATE_PURCHASED     = 'date_purchased';
    const IS_DOWNLOADABLE    = 'is_downloadable';
    const DIGITAL_TRANSCRIPT = 'digital_transcript_purchased';

    //the fields in the import file
    const COL_COURSE_ID          = 'course_id'; //the course id
    const COL_DAX_CUSTOMER_ID    = 'dax_customer_id'; //the customer web user id
    const COL_FORMAT             = 'format'; //media format: 0 for Audio, 1 for Video
    const COL_IS_DOWNLOADABLE    = 'download_allowed'; //int 1 or 0 whether download is allowed or empty for false
    const COL_DIGITAL_TRANSCRIPT = 'digital_transcript'; //int 1 or 0 whether digital transcript has been purchased or empty for false
    const COL_DATE_PURCHASED     = 'timestamp'; //will map to date_purchased
    const COL_HAS_ACCESS         = 'has_access'; //will be false if access should be removed

    private $_entityTable;
    private $_customerTable;
    private $_productTable;
    private $_eavAttributeTable;
    private $_varcharTable;
    private $_courseIdsToProductIds;

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
        /** @var _entityTable `digital_library_access_rights` */
        $this->_entityTable       = Mage::getResourceModel('tgc_dl/accessRights')->getMainTable();
        $this->_customerTable     = 'customer_entity';
        $this->_productTable      = 'catalog_product_entity';
        $this->_eavAttributeTable = 'eav_attribute';
        $this->_varcharTable      = 'catalog_product_entity_varchar';

        $this->_permanentAttributes = array(
            self::COL_COURSE_ID,
            self::COL_DAX_CUSTOMER_ID,
            self::COL_FORMAT,
            self::COL_IS_DOWNLOADABLE,
            self::COL_DIGITAL_TRANSCRIPT,
            self::COL_DATE_PURCHASED,
            self::COL_HAS_ACCESS,
        );

        $this->_initCourseIdsToProductIds();
    }

    public function getEntityTypeCode()
    {
        return 'access_rights';
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
        if (Mage_ImportExport_Model_Import::BEHAVIOR_DELETE == $this->getBehavior()) {
            return true;
        }
        try {
            if (Mage_ImportExport_Model_Import::BEHAVIOR_APPEND == $this->getBehavior()) {
                if ($this->_rowExists($rowData)) {
                    $message = Mage::helper('tgc_dl')->__(
                        'A row with these values already exists'
                    );
                    throw new InvalidArgumentException($message);
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
            $this->_deleteAccessRights();

        } else if (Mage_ImportExport_Model_Import::BEHAVIOR_REPLACE == $this->getBehavior()) {
            $this->_updateAccessRights();

        } else {
            $this->_saveAccessRights();
        }

        return true;
    }

    /**
     * Update existing access rights
     */
    private function _updateAccessRights()
    {
        while ($bunch = $this->_dataSourceModel->getNextBunch()) {
            foreach ($bunch as $rowNum => $rowData) {
                if ($rowNum == 0) {
                    continue;
                }
                if ($this->_removeAccess($rowData)) {
                    continue;
                }
                try {
                    $this->_connection->insertOnDuplicate($this->_entityTable, $this->_map($rowData));
                } catch (InvalidArgumentException $e) {
                    $this->addRowError($e->getMessage(), $rowNum);
                }
            }
        }
    }

    /**
     * Save access rights
     */
    private function _saveAccessRights()
    {
        while ($bunch = $this->_dataSourceModel->getNextBunch()) {
            $data = array();
            foreach ($bunch as $rowNum => $rowData) {
                if ($rowNum == 0) {
                    continue;
                }
                if ($this->_removeAccess($rowData)) {
                    continue;
                }
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
     * Delete access rights
     */
    private function _deleteAccessRights()
    {
        while ($bunch = $this->_dataSourceModel->getNextBunch()) {
            $idsToDelete = array();
            /** @var $resource Tgc_DigitalLibrary_Model_Resource_AccessRights */
            $resource = Mage::getResourceModel('tgc_dl/accessRights');

            foreach ($bunch as $rowNum => $rowData) {
                if ($rowNum == 0) {
                    continue;
                }
                if ($this->_removeAccess($rowData)) {
                    continue;
                }
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
        $date = date(
            Varien_Date::DATE_PHP_FORMAT,
            Mage::getModel('core/date')->timestamp(strtotime($row[self::COL_DATE_PURCHASED]))
        );

        if (empty($row[self::COL_COURSE_ID])) {
            $message = Mage::helper('tgc_dl')->__(
                'Course ID column cannot be empty'
            );
            throw new InvalidArgumentException($message);
        }
        if (empty($row[self::COL_DAX_CUSTOMER_ID])) {
            $message = Mage::helper('tgc_dl')->__(
                'DAX Customer ID column cannot be empty'
            );
            throw new InvalidArgumentException($message);
        }
        return array(
            self::ENTITY_ID          => $this->_rowExists($row),
            self::COURSE_ID          => $this->_validateCourseId($row[self::COL_COURSE_ID]),
            self::FORMAT             => $this->_validateFormat($row[self::COL_FORMAT]),
            self::WEB_USER_ID        => $this->_validateWebUserId($row[self::COL_DAX_CUSTOMER_ID]),
            self::IS_DOWNLOADABLE    => empty($row[self::COL_IS_DOWNLOADABLE]) ? 0 : 1,
            self::DIGITAL_TRANSCRIPT => empty($row[self::COL_DIGITAL_TRANSCRIPT]) ? 0 : 1,
            self::DATE_PURCHASED     => $date,
        );
    }

    private function _rowExists(array $row)
    {
        $select = $this->_connection->select()
            ->from($this->_entityTable, 'entity_id')
            ->where('course_id = :courseId')
            ->where('web_user_id = :webUserId')
            ->where('format = :format');

        $bind = array(
            ':courseId'  => (int)$this->_validateCourseId($row[self::COL_COURSE_ID]),
            ':webUserId' => (string)$this->_validateWebUserId($row[self::COL_DAX_CUSTOMER_ID]),
            ':format'    => (int)$this->_validateFormat($row[self::COL_FORMAT]),
        );

        return $this->_connection->fetchOne($select, $bind);
    }

    //ensure this product exists and return it's entity id
    private function _validateCourseId($courseId)
    {
        if (isset($this->_courseIdsToProductIds[$courseId])) {
            return $this->_courseIdsToProductIds[$courseId];
        }

        $select = $this->_connection->select()
            ->from(array('product' => $this->_productTable), array())
            ->joinLeft(array('entity' => $this->_eavAttributeTable),
                'product.entity_type_id = entity.entity_type_id AND entity.attribute_code = \'course_id\'',
                array()
            )
            ->joinLeft(array('varchar' => $this->_varcharTable),
                'varchar.entity_type_id = entity.entity_type_id AND varchar.attribute_id = entity.attribute_id AND varchar.store_id = 0',
                array('varchar.entity_id')
            )
            ->where('product.type_id = :typeId')
            ->where('varchar.value = :courseId');
        $bind = array(
            ':typeId'   => 'configurable',
            ':courseId' => (string)$courseId,
        );
        $entityId = (int)$this->_connection->fetchOne($select, $bind);

        if (empty($entityId)) {
            throw new InvalidArgumentException(
                Mage::helper('tgc_dl')->__(
                    'Invalid Course ID: %s supplied. There is no corresponding product.',
                    $courseId
                )
            );
        }

        return $entityId;
    }

    private function _validateFormat($format)
    {
        return $format ==  1 ? 1 : 0;
    }

    //ensure web user id exists for dax customer id and return it
    private function _validateWebUserId($daxCustomerId)
    {
        $select = $this->_connection->select()
            ->from($this->_customerTable, 'web_user_id')
            ->where('dax_customer_id = :daxCustomerId');
        $bind = array(
            ':daxCustomerId' => (string)$daxCustomerId,
        );
        $webUserId = $this->_connection->fetchOne($select, $bind);

        if (empty($webUserId)) {
            throw new InvalidArgumentException(
                Mage::helper('tgc_dl')->__(
                    'Invalid DAX Customer ID: %s supplied. There is no corresponding customer.',
                    $daxCustomerId
                )
            );
        }

        return $webUserId;
    }

    private function _shouldRemoveAccess(array $row)
    {
        return strtolower($row[self::COL_HAS_ACCESS]) == 'false';
    }

    private function _removeAccess(array $row)
    {
        if (!$this->_shouldRemoveAccess($row)) {
            return false;
        }

        $select = $this->_connection->select()
            ->from($this->_entityTable, 'entity_id')
            ->where('course_id = :courseId')
            ->where('web_user_id = :webUserId')
            ->where('format = :format');
        $bind = array(
            ':courseId'  => (int)$this->_validateCourseId($row[self::COL_COURSE_ID]),
            ':webUserId' => (string)$this->_validateWebUserId($row[self::COL_DAX_CUSTOMER_ID]),
            ':format'    => (int)$this->_validateFormat($row[self::COL_FORMAT]),
        );

        $idToDelete = $this->_connection->fetchOne($select, $bind);

        if ($idToDelete) {
            $resource = Mage::getResourceModel('tgc_dl/accessRights');
            $resource->deleteRowsByIds(array($idToDelete));
        }

        return true;
    }
}
