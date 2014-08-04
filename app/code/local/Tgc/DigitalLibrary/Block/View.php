<?php
/**
 * Digital Library Player View Page
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DigitalLibrary
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_DigitalLibrary_Block_View extends Mage_Core_Block_Template
{
    protected $_course;
    private   $_format;

    public function getCourse()
    {
        if (isset($this->_course)) {
            return $this->_course;
        }

        $courseId  = $this->getRequest()->getParam('id');
        $this->_course = Mage::getModel('catalog/product')->load($courseId);
        if (!$this->_course->getId()) {
            throw new InvalidArgumentException(
                $this->_getHelper('tgc_dl')->__(
                    'Course does not exist'
                )
            );
        }

        return $this->_course;
    }

    public function isStreamingAvailable()
    {
        $course = $this->getCourse();
        $format = $this->getFormat();

        $streamingAvailability = $course->getAvailabilityOfStreaming();
        if (is_null($streamingAvailability)) {
            $streamingAvailability = 2;
        }

        $isAvailable = in_array($streamingAvailability, array($format, Tgc_DigitalLibrary_Model_Source_Streaming::BOTH));

        return $isAvailable;
    }

    public function getLast()
    {
        $last = $this->getRequest()->getParam('resume');

        return empty($last) ? false : $last;
    }

    public function getLectures()
    {
        $courseId   = $this->getCourse()->getId();
        $format     = $this->getFormat();
        $resource   = Mage::getResourceModel('tgc_dl/crossPlatformResume');
        $webUserId  = Mage::getSingleton('customer/session')->getCustomer()->getWebUserId();

        $lectures = $resource->getLectureData($courseId, $webUserId, $format);

        return $lectures;
    }

    public function convertDuration($duration)
    {
        if (!is_numeric($duration)) {
            return false;
        }

        $time = gmdate('H:i:s', $duration);
        list($hours, $minutes, $seconds) = explode(':', $time);

        $timeString = '';
        if ($hours > 0) {
            $timeString .= $hours . ' hours ';
        }
        if ($minutes > 0) {
            $timeString .= $minutes . ' min ';
        }

        return $timeString;
    }

    public function getProgressPercent($lecture)
    {
        $duration = $lecture['duration'];
        $progress = $lecture['progress'];

        if (!$progress) {
            return 0;
        }

        return intval($progress / $duration * 100);
    }

    public function getFormat()
    {
        if (isset($this->_format)) {
            return $this->_format;
        }

        $format = Mage::app()->getFrontController()->getRequest()->getParam('format');
        $this->_format = $format ? 1 : 0;

        return $this->_format;
    }

    public function filterCms($content)
    {
        return Mage::helper('cms')->getBlockTemplateProcessor()->filter($content);
    }

    public function getProfessorInfo($course)
    {
        $data = $course->getProfessor();
        $professorIds = explode(',', $data);

        if (empty($professorIds)) {
            return '';
        }

        $collection = Mage::getResourceModel('profs/professor_collection')
            ->addFieldToFilter('professor_id', array('in' => $professorIds));

        return $collection;
    }

    public function hasMultipleProfessors($course)
    {
        $data = $course->getProfessor();
        $professors = explode(',', $data);
        if (empty($professors)) {
            return false;
        }

        return count($professors) > 1;
    }

    public function canDownload($lectureId, $format)
    {
        $webUserId = Mage::getSingleton('customer/session')->getCustomer()->getWebUserId();
        $url       = Mage::helper('tgc_dl/akamai')->getLectureDownloadUrl($webUserId, $lectureId, $format);

        return empty($url) ? false : true;
    }

    public function getTranscriptUrl($course)
    {
        $course->setMediaFormat($this->getformat());

        return Mage::helper('tgc_dl/akamai')->getTranscriptUrl($course);
    }

    public function getTranscriptPrice($product)
    {
        $attribute = Mage::getModel('catalog/product')->getResource()->getAttribute('media_format');
        $optionId  = $attribute->getSource()->getOptionId(Tgc_DigitalLibrary_Model_Observer::DIGITAL_TRANSCRIPT);

        $product = Mage::getModel('catalog/product')
            ->getCollection()
            ->addAttributeToFilter('course_id', array('eq' => $product->getCourseId()))
            ->addAttributeToFilter('media_format', array('eq' => $optionId))
            ->addFinalPrice()
            ->getfirstItem();

        if ($product && $product->getId()) {
            $price = $product->getFinalPrice();

            return Mage::helper('core')->currency($price, true, false);
        }

        return false;
    }

    public function getMediaId($lecture)
    {
        return $this->getFormat() == 0 ?
            $lecture['audio_brightcove_id'] :
            $lecture['video_brightcove_id'];

    }

    public function getGuidebookUrl($product)
    {
        return Mage::helper('tgc_dl/akamai')->getGuidebookUrl($product);
    }
}
