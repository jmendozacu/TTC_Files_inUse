<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DataMart
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_Datamart_Model_Resource_EmailLanding_Mediacode extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * Initialize resource model
     *
     */
    protected function _construct()
    {
        $this->_init('tgc_datamart/landing_media_code', 'entity_id');
    }

    /**
     * Perform operations after object load
     *
     * @param Mage_Core_Model_Abstract $object
     * @return Tgc_Datamart_Model_Resource_EmailLanding_Mediacode
     */
    protected function _afterLoad(Mage_Core_Model_Abstract $object)
    {
        if ($object->getId()) {
            $adCodes = $this->loadAliases($object->getId());
            $object->setData('media_code_aliases', $adCodes);
        }

        return parent::_afterLoad($object);
    }

    /**
     * Perform operations after object save
     *
     * @param Mage_Core_Model_Abstract $object
     * @return Tgc_Datamart_Model_EmailLanding_Mediacode
     */
    protected function _afterSave(Mage_Core_Model_Abstract $object)
    {
        $oldAliases = $this->loadAliases($object->getId());
        $newAliases = (array)$object->getMediaCodeAliases();

        $table  = $this->getTable('tgc_datamart/landing_media_code_alias');
        $insert = array_diff($newAliases, $oldAliases);
        $delete = array_diff($oldAliases, $newAliases);

        if ($delete) {
            $where = array(
                'media_code_id = ?'  => (int) $object->getId(),
                'alias IN (?)' => $delete
            );

            $this->_getWriteAdapter()->delete($table, $where);
        }

        if ($insert) {
            $data = array();

            foreach ($insert as $alias) {
                $data[] = array(
                    'media_code_id'  => (int) $object->getId(),
                    'alias' => $alias
                );
            }

            $this->_getWriteAdapter()->insertMultiple($table, $data);
        }

        return parent::_afterSave($object);
    }

    /**
     * Get media code aliases
     *
     * @param int $id
     * @return array
     */
    public function loadAliases($id)
    {
        $adapter = $this->_getReadAdapter();

        $select  = $adapter->select()
            ->from($this->getTable('tgc_datamart/landing_media_code_alias'), 'alias')
            ->where('media_code_id = :media_code_id');

        $binds = array(
            ':media_code_id' => (int) $id
        );

        return $adapter->fetchCol($select, $binds);
    }

    /**
     * Load media code data by media code
     *
     * @param Tgc_Datamart_Model_EmailLanding_Mediacode $object
     * @param integer $mediaCode
     * @return boolean
     */
    public function loadByMediaCode(Tgc_Datamart_Model_EmailLanding_Mediacode $object, $mediaCode)
    {
        $read = $this->_getReadAdapter();
        $select = $read->select()->from(array('main_table' => $this->getMainTable()))
            ->joinLeft(
                array('alias' => $this->getTable('tgc_datamart/landing_media_code_alias')),
                'alias.media_code_id = main_table.entity_id',
                array())
            ->where('main_table.media_code = ?', $mediaCode)
            ->orWhere('alias.alias = ?', $mediaCode)
            ->limit(1);

        $data = $read->fetchRow($select);

        if (!$data) {
            return false;
        }

        $object->setData($data);

        $this->_afterLoad($object);
        return true;
    }
}
