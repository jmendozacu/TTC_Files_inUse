<?php

/**
 * RocketWeb
 *
 * @category   RocketWeb
 * @package    RocketWeb_Podcast
 * @copyright  Copyright (c) 2012 RocketWeb (http://rocketweb.com)
 * @author     RocketWeb
 */


class RocketWeb_Podcast_Block_Podcast extends Mage_Core_Block_Template
{
    
    public function getPodcast()
    {
        $podcast_url = $this->getRequest()->getParam('identifier',0);
        $podcast_id = Mage::helper('podcast')->decodeUrl($podcast_url);
        $podcast = Mage::getModel('podcast/podcast')->load($podcast_id);
        
        return $podcast;
    }
}