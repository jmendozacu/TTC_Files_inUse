<?php
require_once (Mage::getModuleDir('controllers', 'Mage_Checkout') . DS . 'OnepageController.php');
/**
 * Checkout
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Checkout
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Checkout_OnepageController extends Mage_Checkout_OnepageController
{
    public function updateReviewAction()
    {
        if ($this->_expireAjax()) {
            return;
        }
        $this->loadLayout(false);
        $this->renderLayout();
    }

    protected function _trimAddress($data)
    {
        if (isset($data['email'])) {
            $data['email'] = trim($data['email']);
        }
        if (isset($data['city'])) {
            $data['city'] = trim($data['city']);
        }
        if (isset($data['postcode'])) {
            $data['postcode'] = trim($data['postcode']);
        }
        return $data;
    }

    /**
     * Save checkout billing address
     */
    public function saveBillingAction()
    {
        if ($this->_expireAjax()) {
            return;
        }
        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost('billing', array());
            $customerAddressId = $this->getRequest()->getPost('billing_address_id', false);

            $data = $this->_trimAddress($data);

            $result = $this->getOnepage()->saveBilling($data, $customerAddressId);

            if (!isset($result['error'])) {
                if ($this->getOnepage()->getQuote()->isVirtual()) {
                    if ($data['prev_step'] != "review") {
                        $result['goto_section'] = 'payment';
                        $result['update_section'] = array(
                            'name' => 'payment-method',
                            'html' => $this->_getPaymentMethodsHtml()
                        );
                    } else {
                        $this->loadLayout('checkout_onepage_review');
                        $result['goto_section'] = 'review';
                        $result['update_section'] = array(
                            'name' => 'review',
                            'html' => $this->_getReviewHtml()
                        );
                    }
                } elseif (isset($data['use_for_shipping']) && $data['use_for_shipping'] == 1) {
                    $result['goto_section'] = 'shipping_method';
                    $result['update_section'] = array(
                        'name' => 'shipping-method',
                        'html' => $this->_getShippingMethodsHtml()
                    );

                    $result['allow_sections'] = array('shipping');
                    $result['duplicateBillingInfo'] = 'true';
                } else {
                    $result['goto_section'] = 'shipping';
                }
            }

            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
        }
    }

    /**
     * Shipping address save action
     */
    public function saveShippingAction()
    {
        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost('shipping', array());
            $data = $this->_trimAddress($data);
            $this->getRequest()->setPost('shipping', $data);
        }
        parent::saveShippingAction();
    }
}
