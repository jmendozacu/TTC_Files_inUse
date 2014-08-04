<?php
/**
 * Boutique
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Boutique
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Boutique_Model_Resource_BoutiquePages extends Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct()
    {
        $this->_init('tgc_boutique/boutiquePages', 'entity_id');
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

    public function getPageIdByKey($key)
    {
        $select = $this->_getReadAdapter()->select()
            ->from($this->getMainTable(), array('entity_id'))
            ->where('url_key = ?', $key);

        return $this->_getReadAdapter()->fetchOne($select);
    }
}
