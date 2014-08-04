<?php
/**
 * @category    <category>
 * @package     <package>
 * @copyright   Copyright (c) 2014 Guidance
 * @author      Chris Lohman <clohm@guidance.com>
 */

class Tgc_Events_Helper_Data extends Mage_Core_Helper_Abstract
{

    const EXT_IDENTIFIER = 'events';

    public function checkDuplicate($column, $value, $table, $id)
    {
        $isDuplicate = false;
        $collection = Mage::getModel('tgc_events/' . $table)->getCollection();
        $collection->addFieldToFilter($column, $value);

        if ($id) {
            $collection->addFieldToFilter('entity_id', array('neq' => $id));
        }

        if ($collection->getData()) {
            $isDuplicate = true;
        }

        return $isDuplicate;
    }

    public function getEventsByProfessor($professor_id = 0, $limit = 0)
    {
        $collection = Mage::getModel('events/events')->getCollection()->addStatusFilter();
        $collection->getSelect()
            ->join(
                array('events_professors_table' => $collection->getTable('tgc_events/professors')),
                'main_table.event_id = events_professors_table.event_id'
            )
            ->where('events_professors_table.professor_id = (?)', $professor_id);
        if ($limit > 0) {
            $collection->getSelect()->limit($limit);
        }
        return $collection;
    }

    public function extIdentifier()
    {
        return self::EXT_IDENTIFIER;
    }

}