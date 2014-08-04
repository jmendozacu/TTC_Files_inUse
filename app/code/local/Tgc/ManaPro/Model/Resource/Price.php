<?php
/**
 * Description
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Tgc_ManaPro
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_ManaPro_Model_Resource_Price extends Mana_Filters_Resource_Filter2
{
    public function getPriceFilterRangeAndLastAmount()
    {
        $select = $this->_getReadAdapter()->select()
            ->from(array('f' => $this->getMainTable()), array())
            ->joinInner(
                array('v' => $this->getTable('mana_filters/filter2_store')),
                'f.id = v.global_id AND v.store_id = '.$this->_getReadAdapter()->quote(Mage::app()->getStore()->getId()).
                ' AND v.is_enabled = 1',
                array(
                    'show_more_item_count' => 'v.show_more_item_count',
                    'range_step' => 'v.range_step'
                )
            )
            ->where('f.type = ?', 'price')
            ->limit(1);
        $stmt = $this->_getReadAdapter()->query($select);
        if ($row = $stmt->fetch()) {
            return $row;
        }
        return null;
    }
}