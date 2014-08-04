<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Lectures
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_Lectures_Datamart_SpecialController extends Mage_Core_Controller_Front_Action
{
    public function unsubscribeprospectAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function unsubscribeexecuteAction()
    {
        $session = Mage::getModel('core/session');
        $postedEmail = $this->getRequest()->getPost('unsubscribe_email');

        $customerToUnsubscribe = $this->_lecturesHelper()->getCustomerToUnsubscribe($postedEmail); //this performs Zend_Validate_EmailAddress, to ensure its valid.
        if ($customerToUnsubscribe) {
            if($this->_lecturesHelper()->isCustomerEligibleToUnsubscribe()) { //validation needed prevent user from injecting values into form.
                try {
                    $customerToUnsubscribe->setFreeLectureProspect(false);
                    $customerToUnsubscribe->setFreeLectSubscribeStatus(3); //3 is unsubscribed
                    $customerToUnsubscribe->setFreeLectDateUnsubscribed(now());
                    $customerToUnsubscribe->save();
                    $this->_lecturesHelper()->markUserAsUnsubscribed();
                    $this->_freemarketinglectureHelper()->updateNewsletterSubscriptionStatus($customerToUnsubscribe->getEmail(), 'unsubscribe');
                } catch (Exception $e) {
                    Mage::logException($e);
                    $session->addError($e->getMessage());
                }
            }
        }

        $this->_redirect('*/*/unsubscribeprospect');
    }

    protected function _lecturesHelper()
    {
        return Mage::helper('lectures/unsubscribe');
    }

    public function _freemarketinglectureHelper()
    {
        return Mage::helper('tgc_catalog/freemarketinglecture');
    }
}