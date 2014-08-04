<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Events
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_Events_Block_Events extends FME_Events_Block_Events
{
    public function __construct()
    {
        parent::__construct();
        $collection = $this->getCollection();
        $location_id = Mage::registry('location_id');
        if ($location_id && $location_id != Mage::getModel('tgc_events/locations')->getLocationIdForAll()) {
            // Add Events designated as selected location
            $locations = array($location_id);
            // Add Events designated as "All" locations
            if($location_all_id = Mage::getModel('tgc_events/locations')->getLocationIdForAll())
            {
                $locations[] = $location_all_id;
            }
            $collection->getSelect()->where('event_venu IN (?)', $locations);
        }
        $collection->getSelect()->join(
            array('events_types_table' => $collection->getTable('tgc_events/types')),
            'main_table.event_type = events_types_table.entity_id',
            array('event_type_name' => 'events_types_table.type', 'event_type_icon' => 'events_types_table.type_icon')
        );

        //echo (string) $collection->getSelect();exit;
        $this->setCollection($collection);
    }

    public function getLocation()
    {
        $location_id = Mage::registry('location_id');
        $location = Mage::getModel('tgc_events/locations');
        if ($location_id) {
            $location->load($location_id);
        }
        return $location;
    }

    public function getLocationsList()
    {
        $collection = Mage::getModel('tgc_events/locations')
            ->getCollection();
        $collection->getSelect()
            ->where('is_active = (?)', 1)
            ->order('sort_order');
        return $collection->toLocationsArray();
    }

    public function getGlobalFeaturedEvent()
    {
        return Mage::getModel('events/events')->getGlobalFeaturedEvent();
    }

    public function getLocationFeaturedEvent()
    {
        $location_id = Mage::registry('location_id');
        $event = false;
        if ($location_id) {
            $event = Mage::getModel('events/events')->getLocationFeaturedEvent($location_id);
        }
        return $event;
    }

    public function getFeaturedEvent()
    {
        $location_id = Mage::registry('location_id');
        $event = Mage::getModel('events/events');
        if ($location_id) {
            $event = $event->getLocationFeaturedEvent($location_id);
        }
        else
        {
            $event = $event->getGlobalFeaturedEvent();
        }
        return $event;
    }

    public function getEventsRelatedProducts($eventsId)
    {
        $collection = Mage::getModel('events/events')->getEventsRelatedProducts($eventsId);
        $products = array();
        foreach ($collection as $event) {
            $products[] = Mage::getModel('catalog/product')->load($event['product_id']);
        }

        return $products;
    }

    public function getToolbarBlock()
    {
        $block = $this->getLayout()->createBlock('tgc_events/toolbar', microtime());

        return $block;
    }

    public function isCookiedLocation()
    {
        return Mage::registry('location_id') == Mage::getModel('core/cookie')->get('events_location');
    }

}
