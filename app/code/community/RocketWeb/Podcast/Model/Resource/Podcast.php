<?php

/**
 * RocketWeb
 *
 * @category   RocketWeb
 * @package    RocketWeb_Podcast
 * @copyright  Copyright (c) 2012 RocketWeb (http://rocketweb.com)
 * @author     RocketWeb
 */


class RocketWeb_Podcast_Model_Resource_Podcast extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        $this->_init('podcast/podcast', 'podcast_id');
    }
    
    protected function _afterSave(Mage_Core_Model_Abstract $object)
    {
        $condition = $this->_getWriteAdapter()->quoteInto('podcast_id = ?', $object->getId());
        $this->_getWriteAdapter()->delete($this->getTable('store'), $condition);

        if (!$object->getData('stores'))
        {
            $storeArray = array();
            $storeArray['podcast_id'] = $object->getId();
            $storeArray['store_id'] = '0';
            $this->_getWriteAdapter()->insert($this->getTable('store'), $storeArray);
        }
        else
        {
            foreach ((array)$object->getData('stores') as $store) {
                $storeArray = array();
                $storeArray['podcast_id'] = $object->getId();
                $storeArray['store_id'] = $store;
                $this->_getWriteAdapter()->insert($this->getTable('store'), $storeArray);
            }
        }
        return parent::_afterSave($object);
    }
    
    protected function _afterLoad(Mage_Core_Model_Abstract $object)
    {
        $select = $this->_getReadAdapter()->select()
            ->from($this->getTable('store'))
            ->where('podcast_id = ?', $object->getId());

        if ($data = $this->_getReadAdapter()->fetchAll($select)) {
            $storesArray = array();
            foreach ($data as $row) {
                $storesArray[] = $row['store_id'];
            }
            $object->setData('store_id', $storesArray);
        }

        return parent::_afterLoad($object);
    }
    
    protected function _beforeDelete(Mage_Core_Model_Abstract $object)
    {		
        // Cleanup stats on blog delete
        $adapter = $this->_getReadAdapter();
        // Delete blog/store
        $adapter->delete($this->getTable('podcast/store'), 'podcast_id='.$object->getId());        
    }
}