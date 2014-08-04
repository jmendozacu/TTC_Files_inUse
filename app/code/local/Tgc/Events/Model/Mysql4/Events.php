<?php

class Tgc_Events_Model_Mysql4_Events extends FME_Events_Model_Mysql4_Events
{

    public function getGlobalFeaturedEvent(Tgc_Events_Model_Events $obj)
    {
        $select = $this->_getReadAdapter()->select()
                        ->from($this->getMainTable())
                        ->where($this->getMainTable().'.global_featured_event = (?)', 1)
                        ->where('DATE(event_end_date) >= (?)', now());
        $id = $this->_getReadAdapter()->fetchOne($select);
        if ($id)
        {
            $this->load($obj,$id);
        }
        
        return $this;
    }

    public function getLocationFeaturedEvent(Tgc_Events_Model_Events $obj, $location_id)
    {
        $select = $this->_getReadAdapter()->select()
            ->from($this->getMainTable())
            ->where($this->getMainTable().'.location_featured_event = (?)', 1)
            ->where($this->getMainTAble().'.event_venu = (?)', $location_id)
            ->where('DATE(event_end_date) >= (?)', now());
        $id = $this->_getReadAdapter()->fetchOne($select);
        if ($id)
        {
            $this->load($obj,$id);
        }

        return $this;
    }

    protected function _afterLoad(Mage_Core_Model_Abstract $object)
    {
        $location_name = Mage::getModel('tgc_events/locations')
            ->load($object->getEventVenu())
            ->getLocation();

        $object->setData('location_name', $location_name);

        $event_type = Mage::getModel('tgc_events/types')
            ->load($object->getEventType());
        $type_name = $event_type->getType();
        $type_icon = $event_type->getTypeIcon();

        $object->setData('event_type_name', $type_name);
        $object->setData('event_type_icon', $type_icon);

        $professors = $this->__listProfessors($object);
        if ($professors)
        {
            $object->setData('professor_ids', $professors);
        }

        return parent::_afterLoad($object);
    }

    private function __listProfessors(Mage_Core_Model_Abstract $object)
    {
        $select = $this->_getReadAdapter()->select()
            ->from($this->getTable('tgc_events/professors'))
            ->where('event_id = ?', $object->getId());

        $data = $this->_getReadAdapter()->fetchAll($select);

        if ($data)
        {
            $professorsArr = array();
            foreach ($data as $_i)
            {
                $professorsArr[] = $_i['professor_id'];
            }

            return $professorsArr;
        }
    }


    /**
     * Override to add saving of Professors and Events
     *
     * @param Mage_Core_Model_Abstract $object
     * @return Mage_Core_Model_Resource_Db_Abstract
     */
    protected function _afterSave(Mage_Core_Model_Abstract $object)
    {
        $links = $object['links']; // echo '<pre>'; print_r($links);exit;
        if (isset($links['related_profs']))
        {
            $conditionProfessor = $this->_getWriteAdapter()->quoteInto('event_id = ?', $object->getId());
            $this->_getWriteAdapter()->delete($this->getTable('tgc_events/professors'), $conditionProfessor);

            $professor_ids = Mage::helper('adminhtml/js')->decodeGridSerializedInput($links['related_profs']); // echo '<pre>';print_r($productIds);exit;

            foreach ($professor_ids as $_p)
            {
                $objArr = array();
                $objArr['event_id'] = $object->getId();
                $objArr['professor_id'] = $_p;
                $this->_getWriteAdapter()->insert($this->getTable('tgc_events/professors'),$objArr);
            }
        }
        return parent::_afterSave($object);
    }

}
