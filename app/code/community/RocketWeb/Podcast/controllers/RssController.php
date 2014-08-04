<?php

/**
 * RocketWeb
 *
 * @category   RocketWeb
 * @package    RocketWeb_Podcast
 * @copyright  Copyright (c) 2012 RocketWeb (http://rocketweb.com)
 * @author     RocketWeb
 */

class RocketWeb_Podcast_RssController extends Mage_Core_Controller_Front_Action 
{
    
    public function indexAction() 
    {        
        $store_id = Mage::app()->getStore()->getId();
        $this->getResponse()
            ->clearHeaders()
            ->setHeader('Content-Type', 'application/rss+xml; charset=UTF-8')
            ->setBody(file_get_contents(Mage::helper('podcast')->getPodcastDirectoryPath()."rss_$store_id.xml"));
    }
    
    


}
