E<?php
/**
 * Digital Library Cross Platform Resume Import
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DigitalLibrary
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_DigitalLibrary_Model_Import_Entity_CrossPlatformResume extends Tgc_Dax_Model_Import_Entity_Checksum_Base
{
    //attribute names in digital_library_cross+platform_resume table
    const ENTITY_ID          = 'entity_id';
    const LECTURE_ID         = 'lecture_id';
    const WEB_USER_ID        = 'web_user_id';
    const PROGRESS           = 'progress';
    const DOWNLOAD_DATE      = 'download_date';
    const STREAM_DATE        = 'stream_date';
    const WATCHED            = 'watched';
    const FORMAT             = 'format';

    //the fields in the import file
    const COL_DAX_CUSTOMER_ID = 'dax_customer_id';
    const COL_COURSE_ID       = 'course_id';
    const COL_FORMAT          = 'format';
    const COL_LECTURE_NUMBER  = 'lecture_number';
    const COL_PROGRESS        = 'progress';
    const COL_LAST_VIEW_DATE  = 'last_view_date';
    const COL_WATCHED         = 'watched';

    private $_entityTable;
    private $_courseIdsToProductIds;

    public function __construct()
    {
        /** @var _dataSourceModel Mage_ImportExport_Model_Resource_Import_Data */
        $this->_dataSourceModel   = Mage_ImportExport_Model_Import::getDataSourceModel();
        $this->_dataSourceModel->setEntityTypeCode($this->getEntityTypeCode());
        /** @var _connection Magento_Db_Adapter_Pdo_Mysql */
        $this->_connection        = Mage::getSingleton('core/resource')->getConnection('write');
        /** @var _entityTable `digital_library_cross_platform_resume` */
        $this->_entityTable       = Mage::getResourceModel('tgc_dl/crossPlatformResume')->getMainTable();

        $this->_permanentAttributes = array(
            self::COL_DAX_CUSTOMER_ID,
            self::COL_COURSE_ID,
            self::COL_FORMAT,
            self::COL_LECTURE_NUMBER,
            self::COL_PROGRESS,
            self::COL_LAST_VIEW_DATE,
            self::COL_WATCHED,
            self::COL_FORMAT,
        );

        $this->_initCourseIdsToProductIds();
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

    public function getEntityTypeCode()
    {
        return 'cross_platform_resume';
    }

    public function validateRow(array $rowData, $rowNum)
    {
        try {
            if (Mage_ImportExport_Model_Import::BEHAVIOR_DELETE == $this->getBehavior()) {
                return (bool)$this->_rowExists($rowData);
            }
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
            $this->_deleteResumeData();

        } else if (Mage_ImportExport_Model_Import::BEHAVIOR_REPLACE == $this->getBehavior()) {
            $this->_updateResumeData();

        } else {
            $this->_saveResumeData();
        }

        return true;
    }

    private function _updateResumeData()
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

    private function _saveResumeData()
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

    private function _deleteResumeData()
    {
        while ($bunch = $this->_dataSourceModel->getNextBunch()) {
            $idsToDelete = array();
            $resource = Mage::getResourceModel('tgc_dl/crossPlatformResume');

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
        $streamDate = empty($row[self::COL_LAST_VIEW_DATE]) ? null : date(
            Varien_Date::DATETIME_PHP_FORMAT,
            Mage::getModel('core/date')->timestamp(strtotime($row[self::COL_LAST_VIEW_DATE]))
        );

        if (empty($row[self::COL_COURSE_ID])) {
            $message = Mage::helper('tgc_dl')->__(
                'Course ID column cannot be empty'
            );
            throw new InvalidArgumentException($message);
        }
        if (empty($row[self::COL_LECTURE_NUMBER])) {
            $message = Mage::helper('tgc_dl')->__(
                'Lecture Number column cannot be empty'
            );
            throw new InvalidArgumentException($message);
        }
        if (empty($row[self::COL_DAX_CUSTOMER_ID])) {
            $message = Mage::helper('tgc_dl')->__(
                'Dax Customer ID column cannot be empty'
            );
            throw new InvalidArgumentException($message);
        }

        return array(
            self::ENTITY_ID     => $this->_rowExists($row),
            self::LECTURE_ID    => $this->_validateLectureId($row),
            self::WEB_USER_ID   => $this->_validateWebUserId($row),
            self::PROGRESS      => intval($row[self::COL_PROGRESS]),
            self::DOWNLOAD_DATE => null,
            self::STREAM_DATE   => $streamDate,
            self::WATCHED       => empty($row[self::COL_WATCHED]) ? 0 : 1,
            self::FORMAT        => empty($row[self::COL_FORMAT]) ? 0 : 1,
        );
    }

    private function _rowExists(array $row)
    {
        $select = $this->_connection->select()
            ->from($this->_entityTable, 'entity_id')
            ->where('lecture_id = :lectureId')
            ->where('web_user_id = :webUserId')
            ->where('format = :format');

        $bind = array(
            ':lectureId' => (int)$this->_validateLectureId($row),
            ':webUserId' => (string)$this->_validateWebUserId($row),
            ':format'    => (int)$row[self::COL_FORMAT],
        );

        return $this->_connection->fetchOne($select, $bind);
    }

    private function _validateLectureId(array $row)
    {
        $productId = $this->_getProductIdFromCourseId($row[self::COL_COURSE_ID]);

        $select = $this->_connection->select()
            ->from('lectures', 'id')
            ->where('product_id = :productId')
            ->where('lecture_number = :lectureNumber');

        $bind = array(
            ':productId'     => (int)$productId,
            ':lectureNumber' => (int)$row[self::COL_LECTURE_NUMBER],
        );

        $lectureId = (int)$this->_connection->fetchOne($select, $bind);

        if (empty($lectureId)) {
            throw new InvalidArgumentException(
                Mage::helper('tgc_dl')->__('Lecture with ID %s does not exist', $lectureId)
            );
        }

        return $lectureId;
    }

    private function _validateWebUserId(array $row)
    {
        $webUserId = Mage::getResourceModel('tgc_dl/crossPlatformResume')
            ->getWebUserIdByDaxCustomerId($row[self::COL_DAX_CUSTOMER_ID]);
        if (empty($webUserId)) {
            throw new InvalidArgumentException(
                Mage::helper('tgc_dl')->__(
                    'Dax Customer ID %s does not belong to any existing customer',
                    $row[self::COL_DAX_CUSTOMER_ID]
                )
            );
        }

        return $webUserId;
    }

    private function _getProductIdFromCourseId($courseId)
    {
        if (isset($this->_courseIdsToProductIds[$courseId])) {
            return $this->_courseIdsToProductIds[$courseId];
        }

        throw new InvalidArgumentException(
            Mage::helper('tgc_dl')->__('There is no product with Course ID %s', $courseId)
        );
    }
}
