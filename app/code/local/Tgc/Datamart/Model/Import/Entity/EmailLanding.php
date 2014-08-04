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
class Tgc_Datamart_Model_Import_Entity_EmailLanding extends Tgc_Dax_Model_Import_Entity_Checksum_Base
{
    //the real attribute names
    const CATEGORY            = 'category';
    const COURSE_ID           = 'course_id';
    const SORT_ORDER          = 'sort_order';
    const MARKDOWN_FLAG       = 'markdown_flag';
    const SPECIAL_MESSAGE     = 'special_message';
    const DATE_EXPIRES        = 'date_expires';

    //the names used in the import file
    const COL_CATEGORY        = 'email_landing_category';
    const COL_COURSE_ID       = 'course_id';
    const COL_SORT_ORDER      = 'displayorder';
    const COL_MARKDOWN_FLAG   = 'markdown_flag';
    const COL_SPECIAL_MESSAGE = 'lander_msg';
    const COL_DATE_EXPIRES    = 'category_expires';

    private $_entityTable;

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
        $this->_entityTable     = Mage::getResourceModel('tgc_datamart/emailLanding')->getMainTable();

        $this->_permanentAttributes = array(
            self::COL_CATEGORY,
            self::COL_COURSE_ID,
            self::COL_SORT_ORDER,
            self::COL_MARKDOWN_FLAG,
            self::COL_SPECIAL_MESSAGE,
            self::COL_DATE_EXPIRES,
        );
    }

    public function getEntityTypeCode()
    {
        return 'email_landing';
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
                    $message = Mage::helper('tgc_datamart')->__(
                        'A row with category: %s and course ID: %s already exists',
                        $rowData[self::COL_CATEGORY],
                        $rowData[self::COL_COURSE_ID]
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
            $this->_deleteEmailLandingPages();

        } else if (Mage_ImportExport_Model_Import::BEHAVIOR_REPLACE == $this->getBehavior()) {
            $this->_updateEmailLandingPages();

        } else {
            $this->_saveEmailLandingPages();
        }

        return true;
    }

    /**
     * Update existing email landing pages
     */
    private function _updateEmailLandingPages()
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
     * Save email landing pages
     */
    private function _saveEmailLandingPages()
    {
        while ($bunch = $this->_dataSourceModel->getNextBunch()) {
            $data = array();
            foreach ($bunch as $rowNum => $rowData) {
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
     * Delete email landing pages
     */
    private function _deleteEmailLandingPages()
    {
        while ($bunch = $this->_dataSourceModel->getNextBunch()) {
            $idsToDelete = array();
            /** @var $resource Tgc_Datamart_Model_Resource_EmailLanding */
            $resource = Mage::getResourceSingleton('tgc_datamart/emailLanding');

            foreach ($bunch as $rowData) {
                $idsToDelete[] = $resource->getIdByCategoryAndCourse($rowData[self::COL_CATEGORY], $rowData[self::COL_COURSE_ID]);
            }
            if ($idsToDelete) {
                $resource->deleteRowsByIds($idsToDelete);
            }
        }
    }

    protected function _map(array $row)
    {
        $date = date(
            Varien_Date::DATE_PHP_FORMAT,
            Mage::getModel('core/date')->timestamp(strtotime($row[self::COL_DATE_EXPIRES]))
        );
        if (empty($row[self::COL_CATEGORY])) {
            $message = Mage::helper('tgc_datamart')->__(
                'Category column cannot be empty'
            );
            throw new InvalidArgumentException($message);
        }
        if (empty($row[self::COL_COURSE_ID])) {
            $message = Mage::helper('tgc_datamart')->__(
                'Course ID column cannot be empty'
            );
            throw new InvalidArgumentException($message);
        }
        return array(
            self::CATEGORY        => $row[self::COL_CATEGORY],
            self::COURSE_ID       => $row[self::COL_COURSE_ID],
            self::SORT_ORDER      => isset($row[self::COL_SORT_ORDER]) ? $row[self::COL_SORT_ORDER] : 0,
            self::MARKDOWN_FLAG   => isset($row[self::COL_MARKDOWN_FLAG]) ? $row[self::COL_MARKDOWN_FLAG] : 0,
            self::SPECIAL_MESSAGE => $row[self::COL_SPECIAL_MESSAGE],
            self::DATE_EXPIRES    => $date,
        );
    }

    private function _rowExists(array $row)
    {
        $select = $this->_connection->select()
            ->from($this->_entityTable, 'entity_id')
            ->where('category = :category')
            ->where('course_id = :courseId');

        $bind = array(
            ':category' => (string)$row[self::COL_CATEGORY],
            ':courseId' => (int)$row[self::COL_COURSE_ID],
        );

        return (bool)$this->_connection->fetchOne($select, $bind);
    }
}
