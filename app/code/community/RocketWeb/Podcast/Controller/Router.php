<?php

/**
 * RocketWeb
 *
 * @category   RocketWeb
 * @package    RocketWeb_Podcast
 * @copyright  Copyright (c) 2012 RocketWeb (http://rocketweb.com)
 * @author     RocketWeb
 */

class RocketWeb_Podcast_Controller_Router extends Mage_Core_Controller_Varien_Router_Standard
{
    
    public function initControllerRouters($observer)
    {
        $front = $observer->getEvent()->getFront();

        $podcast = new RocketWeb_Podcast_Controller_Router();
        $front->addRouter('podcasts', $podcast);
    }
    
    public function match(Zend_Controller_Request_Http $request)
    {
        if (!Mage::app()->isInstalled()) {
            Mage::app()->getFrontController()->getResponse()
                ->setRedirect(Mage::getUrl('install'))
                ->sendResponse();
            exit;
        }

        $route = Mage::helper('podcast')->getRoute();
        
        $identifier = $request->getPathInfo();
        if (substr(str_replace("/", "",$identifier), 0, strlen($route)) != $route){
            return false;
        }
        
		
        $identifier = substr_replace($request->getPathInfo(),'', 0, strlen("/" . $route. "/") );
        $identifier = str_replace('html', '', $identifier);
        $identifier = str_replace('htm', '', $identifier);		
        
        if($identifier == ''){ 
            $request->setModuleName('podcasts')
                    ->setControllerName('index')
                    ->setActionName('index');
                    return true;
        }
        else if($identifier == 'rss'){
            $request->setModuleName('podcasts')
                    ->setControllerName('rss')
                    ->setActionName('index');
                    return true;
        }
        else {
            $request->setModuleName('podcasts')
                    ->setControllerName('podcast')
                    ->setActionName('view')
                    ->setParam('identifier', $identifier);
                    return true;
        }
        
        return false;
    }
}
