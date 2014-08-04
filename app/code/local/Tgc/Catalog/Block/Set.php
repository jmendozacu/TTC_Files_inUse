<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category
 * @package
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Catalog_Block_Set extends Mage_Catalog_Block_Product
{
    private $_courses;

    /**
     * @return Tgc_DigitalLibrary_Model_Resource_Course_Collection
     */
    public function getCourses()
    {
        if (!$this->_courses) {
            $this->_courses = $this->_loadCourses();
        }

        return $this->_courses;
    }

    /**
     * @return Tgc_DigitalLibrary_Model_Resource_Course_Collection
     */
    protected function _loadCourses()
    {
        return Mage::getResourceModel('tgc_dl/course_collection')
            ->addAttributeToSelect('*')
            ->addFilterBySet($this->getProduct());
    }
}