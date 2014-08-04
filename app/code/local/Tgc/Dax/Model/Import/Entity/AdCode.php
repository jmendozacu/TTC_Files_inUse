<?php
/**
 * Dax adcode entity for importexport
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Dax
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Dax_Model_Import_Entity_AdCode extends Tgc_Dax_Model_Import_Entity_Checksum_Base
{
    //the attribute names
    const ADCODE             = 'code';
    const NAME               = 'name';
    const DESCRIPTION        = 'description';
    const ACTIVE_FLAG        = 'active_flag';
    const CUSTOMER_GROUP_ID  = 'customer_group_id';

    //the names used in the import file
    const COL_ADCODE         = 'AdCode';
    const COL_NAME           = 'Name';
    const COL_DESCRIPTION    = 'Description';
    const COL_ACTIVE_FLAG    = 'ActiveFlag';
    const COL_CATALOG_CODE   = 'CatalogCode';

    const ERROR_INVALID_CATALOG_CODE = 3;
    const ERROR_INVALID_AD_CODE      = 4;

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
        /** @var _entityTable `ad_code` */
        $this->_entityTable     = Mage::getResourceModel('tgc_price/adCode')->getMainTable();

        $this->_permanentAttributes = array(
            self::COL_ADCODE,
            self::COL_CATALOG_CODE,
            self::COL_DESCRIPTION,
            self::COL_NAME,
            self::COL_ACTIVE_FLAG,
        );

        $this->_initErrorMessages();
    }

    private function _initErrorMessages()
    {
        $this->addMessageTemplate(self::ERROR_INVALID_CATALOG_CODE, 'Empty catalog code');
        $this->addMessageTemplate(self::ERROR_INVALID_AD_CODE, 'Empty ad code');
    }

    /**
     * We use ucfirst column names so they are all particular
     *
     * @param string $attrCode
     * @return bool
     */
    public function isAttributeParticular($attrCode)
    {
        return in_array($attrCode, $this->_permanentAttributes);
    }

    public function getEntityTypeCode()
    {
        return 'adcode';
    }

    public function validateRow(array $rowData, $rowNum)
    {
        if (Mage_ImportExport_Model_Import::BEHAVIOR_DELETE == $this->getBehavior()) {
            return true;
        }

        try {
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
            return $this->_deleteAdCodes();
        } else {
            $this->_updateAdCodes();
        }

        return true;
    }

    private function _updateAdCodes()
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
     * Delete ad codes
     */
    private function _deleteAdCodes()
    {
        try {
            while ($bunch = $this->_dataSourceModel->getNextBunch()) {
                $codesToDelete = array();

                foreach ($bunch as $rowData) {
                    $codesToDelete[] = $rowData[self::COL_ADCODE];
                }
                if ($codesToDelete) {
                    $this->_connection->delete(
                        $this->_entityTable,
                        $this->_connection->quoteInto('code IN (?)', $codesToDelete)
                    );
                }
            }
        } catch (Exception $e) {
            Mage::logException($e);
        }
    }

    protected function _map(array $row)
    {
        try {
            $groupId = Mage::helper('tgc_price')->getCustomerGroupIdByCatalogCode($row[self::COL_CATALOG_CODE]);
        } catch (InvalidArgumentException $e) {
            throw new InvalidArgumentException($e->getMessage(), self::ERROR_INVALID_CATALOG_CODE, $e);
        }

        $adCode = (int)$row[self::COL_ADCODE];
        if (!$adCode) {
            throw new InvalidArgumentException('Invalid ad code', self::ERROR_INVALID_AD_CODE);
        }

        return array(
            self::ADCODE            => $row[self::COL_ADCODE],
            self::NAME              => $row[self::COL_NAME],
            self::DESCRIPTION       => $row[self::COL_DESCRIPTION],
            self::ACTIVE_FLAG       => $row[self::COL_ACTIVE_FLAG],
            self::CUSTOMER_GROUP_ID => $groupId,
        );
    }
}
