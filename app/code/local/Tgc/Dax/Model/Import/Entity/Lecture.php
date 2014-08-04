<?php
/**
 * Dax lectures entity for importexport
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Dax
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_Dax_Model_Import_Entity_Lecture extends Tgc_Dax_Model_Import_Entity_Checksum_Base
{
    const COL_PRODUCT_ID                            = 'course_id'; //user inputs course_id, it is converted to a product id.
    const COL_PRODUCT_ID_MAPTO                      = 'product_id';
    const COL_AKAMAI_DOWNLOAD_ID                    = 'downloadfname';
    const COL_AKAMAI_DOWNLOAD_ID_MAPTO              = 'akamai_download_id';
    const COL_VIDEO_BRIGHTCOVE_ID                   = 'videostreamid';
    const COL_VIDEO_BRIGHTCOVE_ID_MAPTO             = 'video_brightcove_id';
    const COL_AUDIO_BRIGHTCOVE_ID                   = 'audiostreamid';
    const COL_AUDIO_BRIGHTCOVE_ID_MAPTO             = 'audio_brightcove_id';
    const COL_LECTURE_NUMBER                        = 'lecture_number';
    const COL_LECTURE_NUMBER_MAPTO                  = 'lecture_number';
    const COL_TITLE                                 = 'title';
    const COL_TITLE_MAPTO                           = 'title';
    const COL_DESCRIPTION                           = 'description';
    const COL_DESCRIPTION_MAPTO                     = 'description';
    const COL_DEFAULT_COURSE_NUMBER                 = 'default_course_number';
    const COL_DEFAULT_COURSE_NUMBER_MAPTO           = 'default_course_number';
    const COL_AUDIO_DURATION                        = 'audioduration';
    const COL_AUDIO_DURATION_MAPTO                  = 'audio_duration';
    const COL_VIDEO_DURATION                        = 'videoduration';
    const COL_VIDEO_DURATION_MAPTO                  = 'video_duration';
    const COL_AUDIO_AVAILABLE                       = 'audioAvailable';
    const COL_AUDIO_AVAILABLE_MAPTO                 = 'audio_available';
    const COL_VIDEO_AVAILABLE                       = 'videoavailable';
    const COL_VIDEO_AVAILABLE_MAPTO                 = 'video_available';
    const COL_AUDIO_DOWNLOAD_FILESIZE               = 'audiodownloadFsize';
    const COL_AUDIO_DOWNLOAD_FILESIZE_MAPTO         = 'audio_download_filesize';
    const COL_VIDEO_DOWNLOAD_FILESIZE_PC            = 'videodownloadFSizePC';
    const COL_VIDEO_DOWNLOAD_FILESIZE_PC_MAPTO      = 'video_download_filesize_pc';
    const COL_VIDEO_DOWNLOAD_FILESIZE_MAC           = 'videodownloadFSizeMac';
    const COL_VIDEO_DOWNLOAD_FILESIZE_MAC_MAPTO     = 'video_download_filesize_mac';


    private $_entityTable;

    protected $_relaxValidation = array(
      'primary_keys'    => true,
    );

    protected $_listSpreadsheetFields = array(
        self::COL_PRODUCT_ID,
        self::COL_AKAMAI_DOWNLOAD_ID,
        self::COL_VIDEO_BRIGHTCOVE_ID,
        self::COL_AUDIO_BRIGHTCOVE_ID,
        self::COL_LECTURE_NUMBER,
        self::COL_TITLE,
        self::COL_DESCRIPTION,
        self::COL_DEFAULT_COURSE_NUMBER,
        self::COL_AUDIO_DURATION,
        self::COL_VIDEO_DURATION,
        self::COL_AUDIO_AVAILABLE,
        self::COL_VIDEO_AVAILABLE,
        self::COL_AUDIO_DOWNLOAD_FILESIZE,
        self::COL_VIDEO_DOWNLOAD_FILESIZE_PC,
        self::COL_VIDEO_DOWNLOAD_FILESIZE_MAC,
    );

    protected $_fieldsRequired = array(
        self::COL_PRODUCT_ID,
        self::COL_LECTURE_NUMBER,
        self::COL_TITLE,
    );

    protected $_fieldIsInteger = array(
        self::COL_PRODUCT_ID,
        self::COL_LECTURE_NUMBER,
        self::COL_AUDIO_DURATION,
        self::COL_VIDEO_DURATION,
    );

    protected $_fieldIsFloat = array(
        self::COL_AUDIO_DOWNLOAD_FILESIZE,
        self::COL_VIDEO_DOWNLOAD_FILESIZE_PC,
        self::COL_VIDEO_DOWNLOAD_FILESIZE_MAC,
    );

    protected $_fieldIsBoolean = array(
        self::COL_AUDIO_AVAILABLE,
        self::COL_VIDEO_AVAILABLE,
    );

    protected $_fieldZeroNotAllowed = array(
        self::COL_PRODUCT_ID,
        self::COL_LECTURE_NUMBER,
        self::COL_AUDIO_DURATION,
        self::COL_VIDEO_DURATION,
        self::COL_AUDIO_DOWNLOAD_FILESIZE,
        self::COL_VIDEO_DOWNLOAD_FILESIZE_PC,
        self::COL_VIDEO_DOWNLOAD_FILESIZE_MAC,
    );

    protected $_acceptableBooleanValues = array(
      1,
      0,
      "1",
      "0"
    );

    protected $_idFieldsList = array(
        self::COL_AKAMAI_DOWNLOAD_ID,
        self::COL_VIDEO_BRIGHTCOVE_ID,
        self::COL_AUDIO_BRIGHTCOVE_ID,
    );

    protected $_primaryKeyFieldsList = array(
        self::COL_VIDEO_BRIGHTCOVE_ID,
        self::COL_AUDIO_BRIGHTCOVE_ID,
    );

    protected $_spreadsheetFieldMapToDB = array(
        self::COL_AKAMAI_DOWNLOAD_ID            => self::COL_AKAMAI_DOWNLOAD_ID_MAPTO,
        self::COL_VIDEO_BRIGHTCOVE_ID           => self::COL_VIDEO_BRIGHTCOVE_ID_MAPTO,
        self::COL_AUDIO_BRIGHTCOVE_ID           => self::COL_AUDIO_BRIGHTCOVE_ID_MAPTO,
        self::COL_PRODUCT_ID                    => self::COL_PRODUCT_ID_MAPTO,
        self::COL_LECTURE_NUMBER                => self::COL_LECTURE_NUMBER_MAPTO,
        self::COL_TITLE                         => self::COL_TITLE_MAPTO,
    );

    const ATTRIBUTE_CODE_COURSE_ID = 'course_id'; //used in creating query that pull a list of all course ids.

    const INVALID_PRODUCT_ID  = 'invalidproductid';

    const INVALID_DEFAULT_COURSE_NUMBER = 'invaliddefaultcoursenumber';

    const NO_PRIMARY_KEY = 'noprimarykey';

    protected $_messageTemplates = array(
        self::INVALID_PRODUCT_ID                =>  "Row cannot be imported because the course id does not exist on this site, ",
        self::INVALID_DEFAULT_COURSE_NUMBER     => "Row cannot be imported because the default course number does not exist on this site, ",
        self::NO_PRIMARY_KEY                    => "Row cannot be imported because audio_brightcove_id or video_brightcove id must be filled in, ",
    );

    protected $listLectureNumbersByProduct;

    protected $listLectureIdsProcessed;

    protected $skuToProductid;

    protected $listAllCourseIds;

    protected $lectureIdsToProductId;

    protected $lectureIdToLecturenumber;

    protected $listProductsWithUpdatedLectureNumbers = array();

    protected $courseIdToProductId;

    protected $lectureIdsOnSpreadsheet;

    protected $rowsWithLecturenumberAlreadyExists;

    protected $existingIdFieldValuesDB;

    protected $existingIdFieldValuesSpreadsheet;

    /**
     * Constructor.
     *
     * @return void
     */
    public function __construct()
    {
        $this->_dataSourceModel = Mage_ImportExport_Model_Import::getDataSourceModel();
        $this->_dataSourceModel->setEntityTypeCode($this->getEntityTypeCode());
        $this->_connection      = $connection = Mage::getSingleton('core/resource')->getConnection('write');
        $this->_entityTable     = Mage::getResourceModel('lectures/lectures')->getMainTable();

        $this->skuToProductid = $this->_connection->fetchPairs("SELECT sku, entity_id FROM catalog_product_entity");

        $this->listLectureNumbersByProduct = $this->generateListLectureNumbers();

        $this->generateListsOfIdValues();

        //Retrieving a list of existing course ids: Step 1 = retrieve attribute id for course_id, Step 2 = generate list course ids.
        $query = 'SELECT attribute_id FROM eav_attribute WHERE attribute_code = "' . self::ATTRIBUTE_CODE_COURSE_ID . '"';
        $result = $connection->fetchCol($query);
        $courseIdAttributeId = !empty($result) ? $result[0] : '';

        $selectCourseIds = $connection->select()
            ->from('catalog_product_entity_varchar', array('entity_id','value'))
            ->where('attribute_id = :attribute_id');

        $this->listAllCourseIds = $connection->fetchPairs($selectCourseIds, array('attribute_id' => $courseIdAttributeId));

        $this->courseIdToProductId = $connection->fetchPairs("SELECT v.value, p.entity_id
              FROM catalog_product_entity p LEFT JOIN catalog_product_entity_varchar v ON p.entity_id = v.entity_id AND v.attribute_id = " . $courseIdAttributeId .  "
              WHERE p.type_id = 'configurable'");

        $this->_permanentAttributes = $this->_listSpreadsheetFields;
    }

    public function generateListsOfIdValues()
    {
        foreach($this->_idFieldsList as $idFieldName) {
            $idFieldDbName = $this->_spreadsheetFieldMapToDB[$idFieldName];
            $selectExistingIdFieldValuesDB = $this->_connection->select()
                ->from('lectures', array($idFieldDbName,'id'))
                ->where($idFieldDbName . ' != :empty_value');
            $this->existingIdFieldValuesDB[$idFieldName] = $this->_connection->fetchPairs($selectExistingIdFieldValuesDB, array('empty_value' => ''));
            $this->listLectureIdsProcessed[$idFieldName] = array(); //setting to blank, this way loops will not fail if nothing is in the array.
            $this->_messageTemplates['DUPLICATE_INDB_' .  $idFieldName] = "Row cannot be imported because a lecture already exists with the same " . $idFieldName . ", ";
            $this->_messageTemplates['DUPLICATE_INSPREADSHEET_' .  $idFieldName] = "Row cannot be imported because the import csv contains another row with the same " . $idFieldName . ", ";
        }
    }

    public function getEntityTypeCode()
    {
        return 'lecture';
    }

    public function getListOfSpreadsheetFields()
    {
        return $this->_listSpreadsheetFields;
    }

    public function getFieldsRequired()
    {
        return $this->_fieldsRequired;
    }

    public function getFieldIsInteger()
    {
        return $this->_fieldIsInteger;
    }

    public function getFieldIsFloat()
    {
        return $this->_fieldIsFloat;
    }

    public function getFieldIsBoolean()
    {
        return $this->_fieldIsBoolean;
    }

    public function getFieldsWhereZeroNotAllowed()
    {
        return $this->_fieldZeroNotAllowed;
    }

    public function validateRow(array $rowData, $rowNum)
    {
        try {
            $this->_map($rowData, $rowNum);
            return true;
        } catch (InvalidArgumentException $e) {
            $this->addRowError($e->getMessage(), $rowNum);
            return false;
        }
    }

    /**
     * Is all of data valid?
     *
     * @return bool
     */
    public function isDataValid()
    {
        $isDataValid = parent::isDataValid();

        $this->processNotices(); //this adds notices for lectures, if any exist.

        return $isDataValid;
    }

    public function processNotices() {
        if(count($this->rowsWithLecturenumberAlreadyExists) > 0) {
            $stringListExistingLectureNumbers = implode(',', $this->rowsWithLecturenumberAlreadyExists);
            $this->_notices[] = "The following records contain lecture numbers that already exist: " . $stringListExistingLectureNumbers . "<br />";
            $this->_notices[] = "(Please note: if an import is performed, lectures belonging to products containing duplicates will be incremented)<br />";
        }
    }

    protected function _importData()
    {
        $this->lectureIdsOnSpreadsheet = false; //if line not included, scheduled cron thinks every record is a duplicate, because this variable gets populated by validateRow function.
        if (Mage_ImportExport_Model_Import::BEHAVIOR_DELETE == $this->getBehavior()) {
            return $this->_deleteLectures();
        } else if (Mage_ImportExport_Model_Import::BEHAVIOR_REPLACE == $this->getBehavior()) {
            $this->_updateLectures();
        } else {
            $this->_saveLectures();
        }

        $this->incrementDuplicateLectureNumbers();

        return true;
    }

    private function _updateLectures()
    {
        while ($bunch = $this->_dataSourceModel->getNextBunch()) {
            foreach ($bunch as $rowNum => $rowData) {
                try {
                    $this->_connection->insertOnDuplicate($this->_entityTable, $this->_map($rowData, $rowNum));
                } catch (InvalidArgumentException $e) {
                    $this->addRowError($e->getMessage(), $rowNum);
                }
            }
        }
    }

    /**
     * Save new ad codes
     */
    private function _saveLectures()
    {
        while ($bunch = $this->_dataSourceModel->getNextBunch()) {
            $data = array();
            foreach ($bunch as $rowNum => $rowData) {
                $data[] = $this->_map($rowData, $rowNum);
            }

            try {
                $this->_connection->insertMultiple($this->_entityTable, $data);
            } catch (Exception $e) {
                Mage::logException($e);
            }
        }
    }

    /**
     * Delete ad codes
     */
    private function _deleteLectures()
    {
        try {
            while ($bunch = $this->_dataSourceModel->getNextBunch()) {
                $codesToDelete = array();

                foreach ($bunch as $rowData) {
                    $codesToDelete[] = $this->retrieveLectureId($rowData);
                }
                if ($codesToDelete) {
                    $this->_connection->delete(
                        $this->_entityTable,
                        $this->_connection->quoteInto('id IN (?)', $codesToDelete)
                    );
                }
            }
        } catch (Exception $e) {
            Mage::logException($e);
        }
    }
    
    protected function _map(array $row, $rowNum)
    {
        //this eliminates php notice that occurs in strict mode, that occurs when the processor tries to retrieve an element in the array that does not exist.
        $this->eliminateUndefinedIndexError($row);

        $this->registerProcessedLectureIds($row, $rowNum);

        $this->determineExceptions($row, $rowNum);

        return array(
            self::COL_PRODUCT_ID_MAPTO                      => $row[self::COL_PRODUCT_ID],
            self::COL_AKAMAI_DOWNLOAD_ID_MAPTO              => $row[self::COL_AKAMAI_DOWNLOAD_ID],
            self::COL_VIDEO_BRIGHTCOVE_ID_MAPTO             => $row[self::COL_VIDEO_BRIGHTCOVE_ID],
            self::COL_AUDIO_BRIGHTCOVE_ID_MAPTO             => $row[self::COL_AUDIO_BRIGHTCOVE_ID],
            self::COL_LECTURE_NUMBER                        => $row[self::COL_LECTURE_NUMBER],
            self::COL_TITLE                                 => $row[self::COL_TITLE],
            self::COL_DESCRIPTION                           => $row[self::COL_DESCRIPTION],
            self::COL_DEFAULT_COURSE_NUMBER_MAPTO           => $row[self::COL_DEFAULT_COURSE_NUMBER],
            self::COL_AUDIO_DURATION_MAPTO                  => $row[self::COL_AUDIO_DURATION],
            self::COL_VIDEO_DURATION_MAPTO                  => $row[self::COL_VIDEO_DURATION],
            self::COL_AUDIO_AVAILABLE_MAPTO                 => $row[self::COL_AUDIO_AVAILABLE],
            self::COL_VIDEO_AVAILABLE_MAPTO                 => $row[self::COL_VIDEO_AVAILABLE],
            self::COL_AUDIO_DOWNLOAD_FILESIZE_MAPTO         => $row[self::COL_AUDIO_DOWNLOAD_FILESIZE],
            self::COL_VIDEO_DOWNLOAD_FILESIZE_PC_MAPTO      => $row[self::COL_VIDEO_DOWNLOAD_FILESIZE_PC],
            self::COL_VIDEO_DOWNLOAD_FILESIZE_MAC_MAPTO     => $row[self::COL_VIDEO_DOWNLOAD_FILESIZE_MAC],
        );
    }

    public function registerProcessedLectureIds($row, $rowNum)
    {
        if($row[self::COL_AKAMAI_DOWNLOAD_ID]) {
            $this->listLectureIdsProcessed[self::COL_AKAMAI_DOWNLOAD_ID][$rowNum]  = $row[self::COL_AKAMAI_DOWNLOAD_ID];
        }

        if($row[self::COL_VIDEO_BRIGHTCOVE_ID]) {
            $this->listLectureIdsProcessed[self::COL_VIDEO_BRIGHTCOVE_ID][$rowNum] = $row[self::COL_VIDEO_BRIGHTCOVE_ID];
        }

        if($row[self::COL_AUDIO_BRIGHTCOVE_ID]) {
            $this->listLectureIdsProcessed[self::COL_AUDIO_BRIGHTCOVE_ID][$rowNum] = $row[self::COL_AUDIO_BRIGHTCOVE_ID];
        }
    }

    public function determineExceptions(&$row, $rowNum)
    {

        //Throws an exception error if a required field is blank.
        foreach($this->getFieldsRequired() as $requiredField) {
            if(!$row[$requiredField]) {
                $requiredCode = 'required' . $requiredField;
                $this->_messageTemplates[$requiredCode] = 'Row cannot be imported because the field "' . $requiredField . '" is required, but is missing, ';
                throw new InvalidArgumentException($requiredCode);
                return $this;
            }
        }

        $this->validateRequiredIds($row);

        //Throws an exception error, if no product in the store has a value equal to parent_sku.
        if(!isset($this->courseIdToProductId[$row[self::COL_PRODUCT_ID]])) {
            throw new InvalidArgumentException(self::INVALID_PRODUCT_ID);
            return $this;
        }

        $row[self::COL_PRODUCT_ID] = $this->courseIdToProductId[$row[self::COL_PRODUCT_ID]]; //converts the course_id to product_id.

        $this->checkoutForDuplicateKeyValueInSpreadsheet($row, $rowNum);

        $this->checkForDuplicateKeyValueInDB($row);

        $this->prepareTitleForSave($row);

        $this->validateFloatFields($row);

        $this->validateIntegerFields($row);

        $this->validateBooleanFields($row);

        $this->convertBooleanFieldsToAcceptableValues($row); //changes true to 1, and false to 0. It must be in that format for database to accept it.

        $this->validateFieldsZeroNoteAllowed($row);

        //if the lecture being associated with a product, that already has lectures attached to it, thwen we have to make sure lecture numbers do not repeat.
        if(isset($this->listLectureNumbersByProduct[$row[self::COL_PRODUCT_ID]])) {
            $currentLecturesInProduct = $this->listLectureNumbersByProduct[$row[self::COL_PRODUCT_ID]];

            if(count($currentLecturesInProduct) > 0) { //if a lecture belonging to current product has been imported, check for duplciate lecture numbers.
                $lectureIdPrimaryKey = $this->retrieveLectureId($row);
                if($lectureIdPrimaryKey) { //runs if product already exists.
                    if(isset($currentLecturesInProduct[$lectureIdPrimaryKey])) {
                        unset($currentLecturesInProduct[$lectureIdPrimaryKey]); //prevents a product from matching with itself during lecture validation.
                    }
                }

                if(in_array($row[self::COL_LECTURE_NUMBER], $currentLecturesInProduct)) {
                    $this->rowsWithLecturenumberAlreadyExists[] = $rowNum;
                }
            }
        }

        if($row[self::COL_DEFAULT_COURSE_NUMBER] && !in_array($row[self::COL_DEFAULT_COURSE_NUMBER], $this->listAllCourseIds)) {
            throw new InvalidArgumentException(self::INVALID_DEFAULT_COURSE_NUMBER);
            return $this;
        }

    }

    public function validateFieldsZeroNoteAllowed(&$row)
    {
        foreach($this->getFieldsWhereZeroNotAllowed() as $fieldName) {
            if($row[$fieldName] == 0) {
                $row[$fieldName] = null;
            }
        }
    }

    public function prepareTitleForSave(&$row)
    {
        if($row[self::COL_TITLE]) {
            $row[self::COL_TITLE] = strip_tags($row[self::COL_TITLE]);
        }
    }

    public function validateBooleanFields($row)
    {
        foreach($this->getFieldIsBoolean() as $booleanFieldName) {
            if(!in_array($row[$booleanFieldName], $this->_acceptableBooleanValues, true)) {
                $errorCode = 'boolean' . $booleanFieldName;
                $this->_messageTemplates[$errorCode] = 'Row cannot be imported because the field "' . $booleanFieldName . '" was not a boolean value. It must be "1" or "0", ';
                throw new InvalidArgumentException($errorCode);
            }
        }
    }

    public function convertBooleanFieldsToAcceptableValues(&$row)
    {
        foreach($this->getFieldIsBoolean() as $booleanFieldName) {
            if($row[$booleanFieldName] == 'true' || $row[$booleanFieldName] == 'TRUE') {
                $row[$booleanFieldName] = 1;
            } elseif($row[$booleanFieldName] == 'false' || $row[$booleanFieldName] == 'FALSE') {
                $row[$booleanFieldName] = 0;
            }
        }
    }

    public function validateIntegerFields($row)
    {
        //Throws an exception error if a field, that is supposed to be a number, is not a number.
        foreach($this->getFieldIsInteger() as $numberField) {
            if($row[$numberField]) {
                if(!is_numeric($row[$numberField]) || floor($row[$numberField]) != $row[$numberField]) {
                    $numberCode = 'numberinteger' . $numberField;
                    $this->_messageTemplates[$numberCode] = 'Row cannot be imported because the field "' . $numberField . '" was not an integer, ';
                    throw new InvalidArgumentException($numberCode);
                    return $this;
                }
            }
        }
    }
    
    public function validateFloatFields($row)
    {
        //Throws an exception error if a field, that is supposed to be a number, is not a number.
        foreach($this->getFieldIsFloat() as $numberField) {
            if($row[$numberField]) {
                if(!is_numeric($row[$numberField])) {
                    $numberCode = 'numberfloat' . $numberField;
                    $this->_messageTemplates[$numberCode] = 'Row cannot be imported because the field "' . $numberField . '" was not a number, ';
                    throw new InvalidArgumentException($numberCode);
                    return $this;
                }
            }
        }      
    }

    public function validateRequiredIds($row, $validationType = 'primary_keys')
    {
        //Checks to ensure that either audio bright cove id or video bright cove id exists.
        if(!$this->_relaxValidation[$validationType]) {
            $requiredIdFieldValidationPassed = false;
            foreach($this->_primaryKeyFieldsList as $requiredIdFieldName) {
                if($row[$requiredIdFieldName]) {
                    $requiredIdFieldValidationPassed = true;
                }
            }

            if(!$requiredIdFieldValidationPassed) {
                throw new InvalidArgumentException(self::NO_PRIMARY_KEY);
            }
        }
    }

    public function checkForDuplicateKeyValueInDB($row)
    {
        //if the behavior is REPLACE, that means any record on spreadsheet will update a record in DB.
        if(Mage_ImportExport_Model_Import::BEHAVIOR_APPEND == $this->getBehavior()) {
            foreach($this->_idFieldsList as $idField) {
                if(array_key_exists($row[$idField], $this->existingIdFieldValuesDB[$idField])) {
                    throw new InvalidArgumentException('DUPLICATE_INDB_' .  $idField);
                    return $this;
                }
            }
        }
    }

    public function checkoutForDuplicateKeyValueInSpreadsheet($row, $rowNum)
    {
        foreach($this->_idFieldsList as $idField) {
            $arrayOfIdValues = $this->listLectureIdsProcessed[$idField];
            unset($arrayOfIdValues[$rowNum]); //this prevents a record from matching with itself.

            if(in_array($row[$idField], $arrayOfIdValues)) {
                throw new InvalidArgumentException('DUPLICATE_INSPREADSHEET_' .  $idField);
                return $this;
            }
        }
    }

    public function generateListLectureNumbers()
    {
        $connection = $this->_connection;
        $sql = $connection->select()
            ->from('lectures', array('product_id','id','lecture_number'));

        $stmt = $connection->query($sql);
        $data = array();
        while ($row = $stmt->fetch(Zend_Db::FETCH_NUM)) {
            $data[$row[0]][$row[1]] = $row[2];
        }

        foreach($data as $key => $value) {
            asort($data[$key]);
        }

        return $data;
    }

    public function incrementDuplicateLectureNumbers()
    {
        //Data will be used later to increment lecture number if there are duplicates.
        $this->generateListsOfIdValues(); //values have been inserted into the database, we need to look at those recently inserted values,  this refreshes list of lectures in DB.
        $this->listLectureNumbersByProduct = $this->generateListLectureNumbers(); //this needs to be regenerated, because new lectures have been saved.
        $this->lectureIdsToProductId = $this->_connection->fetchPairs("SELECT id, product_id FROM lectures");
        $this->lectureIdToLecturenumber = $this->_connection->fetchPairs("SELECT id, lecture_number FROM lectures");

        $lectureIdsByProduct = $this->getImportedLectureIdsByProduct();

        if($lectureIdsByProduct) {
            foreach($lectureIdsByProduct as $productId => $lecturesInProduct) {
                asort($lecturesInProduct);
                foreach($lecturesInProduct as $lecturePrimaryIdKey => $lectureNumber)
                {
                    if(!in_array($productId, $this->listProductsWithUpdatedLectureNumbers)) {
                        //this if statement does not run if another lecture, existing within this product, is already incremented.
                        //one one lecture within a product is incremented, all of them are incremented, so this does not need to be run on other lectures inside of same product.
                        $lecturesInCurrentProduct = $this->listLectureNumbersByProduct[$productId];

                        $matchesAll = array_keys($lecturesInCurrentProduct, $lectureNumber);
                        $numberMatches = count($matchesAll);
                        if($numberMatches >=2) {
                            //statement will not run if only lecture number found, belongs to the product that is being searched for, in other words this eliminates duplicates.
                           $this->incrementLectures($lecturePrimaryIdKey, $lecturesInCurrentProduct);
                        }
                    }
                }
            }
        }
    }

    public function incrementLectures($lectureId, $lecturesInCurrentProduct)
    {
        $lecturesNeedIncrementing = array();
        $lectureNumberOfDuplicate = $updatedLectureNumber = $this->lectureIdToLecturenumber[$lectureId];
        $productId = $this->lectureIdsToProductId[$lectureId];

        foreach($lecturesInCurrentProduct as $lectureInCurrentProductId => $lectureInCurrentProductNumber) {
            if($lectureInCurrentProductNumber >= $lectureNumberOfDuplicate  && $lectureId != $lectureInCurrentProductId) {
                $lectureNumber = $this->lectureIdToLecturenumber[$lectureInCurrentProductId];
                $lecturesNeedIncrementing[$lectureInCurrentProductId] = $lectureNumber;
            }
        }

        if(count($lecturesNeedIncrementing) > 0) {
            foreach($lecturesNeedIncrementing as $lectureToIncrementId => $lectureToIncrementNumber) {
                $updatedLectureNumber++;
                $query = "UPDATE lectures SET lecture_number = '" . $updatedLectureNumber . "' WHERE id = " . $lectureToIncrementId .  "";
                $this->_connection->raw_query($query);
            }

            $this->regenerateListLectureNumbersOneproductOnly($this->lectureIdsToProductId[$lectureId]);
            $this->listProductsWithUpdatedLectureNumbers[] = $productId;
        }
    }

    public function getImportedLectureIdsByProduct()
    {
        $lectureIdsByProduct = array();
        while ($bunch = $this->_dataSourceModel->getNextBunch()) {
            foreach ($bunch as $rowNum => $rowData) {
                $lecturePrimaryIdKey = $this->retrieveLectureId($rowData); //$lecturePrimaryIdKey value is the field called 'id' in lectures table.
                if($lecturePrimaryIdKey) {
                    $productId = $this->lectureIdsToProductId[$lecturePrimaryIdKey];
                    $lectureIdsByProduct[$productId][$lecturePrimaryIdKey] = $this->lectureIdToLecturenumber[$lecturePrimaryIdKey];
                }
            }
        }

        return $lectureIdsByProduct;
    }

    public function getAdjustedLectureNumber($lecturePrimaryKey)
    {
        /* A lecture number on an import spreadsheet can change, because if a duplicate exists.  Those numbers will be automatically incremented.
        *  A lecture number can potentially be incremented more than once.  Therefore, we have kept track of how many times it has changed.
         * This calculates the real lecture number based on how many times it has been incremented AND what it is on the spreadsheet. Both are taken into account.
        */
        $lectureNumberFinal = $lectureNumber = $this->lectureIdToLecturenumber[$lecturePrimaryKey];
        if(isset($this->listUpdatedLectureNumbers['old'][$lecturePrimaryKey]['originalvalue'])) {
            $oldLectureNumberAfterIncrements = $this->listUpdatedLectureNumbers['old'][$lecturePrimaryKey]['originalvalue'] + $this->listUpdatedLectureNumbers['old'][$lecturePrimaryKey]['numbertimesincremented'];
            $currentLectureNumberInDb = '';
            $adjustments = $oldLectureNumberAfterIncrements - $lectureNumber;
            $lectureNumberFinal = $lectureNumber + $adjustments;
        }

        return $lectureNumberFinal;
    }

    public function regenerateListLectureNumbersOneproductOnly($productId)
    {
        $connection = $this->_connection;
        $sql = $connection->select()
            ->from('lectures', array('product_id','id','lecture_number'))
            ->where('product_id = :product_id');

        $stmt = $connection->query($sql, array('product_id' => $productId));
        $data = array();
        while ($row = $stmt->fetch(Zend_Db::FETCH_NUM)) {
            $data[$row[1]] = $row[2];
        }

        asort($data);
        $this->listLectureNumbersByProduct[$productId] = $data;
    }

    public function retrieveLectureId($row)
    {
        foreach($this->_primaryKeyFieldsList as $primaryKeyFieldName) {
            if(isset($row[$primaryKeyFieldName])) {
                if($pkValue = $row[$primaryKeyFieldName]) {
                    if(isset($this->existingIdFieldValuesDB[$primaryKeyFieldName][$pkValue])) {
                        if($id = $this->existingIdFieldValuesDB[$primaryKeyFieldName][$pkValue]) {
                            return $id; //this is the field in the database called 'id'
                        }
                    }
                }
            }
        }

        return false;
    }

    public function validateData()
    {
        if (!$this->_dataValidated) {
            // does all permanent columns exists?
            if (($colsAbsent = array_diff($this->_permanentAttributes, $this->_getSource()->getColNames()))) {
                Mage::throwException(
                    Mage::helper('importexport')->__('Can not find required columns: %s', implode(', ', $colsAbsent))
                );
            }

            // initialize validation related attributes
            $this->_errors = array();
            $this->_invalidRows = array();

            // check attribute columns names validity
            $invalidColumns = array();

            foreach ($this->_getSource()->getColNames() as $colName) {
                if (!preg_match('/^[a-z][a-zA-Z0-9_]*$/', $colName) && !$this->isAttributeParticular($colName)) {
                    $invalidColumns[] = $colName;
                }
            }
            if ($invalidColumns) {
                Mage::throwException(
                    Mage::helper('importexport')->__('Column names: "%s" are invalid', implode('", "', $invalidColumns))
                );
            }
            $this->_saveValidatedBunches();

            $this->_dataValidated = true;
        }
        return $this;
    }

    public function eliminateUndefinedIndexError(&$row)
    {
        foreach($this->getListOfSpreadsheetFields() as $spreadsheetFields) {
            if(!isset($row[$spreadsheetFields])) {
                $row[$spreadsheetFields] = null;
            }
        }
    }

    protected function _helper()
    {
        return Mage::helper('tgc_dax');
    }
}