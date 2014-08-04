E<?php
/**
 * Digital Library Customer Merged Accounts Import
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DigitalLibrary
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_DigitalLibrary_Model_Import_Entity_MergeAccounts extends Tgc_Dax_Model_Import_Entity_Checksum_Base
{
    //attribute names
    const ENTITY_ID               = 'entity_id';
    const DAX_CUSTOMER_ID         = 'dax_customer_id';
    const MERGETO_DAX_CUSTOMER_ID = 'mergeto_dax_customer_id';

    //the fields in the import file
    const COL_ACCOUNTNUM      = 'accountnum';
    const COL_MERGETO_ACCOUNT = 'mergeto_account';

    private $_entityTable;
    private $_customerTable;
    private $_validDaxCustomerIds = array();

    public function __construct()
    {
        /** @var _dataSourceModel Mage_ImportExport_Model_Resource_Import_Data */
        $this->_dataSourceModel = Mage_ImportExport_Model_Import::getDataSourceModel();
        $this->_dataSourceModel->setEntityTypeCode($this->getEntityTypeCode());
        /** @var _connection Magento_Db_Adapter_Pdo_Mysql */
        $this->_connection      = Mage::getSingleton('core/resource')->getConnection('write');
        /** @var _entityTable `digital_library_cross_platform_resume` */
        $this->_entityTable     = Mage::getResourceModel('tgc_dl/mergeAccounts')->getMainTable();
        $this->_customerTable   = 'customer_entity';

        $this->_permanentAttributes = array(
            self::COL_ACCOUNTNUM,
            self::COL_MERGETO_ACCOUNT,
        );
    }

    public function getEntityTypeCode()
    {
        return 'merge_accounts';
    }

    public function validateRow(array $rowData, $rowNum)
    {
        try {
            if (Mage_ImportExport_Model_Import::BEHAVIOR_APPEND == $this->getBehavior() && $this->_rowExists($rowData)) {
                throw new InvalidArgumentException(
                    Mage::helper('tgc_dl')->__(
                        'A row with these values already exists'
                    )
                );
            }
            if ($rowData[self::COL_ACCOUNTNUM] == $rowData[self::COL_MERGETO_ACCOUNT]) {
                throw new InvalidArgumentException(
                    Mage::helper('tgc_dl')->__(
                        'The account with DAX Customer ID: %s cannot be merged to itself.',
                        $rowData[self::COL_ACCOUNTNUM]
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

    protected function _importData()
    {
        if (Mage_ImportExport_Model_Import::BEHAVIOR_DELETE == $this->getBehavior()) {
            $this->_deleteMergeAccountsData();

        } else if (Mage_ImportExport_Model_Import::BEHAVIOR_REPLACE == $this->getBehavior()) {
            $this->_updateMergeAccountsData();

        } else {
            $this->_saveMergeAccountsData();
        }

        return true;
    }

    private function _updateMergeAccountsData()
    {
        while ($bunch = $this->_dataSourceModel->getNextBunch()) {
            foreach ($bunch as $rowNum => $rowData) {
                try {
                    foreach ($this->_map($rowData) as $data) {
                        $this->_connection->insertOnDuplicate($this->_entityTable, $data);
                    }
                } catch (InvalidArgumentException $e) {
                    $this->addRowError($e->getCode(), $rowNum);
                }
            }
        }
    }

    private function _saveMergeAccountsData()
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

    private function _deleteMergeAccountsData()
    {
        while ($bunch = $this->_dataSourceModel->getNextBunch()) {
            $idsToDelete = array();
            $resource = Mage::getResourceModel('tgc_dl/mergeAccounts');

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
            self::ENTITY_ID               => $this->_rowExists($row),
            self::DAX_CUSTOMER_ID         => $this->_validateDaxCustomerId($row[self::COL_ACCOUNTNUM]),
            self::MERGETO_DAX_CUSTOMER_ID => $this->_validateDaxCustomerId($row[self::COL_MERGETO_ACCOUNT]),
        );
    }

    private function _rowExists(array $row)
    {
        $select = $this->_connection->select()
            ->from($this->_entityTable, 'entity_id')
            ->where('dax_customer_id = :daxCustomerId')
            ->where('mergeto_dax_customer_id = :mergetoId');
        $bind = array(
            ':daxCustomerId' => (string)$row[self::COL_ACCOUNTNUM],
            ':mergetoId' => (string)$row[self::COL_MERGETO_ACCOUNT],
        );

        return $this->_connection->fetchOne($select, $bind);
    }

    private function _validateDaxCustomerId($daxCustomerId)
    {
        if (isset($this->_validDaxCustomerIds[$daxCustomerId])) {
            return $this->_validDaxCustomerIds[$daxCustomerId];
        }

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

        $this->_validDaxCustomerIds[$daxCustomerId] = $daxCustomerId;

        return $this->_validDaxCustomerIds[$daxCustomerId];
    }
}
