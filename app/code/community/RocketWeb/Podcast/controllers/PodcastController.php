<?php

/**
 * RocketWeb
 *
 * @category   RocketWeb
 * @package    RocketWeb_Podcast
 * @copyright  Copyright (c) 2012 RocketWeb (http://rocketweb.com)
 * @author     RocketWeb
 */


class RocketWeb_Podcast_PodcastController extends Mage_Core_Controller_Front_Action
{
//    public function preDispatch() 
//    {
//        parent::preDispatch();
//
////        if(!Mage::helper('blog')->getEnabled()) {
//            $this->_redirectUrl(Mage::helper('core/url')->getHomeUrl());
////        }
//
//    }
    
    public function viewAction()
    {
        $podcast_url = $this->getRequest()->getParam('identifier',0);
        $podcast_id = Mage::helper('podcast')->decodeUrl($podcast_url);
        if(!$podcast_id && Mage::getModel('podcast/podcast')->load($podcast_id)){
            $this->_forward('NoRoute');
            return false;
        }
        $this->loadLayout();
        $this->getLayout()->getBlock('root')->setTemplate(Mage::getStoreConfig('rocketweb_podcast/settings/layout'));

        if ($head = $this->getLayout()->getBlock('head')) {
                $head->setTitle(Mage::getStoreConfig('rocketweb_podcast/settings/page_title'));
                $head->setDescription(Mage::getStoreConfig('rocketweb_podcast/settings/page_description'));
        }

        $this->renderLayout();

        
    }
    
    public function noRouteAction($coreRoute = null) 
    {
        $this->getResponse()->setHeader('HTTP/1.1','404 Not Found');
        $this->getResponse()->setHeader('Status','404 File not found');

        $pageId = Mage::getStoreConfig('web/default/cms_no_route');
        if (!Mage::helper('cms/page')->renderPage($this, $pageId)) {
                $this->_forward('defaultNoRoute');
        }
    }
    
    
}