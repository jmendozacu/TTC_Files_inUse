<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Events
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_Events_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        $date = null;
        $dateParam = $this->getRequest()->getParam('date_event');
        $location_code = $this->getRequest()->getParam('location_code');
        $location_id = $this->getRequest()->getParam('location_id');
        if ($dateParam != null AND $dateParam != '') {
            $date = $dateParam;
        }

        Mage::register('eventDate', $date);
        Mage::register('location_code', $location_code);
        Mage::register('location_id', $location_id);
        $this->loadLayout();
        $this->renderLayout();
    }

    public function locationAction()
    {
        $location_id = $this->getRequest()->getParam('location_id');
        if($location_id == Mage::getModel('tgc_events/locations')->getLocationIdForAll())
        {
            // This means "All" location was selected from the frontend.  We want this to be the same as going
            // to tgc.com/events
            $this->_redirect('events');
            return;
        }
        Mage::register('location_id', $location_id);

        $this->loadLayout();
        $this->renderLayout();
    }

    public function setLocationAction()
    {
        // set location cookie
        $location_id = $this->getRequest()->getParam('location_id');
        $lifetime = 3600 * 24 * 365 * 2; // 2 years
        Mage::getModel('core/cookie')->set('events_location', $location_id, $lifetime, '/');

        // redirect to location page
        $location = Mage::getModel('tgc_events/locations')->load($location_id);
        Mage::app()->getResponse()->setRedirect(Mage::getModel('core/url')->getUrl('events/' . $location->getLocationCode(), array()));

        return true;
    }

    public function unsetLocationAction()
    {
        // delete location cookie
        Mage::getModel('core/cookie')->delete('events_location', '/');

        // redirect to events page without filter
        Mage::app()->getResponse()->setRedirect(Mage::getModel('core/url')->getUrl('events', array()));

        return true;
    }
}