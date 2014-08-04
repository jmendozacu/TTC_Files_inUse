<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Events
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_Events_Model_Resource_Locations_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    protected function _construct()
    {
        $this->_init('tgc_events/locations');
    }

    /**
     * Convert items array to array for select options
     *
     * @return Array
     */
    public function toOptionHash()
    {
        return $this->_toOptionHash('entity_id', 'location');
    }

    /**
     * Convert items array to array for select options
     *
     * @return Array
     */
    public function toOptionArray()
    {
        return $this->_toOptionArray('entity_id', 'location');
    }

    /**
     * Convert items array to array for select options
     *
     * @return Array
     */
    public function toLocationsArray()
    {
        return $this->_toOptionArray('location_code', 'location');
    }
}
