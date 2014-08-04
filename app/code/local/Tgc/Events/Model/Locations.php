<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Events
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_Events_Model_Locations extends Mage_Core_Model_Abstract
{
    const LOCATION_ALL_CODE       = 'all';

    protected function _construct()
    {
        $this->_init('tgc_events/locations');
    }

    public function getLocationIdForAll()
    {
        return $this->_getResource()->getLocationIdForAll();
    }

    public function delete()
    {
        if($this->getId() == $this->getLocationIdForAll())
        {
            throw new Exception("You may not delete the location designated as All");
            return false;
        }
        return parent::delete();
    }

    public function getLocationUrl()
    {
        $url = '';
        if($this->getId() == $this->getLocationIdForAll())
        {
            $url = Mage::getUrl('events');
        }
        else
        {
            $url = Mage::getUrl('events/'.$this->getLocationCode());
        }
        return $url;
    }
}
