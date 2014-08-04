<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Lectures
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_Lectures_Helper_Validation extends Mage_Core_Helper_Abstract
{

    const ATTRIBUTE_CODE_COURSE_ID = 'course_id'; //used in creating query that pull a list of all course ids.

    protected $_idFieldsList = array('akamai_download_id','video_brightcove_id','audio_brightcove_id');

    protected $_primaryKeyFieldsList = array('video_brightcove_id','audio_brightcove_id');

    protected $_fieldMustBeNumbers = array('lecture_number','original_lecture_number');

    protected $_requiredLectureFields = array('lecture_number','title');

    protected $_fieldsStripHtml = array('title');

    protected $_connection;

    protected $_listAllAdminFormFields = array('akamai_download_id','video_brightcove_id','audio_brightcove_id','original_lecture_number',
        'lecture_number','product_id','title','professor','description','default_course_number','video_duration','audio_duration',
        'audio_available','video_available','audio_download_filesize','video_download_filesize_pc','video_download_filesize_mac');

    protected $_fieldLabels = array(
        'akamai_download_id'    => 'Akamai Download Id',
        'video_brightcove_id'   => 'Video Brightcove Id',
        'audio_brightcove_id'   => 'Audio Brightcove Id',
    );

    public function __construct()
    {
        $this->_connection = Mage::getSingleton('core/resource')->getConnection('write');
    }

    public function loadLecture($idValue, $idFieldName, $lectureIsBeingEdited, &$messageLectureSave, &$isDataValid)
    {
        $performEdit = false;
        $idValueExists = false;

        if($lectureIsBeingEdited && $idValue) {
            $idFieldName = 'id'; //idFieldName will always equal id.
            $idValueExists = Mage::getModel('lectures/lectures')->getCollection()->addFieldToFilter($idFieldName, $idValue)->count();
            $performEdit = true;

            if(!$idValueExists) {
                $isDataValid = false;
                $performEdit = false;
                $this->_getSession()->addError(
                    Mage::helper('lectures')->__("You are trying to edit a lecture that doesn't exist.  Press the Add new button or change the id.")
                );
            }
        }

        $lecture = Mage::getModel('lectures/lectures');

        if($performEdit) {
            $lecture->load($idValue,$idFieldName);
            $messageLectureSave = 'A lecture has been successfully edited.';
        } else {
            $messageLectureSave = 'A new lecture has been successfully created.';
        }

        if($idValueExists > 1) {
            Mage::log('Multiple lectures with the "Lecture Number", ' . $idValueExists . ', already exist.  All lectures must have a unique lecture number.');
        }

        return $lecture;
    }

    public function retrieveIdFieldNameAndValue($data, &$isDataValid, $lectureIsBeingEdited, $lectureEditedId)
    {
        $primaryId = array('name' => null, 'value' => null);
        if($lectureIsBeingEdited) {
            if($lectureEditedId) {
                //sets the id field name and value when an existing lecture is being edited.
                $primaryId = array('name' => 'id', 'value' => $lectureEditedId);
            }
        } 

        return $primaryId;
    }

    public function validateIfKeyFieldsAreUnique($data, $lecturePrimaryKeyValue = null, &$isDataValid)
    {
        if($isDataValid) {
            foreach($this->_idFieldsList as $fieldName) {
                if($data[$fieldName]) {
                    $isPrimaryKeyFieldUnique = $this->isKeyFieldUnique($fieldName, $data[$fieldName], $lecturePrimaryKeyValue);
                    if(!$isPrimaryKeyFieldUnique) {
                        $this->_getSession()->addError(
                            Mage::helper('lectures')->__('The field "' . $this->_fieldLabels[$fieldName] . '" is the same as another lecture that has been saved.  Please change this field\'s value')
                        );
                        $isDataValid = false;
                    }
                }
            }
        }

        return $isDataValid;
    }

    public function isKeyFieldUnique($fieldName, $value, $lecturePrimaryKeyValue = null)
    {
        $collectionOfPrimaryKeyColumnWithSameValue = Mage::getModel('lectures/lectures')->getCollection()->addFieldToFilter($fieldName, $value);

        if($lecturePrimaryKeyValue) {
            $collectionOfPrimaryKeyColumnWithSameValue->addFieldToFilter('id', array('neq' => $lecturePrimaryKeyValue));
        }

        $recordsWithSameId = $collectionOfPrimaryKeyColumnWithSameValue->count();

        return ($recordsWithSameId > 0) ? false : true;
    }

    public function lectureIdExistsInAnotherProduct($lectureId, $productId, &$isDataValid)
    {
        $lectureIdRequestObject = Mage::app()->getFrontController()->getAction()->getRequest()->getParam('lectureid');
        $lectureIdFormInputField = Mage::getModel('lectures/lectures')->load($lectureId, 'lecture_id')->getId();

        if($lectureIdRequestObject) { //note: $lectureId always exists, because this function will never be called if it does not exist.
            //if statement executes when a record is being edited, and the user has changed that record's lecture id. Checks to see if changed it to value already exists.
            $changedLectureIdToInvalidValue = Mage::getModel('lectures/lectures')->getCollection()->addFieldToFilter('lecture_id', $lectureId)
                ->addFieldToFilter('id', array('neq' => $lectureIdRequestObject))
                ->count();
            if($changedLectureIdToInvalidValue > 0) {
                $this->_getSession()->addError(
                    Mage::helper('lectures')->__('The lecture could not be edited, because the "Lecture ID" was changed to a value that already exists.')
                );
                $isDataValid = false;
            }
        } elseif($lectureIdFormInputField) {
            //if a new record is being created.
            $this->_getSession()->addError(
                Mage::helper('lectures')->__('The lecture could not be saved, because a different product contains a lecture with the same "Lecture ID".')
            );
            $isDataValid = false;
        }
    }

    public function fieldMustBeNumbersValidation($lecturesData, &$isDataValid)
    {
        foreach($this->_fieldMustBeNumbers as $numberField) {
            if($lecturesData[$numberField]) {
                if(!is_numeric($lecturesData[$numberField])) {
                    $isDataValid = false;
                    $this->_getSession()->addError(
                        Mage::helper('lectures')->__('A new lecture could not be created because the following field must be an integer: ' . $numberField)
                    );
                }
            }
        }
    }

    public function validateRequiredFields($lecturesData, &$isDataValid)
    {
        $missingFields = array();
        foreach($this->_requiredLectureFields as $requiredField) {
            if(!$lecturesData[$requiredField]) {
                $isDataValid = false;
                $missingFields[] = $requiredField;
            }
        }

        if(count($missingFields) > 0) {
            $listMissingFields = implode(", ", $missingFields);
            $missingFieldsMessage = 'A new lecture could not be created because the following required field(s) are missing: ' . $listMissingFields;
            $this->_getSession()->addError(
                Mage::helper('lectures')->__($missingFieldsMessage)
            );
        }
    }

    public function doesDefaultCourseNumberMatchExistingProduct(&$defaultCourseNumber, &$isDataValid)
    {
        if($defaultCourseNumber) {
            $doesDefaultCourseNumberMatchExistingProduct = Mage::getModel('catalog/product')->getCollection()->addAttributeToFilter('course_id', $defaultCourseNumber)->count();
            if($doesDefaultCourseNumberMatchExistingProduct == 0) {
                //$isDataValid = false; //isDataValid not changed false because we want data to repopulate on form, if invalid, so admin user not need to reenter data.
                $defaultCourseNumber = null; //default course number is reset because it is invalid.
                $this->_getSession()->addNotice(
                    Mage::helper('lectures')->__('The "Default Course Number" could not be saved, because there is no product with the value you entered.')
                );
            }
        }
    }

    public function lectureNumberExistsForProduct($lectureNumber, $productId)
    {
        if($lectureNumber) {
            $lectureNumberExistsForProduct = Mage::getModel('lectures/lectures')->getCollection()->lectureNumberExistsForProduct($lectureNumber, $productId);

            if($lectureNumberExistsForProduct > 0) {
                $lecturesNeedNumberAdjustment = Mage::getModel('lectures/lectures')->getCollection()
                    ->addFieldToFilter('product_id', $productId)
                    ->addFieldToFilter('lecture_number', array('gteq' => $lectureNumber));
                foreach($lecturesNeedNumberAdjustment as $lectureToAdjust) {
                    $lectureToAdjust->setLectureNumber($lectureToAdjust->getLectureNumber() + 1);
                }
                $lecturesNeedNumberAdjustment->save();
            }
        }
    }

    public function isRequestedLectureInvalid(&$isDataValid)
    {
        $lectureIdRequested = Mage::app()->getFrontController()->getAction()->getRequest()->getParam('lectureid');

        if($lectureIdRequested) {
            $requestedLectureIdExists = Mage::getModel('lectures/lectures')->load($lectureIdRequested)->getId();
            if(!$requestedLectureIdExists) {
                $this->_getSession()->addError(
                    Mage::helper('lectures')->__('The lecture that you are trying to edit does not exist. Please click on an item on the grid you would like to edit.')
                );
                $isDataValid = false;
            }
        }
    }

    public function formatDataToAvoidUndefinedIndexErrors(&$data)
    {
        foreach($this->_listAllAdminFormFields as $fieldName) {
            if(!isset($data[$fieldName])) {
                $data[$fieldName] = null;
            }
        }

        foreach($data as $dataFieldName => $dataFieldValue) {
            if(!in_array($dataFieldName, $this->_listAllAdminFormFields)) {
                unset($data[$dataFieldName]);
            }
        }
    }

    public function validateKeyFieldsForUniqueness($primaryKeyInfo, $data, &$isDataValid)
    {
        $this->validateCompositePrimaryKeyForIsRequired($data, $isDataValid);

        $realPrimaryKeyValue = null;
        if($primaryKeyInfo['name'] == 'id') {
            $realPrimaryKeyValue = $primaryKeyInfo['value'];
        }

        $arePrimaryKeysValid = $this->validateIfKeyFieldsAreUnique($data, $realPrimaryKeyValue, $isDataValid);
        if(!$arePrimaryKeysValid) {
            $isDataValid = false;
        }
    }

    public function validateCompositePrimaryKeyForIsRequired($data, &$isDataValid)
    {
        $primaryKeyValidationResult = false;
        foreach($this->_primaryKeyFieldsList as $primaryKeyFieldName) {
            if($data[$primaryKeyFieldName]) {
                $primaryKeyValidationResult = true;
            }
        }

        if(!$primaryKeyValidationResult) {
            $this->_getSession()->addError(
                Mage::helper('lectures')->__("The lecture could not be saved because either the Audio Brightcove Id or Video Brightcove Id must be filled in.")
            );
            $isDataValid = false;
        }
    }

    public function hasLecturesFormBeenSubmitted($formData)
    {
        foreach($this->_listAllAdminFormFields as $fieldName) {
            if(isset($formData[$fieldName])) {
                if($formData[$fieldName]) {
                    return true;
                }
            }
        }

        return false;
    }

    public function convertProfessorToSavableFormat(&$lecturesData)
    {
        if($lecturesData['professor']) {
            $lecturesData['professor'] = implode(',',$lecturesData['professor']);
        }
    }

    public function validateOriginalLectureNumberAndDefaultCourseNumber($lecturesData, &$isDataValid)
    {
        $connection = $this->_connection;
        $originalProductId = false; //this is product id that corresponds to original course number.

        if(!$lecturesData['default_course_number'] && $lecturesData['original_lecture_number']) {
            $this->_getSession()->addError(
                Mage::helper('lectures')->__('If a value is entered for "Original Lecture Number" then "Original Course Number" must be filled in as well.')
            );
            $isDataValid = false;
        } elseif($lecturesData['default_course_number']) {
            $query = 'SELECT attribute_id FROM eav_attribute WHERE attribute_code = "' . self::ATTRIBUTE_CODE_COURSE_ID . '"';
            $result = $connection->fetchCol($query);
            $courseIdAttributeId = !empty($result) ? $result[0] : '';

            if($courseIdAttributeId) {
                $courseIdToProductId = $connection->fetchPairs("SELECT v.value, p.entity_id
                  FROM catalog_product_entity p LEFT JOIN catalog_product_entity_varchar v ON p.entity_id = v.entity_id AND v.attribute_id = " . $courseIdAttributeId .  "
                  WHERE p.type_id = 'configurable'");

                if(isset($courseIdToProductId[$lecturesData['default_course_number']])) {
                    $originalProductId = $courseIdToProductId[$lecturesData['default_course_number']];
                }
            }
        }

        if($lecturesData['default_course_number'] && $lecturesData['original_lecture_number'] && !$originalProductId) {
            $this->_getSession()->addError(
                Mage::helper('lectures')->__('The lecture could not be saved because an invalid "Original Course Number" was entered.')
            );
            $isDataValid = false;
        }

        if($lecturesData['default_course_number'] && $lecturesData['original_lecture_number'] && $originalProductId) {
            $sqlLecturesInCourse = $connection->select()
                ->from('lectures', array('lecture_number'))
                ->where('product_id = :product_id');

            $lecturesInCourse = $connection->fetchCol($sqlLecturesInCourse, array('product_id' => $originalProductId));
            if(!in_array($lecturesData['original_lecture_number'], $lecturesInCourse, true)) {
                $this->_getSession()->addError(
                    Mage::helper('lectures')->__('The lecture could not be saved because the "Original Lecture Number" is not valid. The "Original Course Number" does not contain a lecture with that number. ')
                );
                $isDataValid = false;
            }
        }
    }

    public function unsetBlankValues(Mage_Core_Model_Abstract &$modelObject)
    {
        $data = $modelObject->getData();

        if(is_array($data)) {
            foreach($data as $key => $value) {
                if($data[$key] == '') {
                    $modelObject->setData($key, null);
                }
            }
        }
    }

    public function stripHtmlTags(&$row)
    {
        foreach($this->_fieldsStripHtml as $fieldName) {
            if($row[$fieldName]) {
                $row[$fieldName] = strip_tags($row[$fieldName]);
            }
        }
    }

    protected function _getSession()
    {
        return Mage::getSingleton('adminhtml/session');
    }
}