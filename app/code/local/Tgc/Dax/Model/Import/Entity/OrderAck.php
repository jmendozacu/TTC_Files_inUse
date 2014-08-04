<?php
/**
 * Order acknowledge import entity
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Dax
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 * PREVIOUSLY USED CHECKSUM INTERFACE
 */
class Tgc_Dax_Model_Import_Entity_OrderAck extends Tgc_Dax_Model_Import_Entity_Checksum_Base
{
    const COL_ORDER_ID     = 'web_order_id';
    const COL_DAX_ORDER_ID = 'dax_sales_id';

    private   $_entityTable;

    public function __construct()
    {
        $this->_dataSourceModel = Mage_ImportExport_Model_Import::getDataSourceModel();
        $this->_dataSourceModel->setEntityTypeCode($this->getEntityTypeCode());
        $this->_connection      = Mage::getSingleton('core/resource')->getConnection('write');
        $this->_entityTable     = Mage::getResourceModel('sales/order')->getMainTable();

        $this->_permanentAttributes = array(
            self::COL_ORDER_ID,
            self::COL_DAX_ORDER_ID,
        );
    }

    public function getEntityTypeCode()
    {
        return 'orderack';
    }

    public function validateRow(array $rowData, $rowNum)
    {
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
            throw new InvalidArgumentException('This import is only for acknowledging orders sent to DAX.');
        }

        while ($bunch = $this->_dataSourceModel->getNextBunch())
        {
            foreach ($bunch as $rowNum => $rowData) {
                if ($rowNum == 0) {
                    continue;
                }

                $data   = $this->_map($rowData);
                $update = array(
                    'dax_order_id' => $data['dax_order_id'],
                    'dax_received' => '1',
                );
                try {
                    $where = $this->_connection->quoteInto('increment_id = (?)', $data['increment_id']);
                    $this->_connection->update($this->_entityTable, $update, $where);
                } catch (Exception $e) {
                    Mage::logException($e);
                }
            }
        }
        return true;
    }

    protected function _map(array $row)
    {
        if (empty($row[self::COL_DAX_ORDER_ID]) || empty($row[self::COL_ORDER_ID])) {
            if (empty($row[self::COL_DAX_ORDER_ID])) {
                throw new InvalidArgumentException('DAX Order ID missing');
            }

            throw new InvalidArgumentException('Magento Order ID missing');
        }

        return array(
            'increment_id' => $row[self::COL_ORDER_ID],
            'dax_order_id' => $row[self::COL_DAX_ORDER_ID],
        );
    }
}
