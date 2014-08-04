<?php

/**
 * RocketWeb
 *
 * @category   RocketWeb
 * @package    RocketWeb_Podcast
 * @copyright  Copyright (c) 2012 RocketWeb (http://rocketweb.com)
 * @author     RocketWeb
 */


class RocketWeb_Podcast_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction() {
        $this->loadLayout();

        $this->getLayout()->getBlock('root')->setTemplate(Mage::getStoreConfig('rocketweb_podcast/settings/layout'));
        
        if ($head = $this->getLayout()->getBlock('head')) {
                $head->setTitle(Mage::getStoreConfig('rocketweb_podcast/settings/page_title'));
                $head->setDescription(Mage::getStoreConfig('rocketweb_podcast/settings/page_description'));
        }
        
        $this->renderLayout();
    }
    
}

