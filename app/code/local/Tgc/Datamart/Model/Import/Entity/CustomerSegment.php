<?php
/**
 * DataMart integration
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DataMart
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Datamart_Model_Import_Entity_CustomerSegment extends Tgc_Dax_Model_Import_Entity_Checksum_Base
{
    //the names used in the import file
    const COL_CUSTOMER_ID        = 'dax_customer_id';
    const COL_SEGMENT_GROUP      = 'segmentgroup';

    //attribute codes
    const DAX_CUSTOMER_ID        = 'dax_customer_id';
    const DATAMART_CUSTOMER_PREF = 'datamart_customer_pref';

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
        $this->_entityTable     = Mage::getModel('customer/customer')->getResource()->getEntityTable();

        $this->_permanentAttributes = array(
            self::COL_CUSTOMER_ID,
            self::COL_SEGMENT_GROUP,
        );
    }

    public function getEntityTypeCode()
    {
        return 'customer_segment';
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
        return true;
    }

    protected function _importData()
    {
        if (Mage_ImportExport_Model_Import::BEHAVIOR_DELETE == $this->getBehavior()) {
            $this->_deleteCustomerSegment();
        } else {
            $this->_saveCustomerSegment();
        }

        return true;
    }

    /**
     * Save customer segment
     */
    private function _saveCustomerSegment()
    {
        while ($bunch = $this->_dataSourceModel->getNextBunch()) {
            foreach ($bunch as $rowNum => $rowData) {
                $where = array(
                    sprintf("%s = '%s'", self::DAX_CUSTOMER_ID, $rowData[self::COL_CUSTOMER_ID])
                );
                try {
                    $this->_connection->update($this->_entityTable, $this->_map($rowData), $where);
                } catch (InvalidArgumentException $e) {
                    $this->addRowError($e->getCode(), $rowNum);
                }
            }
        }
    }

    /**
     * Delete customer segment
     */
    private function _deleteCustomerSegment()
    {
        while ($bunch = $this->_dataSourceModel->getNextBunch()) {
            foreach ($bunch as $rowNum => $rowData) {
                $bind = array(
                    self::DATAMART_CUSTOMER_PREF => null,
                );
                $where = array(
                    sprintf("%s = '%s'", self::DAX_CUSTOMER_ID, $rowData[self::COL_CUSTOMER_ID])
                );
                try {
                    $this->_connection->update($this->_entityTable, $bind, $where);
                } catch (InvalidArgumentException $e) {
                    $this->addRowError($e->getCode(), $rowNum);
                }
            }
        }
    }

    protected function _map(array $row)
    {
        return array(
            self::DATAMART_CUSTOMER_PREF => $row[self::COL_SEGMENT_GROUP],
        );
    }
}
