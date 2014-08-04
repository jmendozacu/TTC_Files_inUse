<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DigitalLibrary
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_DigitalLibrary_FingerprintController extends Mage_Core_Controller_Front_Action
{
    const QUERYSTRING_PARAM_USER_ID           = 'userid';
    const QUERYSTRING_PARAM_ORDER_ID          = 'orderid';
    const QUERYSTRING_PARAM_COURSE_ID         = 'courseid';
    const QUERYSTRING_PARAM_DOWNLOAD_FILENAME = 'Downloadfilename';
    const SUCCESS_RESPONSE                    = 'TRUE';

    private function _getSession()
    {
        return Mage::getSingleton('customer/session');
    }

    private function _getDownloads()
    {
        $downloads = (array)$this->_getSession()->getAkamaiDownloads();

        return $downloads;
    }

    public function indexAction()
    {
        $userId           = $this->getRequest()->getParam(self::QUERYSTRING_PARAM_USER_ID);
        $orderId          = $this->getRequest()->getParam(self::QUERYSTRING_PARAM_ORDER_ID);
        $courseId         = $this->getRequest()->getParam(self::QUERYSTRING_PARAM_COURSE_ID);
        $downloadFilename = $this->getRequest()->getParam(self::QUERYSTRING_PARAM_DOWNLOAD_FILENAME);

        if (empty($userId) || empty($orderId) || empty($courseId) || empty($downloadFilename)) {
            $this->_sendFailureResponse();
            return;
        }

        $downloads = $this->_getDownloads();
        if (isset($downloads[$userId]) && isset($downloads[$userId][$downloadFilename])
                && $downloads[$userId][$downloadFilename]['order_id'] == $orderId
                && $downloads[$userId][$downloadFilename]['course_id'] == $courseId)
        {
            $this->_sendSuccessResponse();
            return;
        }

        $resource = Mage::getResourceModel('tgc_dl/accessRights');
        if ($resource->hasDownloadRights($userId, $courseId, $orderId)) {
            $downloads[$userId][$downloadFilename] = array(
                'order_id'  => $orderId,
                'course_id' => $courseId,
            );

            $this->_getSession()->setAkamaiDownloads($downloads);
            $this->_sendSuccessResponse();
        } else {
            $this->_sendFailureResponse();
        }
    }

    private function _sendSuccessResponse()
    {
        $response = self::SUCCESS_RESPONSE;

        $this->getResponse()
            ->clearHeaders()
            ->setHeader('Content-Type', 'text/html; charset=UTF-8')
            ->setHeader('Content-Length', strlen($response), true)
            ->setBody($response);
    }

    private function _sendFailureResponse()
    {
        $this->getResponse()->setHeader('HTTP/1.1','403 Forbidden');
    }
}
