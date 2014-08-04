<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Events
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_Events_Controller_Router extends Mage_Core_Controller_Varien_Router_Abstract
{
    public function initProtocol($observer)
    {
        $front = $observer->getEvent()->getFront();
        //replace FME_Events_Controller_Router class with ours.
        $router = new Tgc_Events_Controller_Router();
        $front->addRouter('events', $router);
    }

    public function match(Zend_Controller_Request_Http $request)
    {
        //the problem here is that when the router is already looking for a match for the install request
        //and it looks here for a match before the actual install controller,
        //it gets dispatched with a new install request which results in a never ending loop ;P
        //so a fresh install would be impossible.
//        if (!Mage::isInstalled()) {
//            Mage::app()->getFrontController()->getResponse()
//                ->setRedirect(Mage::getUrl('install'))
//                ->sendResponse();
//            exit;
//        }

        // If our Module, Controller, and Action are all set, that means we are now in this match function
        // for the second time.  Therefore, we should return false to allow the match function to continue
        // iterating through the routers so that our request will get handled by the Standard router.
        // See Mage_Core_Controller_Varien_Front::dispatch() where it loops the routers calling match() on each.
        if ($request->getModuleName()
            && $request->getControllerName()
            && $request->getActionName()
        ) {
            return false;
        }

        $identifierNew = Mage::helper('tgc_events')->extIdentifier();
        $currRouter = trim($request->getPathInfo(), '/');

        // If our location cookie is present and the location_code is not already in the url, append it so that we
        // redirect to our cookied location.
        if ($cookie_location_id = Mage::getModel('core/cookie')->get('events_location')) {
            $location_code = Mage::getModel('tgc_events/locations')->load($cookie_location_id)->getLocationCode();
            if (stripos($currRouter, $location_code) === false) {
                $currRouter .= '/' . $location_code;
            }
        }
        $explode = explode('/', $currRouter); // echo '<pre>';print_r($explode);exit;

        if ($currRouter == $identifierNew) {
            $request->setModuleName('events')
                ->setControllerName('index')
                ->setActionName('index');

            return true;
        } elseif ($currRouter != $identifierNew) { //event/index/view/pfx/live-nash
            $identifier = trim($explode[0]);
            if (!in_array($identifier, array('event', 'events'))) {
                return false;
            }

            $location_code = trim($explode[1]);
            $model = Mage::getModel('tgc_events/locations')->load($location_code, 'location_code'); // echo '<pre>'; print_r($model->getData());exit;
            $location_id = $model->getEntityId();
            if ($location_id > 0) {
                $request->setModuleName('events')
                    ->setControllerName('index')
                    ->setActionName('location')
                    ->setParam('location_id', $location_id);
                $request->setAlias(
                    Mage_Core_Model_Url_Rewrite::REWRITE_REQUEST_PATH_ALIAS,
                    $currRouter
                );

                return true;
            }


            $pfx = trim($explode[1]);
            $model = Mage::getModel('events/events')->loadByPrefix($pfx); // echo '<pre>'; print_r($model->getData());exit;
            $isPfx = $model->getEventId();

            if ($isPfx > 0) {
                $request->setModuleName('events')
                    ->setControllerName('index')
                    ->setActionName('view')
                    ->setParam('pfx', $pfx);
                $request->setAlias(
                    Mage_Core_Model_Url_Rewrite::REWRITE_REQUEST_PATH_ALIAS,
                    $currRouter
                );

                return true;
            } elseif ($currRouter == $identifierNew . '/calendar') {
                $request->setModuleName('events')
                    ->setControllerName('index')
                    ->setActionName(trim($explode[1]));

                $request->setAlias(
                    Mage_Core_Model_Url_Rewrite::REWRITE_REQUEST_PATH_ALIAS,
                    $currRouter
                );

                return true;
            } elseif ($currRouter == $identifierNew . '/' . $explode[1]) {
                $request->setModuleName('events')
                    ->setControllerName('index')
                    ->setActionName('index');
                if (Mage::helper('events')->isValidDate($explode[1])) {
                    $request->setParam('date_event', $explode[1]);
                }

                $request->setAlias(
                    Mage_Core_Model_Url_Rewrite::REWRITE_REQUEST_PATH_ALIAS,
                    $currRouter
                );

                return true;
            }

            return false;
        }

        return false;
    }
}