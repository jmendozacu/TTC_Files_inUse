<?php
/**
 * Boutique
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Boutique
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Boutique_Model_Resource_Boutique_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    protected function _construct()
    {
        $this->_init('tgc_boutique/boutique');
    }

    public function toOptionArray()
    {
        return $this->_toOptionArray('entity_id', 'name');
    }

    public function toItemArray()
    {
        $select = $this->getSelect()
            ->from($this->getMainTable(), array('entity_id', 'name'));

        return $this->getConnection()->fetchPairs($select);
    }
}
