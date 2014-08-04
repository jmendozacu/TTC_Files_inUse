<?php

/**
 * RocketWeb
 *
 * @category   RocketWeb
 * @package    RocketWeb_Podcast
 * @copyright  Copyright (c) 2012 RocketWeb (http://rocketweb.com)
 * @author     RocketWeb
 */

class RocketWeb_Podcast_Model_Status extends Varien_Object{

    const STATUS_ENABLED    = 1;
    const STATUS_DISABLED   = 2;
    const STATUS_HIDDEN     = 3;

    public function addEnabledFilterToCollection($collection)
    {
        $collection->addEnableFilter(array('in'=>$this->getEnabledStatusIds()));
        return $this;
    }
    
    public function getEnabledStatusIds()
    {
        return array(self::STATUS_ENABLED);
    }
	
    public function getDisabledStatusIds()
    {
        return array(self::STATUS_DISABLED);
    }
	
    public function getHiddenStatusIds()
    {
        return array(self::STATUS_HIDDEN);
    }

    static public function toOptionArray()
    {
        return array(
            self::STATUS_ENABLED    => Mage::helper('podcast')->__('Enabled'),
            self::STATUS_DISABLED   => Mage::helper('podcast')->__('Disabled'),
            self::STATUS_HIDDEN     => Mage::helper('podcast')->__('Hidden')
        );
    }
}
