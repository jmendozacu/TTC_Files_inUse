<?php
/**
 * SeeWhy
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     SeeWhy
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_SeeWhy_FetchController extends Mage_Core_Controller_Front_Action
{
    public function preDispatch()
    {
        if (!$this->_isAjax()) {
            $this->setFlag('', 'no-dispatch', true);
        }
        parent::preDispatch();
    }

    private function _isAjax()
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

    private function _sendAjaxResponse($response)
    {
        $jsonData = Zend_Json::encode($response);
        $this->getResponse()
            ->clearHeaders()
            ->setHeader('Content-Type', 'application/json')
            ->setBody($jsonData);
    }

    public function addToCartDataAction()
    {
        $productId = $this->getRequest()->getParam('productId');
        if (empty($productId)) {
            exit;
        }

        $product = Mage::getModel('catalog/product')->load($productId);
        $session = Mage::getSingleton('customer/session');
        $customer = $session->getCustomer();
        $cartHelper = Mage::helper('checkout/cart');
        $productHelper = Mage::helper('catalog/output');
        $imageHelper = Mage::helper('infortis/image');
        $professorData = Mage::helper('tgc_catalog')->getProfessors($product);
        $currencySymbol = Mage::app()->getLocale()->currency(Mage::app()->getStore()->getCurrentCurrencyCode())->getSymbol();
        $bvHelper = Mage::helper('tgc_bv');
        $rating = $bvHelper->getRatingForProduct($product);

        $data = array(
            'name' => $customer->getName() ? $customer->getName() : Tgc_SeeWhy_Model_Observer::JS_VAR_GUEST,
            'intent' => $session->isLoggedIn() ? Tgc_SeeWhy_Model_Observer::JS_VAR_INTENT : '',
            'cartTotal' => $cartHelper->getQuote()->getGrandTotal() ? $cartHelper->getQuote()->getGrandTotal() : 0,
            'cartUrl' => $cartHelper->getCartUrl(),
            'email' => $customer->getEmail() ? $customer->getEmail() : '',
            'productName' => $productHelper->productAttribute($product, $product->getName(), 'name'),
            'productImage' => $imageHelper->getImg($product, 110, 160, 'thumbnail')->__toString(),
            'professorName' => $this->_getProfessorNameFromData($professorData),
            'professorImage' => $this->_getProfessorImageFromData($professorData),
            'pageUrl' => $product->getProductUrl(),
            'productReviewScore' => $rating,
            'productReviewImage' => Mage::getDesign()->getSkinUrl('images/bazaarvoice/rating-'
                    . str_replace('.', '_', $rating) . '.gif'),
            'currencySymbol' => $currencySymbol,
        );

        $this->_sendAjaxResponse($data);
    }

    private function _getProfessorImageFromData($professorData)
    {
        foreach ($professorData as $professor) {
            $professor = Mage::getModel('profs/professor')->load($professor['professor_id']);
            return Mage::helper('profs/image')->init($professor)->__toString();
        }

        return '';
    }

    private function _getProfessorNameFromData($professorData)
    {
        $names = array();

        foreach ($professorData as $professor) {
            $names[] = $professor['name'];
        }

        return empty($names) ? '' : join(', ', $names);
    }
}
