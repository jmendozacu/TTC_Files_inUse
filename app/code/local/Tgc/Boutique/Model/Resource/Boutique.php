<?php
/**
 * Boutique
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Boutique
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Boutique_Model_Resource_Boutique extends Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct()
    {
        $this->_init('tgc_boutique/boutique', 'entity_id');
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

    public function clearDefaults()
    {
        $adapter = $this->_getWriteAdapter();
        $bind = array(
            'is_default' => 0,
        );
        $adapter->update(
            $this->getMainTable(),
            $bind,
            array($adapter->quoteInto("`entity_id` > ? ", 0))
        );
    }

    public function getBoutiqueIdByKey($key)
    {
        $select = $this->_getReadAdapter()->select()
            ->from($this->getMainTable(), array('entity_id'))
            ->where('url_key = ?', $key);

        return $this->_getReadAdapter()->fetchOne($select);
    }

    public function getDefaultBoutiqueId()
    {
        $select = $this->_getReadAdapter()->select()
            ->from($this->getMainTable(), array('entity_id'))
            ->where('is_default = ?', 1);

        return $this->_getReadAdapter()->fetchOne($select);
    }

    public function getDefaultPageForBoutique($id)
    {
        $select = $this->_getReadAdapter()->select()
            ->from($this->getMainTable(), array('pages'))
            ->where('entity_id = ?', $id);

        $pages = $this->_getReadAdapter()->fetchOne($select);

        if (empty($pages)) {
            return false;
        }

        $pages = unserialize($pages);
        if (empty($pages)) {
            return false;
        }

        $select = $this->_getReadAdapter()->select()
            ->from('tgc_boutique_pages', array('entity_id'))
            ->order('sort_order', 'asc')
            ->where('entity_id IN (?) ', $pages)
            ->where('store_id IN (?) ', array(0, Mage::app()->getStore()->getId()));

        $page = $this->_getReadAdapter()->fetchOne($select);

        return $page;
    }
}
