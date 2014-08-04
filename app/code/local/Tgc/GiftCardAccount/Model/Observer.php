<?php
/**
 * Gift Card Account customization
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     GiftCardAccount
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_GiftCardAccount_Model_Observer
{
    /**
     * Modify Add Gift Card Action response
     *
     * @param Varien_Event_Observer $observer
     * @return \Tgc_GiftCardAccount_Model_Observer
     */
    public function appendAddActionResponse(Varien_Event_Observer $observer)
    {
        $controller = $observer->getEvent()->getControllerAction();
        if ($controller->getRequest()->isAjax() && $controller->getRequest()->getParam('from_checkout')) {
            $coreHelper = Mage::helper('core');
            try {
                $response = $coreHelper->jsonDecode($controller->getResponse()->getBody());
                if (is_array($response)) {
                    if (isset($response['totals']) && $response['totals']) {
                        $response['totals'] = Mage::helper('tgc_checkout')
                            ->getTableTotals($controller->getLayout());
                    }

                    $response['update_section'] = array(
                        'name' => 'payment-method',
                        'html' => $this->_getPaymentMethodsHtml($controller)
                    );
                }
                $controller->getResponse()->setBody($coreHelper->jsonEncode($response));
            } catch (Exception $e) {}
        }
        return $this;
    }

    /**
     * Modify Remove Gift Card Action response
     *
     * @param Varien_Event_Observer $observer
     * @return \Tgc_GiftCardAccount_Model_Observer
     */
    public function appendRemoveActionResponse(Varien_Event_Observer $observer)
    {
        $controller = $observer->getEvent()->getControllerAction();
        if ($controller->getRequest()->isAjax() && $controller->getRequest()->getParam('from_checkout')) {
            $coreHelper = Mage::helper('core');
            try {
                $response = $coreHelper->jsonDecode($controller->getResponse()->getBody());
                if (is_array($response)) {
                    $response['totals'] = Mage::helper('tgc_checkout')
                        ->getTableTotals($controller->getLayout(), false);

                    $response['reviewTotals'] = Mage::helper('tgc_checkout')
                        ->getTableReviewTotals($controller->getLayout(), false);

                    $response['update_section'] = array(
                        'name' => 'payment-method',
                        'html' => $this->_getPaymentMethodsHtml($controller)
                    );
                    $quote = Mage::getSingleton('checkout/session')->getQuote();
                    if ($quote->getPayment()->getMethod() == 'free') {
                        $response['goto_payment'] = true;
                    }
                }
                $controller->getResponse()->setBody($coreHelper->jsonEncode($response));
            } catch (Exception $e) {}
        }
        return $this;
    }

    /**
     * Generate checkout payment methods html
     *
     * @param Mage_Core_Controller_Varien_Action $controller
     * @return type
     */
    protected function _getPaymentMethodsHtml(Mage_Core_Controller_Varien_Action $controller)
    {
        $layout = $controller->getLayout();
        $update = $layout->getUpdate();
        $update->load('checkout_onepage_paymentmethod');
        $layout->generateXml();
        $layout->generateBlocks();
        return $layout->getOutput();
    }
}
