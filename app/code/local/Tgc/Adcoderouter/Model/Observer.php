<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Guidance
 * @package     Adcoderouter
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_Adcoderouter_Model_Observer
{

    protected $_sessionErrorsList = array();

    /**
     * Note: the ad code redirects router needs to be able to create global messages, when there is an error.
     * Global messages cannot be added in the adcode router, because the core/session model has not yet been instantiated.
     * that model must be instantiated before a global message has been instantiated.
     * Therefore, an observer was created, that runs just after the core/session model has been instantiated.
     *
     * Also, sometimes the ad code router does a hard redirect.  I am calling a hard redirect , a redirect where
     * magento reloads the entire page (this is different than just changing the route which is how typical ad code redirect works)
     * On a hard redirect, the following observer is called, so that when the page reloads the global messages are not erased.
     *
     * @param Varien_Event_Observer $observer
     */
    public function addAnyAdcoderedirectErrormessages(Varien_Event_Observer $observer)
    {
        $redirectErrors = $this->_helper()->getRedirectsSession()->getRedirectErrors();
        if(count($redirectErrors) > 0) {
            $this->collectSessionErrorMessages();
            foreach($redirectErrors as $redirectError) {
                if(!in_array($redirectError, $this->_sessionErrorsList)) {
                    $this->getCoreSession()->addError($redirectError);
                }
            }
        }
    }

    public function collectSessionErrorMessages()
    {
        foreach($this->getCoreSession()->getMessages(true)->getErrors() as $error) {
            $this->_sessionErrorsList[] = $error;
        }
    }

    public function addAnyAdcodeHardredirects(Varien_Event_Observer $observer)
    {
        $adcodeHardRedirectPath = $this->_helper()->getRedirectsSession()->getAdCodeHardRedirectPath();

        if($adcodeHardRedirectPath) {
            Mage::app()->getFrontController()->getAction()->getResponse()->setRedirect(Mage::getUrl($adcodeHardRedirectPath));
        }
    }

    protected function getCoreSession()
    {
        return Mage::getSingleton('core/session');
    }

    protected function _helper()
    {
        return Mage::helper('adcoderouter');
    }
}
