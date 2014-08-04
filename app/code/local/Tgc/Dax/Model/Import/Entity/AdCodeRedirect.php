<?php
/**
 * Dax adcode entity for importexport
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Dax
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Dax_Model_Import_Entity_AdCodeRedirect extends Tgc_Dax_Model_Import_Entity_Checksum_Base
{
    const COL_SEARCH_EXPRESSION         = 'search_expression';
    const COL_AD_TYPE                   = 'ad_type';
    const COL_DAX_KEY                   = 'dax_key';
    const COL_START_DATE                = 'start_date';
    const COL_END_DATE                  = 'end_date';
    const COL_REDIRECT_QUERYSTRING      = 'redirect_querystring';
    const COL_DESCRIPTION               = 'description';
    const COL_WELCOME_SUBTITLE          = 'welcome_subtitle';
    const COL_MORE_DETAILS              = 'more_details';
    const BLANK_DATE                    = '0000-00-00';

    const EMAIL_TITLE = "An Ad Code Redirect Failed to Import from Dax";

    const ERROR_INVALID_WEBSITE = 1;

    /******************************************************************************************************************/
    /**************************************************SETTINGS********************************************************/
    const DISABLE_VALIDATION_VALID_AD_CODE = true;

    /******************************************************************************************************************/

    private $_entityTable;
    private $_storesCache = array();

    protected $_listSpreadsheetFields = array(
        self::COL_SEARCH_EXPRESSION,
        self::COL_AD_TYPE,
        self::COL_DAX_KEY,
        self::COL_START_DATE,
        self::COL_END_DATE,
        self::COL_REDIRECT_QUERYSTRING,
        self::COL_DESCRIPTION,
        self::COL_WELCOME_SUBTITLE ,
        self::COL_MORE_DETAILS,
    );

    protected $_spreadsheetFieldsTypeDate = array(
        self::COL_START_DATE,
        self::COL_END_DATE,
    );

    protected $_storeCodeMappings = array(
        'us' => 'default',
        'uk' => 'uk_en',
        'au' => 'au_en',
    );

    protected $_listAllParameters = array(
        'ai',
        'cid',
        'profid',
        'catid',
        'cmsid',
        'storeid',
        'pid',
    );

    protected $_parametersRequired = array(
        'ai',
    );

    protected $_parametersIsNumber = array(
        'ai',
        'cid',
        'profid',
        'catid',
        'cmsid',
    );

    protected $_fieldIsNumber = array(
        self::COL_DAX_KEY,
    );

    protected $_convertFieldnamesToParameterNames = array(
        'ad_code' => 'ai',
        'course_id' => 'cid',
        'professor_id' => 'profid',
        'category_id' => 'catid',
        'cms_page_id' => 'cmsid',
        'store_id'    => 'storeid',
    );

    protected $_adTypeOptions;

    public $listAllAdcoderedirects;

    public $listExistingAdcodes;

    protected $_entriesWithOverlappingDates;

    const EMPTY_SEARCH_EXPRESSION = 'emptysearchexpression';
    const INVALID_DOMAIN = 'invaliddomain';
    const ERROR_DUPLICATE_RECORD_DB = 'duplicaterecorddb';
    const ERROR_DUPLICATE_RECORD_SPREADSHEET = 'duplicaterecordspreadsheet';
    const INVALID_STORE_CODE = 'invalidstorecode';
    const EMPTY_REDIRECT_QUERYSTRING = 'emptyredirectquerystring';
    const INVALID_PAGE_ID_TOOMANY = 'invalidpageidtoomany';
    const INVALID_PAGE_ID_NONE = 'pageidnone';
    const INVALID_ADCODE = 'invalidadcode';
    const INVALID_ADCODE_TYPE = 'invalidadcodetype';
    const ADCODE_REQUIRED_FIELDS_FAIL = 'adcodequiredfieldsfail';
    const INVALID_DATE = 'invaliddatestandard';
    const INVALID_DATE_OVERLAP = 'invaliddateoverlap';
    const INVALID_CHARACTER_ENCODING = 'invalidcharacterencoding';

    protected $_messageTemplates = array(
        self::EMPTY_SEARCH_EXPRESSION           => 'Row cannot be imported because search_expression, a required field, was left blank',
        self::EMPTY_REDIRECT_QUERYSTRING        =>  'The record could not be imported because the "redirect querystring" was empty',
        self::INVALID_DOMAIN                    => 'Row cannot be imported because search_expression contains a domain name. Please only include the portion of the url after the domain name',
        self::INVALID_STORE_CODE                => 'Store code does not exist',
        self::ERROR_INVALID_WEBSITE             => 'Invalid website code for super attribute',
        self::ERROR_DUPLICATE_RECORD_DB         => 'Record cannot be imported because another record exists in the database with same request path and id',
        self::ERROR_DUPLICATE_RECORD_SPREADSHEET    => 'Record cannot be imported because another record exists on the spreadsheet with the same search_expression,ad code, and store_id',
        self::INVALID_PAGE_ID_TOOMANY           => 'Row cannot be imported because ONLY ONE of the following can be filled in: Course Id, Professor ID, Category ID, CMS Page Id',
        self::INVALID_PAGE_ID_NONE              => 'Row cannot be imported because ONE of the following MUST be filled in: Course Id, Professor ID, Category ID, CMS Page Id',
        self::INVALID_ADCODE                    => 'Row cannot be imported because the ad code does not exist in this store',
        self::INVALID_ADCODE_TYPE               => 'Row cannot be imported because an invalid ad code was entered.  Only valid ad codes are "None","Space Ad","Buffet Ad","Other"',
        self::INVALID_DATE                      => 'Date is invalid because start date must come before end date.',
        self::ADCODE_REQUIRED_FIELDS_FAIL       => 'Row cannot be imported because if ad type is equal to space code, then the fields more_details and welcome_subtitle must be filled in.',
        self::INVALID_DATE_OVERLAP              => 'Row cannot be imported because another row with the same search_expression and store_id has a date range that overlaps with this record.',
        self::INVALID_CHARACTER_ENCODING        => 'Row cannot be imported because the character encoding is invalid.  Encoding must be ASCII',
    );

    /**
     * Constructor.
     *
     * @return void
     */
    public function __construct()
    {
        $this->_dataSourceModel = Mage_ImportExport_Model_Import::getDataSourceModel();
        $this->_dataSourceModel->setEntityTypeCode($this->getEntityTypeCode());
        $this->_connection      = Mage::getSingleton('core/resource')->getConnection('write');
        $this->_entityTable     = Mage::getResourceModel('adcoderouter/redirects')->getMainTable();

        $this->listExistingAdcodes = $this->_connection->fetchCol('SELECT code FROM ad_code');

        $this->addMessageTemplate(self::ERROR_INVALID_WEBSITE, 'Invalid website code');

        $this->_adTypeOptions = Mage::getSingleton('adcoderouter/field_source_adtype')->getAllOptions();

        $this->setArrayRedirectsForDuplicateCheck();

        $this->_permanentAttributes = array(
            self::COL_SEARCH_EXPRESSION,
            self::COL_REDIRECT_QUERYSTRING,
        );
    }

    public function getSpreadSheetFieldsTypeDate()
    {
        return $this->_spreadsheetFieldsTypeDate;
    }

    public function getStoreCodeMappings()
    {
        return $this->_storeCodeMappings;
    }

    public function getParametersRequired()
    {
        return $this->_parametersRequired;
    }

    public function getListOfSpreadsheetFields()
    {
        return $this->_listSpreadsheetFields;
    }

    public function getParametersIsNumber()
    {
        return $this->_parametersIsNumber;
    }

    public function getEntityTypeCode()
    {
        return 'adcode_redirect';
    }

    public function getListAllParameters()
    {
        return $this->_listAllParameters;
    }

    public function getFieldIsNumber() {
        return $this->_fieldIsNumber;
    }

    public function validateRow(array $rowData, $rowNum)
    {
        try {
            $this->_map($rowData);
            return true;
        } catch (InvalidArgumentException $e) {
            $this->addRowError($e->getMessage(), $rowNum);

            return false;
        }
    }

    protected function _importData()
    {
        if (Mage_ImportExport_Model_Import::BEHAVIOR_DELETE == $this->getBehavior()) {
            return $this->_deleteAdCodeRedirects();
        } else if (Mage_ImportExport_Model_Import::BEHAVIOR_REPLACE == $this->getBehavior()) {
            $this->_updateAdCodeRedirects();
        } else {
            $this->_saveAdCodeRedirects();
        }

        $this->listAllAdcoderedirects = null;

        return true;
    }

    private function _updateAdCodeRedirects()
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
    private function _saveAdCodeRedirects()
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
    private function _deleteAdCodeRedirects()
    {
        try {
            while ($bunch = $this->_dataSourceModel->getNextBunch()) {
                $codesToDelete = array();

                foreach ($bunch as $rowData) {
                    $primaryKeyValue = $this->retrievePrimaryKeyValue($rowData);
                    if($primaryKeyValue) {
                        $codesToDelete[] = $primaryKeyValue;
                    }
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

    protected function _map(array $row)
    {
        $parameters = $this->parseTheRedirectQuerystring($row);

        foreach($this->getListOfSpreadsheetFields() as $spreadsheetFields) {
            if(!isset($row[$spreadsheetFields])) {
                $row[$spreadsheetFields] = null;
            }
        }

        $formattedStartDate = $this->processDate($row[self::COL_START_DATE]);
        $formattedEndDate = $this->processDate($row[self::COL_END_DATE]);

        if($parameters['storeid']) { //if this is not found here, since this field is required, determineExceptions will end up throwing an exception.
            $row['store_id'] = $this->_getStoreByCode($parameters['storeid']);
        }
        $this->determineExceptions($row, $parameters);

        $row[self::COL_AD_TYPE] = $this->convertAdCodeTypeToNameToId($row[self::COL_AD_TYPE]); //converting name to an id.

        return array(
            'search_expression'     => $row[self::COL_SEARCH_EXPRESSION],
             self::COL_AD_TYPE      => $row[self::COL_AD_TYPE],
            'start_date'            => $formattedStartDate,
            'end_date'              => $formattedEndDate,
            'redirect_querystring'  => $row[self::COL_REDIRECT_QUERYSTRING],
            'ad_code'               => $parameters['ai'],
            'course_id'             => $parameters['cid'],
            'professor_id'          => $parameters['profid'],
            'category_id'           => $parameters['catid'],
            'cms_page_id'           => $parameters['cmsid'],
            'pid'                   => $parameters['pid'],
            'store_id'              => $row['store_id'],
            'description'           => $row['description'],
            'welcome_subtitle'      => $row[self::COL_WELCOME_SUBTITLE],
            'more_details'          => $row[self::COL_MORE_DETAILS],
            'dax_key'               => $row[self::COL_DAX_KEY],
        );
    }

    public function parseTheRedirectQuerystring(&$row)
    {
        $redirectQuerystring = $row[self::COL_REDIRECT_QUERYSTRING];

        if(mb_detect_encoding($redirectQuerystring) != 'ASCII') {
            throw new InvalidArgumentException(self::INVALID_CHARACTER_ENCODING);
        }

        if(!$redirectQuerystring) {
            throw new InvalidArgumentException(self::EMPTY_REDIRECT_QUERYSTRING);
        }

        parse_str($redirectQuerystring, $parameters);

        foreach($parameters as $paramKey => $paramValue) {
            $parameterUrlEncoded = urlencode($paramValue);
            str_replace($parameters[$paramKey], $parameterUrlEncoded, $redirectQuerystring);
            $parameters[$paramKey] = $parameterUrlEncoded;
        }

        $row[self::COL_REDIRECT_QUERYSTRING] = $redirectQuerystring;

        if(!isset($parameters['store_id'])) {
            $parameters['storeid'] = 'us'; //us is the default value.
        }

        foreach($this->getListAllParameters() as $parameterListItem) {
            if(!isset($parameters[$parameterListItem])) {
                $parameters[$parameterListItem] = null; //setting any blank parameters to null.  This prevents undefined index error from being thrown.
            }
        }

        foreach($this->getParametersIsNumber() as $numberParameter) {
            if($parameters[$numberParameter]) {
                $parameters[$numberParameter] = is_numeric($parameters[$numberParameter])  ? (int) $parameters[$numberParameter] : 0; //cats each variable that should be an integer, as an integer, this allows us to validate it more easily later.
            }
        }

        $parameters['pid'] = $this->_helper()->formatPid($parameters['pid']);

        return $parameters;
    }

    public function determineExceptions($row, $parameters)
    {
        if(!$row['search_expression']) {
            throw new InvalidArgumentException(self::EMPTY_SEARCH_EXPRESSION);
            return $this;
        }

        //Makes date fields required.
        foreach($this->getSpreadSheetFieldsTypeDate() as $dateFieldname) {
            if(!$row[$dateFieldname] && $row[$dateFieldname] != self::BLANK_DATE) {
                throw new InvalidArgumentException(
                    "Row cannot be imported because the following field is missing: $dateFieldname."
                );
            }
        }

        $searchExpressionTestUrl = "_" . $row['search_expression'] . "_";
        if(strpos($searchExpressionTestUrl, "http:") || strpos($searchExpressionTestUrl, "https:") || strpos($searchExpressionTestUrl, ".com")) {
            throw new InvalidArgumentException(self::INVALID_DOMAIN);
            return $this;
        }

        foreach($this->getParametersRequired() as $requiredParam) {
            if(!$parameters[$requiredParam]) {
                $requiredCode = 'required' . $requiredParam;
                $this->_messageTemplates[$requiredCode] = 'Row cannot be imported because the parameter "' . $requiredParam . '" must exist in the querystring';
                throw new InvalidArgumentException($requiredCode);
                return $this;
            }
        }

        foreach($this->getFieldIsNumber() as $numberFieldName) {
            if($row[$numberFieldName]) {
                if(!Zend_Validate::is($row[$numberFieldName], 'Digits')) {
                    $fieldNumberCode = 'number' . $numberFieldName;
                    $this->_messageTemplates[$fieldNumberCode] = 'Row cannot be imported because the parameter "' . $numberFieldName . '" was not an integer';
                    throw new InvalidArgumentException($fieldNumberCode);
                    return $this;
                }
            }
        }

        foreach($this->getParametersIsNumber() as $numberParameter) {
            if($parameters[$numberParameter] === 0) {
                $numberCode = 'number' . $numberParameter;
                $this->_messageTemplates[$numberCode] = 'Row cannot be imported because the parameter "' . $numberParameter . '" was not an integer';
                throw new InvalidArgumentException($numberCode);
                return $this;
            }
        }

        $this->validatePageIds($parameters);
        $this->validateAdCodeExists($parameters['ai']);
        $this->performAdCodeTypeValidation($row);

        $listAllParameters = $this->getListAllParameters();
        foreach($parameters as $paramKey => $paramValue) {
            if(!in_array($paramKey, $listAllParameters)) {
                $unrecognizedCode = 'unrecognized' . $paramValue;
                $this->_messageTemplates[$unrecognizedCode] = 'Row cannot be imported because the parameter "' . $paramKey . '" is not recognized';
                throw new InvalidArgumentException($unrecognizedCode);
                return $this;
            }
        }

        $this->hasDuplicate($row, $parameters); //if a duplicate Exists, this throws an exception.

        $this->validateEndDateGreaterStartDate($row); //throws an exception is end date does NOT come after start date.

        $this->validateDateOverlap($row, $parameters); //if another record, with same search_expression, ad_code, and store_id has an overlapping date range, this throws an exception.
    }

    public function processDate($csvDate = '')
    {
        $formmatedDate = false;
        if($csvDate) {
            $TsStartDate = strtotime($csvDate);
            $formmatedDate = date('Y-m-d 00:00:00', $TsStartDate); //Hour minute and second are not needed and thus not recorded.
        }

        return $formmatedDate;
    }

    protected function _getStoreByCode($fakeCode)
    {
        if(!isset($fakeCode)) {
            return false;
        }

        if (!isset($this->_storesCache[$fakeCode])) {
            $mappings = $this->getStoreCodeMappings();
            if(!isset($mappings[$fakeCode])) {
                throw new InvalidArgumentException(self::INVALID_STORE_CODE);
                return false;
            }

            $code = $mappings[$fakeCode];

            $store = Mage::getModel('core/store')->load($code);
            $storeId = $store->getId();
            if (!$storeId) {
                throw new InvalidArgumentException(self::INVALID_STORE_CODE);
            }
            $this->_storesCache[$fakeCode] = $storeId;
        }

        return $this->_storesCache[$fakeCode];
    }

    public function validatePageIds($data, $exceptionAsCode = true)
    {
        $numberPageIdsExist = 0;
        foreach($this->getParametersIsNumber() as $parameterName) {
            if($parameterName != 'ai') { //ad code skipped, that is validated seperately
                if(isset($data[$parameterName])) {
                    $numberPageIdsExist++;
                }
            }
        }

        $exceptionArgument = false;
        if($numberPageIdsExist > 1) {
            $exceptionArgument = $exceptionAsCode ? self::INVALID_PAGE_ID_TOOMANY : $this->_messageTemplates[self::INVALID_PAGE_ID_TOOMANY];
        } elseif($numberPageIdsExist == 0) {
            $exceptionArgument = $exceptionAsCode ? self::INVALID_PAGE_ID_NONE : $this->_messageTemplates[self::INVALID_PAGE_ID_NONE];
        }

        if($exceptionArgument && !$exceptionAsCode) {
            $exceptionArgument = str_replace('Row cannot be imported','The redirect could not be saved',$exceptionArgument);
        }

        if($exceptionArgument) {
            throw new InvalidArgumentException($exceptionArgument);
            return $this;
        }
    }

    public function convertFieldnamesToParameterNames($data)
    {
        $convertedParameterValues = array();
        foreach($this->_convertFieldnamesToParameterNames as $fieldName => $parameterName) {
            if(isset($data[$fieldName])) {
                $convertedParameterValues[$parameterName] = $data[$fieldName];
            }
        }

        return $convertedParameterValues;
    }

    public function validateAdCodeExists($adCode, $exceptionAsCode = true)
    {
        if(!self::DISABLE_VALIDATION_VALID_AD_CODE) {
            if(!in_array($adCode, $this->listExistingAdcodes)) {
                $exceptionArgument = $exceptionAsCode ? self::INVALID_ADCODE : $this->_messageTemplates[self::INVALID_ADCODE];

                if(!$exceptionAsCode) {
                    $exceptionArgument = str_replace('Row cannot be imported','The redirect could not be saved',$exceptionArgument);
                }

                throw new InvalidArgumentException($exceptionArgument);

                return $this;
            }
        }
    }

    public function convertAdCodeTypeToNameToId($adCodeTypeName)
    {
        $adCodeTypeId = 0; //this is equivalent to blank value, because 0 corresponds to ad code type of 'None'
        if($adCodeTypeName) {
            $adCodeTypeId = array_search($adCodeTypeName, $this->_adTypeOptions);
        }
        return $adCodeTypeId;
    }

    public function performAdCodeTypeValidation($row)
    {
        $isValid = $this->validateAdCodeTypeDropDown($row[self::COL_AD_TYPE]);
        if(!$isValid) {
            throw new InvalidArgumentException(self::INVALID_ADCODE_TYPE);
        }
        $adCodeTypeId = $this->convertAdCodeTypeToNameToId($row[self::COL_AD_TYPE]);
        $isValid = $this->validateAdTypeRequiredFields($adCodeTypeId, $row[self::COL_MORE_DETAILS]);
        if(!$isValid) {
            throw new InvalidArgumentException(self::ADCODE_REQUIRED_FIELDS_FAIL);
        }
    }

    public function validateAdCodeTypeDropDown($adCodeTypeName = null)
    {
        $isValid = true;
        if($adCodeTypeName) {
            $isValid = array_search($adCodeTypeName, $this->_adTypeOptions) ? true : false;
        }
        return $isValid;
    }

    public function validateAdTypeRequiredFields($adCodeTypeId, $moreDetails)
    {
        $isValid = true;
        if($adCodeTypeId) {
            if($adCodeTypeId == Tgc_Adcoderouter_Model_Field_Source_Adtype::SPACE_AD_ID) {
                if(!$moreDetails) {
                    $isValid = false;
                }
            }
        }

        return $isValid;
    }

    public function validateEndDateGreaterStartDate($rowData)
    {
        $startDate = strtotime(date('Y-m-d',strtotime($rowData['start_date']))); //rounds date to nearest day and then finds timestamp.
        $endDate = strtotime(date('Y-m-d',strtotime($rowData['end_date']))); //rounds date to nearest day and then finds timestamp.

        if($endDate < $startDate) {
            throw new InvalidArgumentException(self::INVALID_DATE);
        }
    }

    public function validateDateOverlap($rowData, $parameters = array())
    {
        $adCodeValues = $this->deriveAdCodeValues($rowData, $parameters);

        $matchingEntries = $this->listAllAdcoderedirects[$adCodeValues[self::COL_SEARCH_EXPRESSION]][$adCodeValues['store_id']];

        if($matchingEntries) {
            ksort($matchingEntries);
            $previousEndDate = null;

            foreach($matchingEntries as $startDate => $endDate) {
                ksort($endDate);
                foreach($endDate as $endDateReal => $endDateDescription) {
                    if($previousEndDate) {
                        if(!isset($this->_entriesWithOverlappingDates[$adCodeValues[self::COL_SEARCH_EXPRESSION]][$adCodeValues['store_id']][$startDate][$endDateReal]) ||
                            !$this->_entriesWithOverlappingDates[$adCodeValues[self::COL_SEARCH_EXPRESSION]][$adCodeValues['store_id']][$startDate][$endDateReal]) {
                            //above if statements prevents a previous overlapping entry from throwing an error.
                            if($startDate < $previousEndDate) { //its okay if start date is equal end date.  It just can't come before it.
                                $this->_entriesWithOverlappingDates[$adCodeValues[self::COL_SEARCH_EXPRESSION]][$adCodeValues['store_id']][$startDate][$endDateReal] = true;
                                throw new InvalidArgumentException(self::INVALID_DATE_OVERLAP);
                            }
                        }
                    }
                    $previousEndDate = $endDateReal;
                }
            }
        }
    }

    public function setArrayRedirectsForDuplicateCheck()
    {
        $connection = $this->_connection;
        $sql = $connection->select()
            ->from('adcode_redirects', array('search_expression','ad_code','store_id','start_date','end_date','id'));

        $stmt = $connection->query($sql);
        $data = array();
        while ($row = $stmt->fetch(Zend_Db::FETCH_NUM)) {
            $startDate = strtotime(date('Y-m-d',strtotime($row[3]))); //this rounds to current day, then derives timestamp
            $endDate = strtotime(date('Y-m-d',strtotime($row[4]))) + 86399; //this rounds to current day, then derives timestamp
            $data[$row[0]][$row[1]][$row[2]][$startDate][$endDate] = array('type' => 'existing', 'id' => $row[5]);
        }

        asort($data);
        $this->listAllAdcoderedirects = $data;
    }

    public function hasDuplicate($rowData, $parameters)
    {
        $hasDuplicate = false;

        $adCodeValues = $this->deriveAdCodeValues($rowData, $parameters);

        if($this->getBehavior() == Mage_ImportExport_Model_Import::BEHAVIOR_APPEND) {
            if($this->retrievePrimaryKeyValue($rowData, $parameters)) { //primary key only exists if value has been stored to DB.  Looks for duplicate records in DB.
                throw new InvalidArgumentException(self::ERROR_DUPLICATE_RECORD_DB);
            }
        }

        if(isset($this->listAllAdcoderedirects[$adCodeValues[self::COL_SEARCH_EXPRESSION]][$adCodeValues['store_id']][$adCodeValues['start_date']][$adCodeValues['end_date']]['type']) &&
            $this->listAllAdcoderedirects[$adCodeValues[self::COL_SEARCH_EXPRESSION]][$adCodeValues['store_id']][$adCodeValues['start_date']][$adCodeValues['end_date']]['type'] == 'new') {
            throw new InvalidArgumentException(self::ERROR_DUPLICATE_RECORD_SPREADSHEET);
        }

        $this->listAllAdcoderedirects[$adCodeValues[self::COL_SEARCH_EXPRESSION]][$adCodeValues['store_id']][$adCodeValues['start_date']][$adCodeValues['end_date']]['type'] = 'new';

        return $hasDuplicate;
    }

    public function retrievePrimaryKeyValue($rowData, $parameters = array())
    {
        if(count($parameters) === 0) {
            $parameters = $this->parseTheRedirectQuerystring($rowData);
        }

        $adCodeValues = $this->deriveAdCodeValues($rowData, $parameters);

        $primaryKeyValue = null;

        if(isset($this->listAllAdcoderedirects[$adCodeValues[self::COL_SEARCH_EXPRESSION]][$adCodeValues['store_id']][$adCodeValues['start_date']][$adCodeValues['end_date']])) {
            $primaryKeyValue = $this->listAllAdcoderedirects[$adCodeValues[self::COL_SEARCH_EXPRESSION]][$adCodeValues['store_id']][$adCodeValues['start_date']][$adCodeValues['end_date']]['id'];
        }

        return $primaryKeyValue;
    }

    public function deriveAdCodeValues($rowData, $parameters = array())
    {
        if(count($parameters) === 0) {
            $parameters = $this->parseTheRedirectQuerystring($rowData);
        }

        $adCodeValues = array_merge($rowData, $parameters);
        $adCodeValues['start_date'] = strtotime(date('Y-m-d', strtotime($adCodeValues['start_date'])));
        $adCodeValues['end_date'] = strtotime(date('Y-m-d', strtotime($adCodeValues['end_date']))) + 86399; //there are 86400 in a day, end date is 1 second before next day
        $adCodeValues['ad_code'] = $adCodeValues[$this->_convertFieldnamesToParameterNames['ad_code']];
        $adCodeValues['store_id'] = $this->_getStoreByCode($adCodeValues[$this->_convertFieldnamesToParameterNames['store_id']]);

        return $adCodeValues;
    }

    private function _helper()
    {
        return Mage::helper('tgc_dax');
    }
}