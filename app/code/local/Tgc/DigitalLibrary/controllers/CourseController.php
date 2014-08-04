<?php
require_once (Mage::getModuleDir('controllers', 'Tgc_DigitalLibrary') . DS . 'AccountController.php');
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DigitalLibrary
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_DigitalLibrary_CourseController extends Tgc_DigitalLibrary_AccountController
{
    public function viewAction()
    {
        if (!$this->_isAllowedToViewCourse()) {
            $this->_getSession()->addError(
                Mage::helper('tgc_dl')->__(
                    'You don\'t have access to view that course'
                )
            );
            $this->_redirect('*/account/index');
            return;
        }

        $this->loadLayout();
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('catalog/session');
        $this->getLayout()->getBlock('head')->setTitle($this->__('Course Player Page'));
        $this->renderLayout();
    }

    private function _isAllowedToViewCourse()
    {
        $webUserId = $this->_getCustomer()->getWebUserId();
        $courseId  = $this->getRequest()->getParam('id');
        $format    = $this->getRequest()->getParam('format');
        $access    = Mage::getResourceModel('tgc_dl/accessRights');

        return (bool)$access->checkCustomerAccessToCourse($webUserId, $courseId, $format);
    }

    private function _isAllowedToDownloadLecture()
    {
        $webUserId = $this->_getCustomer()->getWebUserId();
        $courseId  = $this->getRequest()->getParam('course');
        $format    = $this->getRequest()->getParam('format');

        $access = Mage::getResourceModel('tgc_dl/accessRights');

        return (bool)$access->checkCustomerCanDownloadLecture($webUserId, $courseId, $format);
    }

    public function saveProgressAction()
    {
        $webUserId = $this->_getCustomer()->getWebUserId();
        $lectureId = $this->getRequest()->getParam('id');
        $progress  = $this->getRequest()->getParam('position');
        $format    = $this->getRequest()->getParam('format');
        $cpr       = Mage::getResourceModel('tgc_dl/crossPlatformResume');

        $cpr->saveProgressForCustomer($webUserId, $lectureId, $progress, $format);
        Mage::getModel('core/cookie')->set(Tgc_DigitalLibrary_Model_Resource_CrossPlatformResume::COOKIE_NAME, $lectureId);
    }

    public function setWatchedAction()
    {
        $webUserId = $this->_getCustomer()->getWebUserId();
        $lectureId = $this->getRequest()->getParam('id');
        $format    = $this->getRequest()->getParam('format');
        $cpr       = Mage::getResourceModel('tgc_dl/crossPlatformResume');

        $cpr->setWatchedForCustomer($webUserId, $lectureId, $format);
    }

    public function downloadLectureAction()
    {
        if (!$this->_isAllowedToDownloadLecture()) {
            $message = Mage::helper('tgc_dl')->__(
                'You don\'t have access to download this lecture'
            );
            $response = array('status' => 'noaccess', 'message' => $message);
            $this->_sendAjaxResponse($response);
            return;
        }

        $webUserId = $this->_getCustomer()->getWebUserId();
        $lectureId = $this->getRequest()->getParam('lecture');
        $format    = $this->getRequest()->getParam('format');
        $cpr       = Mage::getResourceModel('tgc_dl/crossPlatformResume');

        $cpr->saveDownloadDateForCustomer($webUserId, $lectureId, $format);
        $url = Mage::helper('tgc_dl/akamai')->getLectureDownloadUrl($webUserId, $lectureId, $format);

        $response = array('status' => 'success', 'url' => $url);
        $this->_sendAjaxResponse($response);
        return;
    }

    public function purchaseTranscriptAction()
    {
        $courseId = $this->getRequest()->getParam('courseId');
        if (empty($courseId)) {
            $response = array('status' => 'failure', 'message' => 'Invalid Course ID');
            $this->_sendAjaxResponse($response);
            return;
        }

        $attribute = Mage::getModel('catalog/product')->getResource()->getAttribute('media_format');
        $optionId  = $attribute->getSource()->getOptionId(Tgc_DigitalLibrary_Model_Observer::DIGITAL_TRANSCRIPT);

        $product = Mage::getModel('catalog/product')
            ->getCollection()
            ->addAttributeToFilter('course_id', array('eq' => $courseId))
            ->addAttributeToFilter('media_format', array('eq' => $optionId))
            ->addFinalPrice()
            ->getfirstItem();

        if (!$product) {
            $response = array('status' => 'failure', 'message' => 'Digital transcript not found');
            $this->_sendAjaxResponse($response);
            return;
        }

        $cart = Mage::getModel('checkout/cart');
        try {
            $cart->addProduct($product->getId(), '1');
            $cart->save();
        } catch (Exception $e) {
            Mage::logException($e);
            $response = array('status' => 'failure', 'message' => 'There was a problem adding the transcript to cart');
            $this->_sendAjaxResponse($response);
            return;
        }

        $response = array('status' => 'success', 'message' => '');
        $this->_sendAjaxResponse($response);
        return;
    }
}
