<?php
/**
 * Dax adcode entity for importexport
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Dax
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */


class Tgc_Dax_Model_Import_Entity_Set extends Tgc_Dax_Model_Import_Entity_Course
{
    protected $_entityTypeCode = 'set';

    const PROFILE_ATTRIBUTE_SET = 'Sets';

    const ENTITY_TYPE_CODE = 'set';

    const ATTRIBUTE_CODE_COURSE_ID = 'course_id'; //used in creating query that pull a list of all course ids.

    protected $listAllCourseIds;

    public function __construct()
    {
        parent::__construct();
        $this->setEntityTypeCode('set'); //entity type id not referenced in any significant way in this class or in any parent, therefore, okay to change!

        //the function Tgc_Dax_Model_Resource_Import_Data::getIterator() requires that this value is set in order to understand what rows need to be retrieved from importexport_importdata
        $this->_dataSourceModel->setEntityTypeCode($this->getEntityTypeCode());

        //Retrieving a list of existing course ids: Step 1 = retrieve attribute id for course_id, Step 2 = generate list course ids.
        $query = 'SELECT attribute_id FROM eav_attribute WHERE attribute_code = "' . self::ATTRIBUTE_CODE_COURSE_ID . '"';
        $result = $this->_connection->fetchCol($query);
        $courseIdAttributeId = !empty($result) ? $result[0] : '';

        if($courseIdAttributeId) {
            $selectCourseIds = $this->_connection->select()
                ->from('catalog_product_entity_varchar', array('entity_id','value'))
                ->where('attribute_id = :attribute_id');

            $this->listAllCourseIds = $this->_connection->fetchPairs($selectCourseIds, array('attribute_id' => $courseIdAttributeId));
        }
    }

    public function getProfileAttributeSet()
    {
        return self::PROFILE_ATTRIBUTE_SET;
    }

    /**
     * Performs validation for the sets.
     *
     * @param array $rowData
     * @param int $rowNum
     * @return bool
     */
    public function validateRow(array $rowData, $rowNum)
    {
        $isRowValid = true;

        if($rowNum != 0) {
            $associatedColumnValid = false;
            if(isset($rowData['associated'])) {
                if($rowData['associated']) {
                    $associatedColumnValid = true;
                }
            }

            /*When this runs when data is being validated, Teaching company spredsheets always have this data filled in.
              parent::validateRow calls _filterRowData, which - for simple products - deletes this column and places value in the set_members. Then, save validated bunches stores all rows in
              spreadsheet into importexport_importdata table.
              When you press import button, it is pulling data from importexport_importdata table, NOT from the spreadsheet!
            */
            if(!is_numeric($rowData['sku'])) {
                if(isset($rowData['set_members'])) {
                    if($rowData['set_members']) {
                        $associatedColumnValid = true; //Simple products in sets import have value under associated, it is not children products, rather, it is equivalent to set_members.
                    }
                }
            }

            if(!$associatedColumnValid) {
                $this->addRowError(self::SET_NO_ASSOCIATED_DATA, $rowNum);
            }

            if(!is_numeric($rowData['sku'])) {
                $this->_validateCourse($rowData['associated'], $rowNum, 'associated', $isRowValid);
            }
        }

        $areParentsValid = parent::validateRow($rowData, $rowNum);

        if(!$areParentsValid) {
            $isRowValid = false;
        }

        return $isRowValid;
    }

    /**
     * Some configurable attributes are composed of values taken from one or more of its child products.  That is why they are called composite.
     *
     * @param $rowData
     * @param $rowSku
     */
    public function collectCompositeAttributes($rowData, $rowSku)
    {
        if(count($this->_compositeAttributes['list']) > 0)
        {
            foreach($this->_compositeAttributes['list'] as $attributeCode) {
                $compositeSku = Zend_Filter::filterStatic($rowSku, 'Digits');
                if($rowData['type'] != 'configurable') {
                    $originalValueArray = array();
                    if(isset($this->_compositeAttributes['data'][$attributeCode][$compositeSku]['value'])) {
                        $originalValueArray = $this->_compositeAttributes['data'][$attributeCode][$compositeSku]['value'];
                    }

                    $currentRowValueArray = array();
                    if(isset($rowData[$attributeCode])) {
                        $currentRowValueArray = $this->userCSVDataAsArray($rowData[$attributeCode]);
                    }

                    $finalAttributeValue = array_unique(array_merge($originalValueArray, $currentRowValueArray));
                    $this->_compositeAttributes['data'][$attributeCode][$compositeSku]['value'] = $finalAttributeValue;
                    $this->_compositeAttributes['data'][$attributeCode][$compositeSku]['entity_type_id'] = $this->_entityTypeId;
                    $this->_compositeAttributes['data'][$attributeCode][$compositeSku]['store_id'] = 0;
                }
            }
        }
    }

    /**
     * Validates course_ids.  Ensures that the ids correspond to a course that exist in the database.
     * @param $courses - can contain one, or more courses.
     */
    protected function _validateCourse($courses, $rowNum, $fieldName, &$isRowValid)
    {
        if($courses) {
            $coursesArray = $this->userCSVDataAsArray($courses);
            foreach($coursesArray as $course) {
                $courseId = $this->_helper()->stripNonAlphaNumeric($course);
                if(!in_array($courseId, $this->listAllCourseIds)) {
                    $isRowValid = false;
                    $this->addRowError(self::SET_COURSE_INVALID, $rowNum, $fieldName);
                }
            }
        }
    }
}