<?php

/**
 * RocketWeb
 *
 * @category   RocketWeb
 * @package    RocketWeb_Podcast
 * @copyright  Copyright (c) 2012 RocketWeb (http://rocketweb.com)
 * @author     RocketWeb
 */


class RocketWeb_Podcast_Model_Podcast extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('podcast/podcast');
    }
    
    public function getShortContent(){
        $content = $this->getData('short_content');
        $processor = Mage::getModel('core/email_template_filter');
        
        return $processor->filter($content);
    }
    
    public function getLongContent(){
        $content = $this->getData('long_content');
        $processor = Mage::getModel('core/email_template_filter');
        
        return $processor->filter($content);
    }
}