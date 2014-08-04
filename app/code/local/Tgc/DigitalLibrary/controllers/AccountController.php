<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DigitalLibrary
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_DigitalLibrary_AccountController extends Mage_Core_Controller_Front_Action
{
    protected function _getSession()
    {
        return Mage::getSingleton('customer/session');
    }

    protected function _initLayout()
    {
        $this->loadLayout();
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('catalog/session');
    }

    public function indexAction()
    {
        $this->_initLayout();
        $this->getLayout()->getBlock('head')->setTitle($this->__('My Digital Library'));
        $this->renderLayout();
    }

    public function audioAction()
    {
        $this->_initLayout();
        $this->getLayout()->getBlock('head')->setTitle($this->__('My Digital Library - Audio'));
        $this->renderLayout();
    }

    public function videoAction()
    {
        $this->_initLayout();
        $this->getLayout()->getBlock('head')->setTitle($this->__('My Digital Library - Videos'));
        $this->renderLayout();
    }

    public function streamFaqsAction()
    {
        $this->_initLayout();
        $this->getLayout()->getBlock('head')->setTitle($this->__('Streaming FAQs'));
        $this->renderLayout();
    }

    public function downloadFaqsAction()
    {
        $this->_initLayout();
        $this->getLayout()->getBlock('head')->setTitle($this->__('Download FAQs'));
        $this->renderLayout();
    }

    public function downloadPrefsAction()
    {
        $this->_initLayout();
        $this->getLayout()->getBlock('head')->setTitle($this->__('Download Preferences'));
        $this->renderLayout();
    }

    protected function _getCustomer()
    {
        $customer = Mage::getSingleton('customer/session')->getCustomer();

        return $customer;
    }

    public function updateAudioFormatAction()
    {
        if (!$this->_isAjax()) {
            $this->_redirect('home');
            return;
        }

        $setting = $this->getRequest()->getParam('format');
        $customer = $this->_getCustomer();
        $audioOptions = Mage::getModel('tgc_dl/source_audio_format')->toOptionArray();
        $options = array_flip($audioOptions);

        if (array_key_exists($setting, $options)) {
            $customer->setAudioFormat($options[$setting])
                ->save();
        }

        $this->_sendAjaxResponse('saved');
    }

    public function updateVideoFormatAction()
    {
        if (!$this->_isAjax()) {
            $this->_redirect('home');
            return;
        }

        $setting = $this->getRequest()->getParam('format');
        $customer = $this->_getCustomer();
        $videoOptions = Mage::getModel('tgc_dl/source_video_format')->toOptionArray();
        $options = array_flip($videoOptions);

        if (array_key_exists($setting, $options)) {
            $customer->setVideoFormat($options[$setting])
                ->save();
        }

        $this->_sendAjaxResponse('saved');
    }

    protected function _isAjax()
    {
        if ($this->_isXmlHttpRequest()) {
            return true;
        }

        return false;
    }

    private function _isXmlHttpRequest()
    {
        return ($this->getRequest()->getHeader('X_REQUESTED_WITH') == 'XMLHttpRequest');
    }

    protected function _sendAjaxResponse($response)
    {
        $jsonData = Zend_Json::encode($response);
        $this->getResponse()
            ->clearHeaders()
            ->setHeader('Content-Type', 'application/json')
            ->setBody($jsonData);
    }

    public function closeResumeAction()
    {
        if (!$this->_isAjax()) {
            $this->_redirect('home');
            return;
        }

        $name = $this->getRequest()->getParam('cookieName');
        $time = $this->getRequest()->getParam('cookieLifetime');

        Mage::getModel('core/cookie')->set($name, true, $time);
    }

    public function preDispatch()
    {
        parent::preDispatch();

        if (!$this->getRequest()->isDispatched()) {
            return;
        }

        if (!$this->_getSession()->isLoggedIn() || !$this->_getCustomer()->getWebUserId()) {
            $this->setFlag('', 'no-dispatch', true);
            $this->_redirect('home');
            return;
        }

        if ($this->_isAjax()) {
            return;
        }

        $customer = $this->_getCustomer();
        if (is_null($customer->getAudioFormat()) || is_null($customer->getVideoFormat())) {
            if ($this->getRequest()->getActionName() != 'downloadPrefs') {
                $this->setFlag('', 'no-dispatch', true);
                $this->_redirect('*/*/downloadPrefs');
                return;
            }
        }
    }
}
