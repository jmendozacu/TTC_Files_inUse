<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category
 * @package
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_MobileApi_Model_Course extends Tgc_MobileApi_Model_Course_Abstract
{
    protected function _retrieve()
    {
        $courseId = $this->getRequest()->getParam('courseId');
        $userId = $this->getRequest()->getParam('userId');
        $customer = $this->_loadCustomerById($userId);

        return $this->_loadCourse($userId, $courseId);
    }

    private function _loadCourse($userId, $courseId)
    {
        $response = array('contentlength' => null, 'courseList' => array());

        $courses = Mage::getResourceModel('tgc_dl/course_collection')
            ->addAttributeToSelect('*')
            ->addFilterByWebUserId($userId)
            ->setOrder('date_purchased', Varien_Data_Collection::SORT_ORDER_DESC)
            ->groupByAttribute('entity_id')
            ->addAttributeToFilter('course_id', (int)$courseId);

        foreach ($courses as $c) {
            $response['courseList'][] = array(
                'id'          => $this->_getCourseId($c),
                'title'       => $c->getName(),
                'description' => $c->getDescription(),
                'mediaType'   => $this->_getMediaType($c->getFormat()),
                'guide'       => $c->getGuidebook(),
                'cat'         => $this->_getCategoryName($c),
                'professors'  => $this->_getProfessors($c),
                'lectures'    => $this->_getLectures($c, $userId),
            );
        }

        $response['contentlength'] = count($response['courseList']);

        return $response;
    }

    private function _getProfessors(Mage_Catalog_Model_Product $course)
    {
        $professorIds = array_filter(explode(',', $course->getProfessor()));
        if (empty($professorIds)) {
            return array();
        }

        $professors = Mage::getResourceModel('profs/professor_collection')
            ->addFieldToFilter('main_table.professor_id', array('in' => $professorIds))
            ->addFieldToSelect('*')
            ->addAlmaMaterList()
            ->addSchoolList();

        $result = array();
        foreach ($professors as $p) {
            $result[] = array(
               'id'        => $p->getId(),
               'firstName' => $p->getFirstName(),
               'lastName'  => $p->getLastName(),
               'image'     => (string)$p->getPhoto(),
               'degree'    => $p->getQual() . ($p->getAlmaMaterList() ? ", {$p->getAlmaMaterList()}" : ''),
               'school'    => $p->getSchoolList(),
               'bio'       => $p->getBio(),
           );
        }

        return $result;
    }

    private function _getLectures(Mage_Catalog_Model_Product $course, $userId)
    {
        $lectures = Mage::getResourceModel('lectures/lectures_collection')
            ->addFieldToSelect('*')
            ->addFieldToFilter('product_id', $course->getId())
            ->addProgressForUser($userId);

        $isAudio = $course->getFormat() == Tgc_DigitalLibrary_Model_Source_Format::AUDIO;
        $result = array();

        foreach ($lectures as $l) {
             $lr = array(
                't'  => $l->getTitle(),
                'd'  => $l->getDescription(),
                'id' => $isAudio ? $l->getAudioBrightcoveId() : $l->getVideoBrightcoveId(),
                'dr' => $isAudio ? $l->getAudioDuration() : $l->getVideoDuration(),
            );
            if ($l->getProgress()) {
                $lr['pg'] = (int)$l->getProgress();
            }
            $result[] = $lr;
        }

        return $result;
    }
}