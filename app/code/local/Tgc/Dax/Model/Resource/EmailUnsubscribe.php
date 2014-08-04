<?php
/**
 * Dax integration
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Dax
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Dax_Model_Resource_EmailUnsubscribe extends Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct()
    {
        $this->_init('tgc_dax/emailUnsubscribe', 'entity_id');
    }

    public function getCollection()
    {
        return Mage::getResourceModel('tgc_dax/emailUnsubscribe_collection');
    }

    public function archiveRecords()
    {
        $read = $this->_getReadAdapter();

        $select = $read->select()
            ->from($this->getMainTable(), 'entity_id')
            ->where('is_archived = :notArchived')
            ->where('DATEDIFF( NOW(), unsubscribe_date) >= :archiveDays');

        $bind = array(
            ':notArchived' => 0,
            ':archiveDays' => 30,
        );

        $idsToArchive = (array)$read->fetchCol($select, $bind);

        if (!empty($idsToArchive)) {
            $write = $this->_getWriteAdapter();

            $bind = array(
                'is_archived' => 1,
            );

            $write->update(
                $this->getMainTable(),
                $bind,
                array($write->quoteInto("`entity_id` IN(?) ", $idsToArchive))
            );
        }
    }

    /**
     * Delete rows given an array of entity ids
     *
     * @param array $idsToDelete
     */
    public function deleteRowsByIds(array $idsToDelete)
    {
        $adapter = $this->_getWriteAdapter();

        $adapter->delete(
            $this->getMainTable(),
            array($adapter->quoteInto("`entity_id` IN(?) ", $idsToDelete))
        );
    }

    public function getDaxCustomerIdsByEmail($email)
    {
        $adapter = $this->_getReadAdapter();

        $select = $adapter->select()
            ->from($this->getTable('customer/entity'), 'dax_customer_id')
            ->where('email = :email');

        $bind = array(
            ':email' => (string)$email,
        );

        return (array)$adapter->fetchCol($select, $bind);
    }
}
