<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Guidance
 * @package     Setup
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Price_AdcodeController extends Mage_Core_Controller_Front_Action
{
    /**
     * Update pricing by ad code action
     * 
     * Parameter: code
     * Use helper tgc_pricing::getPriceUpdateUrl() to get URL of the action 
     */
    public function updateAction()
    {
        $request = $this->getRequest();
        $adCode = $request->getParam(Tgc_Price_Helper_Data::AD_CODE_PARAM);
        $this->_getAdCodeProcessor()->changePrices($adCode);

        $display = Mage::helper('ninja/ninja')->shouldDisplayPriorityCode($adCode);

        if ($request->isAjax()) {
            $session = Mage::getSingleton('customer/session');
            if (!$display) {
                $response = 'success';
                Mage::getSingleton('customer/session')->setHasSubmittedDefaultAdcode(true);
            } else if ($adCode == $session->getAdCode()) {
                $response = 'success';
                Mage::getSingleton('customer/session')->unsHasSubmittedDefaultAdcode();
            } else {
                $response = 'invalid';
                Mage::getSingleton('customer/session')->unsHasSubmittedDefaultAdcode();
            }
            $this->_sendAjaxResponse($response);
        } else {
            $this->_redirectReferer();
        }
    }

    private function _sendAjaxResponse($response)
    {
        $jsonData = Zend_Json::encode($response);
        $this->getResponse()
            ->clearHeaders()
            ->setHeader('Content-Type', 'application/json')
            ->setBody($jsonData);
    }
    
    /**
     * Reset pricing by ad code action
     * 
     * Use helper tgc_pricing::getPriceResetUrl() to get URL of the action
     */
    public function resetAction()
    {
        $this->_getAdCodeProcessor()->resetPrices();
        $this->_redirectReferer();
    }
    
    /**
     * Returns ad code processor
     * 
     * @return Tgc_Price_Model_AdCode_Processor
     */
    private function _getAdCodeProcessor()
    {
        return Mage::helper('tgc_price')->getAdCodeProcessor();
    }
}
