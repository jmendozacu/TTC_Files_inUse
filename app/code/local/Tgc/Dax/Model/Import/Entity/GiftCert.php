<?php
/**
 * Gift certificate import entity for Mage_ImportExport
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category
 * @package
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Dax_Model_Import_Entity_GiftCert extends Tgc_Dax_Model_Import_Entity_Checksum_Base
{
    const COL_CODE = 'code';
    const COL_WEBSITE = 'website';
    const COL_EXPIRES = 'date_expires';
    const COL_BALANCE = 'balance';
    const COL_REDEAMABLE = 'is_redeemable';

    const ERROR_INVALID_WEBSITE = 1;

    private $_entityTable;
    private $_websitesCache = array();
    private $_validationCache = array();

    /**
     * Constructor.
     *
     * @return void
     */
    public function __construct()
    {
        $this->_dataSourceModel = Mage_ImportExport_Model_Import::getDataSourceModel();
        $this->_dataSourceModel->setEntityTypeCode($this->getEntityTypeCode());
        $this->_connection = Mage::getSingleton('core/resource')->getConnection('write');
        $this->_entityTable = Mage::getResourceModel('enterprise_giftcardaccount/giftcardaccount')->getMainTable();

        $this->addMessageTemplate(self::ERROR_INVALID_WEBSITE, 'Invalid website code');

        $this->_permanentAttributes = array(
            self::COL_BALANCE,
            self::COL_CODE,
            self::COL_EXPIRES,
            self::COL_REDEAMABLE,
            self::COL_WEBSITE,
        );
    }

    public function getEntityTypeCode()
    {
        return 'giftcert';
    }

    public function validateRow(array $rowData, $rowNum)
    {
        try {
            $this->_map($rowData);
            return true;
        } catch (InvalidArgumentException $e) {
            $this->addRowError($e->getCode(), $rowNum);
            return false;
        }
    }

    protected function _importData()
    {
        $result = true;
        while ($bunch = $this->_dataSourceModel->getNextBunch()) {
            foreach ($bunch as $rowNum => $rowData) {
                try {
                    $this->_connection->insertMultiple($this->_entityTable, $this->_map($rowData));
                } catch (InvalidArgumentException $e) {
                    $this->addRowError($e->getCode(), $rowNum);
                    $result = false;
                }
            }
        }

        return $result;
    }

    protected function _map(array $row)
    {
        $createdAt = new DateTime;
        $websiteId = $this->_getWesiteByCode($row[self::COL_WEBSITE])->getId();
        $redeemable = $row[self::COL_REDEAMABLE] ? 1 : 0;
        $status = Enterprise_GiftCardAccount_Model_Giftcardaccount::STATUS_ENABLED;

        return array(
            'code' => $row[self::COL_CODE],
            'status' => $status,
            'date_created' => $createdAt->format(DateTime::ISO8601),
            'date_expires' => $row[self::COL_EXPIRES],
            'website_id' => $websiteId,
            'balance' => (float)$row[self::COL_BALANCE],
            'state' => Enterprise_GiftCardAccount_Model_Giftcardaccount::STATE_AVAILABLE,
            'is_redeemable' => $redeemable,
        );
    }

    protected function _getWesiteByCode($code)
    {
        if (!isset($this->_websitesCache[$code])) {
            $website = Mage::getModel('core/website')->load($code);
            if ($website->isObjectNew()) {
                throw new InvalidArgumentException("Website $code does not exist.", self::ERROR_INVALID_WEBSITE);
            }
            $this->_websitesCache[$code] = $website;
        }

        return $this->_websitesCache[$code];
    }
}
