<?php
/**
 * Digital Library
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DigitalLibrary
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_DigitalLibrary_Helper_Akamai extends Mage_Core_Helper_Data
{
    const LECTURE_DOWNLOAD_ENDPOINT = 'http://download.eastbaymedia-drm.com.edgesuite.net/anon.eastbaymedia-drm/courses/';

    public function getLectureDownloadUrl($webUserId, $lectureId, $format)
    {
        $resource = $resource = Mage::getResourceModel('tgc_dl/accessRights');
        $access = $resource->getAcess($webUserId, $lectureId, $format);

        if (!$access || !$access->getEntityId()) {
            return false;
        }

        $courseId     = $this->_getCourseId($access);
        $orderId      = $access->getOrderId();
        $downloadPref = $this->_getDownloadPreference($format);
        $akamaiId     = $this->_getAkamaiId($lectureId);

        if (empty($courseId) || empty($orderId) || empty($downloadPref) || empty($akamaiId)) {
            return false;
        }

        $query = array(
            'userid'   => $webUserId,
            'orderid'  => $orderId,
            'courseid' => $courseId,
            'FName'    => $akamaiId,
        );
        $queryString = http_build_query($query);

        return self::LECTURE_DOWNLOAD_ENDPOINT . $courseId . DS . $downloadPref . DS . $akamaiId . '.' . $downloadPref
            . '?' . $queryString;
    }

    private function _getAkamaiId($lectureId)
    {
        $resource = $resource = Mage::getResourceModel('tgc_dl/accessRights');
        $akamaiId = $resource->getAkamaiIdFromLectureId($lectureId);

        return $akamaiId;
    }

    private function _getDownloadPreference($format)
    {
        $customer = Mage::getSingleton('customer/session')->getCustomer();

        switch ($format) {
            case '0':
                $audio = $customer->getAudioFormat();
                $source = Mage::getModel('tgc_dl/source_audio_format')->toOptionArray();

                return isset($source[$audio]) ? $source[$audio] : false;

            case '1':
                $video = $customer->getVideoFormat();
                $source = Mage::getModel('tgc_dl/source_video_format')->toOptionArray();

                return isset($source[$video]) ? $source[$video] : false;
        }

        return false;
    }

    private function _getCourseId(Tgc_DigitalLibrary_Model_AccessRights $access)
    {
        $product = Mage::getModel('catalog/product')
            ->getCollection()
            ->addAttributeToSelect('course_id')
            ->addAttributeToFilter('entity_id', array('eq' => $access->getCourseId()))
            ->getFirstItem();

        if (!$product || !$product->getCourseId()) {
            return false;
        }

        return $product->getCourseId();
    }

    public function getGuidebookUrl($product)
    {
        $content = Mage::getModel('tgc_dl/akamaiContent')
            ->getCollection()
            ->addfieldToFilter('course_id', array('eq' => $product->getCourseId()))
            ->getFirstItem();

        if ($content && $content->getGuidebookFileName() && $content->getGuidebookUrlPrefix()) {
            $url = $content->getGuidebookUrlPrefix() . $content->getGuidebookFileName();
            return $url;
        }

        return false;
    }

    public function getTranscriptUrl($product)
    {
        $format = $product->getMediaFormat();
        $webUserId = Mage::getSingleton('customer/session')->getCustomer()->getWebUserId();
        $access = Mage::getModel('tgc_dl/accessRights')
            ->getCollection()
            ->addFieldToFilter('format', array('eq' => $format))
            ->addFieldToFilter('web_user_id', array('eq' => $webUserId))
            ->addFieldToFilter('course_id', array('eq' => $product->getId()))
            ->getFirstItem();

        if ($access->getId() && $access->getDigitalTranscriptPurchased()) {
            $content = Mage::getModel('tgc_dl/akamaiContent')
                ->getCollection()
                ->addfieldToFilter('course_id', array('eq' => $product->getCourseId()))
                ->getFirstItem();

            $orderId = $access->getOrderId();
            if ($content && $content->getTranscriptFileName() && $content->getTranscriptUrlPrefix() && $orderId) {
                $url = $content->getTranscriptUrlPrefix() . $content->getTranscriptFileName();
                $query = array(
                    'userid'   => $webUserId,
                    'orderid'  => $orderId,
                    'CourseID' => $product->getCourseId(),
                    'FName'    => $content->getTranscriptFileName(),
                );
                $queryString = http_build_query($query);

                return $url . '?' . $queryString;
            }
        }

        return false;
    }
}
