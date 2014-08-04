<?php
/**
 * User: mhidalgo
 * Date: 18/03/14
 * Time: 11:52
 */

class Tgc_Cms_Block_FreeCourses extends Mage_Core_Block_Template
{
    const CACHE_TAG = 'FREE_COURSES';

    protected $_course  = null;
    protected $_courses = null;
    protected $_videos  = null;
    protected $_audios  = null;

    /** @var $_helper Tgc_Catalog_Helper_Data */
    protected $_helper = null;
    /**
     * Initialize block's cache and template settings
     */
    protected function _construct()
    {
        parent::_construct();

        if (empty($this->_template)) {
            $this->setTemplate('freelectures/freeCourses.phtml');
        }

        $cacheLifetime = $this->getCacheLifetime() ? $this->getCacheLifetime() : false;
        $this->addData(array('cache_lifetime' => $cacheLifetime));
        $this->addCacheTag(array(
            self::CACHE_TAG,
        ));

        $this->_helper = Mage::helper('tgc_catalog');
    }

    public function getFreeVideoCollection() {
        return $this->getFreeLecturesByType('video');
    }

    public function getFreeAudioCollection() {
        return $this->getFreeLecturesByType('audio');
    }

    public function getFreeLecturesByType($type = '')
    {
        $mediaObject = false;
        if($type == 'audio' || $type == 'video') {
            $idField = null;
            if($type == 'audio') {
                $mediaObject = $this->_audios;
                $idField = 'audio_brightcove_id';
            }
            if($type == 'video') {
                $mediaObject = $this->_videos;
                $idField = 'video_brightcove_id';
            }

            if(is_null($mediaObject)) {
                $courses = $this->getFreeCourses();
                $mediaLectures = new Varien_Data_Collection();
                foreach($courses as $course) {
                    $lectures = $course->getLectures();
                    $lectures->addFieldToFilter($idField,array('neq' =>'NULL'));
                    foreach($lectures as $lecture) {
                        $lecture->setFreeCourse($course);
                        $mediaLectures->addItem($lecture);
                    }
                }

                if($type == 'audio') {
                    $this->_audios = $mediaLectures;
                }
                if($type == 'video') {
                    $this->_videos = $mediaLectures;
                }

                $mediaObject = $mediaLectures;
            }
        }

        return $mediaObject;
    }

    public function getFreeCourse() {
        /**
         * @var $collection Tgc_DigitalLibrary_Model_Resource_Course_Collection
         */
        if (is_null($this->_course)) {
            $collection = Mage::getResourceModel('tgc_dl/course_collection')
                ->addAttributeToSelect('*')
                ->addFreeMarketingFilter();

            $this->_course = $collection->getFirstItem();
        }

        return $this->_course;
    }

    public function getFreeCourses() {
        /**
         * @var $collection Tgc_DigitalLibrary_Model_Resource_Course_Collection
         */
        if (is_null($this->_courses)) {
            $collection = Mage::getResourceModel('tgc_dl/course_collection')
                ->addAttributeToSelect('*')
                ->addFreeMarketingFilter();

            $this->_courses = $collection;
        }

        return $this->_courses;
    }

    public function getCourseDescriptionFromLecture($lecture) {
        return $this->getOriginalCourseFromLecture($lecture)->getShortDescription();
    }

    public function getCourseNameFromLecture($lecture) {
        return $this->getOriginalCourseFromLecture($lecture)->getName();
    }

    public function getCourseUrlFromLecture($lecture) {
        if ($course_id = $this->getOriginalCourseIdFromLecture($lecture)) {
            $url = $this->_helper->getProductUrlFromCourseId($course_id);
        } else {
            $url = "Javascript:void(0)";
        }
        return $url;
    }

    public function getOriginalCourseFromLecture($lecture) {
        if ($courseId = $this->getOriginalCourseIdFromLecture($lecture)) {
            $course = Mage::getModel('tgc_dl/course')->load(
                $this->_helper->getProductIdFromCourseId($courseId)
            );
        } else {
            $course = $lecture->getFreeCourse();
        }
        return $course;
    }

    public function getOriginalCourseIdFromLecture($lecture) {
        $courseId = $lecture->getOriginalCourseNumber();
        if (!isset($courseId)) {
            $courseId = $this->getDefaultCourseIdFromLecture($lecture);
        }
        return $courseId;
    }

    /**
     * @param $lecture
     * @return bool|int
     */
    public function getDefaultCourseIdFromLecture($lecture) {
        $courseId = $lecture->getDefaultCourseNumber();
        if (!isset($courseId)) {
            $courseId = false;
        }
        return $courseId;
    }

    public function getProfessorFromLecture($lecture) {
        $course = $this->getOriginalCourseFromLecture($lecture);

        $professor = Mage::getModel('profs/professor')->load($course->getProfessor());

        return $professor;
    }
}