<?php

/**
 * Subscriber resource model class
 *
 * @category    Enterprise
 * @package     Enterprise_Mview
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Tgc_Mview_Model_Resource_Subscriber extends Enterprise_Mview_Model_Resource_Subscriber
{
    public function getCustomTrigger($event, $subscriberId)
    {
        $select = $this->_getReadAdapter()
            ->select()
            ->from($this->getTable('tgc_mview/custom_trigger'), array('trigger_body'))
            ->where('subscriber_id = ?', $subscriberId)
            ->where('event_name = ?', $event);
        return $this->_getReadAdapter()->fetchOne($select);
    }
}
