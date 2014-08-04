<?php
/**
 * Customer
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Customer
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Customer_OrderhistoryController extends Mage_Core_Controller_Front_Action
{
    public function preDispatch()
    {
        if (!$this->isAjax()) {
            exit;
        }

        parent::preDispatch();
    }

    public function isAjax()
    {
        if ($this->isXmlHttpRequest()) {
            return true;
        }

        return false;
    }

    public function isXmlHttpRequest()
    {
        return ($this->getRequest()->getHeader('X_REQUESTED_WITH') == 'XMLHttpRequest');
    }

    protected function _sendAjaxResponse($response)
    {
        $jsonData = Mage::helper('core')->jsonEncode($response);
        $this->getResponse()
            ->clearHeaders()
            ->setHeader('Content-Type', 'application/json')
            ->setBody($jsonData);
    }

    public function getOrderAction()
    {
        if ($this->getRequest()->isPost()) {
            $orderId = $this->getRequest()->getPost('orderId');
            $daxId   = $this->getRequest()->getPost('daxId');
            $block = $this->getLayout()->createBlock('tgc_customer/order_history')->setTemplate('sales/order/history/detail.phtml');
            $block->setOrderId($orderId);
            $block->setDaxId($daxId);
            $html = $block->toHtml();

            $response = array('status' => 'success', 'html' => $html);
            $this->_sendAjaxResponse($response);
        } else {
            $response = array('status' => 'failure');
            $this->_sendAjaxResponse($response);
        }
    }
}
