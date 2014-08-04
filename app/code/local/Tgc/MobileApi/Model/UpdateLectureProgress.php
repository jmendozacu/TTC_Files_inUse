<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category
 * @package
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_MobileApi_Model_UpdateLectureProgress extends Tgc_MobileApi_Model_Resource
{
    const FORMAT_VIDEO = 'ZV';
    const FORMAT_AUDIO = 'ZA';

    protected function _retrieve()
    {
        $courseId   = $this->getRequest()->getParam('courseId');
        $userId     = $this->getRequest()->getParam('userId');
        $lectureNum = $this->getRequest()->getParam('lectureNumber');
        $progress   = $this->getRequest()->getParam('progress');
        $cpr        = Mage::getResourceModel('tgc_dl/crossPlatformResume');

        try {
            list ($courseId, $format) = $this->_parseCourseId($courseId);
            $this->_loadCustomerById($userId);
            $cpr->saveProgressForCustomer($userId, $this->_getLectureId($courseId, $lectureNum), $progress, $format);
            $notification = 'Successfully updated lecture progress';
        } catch (Exception $e) {
            Mage::logException($e);
            $notification = 'Lecture progress update failed';
        }

        return array('notification' => $notification);
    }

    private function _getProductId($courseId)
    {
        $course = Mage::getResourceModel('tgc_dl/course_collection')
            ->addAttributeToFilter('course_id', $courseId)
            ->fetchItem();

        if (!$course) {
            throw new InvalidArgumentException('Invalid course ID');
        }

        return $course->getId();
    }

    private function _getLectureId($courseId, $lectureNum)
    {
        $lecture = Mage::getResourceModel('lectures/lectures_collection')
            ->addFieldToFilter('product_id', $this->_getProductId($courseId))
            ->addFieldToFilter('lecture_number', $lectureNum)
            ->fetchItem();

        if (false === $lecture) {
            throw new InvalidArgumentException('Invalid lecture number.');
        }

        return $lecture->getId();
    }

    private function _parseCourseId($id)
    {
        if (strlen($id) < 3) {
            throw new InvalidArgumentException('Too short course ID.');
        }

        switch (substr($id, 0, 2)) {
            case self::FORMAT_AUDIO:
                $format = Tgc_DigitalLibrary_Model_Resource_CrossPlatformResume::FORMAT_AUDIO;
                break;

            case self::FORMAT_VIDEO:
                $format = Tgc_DigitalLibrary_Model_Resource_CrossPlatformResume::FORMAT_VIDEO;
                break;

            default:
                throw new InvalidArgumentException('Invalid course ID format prefix.');
        }

        $courseId = substr($id, 2);

        return array($courseId, $format);
    }
}