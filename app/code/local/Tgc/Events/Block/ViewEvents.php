<?php
class Tgc_Events_Block_ViewEvents extends FME_Events_Block_ViewEvents
{

	public function getEvents()
    { 
       $block = $this->getLayout()->createBlock('events/events', microtime());
		
       return $block->getEvents();
    }

    public function getSimilarEvents($id, $location_id, $type_id)
    {
        $collection = Mage::getModel('events/events')->getCollection();
        $events_typesTable = Mage::getSingleton('core/resource')->getTableName('events_types');
        $locations = array();
        if($location_id != Mage::getModel('tgc_events/locations')->getLocationIdForAll())
        {
            $locations[] = $location_id;
            if($location_all_id = Mage::getModel('tgc_events/locations')->getLocationIdForAll())
            {
                $locations[] = $location_all_id;
            }
        }

        /* Retrieve events that are the same type in the same location with an end date greater than now */
        $collection->getSelect()
            ->join(array('types' => $events_typesTable),
                'main_table.event_type = types.entity_id'
            )
            ->where('event_type = (?)', $type_id)
            ->where('event_id != (?)', $id)
            ->where('DATE(event_end_date) >= (?)', now())
            ->order('DATE(event_end_date) ASC')
        ;
        if(count($locations))
        {
            $collection->getSelect()->where('event_venu in (?)',$locations);
        }

        return $collection;
    }

    public function getLocation($location_id = null)
    {
        $location = Mage::getModel('tgc_events/locations');
        if($location_id)
        {
            $location->load($location_id);
        }
        return $location;
    }

}