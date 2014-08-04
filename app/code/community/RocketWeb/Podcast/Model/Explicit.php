<?php

/**
 * RocketWeb
 *
 * @category   RocketWeb
 * @package    RocketWeb_Podcast
 * @copyright  Copyright (c) 2012 RocketWeb (http://rocketweb.com)
 * @author     RocketWeb
 */

class RocketWeb_Podcast_Model_Explicit extends Varien_Object{

    const EXPLICIT_NO           = 0;
    const EXPLICIT_YES          = 1;
    const EXPLICIT_CLEAN        = 2;

    public function addEnabledFilterToCollection($collection)
    {
        $collection->addEnableFilter(array('in'=>$this->getEnabledStatusIds()));
        return $this;
    }
    
    public function getYesExplicitIds()
    {
        return array(self::EXPLICIT_YES);
    }
	
    public function getNoExplicitIds()
    {
        return array(self::EXPLICIT_NO);
    }
	
    public function getCleanStatusIds()
    {
        return array(self::EXPLICIT_CLEAN);
    }

    static public function toOptionArray()
    {
        return array(
            self::EXPLICIT_NO       => Mage::helper('podcast')->__('No'),
            self::EXPLICIT_YES      => Mage::helper('podcast')->__('Yes'),
            self::EXPLICIT_CLEAN    => Mage::helper('podcast')->__('Clean')
        );
    }
}
