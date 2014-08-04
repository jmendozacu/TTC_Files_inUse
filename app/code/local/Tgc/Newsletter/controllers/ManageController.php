<?php
/**
 * Newsletter management controller
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Newsletter
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

require_once (Mage::getModuleDir('controllers', 'Mage_Newsletter') . DS . 'ManageController.php');
class Tgc_Newsletter_ManageController extends Mage_Newsletter_ManageController
{
    public function saveAction()
    {
        if (!$this->_validateFormKey()) {
            return $this->_redirect('*/*/');
        }
        try {
            Mage::getSingleton('customer/session')->getCustomer()
            ->setStoreId(Mage::app()->getStore()->getId())
            ->setIsSubscribed((boolean)$this->getRequest()->getParam('is_subscribed', false))
            ->save();
            if ((boolean)$this->getRequest()->getParam('is_subscribed', false)) {
                Mage::getSingleton('core/session')->addSuccess($this->__('Success! ') . $this->__('The subscription has been saved.'));
            } else {
                Mage::getSingleton('core/session')->addSuccess($this->__('Success! ') . $this->__('The subscription has been removed.'));
            }
        }
        catch (Exception $e) {
            Mage::getSingleton('core/session')->addError($this->__('An error occurred while saving your subscription.'));
        }
        $this->_redirect('*/*/');
    }
}
