<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Events
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_Events_Model_Events extends FME_Events_Model_Events
{
    public function getGlobalFeaturedEvent()
    {
        $this->_getResource()->getGlobalFeaturedEvent($this);

        return $this;
    }

    public function getLocationFeaturedEvent($location_id)
    {
        $this->_getResource()->getLocationFeaturedEvent($this, $location_id);

        return $this;
    }

    public function getLocationIdForAll()
    {
        return $this->_getResource()->getLocationIdForAll();
    }

}
