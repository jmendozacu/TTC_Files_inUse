<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Lectures
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Lectures_Model_Import_Entity_FreeLectureProspect extends Tgc_Dax_Model_Import_Entity_Checksum_Customer
{
    //The column web_customer_id is ignored.

    const COL_WEB_PROSPECT_ID                       = 'web_prospect_id';
    const COL_WEB_PROSPECT_ID_MAPTO                 = 'web_prospect_id';
    const COL_DAX_CUSTOMER_ID                       = 'dax_customer_id';
    const COL_DAX_CUSTOMER_ID_MAPTO                 = 'dax_customer_id';
    const COL_FREE_LECTURES_DATE_COLLECTED          = 'date_collected';
    const COL_FREE_LECTURES_DATE_COLLECTED_MAP_TO   = 'free_lectures_date_collected';
    const COL_FREE_LECT_LAST_DATE_COLLECTED         = 'last_date_collected';
    const COL_FREE_LECT_LAST_DATE_COLLECTED_MAP_TO  = 'free_lect_last_date_collected';
    const COL_INITIAL_SOURCE                        = 'initial_source';
    const COL_INITIAL_SOURCE_MAPTO                  = 'free_lectures_initial_source';
    const COL_EMAIL_ADDRESS                         = 'email_address';
    const COL_EMAIL_ADDRESS_MAPTO                   = 'email';
    const COL_INITIAL_USER_AGENT                    = 'initialuseragent';
    const COL_INITIAL_USER_AGENT_MAPTO              = 'free_lect_initial_user_agent';
    const COL_SUBSCRIBE_STATUS                      = 'subscribe_status';
    const COL_SUBSCRIBE_STATUS_MAPTO                = 'free_lect_subscribe_status';
    const COL_EMAIL_VERIFIED                        = 'email_verified';
    const COL_EMAIL_VERIFIED_MAPTO                  = 'email_verified';
    const COL_DATE_VERIFIED                         = 'date_verified';
    const COL_DATE_VERIFIED_MAP_TO                  = 'date_verified';
    const COL_CONFIRMATION_GUID                     = 'confirmation_guid';
    const COL_CONFIRMATION_GUID_MAPTO               = 'confirmation_guid';
    const COL_IS_ACCOUNT_AT_SIGNUP                  = 'is_account_at_signup';
    const COL_IS_ACCOUNT_AT_SIGNUP_MAPTO            = 'is_account_at_signup';
    const COL_DATE_UNSUBSCRIBED                     = 'date_unsubscribed';
    const COL_DATE_UNSUBSCRIBED_MAPTO               = 'free_lect_date_unsubscribed';
    const COL_FREE_LECTURE_PROSPECT_MAPTO           = 'free_lecture_prospect'; //this is no column on spreadsheet for this.

    const INVALID_CUSTOMER_EMAIL                    = 'invalid_customer_email';
    const BLANK_CUSTOMER_EMAIL                      = 'blank_customer_email';
    const WEB_PROSPECT_ID_BLANK                     = 'web_prospect_id_blank';
    const UNSUBSCRIBE_DATE_REQUIRED                 = 'unsubscribe_date_required';

    protected $_customerEmailToCustomerId;

    protected $listAttributesData;

    protected $_freeMarketingFieldsList = array(
        self::COL_WEB_PROSPECT_ID,
        self::COL_EMAIL_ADDRESS,
        self::COL_DAX_CUSTOMER_ID,
        self::COL_FREE_LECTURES_DATE_COLLECTED,
        self::COL_FREE_LECT_LAST_DATE_COLLECTED,
        self::COL_INITIAL_SOURCE,
        self::COL_INITIAL_USER_AGENT,
        self::COL_SUBSCRIBE_STATUS,
        self::COL_EMAIL_VERIFIED,
        self::COL_DATE_VERIFIED,
        self::COL_CONFIRMATION_GUID,
        self::COL_IS_ACCOUNT_AT_SIGNUP,
        self::COL_DATE_UNSUBSCRIBED,
    );

    protected $_freeMarketingStaticFieldsToUpdateList = array(
        self::COL_DAX_CUSTOMER_ID,
        self::COL_FREE_LECTURE_PROSPECT_MAPTO,
    );

    protected $_freeMarketingFieldsSkipSaving = array(
        self::COL_EMAIL_ADDRESS_MAPTO,
    );

    protected $_freeMarketingAttributesList = array(
        self::COL_WEB_PROSPECT_ID_MAPTO,
        self::COL_EMAIL_ADDRESS_MAPTO,
        self::COL_DAX_CUSTOMER_ID_MAPTO,
        self::COL_FREE_LECTURES_DATE_COLLECTED_MAP_TO,
        self::COL_FREE_LECT_LAST_DATE_COLLECTED_MAP_TO,
        self::COL_INITIAL_SOURCE_MAPTO,
        self::COL_INITIAL_USER_AGENT_MAPTO,
        self::COL_SUBSCRIBE_STATUS_MAPTO,
        self::COL_EMAIL_VERIFIED_MAPTO,
        self::COL_DATE_VERIFIED_MAP_TO,
        self::COL_CONFIRMATION_GUID_MAPTO,
        self::COL_IS_ACCOUNT_AT_SIGNUP_MAPTO,
        self::COL_DATE_UNSUBSCRIBED_MAPTO,
        self::COL_FREE_LECTURE_PROSPECT_MAPTO,
    );

    protected $_listDateFields = array(
        self::COL_FREE_LECTURES_DATE_COLLECTED_MAP_TO,
        self::COL_FREE_LECT_LAST_DATE_COLLECTED_MAP_TO,
        self::COL_DATE_VERIFIED_MAP_TO,
        self::COL_DATE_UNSUBSCRIBED_MAPTO,
    );

    //Note: email is a required field, but validation is not handled with this variable.
    protected $_fieldsRequired = array(
        self::COL_WEB_PROSPECT_ID_MAPTO,
    );

    protected $_fieldIsInteger = array(
        self::COL_FREE_LECTURE_PROSPECT_MAPTO,
        self::COL_WEB_PROSPECT_ID_MAPTO,
    );

    protected $_freeMarketingAttributeObjects;

    public function __construct()
    {
        $this->_entityTypeId = 1;
        $this->_dataSourceModel = Mage_ImportExport_Model_Import::getDataSourceModel();
        $this->_dataSourceModel->setEntityTypeCode($this->getEntityTypeCode());
        $this->_connection = Mage::getSingleton('core/resource')->getConnection('write');
        $this->_entityTable = 'customer_entity';
        $this->_permanentAttributes = $this->_freeMarketingFieldsList;
        $this->initializeMessageTemplate();
        $this->generateFreemarketingAttributesRegistry();
        $this->_generateAttributeValuesList();
    }

    public function initializeMessageTemplate()
    {
        $this->addNewMessageTemplate(self::INVALID_CUSTOMER_EMAIL, 'Row could not be imported because no customer exists with that email address');
        $this->addNewMessageTemplate(self::BLANK_CUSTOMER_EMAIL,'Row could not be imported because email is missing');
        $this->addNewMessageTemplate(self::WEB_PROSPECT_ID_BLANK,'Row could not be imported, because a required field "web_prospect_id is blank"');
        $this->addNewMessageTemplate(self::UNSUBSCRIBE_DATE_REQUIRED, 'Row could not be imported because if the "Subscribe Status" is set to unsubscribed, then date_unsubscribed is required');
    }

    public function addNewMessageTemplate($code, $message)
    {
        if(!isset($this->_messageTemplates[$code])) {
            $this->_messageTemplates[$code] = $message . ",";
        }
    }

    public function getCustomerIdFromEmail($emailAddress)
    {
        $customerByEmailSelect = $this->_connection->select()
            ->from(array('e' => 'customer_entity'), array('entity_id'))
            ->where('e.email = ?', $emailAddress);

        $customerId = $this->_connection->fetchOne($customerByEmailSelect);

        return $customerId;
    }

    public function getEntityTypeCode()
    {
        return 'free_lecture_prospect';
    }

    public function validateRow(array $rowData, $rowNum)
    {
        try {
            if(!isset($rowData[self::COL_EMAIL_ADDRESS]) || !$rowData[self::COL_EMAIL_ADDRESS]) {
                throw new InvalidArgumentException(self::BLANK_CUSTOMER_EMAIL);
            } else {
                $emailAddress = $rowData[self::COL_EMAIL_ADDRESS];
            }

            $this->eliminateUndefinedIndexError($rowData);

            $this->performCustomMapping($rowData); //reason for including this here, is I would like validateRequiredFields and validateIntegerFields to be compatible with attribute names, NOT field names on spreadsheet.

            if($rowData[self::COL_SUBSCRIBE_STATUS_MAPTO] == 'unsubscribed') {
                if(!$rowData[self::COL_DATE_UNSUBSCRIBED_MAPTO]) {
                    throw new InvalidArgumentException(self::UNSUBSCRIBE_DATE_REQUIRED);
                }
            }

            $this->validateRequiredFields($rowData);

            $this->validateIntegerFields($rowData);

            if (!$this->getCustomerIdFromEmail($emailAddress)) {
                throw new InvalidArgumentException(self::INVALID_CUSTOMER_EMAIL);
            }

            return true;
        } catch (InvalidArgumentException $e) {
            $this->addRowError($e->getMessage(), $rowNum);
            return false;
        }
    }

    /**
     * Save customer data to DB.
     *
     * @throws Exception
     * @return bool Result of operation.
     */
    protected function _importData()
    {
        if (Mage_ImportExport_Model_Import::BEHAVIOR_DELETE == $this->getBehavior()) {
            //there is no such thing as the behavior 'Append', because a customer record must exist first, in order to create customer acknowledgements.
            //Therefore, if user selects the behavior 'Append', this really runs the behavior 'Replace'.
            Mage::throwException(Mage::helper('importexport')->__('The delete action does not exist for Free Marketing Lectures. No records have been deleted.'));
        } else {
            $this->_saveFreeMarketingLectures();
        }
        return true;
    }

    /**
     * Gather and save information about customer entities.
     *
     * @return Mage_ImportExport_Model_Import_Entity_Customer
     */
    protected function _saveFreeMarketingLectures()
    {
        /** @var $resource Mage_Customer_Model_Customer */
        $resource       = Mage::getModel('customer/customer');

        while ($bunch = $this->_dataSourceModel->getNextBunch()) {
            $entityRowsUp = array();
            $entityRowsIn = array();
            $attributes   = array();

            foreach ($bunch as $rowNum => $rowData) {
                if (!$this->validateRow($rowData, $rowNum)) {
                    continue;
                }

                $this->eliminateUndefinedIndexError($rowData);

                $this->performCustomMapping($rowData);

                $this->performConversions($rowData, $rowNum);

                $customerEntityId = $rowData['entity_id'];

                if(!$customerEntityId) {
                    continue;
                }

                $entityRowsUp[] = $rowData;

                // attribute values
                foreach ($this->_freeMarketingAttributesList as $attrCode) {
                    /** @var $attribute Mage_Customer_Model_Attribute */
                    if(!in_array($attrCode,$this->_freeMarketingFieldsSkipSaving)) { //if the attribute is not in list of fields to skip, we sill save it....
                        if (!$this->listAttributesData[$attrCode]['is_static']) {
                            $value = $rowData[$attrCode];
                            $attribute  = $resource->getAttribute($attrCode);
                            $backModel  = $attribute->getBackendModel();
                            $attributeId = $this->listAttributesData[$attrCode]['id'];

                            if ('select' == $this->listAttributesData[$attrCode]['type']) {
                                if(isset($this->listAttributesData[$attrCode]['options'][strtolower($value)]) && $this->listAttributesData[$attrCode]['options'][strtolower($value)]) {
                                    $value = $this->listAttributesData[$attrCode]['options'][strtolower($value)];
                                }
                            } elseif ($backModel) {
                                $attribute->getBackend()->beforeSave($resource->setData($attrCode, $value));
                                $value = $resource->getData($attrCode);
                            }

                            if(strlen($value)) {
                                $attributes[$attribute->getBackend()->getTable()][$customerEntityId][$attributeId] = $value;
                                $attribute->setBackendModel($backModel);
                            }
                        }
                    }
                }
            }
            $this->_saveCustomerEntity($entityRowsIn, $entityRowsUp)->_saveCustomerAttributes($attributes);
        }
        return $this;
    }

    /**
     * Update data in entity table itself and related tables.
     *
     * Edit: Add Additional TGC entity attributes
     *
     * @param array $entityRowsIn Row for insert
     * @param array $entityRowsUp Row for update
     * @return Mage_ImportExport_Model_Import_Entity_Customer
     */
    protected function _saveCustomerEntity(array $entityRowsIn, array $entityRowsUp)
    {
        if ($entityRowsUp) {
            $colsToUpdate = $this->_freeMarketingStaticFieldsToUpdateList;

            $entityRowsToUpdate = $this->formatEntityRowsToImport($entityRowsUp);

            $this->_connection->insertOnDuplicate(
                $this->_entityTable,
                $entityRowsToUpdate,
                $colsToUpdate
            );
        }
        return $this;
    }

    /**
     *  This function performs the mapping.
     */
    public function performCustomMapping(&$rowData)
    {
        $rowData = array(
            self::COL_WEB_PROSPECT_ID_MAPTO                     => $rowData[self::COL_WEB_PROSPECT_ID],
            self::COL_DAX_CUSTOMER_ID_MAPTO                     => $rowData[self::COL_DAX_CUSTOMER_ID],
            self::COL_FREE_LECTURES_DATE_COLLECTED_MAP_TO       => $rowData[self::COL_FREE_LECTURES_DATE_COLLECTED],
            self::COL_FREE_LECT_LAST_DATE_COLLECTED_MAP_TO      => $rowData[self::COL_FREE_LECT_LAST_DATE_COLLECTED],
            self::COL_INITIAL_SOURCE_MAPTO                      => $rowData[self::COL_INITIAL_SOURCE],
            'entity_id'                                         => $this->getCustomerIdFromEmail($rowData[self::COL_EMAIL_ADDRESS]),
            self::COL_INITIAL_USER_AGENT_MAPTO                  => $rowData[self::COL_INITIAL_USER_AGENT],
            self::COL_SUBSCRIBE_STATUS_MAPTO                    => $rowData[self::COL_SUBSCRIBE_STATUS],
            self::COL_EMAIL_VERIFIED_MAPTO                      => $rowData[self::COL_EMAIL_VERIFIED],
            self::COL_DATE_VERIFIED_MAP_TO                      => $rowData[self::COL_DATE_VERIFIED],
            self::COL_CONFIRMATION_GUID_MAPTO                   => $rowData[self::COL_CONFIRMATION_GUID],
            self::COL_IS_ACCOUNT_AT_SIGNUP_MAPTO                => $rowData[self::COL_IS_ACCOUNT_AT_SIGNUP],
            self::COL_DATE_UNSUBSCRIBED_MAPTO                   => $rowData[self::COL_DATE_UNSUBSCRIBED],
        );
    }

    /**
     * When saving a customer, static attributes are saved differently than other attributes.  An array must be created containing
     * all static attributes.
     *
     * @param $entityRowsUp
     */
    public function formatEntityRowsToImport($entityRowsUp)
    {
        $entityImportRows = array();
        foreach($entityRowsUp as $entityRow) {
            //Currently there is only one static field that needs to be import.
            $entityImportRows[] = array(
                'entity_id'                             => $entityRow['entity_id'],
                self::COL_DAX_CUSTOMER_ID               => $entityRow[self::COL_DAX_CUSTOMER_ID],
                self::COL_FREE_LECTURE_PROSPECT_MAPTO   => $entityRow[self::COL_FREE_LECTURE_PROSPECT_MAPTO],
            );
        }

        return $entityImportRows;
    }

    /**
     * This function prevents undefined index errors from occuring.
     * @param $row
     */
    public function eliminateUndefinedIndexError(&$row)
    {
        foreach($this->_freeMarketingFieldsList as $spreadsheetFields) {
            if(!isset($row[$spreadsheetFields])) {
                $row[$spreadsheetFields] = null;
            }

            if($row[$spreadsheetFields] == 'NULL') {
                $row[$spreadsheetFields] = null;
            }
        }
    }

    /**
     * @param $row
     * @param $rowNum
     */
    public function performConversions(&$row, $rowNum)
    {
        foreach($this->_listDateFields as $dateFieldName) {
            if($row[$dateFieldName]) {
                $row[$dateFieldName] = date('Y-m-d H:i:s',strtotime($row[$dateFieldName]));
            }
        }

        if (strtolower($row[self::COL_IS_ACCOUNT_AT_SIGNUP_MAPTO]) == "true") {
            $row[self::COL_IS_ACCOUNT_AT_SIGNUP_MAPTO] = true;
        }

        if (strtolower($row[self::COL_IS_ACCOUNT_AT_SIGNUP_MAPTO]) == "false") {
            $row[self::COL_IS_ACCOUNT_AT_SIGNUP_MAPTO] = false;
        }

        switch($row[self::COL_EMAIL_VERIFIED_MAPTO]) {
            case 1:
                $row[self::COL_FREE_LECTURE_PROSPECT_MAPTO] = 1;
                break;
            case 0:
                $row[self::COL_FREE_LECTURE_PROSPECT_MAPTO] = 0;
                break;
            default:
                $row[self::COL_FREE_LECTURE_PROSPECT_MAPTO] = 0;
                break;
        }
    }

    /**
     * When a customer is saved, an attribute can only be saved if we know certain specific information about that attribute.  This function determines this information
     *
     */
    protected function _generateAttributeValuesList()
    {
        $connection = $this->_connection;
        $sql = $connection->select()
            ->from('eav_attribute', array('attribute_code','attribute_id'))
            ->where('entity_type_id = ?', 1);

        $stmt = $connection->query($sql);
        $data = array();
        while ($row = $stmt->fetch(Zend_Db::FETCH_NUM)) {
            $attributeCode = $row[0];
            $data[$attributeCode]['id'] = $row[1];
            if(in_array($attributeCode, $this->_freeMarketingAttributesList)) {
                $currentAttribute = $this->getFreemarketingAttributeByCode($attributeCode);
                $data[$attributeCode]['is_static'] = $currentAttribute->isStatic();
                $data[$attributeCode]['type'] = $currentAttribute->getFrontendInput();

                if($currentAttribute->getFrontendInput() == 'select') {
                    $data[$attributeCode]['options'] = $this->getAttributeOptions($currentAttribute);
                }
            }
        }

        asort($data);
        $this->listAttributesData = $data;
    }

    public function generateFreemarketingAttributesRegistry()
    {
        $eavAttributeConfig = Mage::getModel('eav/config');

        foreach($this->_freeMarketingAttributesList as $attributeCode) {
            $attribute = $eavAttributeConfig->getAttribute('customer', $attributeCode);
            $this->_freeMarketingAttributeObjects[$attributeCode] = $attribute;
        }
    }

    public function validateIntegerFields($row)
    {
        //Throws an exception error if a field, that is supposed to be a number, is not a number.
        foreach($this->_fieldIsInteger as $numberField) {
            if(isset($row[$numberField]) && $row[$numberField]) {
                if(!Zend_Validate::is($row[$numberField],'Int')) {
                    $numberCode = 'numberinteger' . $numberField;
                    $this->_messageTemplates[$numberCode] = 'Row cannot be imported because the field "' . $numberField . '" was not an integer, ';
                    throw new InvalidArgumentException($numberCode);
                    return $this;
                }
            }
        }
    }

    public function validateRequiredFields($row)
    {
        //Throws an exception error if a required field is blank.
        foreach($this->_fieldsRequired as $requiredField) {
            if(!$row[$requiredField]) {
                $requiredCode = 'required' . $requiredField;
                $this->_messageTemplates[$requiredCode] = 'Row cannot be imported because the field "' . $requiredField . '" is required, but is missing, ';
                throw new InvalidArgumentException($requiredCode);
                return $this;
            }
        }
    }

    public function getFreemarketingAttributeByCode($attributeCode)
    {
        $freeMarketingAttribute = false;

        if(isset($this->_freeMarketingAttributeObjects[$attributeCode]) && $this->_freeMarketingAttributeObjects[$attributeCode]) {
            $freeMarketingAttribute = $this->_freeMarketingAttributeObjects[$attributeCode];
        }

        return $freeMarketingAttribute;
    }
}
