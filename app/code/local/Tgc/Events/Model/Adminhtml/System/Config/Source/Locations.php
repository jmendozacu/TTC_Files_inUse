<?php
/**
 * Events
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Events
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Events_Model_Adminhtml_System_Config_Source_Locations
{
    protected $_options;

    public function toOptionArray()
    {
        // TODO Deprecated Class
        if (!$this->_options) {
            $this->_options = $this->_toOptionIdArray();
        }

        return $this->_options;
    }

    /**
     * Returns Locations available
     *
     * @return array
     */
    private function _toOptionIdArray()
    {
        $res = array();
        $locations = Mage::getResourceModel('tgc_events/locations_collection');
        foreach ($locations as $location)
        {
            $data['value'] = $location->getLocation();
            $data['label'] = $location->getLocation();
            $res[] = $data;
        }

        asort($res);

        return $res;
    }
}
