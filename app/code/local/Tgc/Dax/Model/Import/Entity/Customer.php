<?php
/**
 * Magento Enterprise Edition
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Magento Enterprise Edition License
 * that is bundled with this package in the file LICENSE_EE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.magentocommerce.com/license/enterprise-edition
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_ImportExport
 * @copyright   Copyright (c) 2013 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://www.magentocommerce.com/license/enterprise-edition
 */

/**
 * Import entity customer model
 *
 * @category    Tgc
 * @package     Tgc_Dax
 * @author      Guidance Team <clohm@guidance.com>
 */
class Tgc_Dax_Model_Import_Entity_Customer extends Tgc_Dax_Model_Import_Entity_Checksum_Customer
{
    /**
     * Size of bunch - part of entities to save in one step.
     */
    const BUNCH_SIZE = 50; // Bunch size increased to speed large dataset processing times for TGC.

    /**
     * Error codes.
     */
    const ERROR_WEB_USER_ID_ALREADY_EXISTS = 'webUserIdAlreadyExists';
    const ERROR_DUPLICATE_WEB_USER_ID = 'duplicateWebUserId';
    const ERROR_WEB_USER_ID_EMPTY = 'webUserIdEmpty';
    const ERROR_CHARACTERS_IN_INVALID_CHARSET = 'charactersininvalidcharset';

    /**
     * Permanent column names.
     *
     * Names that begins with underscore is not an attribute. This name convention is for
     * to avoid interference with same attribute name.
     */
    const COL_DAX_CUSTOMER_ID = 'dax_customer_id';
    const COL_DATAMART_CUSTOMER_PREF = 'datamart_customer_pref';
    const COL_WEB_USER_ID = 'web_user_id';
    const COL_AUDIO_DOWNLOAD_PREFERENCE = 'audio_pref';
    const COL_VIDEO_DOWNLOAD_PREFERENCE = 'video_pref';
    const COL_SUBSCRIBER_STATUS = 'email_pref';
    const COL_ADDRESS_TELEPHONE = '_address_telephone';


    protected $_fieldsforAudioVideoPreference = array(
        self::COL_AUDIO_DOWNLOAD_PREFERENCE,
        self::COL_VIDEO_DOWNLOAD_PREFERENCE,
    );
    /**
     * Customer constants
     *
     */
    const MAX_PASSWD_LENGTH = 4; // Lowered to accept older TGC customer accounts

    /**
     * Existing cutomers web_user_id to email map
     *
     * @var array
     */
    protected $_oldCustomersWebId = array();

    /**
     * New cutomers web_user_id to email map
     *
     * @var array
     */
    protected $_newCustomersWebId = array();

    /**
     * Limit of errors after which pre-processing will exit.
     *
     * @var int
     */
    protected $_errorsLimit = 1000; // Raised to allow more errors due to size of TGC customer base.

    protected $_optionalColumns = array('datamart_customer_pref','web_user_id');

    public $existingCustomerData;

    protected $_attributesRelaxedForImport = array(self::COL_ADDRESS_TELEPHONE);

    /**
     * Permanent entity columns.
     *
     * @var array
     */
    protected $_permanentAttributes = array(self::COL_EMAIL, self::COL_WEBSITE, self::COL_DAX_CUSTOMER_ID);

    protected $_setUndefinedFieldsToNull = array(
        self::COL_AUDIO_DOWNLOAD_PREFERENCE,
        self::COL_VIDEO_DOWNLOAD_PREFERENCE,
        'is_address_row',
        self::COL_DATAMART_CUSTOMER_PREF,
        self::COL_WEB_USER_ID,
    );

    /**
     * Constructor.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->_dataSourceModel->setEntityTypeCode($this->getEntityTypeCode());
        $this->_messageTemplates[self::ERROR_WEB_USER_ID_ALREADY_EXISTS] = 'Web User Id is already assigned to existing customer';
        $this->_messageTemplates[self::ERROR_DUPLICATE_WEB_USER_ID] = 'Web User Id is duplicated in import file';
        $this->_messageTemplates[self::ERROR_WEB_USER_ID_EMPTY] = 'Web User Id can\'t be empty for existsing customer';
        $this->existingCustomerData['datamart_customer_pref'] = $this->_connection->fetchPairs("SELECT email, datamart_customer_pref FROM customer_entity");
        $this->existingCustomerData['web_user_id'] = $this->_connection->fetchPairs("SELECT email, web_user_id FROM customer_entity");
    }

    /**
     * Initialize existent customers data.
     *
     * @return Mage_ImportExport_Model_Import_Entity_Customer
     */
    protected function _initCustomers()
    {
        // Rewrite to avoid using collections.  We do not want to load entire customer
        $resource = Mage::getSingleton('core/resource');
        $read = $resource->getConnection('core_read');
        $table = 'customer_entity';
        $query = 'SELECT entity_id, website_id, email, web_user_id FROM ' . $table;
        $result = $read->query($query);
        while ($row = $result->fetch())
        {
            $email = $row['email'];

            if (!isset($this->_oldCustomers[$email])) {
                $this->_oldCustomers[$email] = array();
            }
            $this->_oldCustomers[$email][$this->_websiteIdToCode[$row['website_id']]] = $row['entity_id'];

            $webUserId = $row['web_user_id'];
            if (!isset($this->_oldCustomersWebId[$webUserId])) {
                $this->_oldCustomersWebId[$webUserId] = array();
            }
            $this->_oldCustomersWebId[$webUserId][$row['entity_id']] = strtolower($email);
        }
        /*foreach (Mage::getResourceModel('customer/customer_collection') as $customer) {
            $email = $customer->getEmail();

            if (!isset($this->_oldCustomers[$email])) {
                $this->_oldCustomers[$email] = array();
            }
            $this->_oldCustomers[$email][$this->_websiteIdToCode[$customer->getWebsiteId()]] = $customer->getId();

            $webUserId = $customer->getWebUserId();
            if (!isset($this->_oldCustomersWebId[$webUserId])) {
                $this->_oldCustomersWebId[$webUserId] = array();
            }
            $this->_oldCustomersWebId[$webUserId][$customer->getId()] = strtolower($email);
        }*/
        $this->_customerGlobal = Mage::getModel('customer/customer')->getSharingConfig()->isGlobalScope();

        return $this;
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
            $this->_deleteCustomers();
        } else {
            $this->_saveCustomers();
            $this->_saveNewsletterData();
            $this->_addressEntity->importData();
            $this->deleteBlankPhoneNumbers();
        }
        return true;
    }

    public function customFieldMapping(&$rowData)
    {
        $this->_setUndefinedIndexes($rowData);

        //value must be a 0 or 1 for audio_prefo or video_pref
        foreach($this->_fieldsforAudioVideoPreference as $fieldName) {
            if(isset($rowData[$fieldName])) {
                if(!in_array($rowData[$fieldName], array(0,1))) {
                    $rowData[$fieldName] = 0;
                }
            }
        }

        $subscriberStatus = null;
        switch($rowData[self::COL_SUBSCRIBER_STATUS]) {
            case 'Subscribe':
                $subscriberStatus = 1;
                break;
            case 'Unsubscribe':
                $subscriberStatus = 3;
                break;
            default:
                $subscriberStatus = null;
                break;
        }

        $rowData[self::COL_SUBSCRIBER_STATUS] = $subscriberStatus;
        $rowData['created_at'] = $this->formatDateDMY2Datetime($rowData['created_at']);

        $audioFormat = null;
        switch($rowData[self::COL_AUDIO_DOWNLOAD_PREFERENCE]) {
            case 'mp3':
                $audioFormat = 1;
                break;
            case 'm4b':
                $audioFormat = 0;
                break;
            default:
                $audioFormat = null;
                break;
        }

        $videoFormat = null;
        switch($rowData[self::COL_VIDEO_DOWNLOAD_PREFERENCE]) {
            case 'wmv':
                $videoFormat = 1;
                break;
            case 'm4v':
                $videoFormat = 0;
                break;
            default:
                $videoFormat = null;
                break;
        }

        $rowData['audio_format'] = $audioFormat;
        $rowData['video_format'] = $videoFormat;
    }

    public function validateCustomerOptionValues($rowData, $rowNum)
    {
        $subscriberValidOptions = array('Subscribe','Unsubscribe');
        $isSubscriberStatusValid = $this->isOptionAcceptable($rowData[self::COL_SUBSCRIBER_STATUS], $subscriberValidOptions);
        if(!$isSubscriberStatusValid) {
            $this->_messageTemplates['optionsvalidsubscriberstatus'] = "The value for " . self::COL_SUBSCRIBER_STATUS . " is invalid.  Only these values are allowed: ". implode(", ", $subscriberValidOptions) . ",";
            $this->addRowError('optionsvalidsubscriberstatus', $rowNum);
        }

        $videoValidOptions = array('wmv','m4v');
        $isVideoPrefValid = $this->isOptionAcceptable($rowData[self::COL_VIDEO_DOWNLOAD_PREFERENCE], $videoValidOptions);
        if(!$isVideoPrefValid) {
            $this->_messageTemplates['optionsvalidvideopref'] = "The value for " . self::COL_VIDEO_DOWNLOAD_PREFERENCE . " is invalid.  Only these values are allowed: ". implode(", ", $videoValidOptions) . ",";
            $this->addRowError('optionsvalidvideopref', $rowNum);
        }

        $audioValidOptions = array('mp3','m4b');
        $isAudioPrefValid = $this->isOptionAcceptable($rowData[self::COL_AUDIO_DOWNLOAD_PREFERENCE], $audioValidOptions);
        if(!$isAudioPrefValid) {
            $this->_messageTemplates['optionsvalidaudiopref'] = "The value for " . self::COL_AUDIO_DOWNLOAD_PREFERENCE . " is invalid.  Only these values are allowed: ". implode(", ", $audioValidOptions) . ",";
            $this->addRowError('optionsvalidaudiopref', $rowNum);
        }
    }

    public function isOptionAcceptable($optionValue = '', $acceptableOptions)
    {
        $isValid = true;
        if(!empty($optionValue) || $optionValue === 0 || $optionValue === "0" || $optionValue === 0.0) {
            $acceptableOptions = array_map('strtolower', $acceptableOptions);
            $optionValue = trim(strtolower($optionValue));

            if(!in_array($optionValue, $acceptableOptions, true)) {
               $isValid = false;
            }
        }

        return $isValid;
    }

    public function formatDateDMY2Datetime($dateCreatedOn)
    {
        if(isset($dateCreatedOn) && $dateCreatedOn) {
            if(Zend_Validate::is($dateCreatedOn, 'Date', array('format'=>'MM-dd-Y HH:mm:ss a'))) {
                $dateArray = explode(' ',$dateCreatedOn);
                $datePartYMD = array_shift($dateArray);
                $datePartYMDasArray = explode('/',$datePartYMD);
                $datePartRemainder = date('H:i:s',strtotime(implode(" ",$dateArray)));
                $dmyFormattedCorrectly = date('Y-m-d', strtotime($datePartYMDasArray[2] . "-" . $datePartYMDasArray[0] . "-" . $datePartYMDasArray[1]));
                $dateFull = $dmyFormattedCorrectly . " " . $datePartRemainder;
                $dateCreatedOn = $dateFull;
            } else {
                $dateCreatedOn = date('Y-m-d H:i:s',strtotime($dateCreatedOn));
            }

            if(!Zend_Validate::is($dateCreatedOn, 'Date', array('format'=>'Y-MM-dd HH:mm:ss')))
            {
                $rowData['created_at'] = '0000-00-00 00:00:00';
            }
        }

        return $dateCreatedOn;
    }

    /**
     * If an attributes value is blank, it creates an element in the $rowData array with a value null.  This prevents undefined index notices.
     *
     * @param $rowData
     */
    protected function _setUndefinedIndexes(&$rowData)
    {
        if(!isset($rowData['undefined_indexes_set'])) {
            foreach($this->_setUndefinedFieldsToNull as $listAllRowDataFieldsName) {
                if(!isset($rowData[$listAllRowDataFieldsName])) {
                    $rowData[$listAllRowDataFieldsName] = null; //prevents undefined index errors occuring, by setting all undefined to null.
                }
            }

            if(isset($rowData['email']) && $rowData['email']) {
                foreach($this->_optionalColumns as $optionalColumns) {
                    if(!isset($rowData[$optionalColumns]) || !$rowData[$optionalColumns]) {
                        if(array_key_exists($rowData['email'], $this->existingCustomerData[$optionalColumns])) {
                            $rowData[$optionalColumns] = $this->existingCustomerData[$optionalColumns][$rowData['email']];
                        }
                    }
                    //if above if condition is not met, that means this value exists on the import spreadsheet, if it exists, it will overwrite what is in the db.
                }
            }

            $rowData['undefined_indexes_set'] = true;
        }
    }

    public function validateFieldsInvalidCharacterSets($rowData, $rowNum)
    {
        foreach($rowData as $rowDataKey => $rowDataValue) {
            if($rowDataValue) {
                $rowDataValueConverted = iconv("UTF-8","UTF-8",$rowDataValue);
                if(!$rowDataValueConverted || $rowDataValueConverted != $rowDataValue) {
                    $errorCode = self::ERROR_CHARACTERS_IN_INVALID_CHARSET . $rowDataKey;
                    $this->_messageTemplates[$errorCode] = "The value for $rowDataKey is not valid because it contains a non UTF-8 character.";
                    $this->addRowError($errorCode, $rowNum);
                }
            }
        }
    }

    /**
     * Gather and save information about customer entities.
     *
     * @return Mage_ImportExport_Model_Import_Entity_Customer
     */
    protected function _saveCustomers()
    {
        /** @var $resource Mage_Customer_Model_Customer */
        $resource = Mage::getModel('customer/customer');
        $strftimeFormat = Varien_Date::convertZendToStrftime(Varien_Date::DATETIME_INTERNAL_FORMAT, true, true);
        $table = $resource->getResource()->getEntityTable();
        $nextEntityId = Mage::getResourceHelper('importexport')->getNextAutoincrement($table);
        $passId = $resource->getAttribute('password_hash')->getId();
        $passTable = $resource->getAttribute('password_hash')->getBackend()->getTable();
        $tgcCustomerHelper = Mage::helper('tgc_customer');

        while ($bunch = $this->_dataSourceModel->getNextBunch()) {
            $entityRowsIn = array();
            $entityRowsUp = array();
            $attributes = array();

            $oldCustomersToLower = array_change_key_case($this->_oldCustomers, CASE_LOWER);

            foreach ($bunch as $rowNum => $rowData) {
                if (!$this->validateRow($rowData, $rowNum)) {
                    continue;
                }
                if($rowData['is_address_row']) { //the rows on spreadsheet that correspond to address and not customer should NOT be saved.
                    continue;
                }

                $this->customFieldMapping($rowData);

                if (self::SCOPE_DEFAULT == $this->getRowScope($rowData)) {
                    // entity table data & entity row attributes for DAX and Datamart
                    $entityRow = array(
                        'group_id' => empty($rowData['group_id']) ? self::DEFAULT_GROUP_ID : $rowData['group_id'],
                        'store_id' => empty($rowData[self::COL_STORE])
                                ? 0 : $this->_storeCodeToId[$rowData[self::COL_STORE]],
                        'created_at' => empty($rowData['created_at'])
                                ? now() : gmstrftime($strftimeFormat, strtotime($rowData['created_at'])),
                        'updated_at' => now(),
                        'audio_format'              => $rowData['audio_format'],
                        'video_format'              => $rowData['video_format'],
                        'datamart_customer_pref'    => $rowData[self::COL_DATAMART_CUSTOMER_PREF],
                        'web_user_id'               => $rowData[self::COL_WEB_USER_ID],
                        'dax_customer_id'           => $rowData[self::COL_DAX_CUSTOMER_ID],
                    );

                    $emailToLower = strtolower($rowData[self::COL_EMAIL]);
                    if (isset($oldCustomersToLower[$emailToLower][$rowData[self::COL_WEBSITE]])) { // edit
                        $entityId = $oldCustomersToLower[$emailToLower][$rowData[self::COL_WEBSITE]];
                        $entityRow['entity_id'] = $entityId;
                        $entityRowsUp[] = $entityRow;
                    } else { // create
                        $entityId = $nextEntityId++;
                        $entityRow['entity_id'] = $entityId;
                        $entityRow['entity_type_id'] = $this->_entityTypeId;
                        $entityRow['attribute_set_id'] = 0;
                        $entityRow['website_id'] = $this->_websiteCodeToId[$rowData[self::COL_WEBSITE]];
                        $entityRow['email'] = $rowData[self::COL_EMAIL];
                        $entityRow['is_active'] = 1;

                        if (isset($rowData[self::COL_WEB_USER_ID]) && $rowData[self::COL_WEB_USER_ID]) {
                            $entityRow['web_user_id'] = $rowData[self::COL_WEB_USER_ID];
                        } else {
                            // generate web_user_id
                            $webUserId = $tgcCustomerHelper->generateWebUserId();
                            while (isset($this->_oldCustomersWebId[$webUserId])
                                || isset($this->_newCustomersWebId[$webUserId])) {
                                $webUserId = $tgcCustomerHelper->generateWebUserId(array(
                                    $entityRow['entity_id'],
                                    $entityRow['email'],
                                    microtime(true),
                                    mt_rand()
                                ));
                            }
                            $entityRow['web_user_id'] = $webUserId;
                        }

                        $entityRowsIn[] = $entityRow;

                        $this->_newCustomers[$rowData[self::COL_EMAIL]][$rowData[self::COL_WEBSITE]] = $entityId;
                    }
                    // attribute values
                    foreach (array_intersect_key($rowData, $this->_attributes) as $attrCode => $value) {
                        if (!$this->_attributes[$attrCode]['is_static'] && strlen($value)) {
                            /** @var $attribute Mage_Customer_Model_Attribute */
                            $attribute = $resource->getAttribute($attrCode);
                            $backModel = $attribute->getBackendModel();
                            $attrParams = $this->_attributes[$attrCode];

                            if ('select' == $attrParams['type']) {
                                $value = $attrParams['options'][strtolower($value)];
                            } elseif ('datetime' == $attrParams['type']) {
                                $value = gmstrftime($strftimeFormat, strtotime($value));
                            } elseif ($backModel) {
                                $attribute->getBackend()->beforeSave($resource->setData($attrCode, $value));
                                $value = $resource->getData($attrCode);
                            }
                            $attributes[$attribute->getBackend()->getTable()][$entityId][$attrParams['id']] = $value;

                            // restore 'backend_model' to avoid default setting
                            $attribute->setBackendModel($backModel);
                        }
                    }
                    // password change/set
                    if (isset($rowData['password']) && strlen($rowData['password'])) {
                        $attributes[$passTable][$entityId][$passId] = $resource->hashPassword($rowData['password']);
                    }
                }
            }
            $this->_saveCustomerEntity($entityRowsIn, $entityRowsUp)->_saveCustomerAttributes($attributes);
        }
        return $this;
    }

    public function _saveNewsletterData()
    {
        /** @var $resource Mage_Customer_Model_Customer */
        $this->_generateListCustomerIds(); //saves all customer ids to an object variable.
        $this->_generateListOfSubscribers(); //saves all subscriber ids to an object variable.
        $resource = Mage::getSingleton('core/resource');
        $newsLetterTable = $resource->getTableName('newsletter/subscriber');
        $newsletterSubscriberObject = Mage::getModel('newsletter/subscriber');

        while ($bunch = $this->_dataSourceModel->getNextBunch()) {
            $entityRowsIn = array();
            $entityRowsUp = array();

            foreach ($bunch as $rowNum => $rowData) {
                if (!$this->validateRow($rowData, $rowNum)) {
                    continue;
                }
                if($rowData['is_address_row']) { //the rows on spreadsheet that correspond to address and not customer should NOT be saved.
                    continue;
                }

                $this->customFieldMapping($rowData);

                $storeId = empty($rowData[self::COL_STORE])
                    ? false : $this->_storeCodeToId[$rowData[self::COL_STORE]];

                $customerId = isset($this->existingCustomerData['customerids'][$rowData['email']][$storeId]) ?
                    $this->existingCustomerData['customerids'][$rowData['email']][$storeId] : null;

                if(Zend_Validate::is($storeId, 'Digits') && $customerId) {
                    $entityRow = array(
                      'change_status_at'    => now(),
                      'subscriber_status'   => $rowData[self::COL_SUBSCRIBER_STATUS],
                    );

                    $subscriberId = $this->ifSubscriberRetrieveId($customerId, $storeId);

                    if(!$subscriberId) {
                        $entityRow['customer_id'] = $customerId;
                        $entityRow['subscriber_email'] = $rowData['email'];
                        $entityRow['needs_export'] = 0;
                        $entityRow['store_id'] = $storeId;
                        $entityRow['subscriber_confirm_code'] = $newsletterSubscriberObject->randomSequence();
                        $entityRowsIn[] = $entityRow;
                    } else {
                        $entityRow['subscriber_id'] = $subscriberId;
                        $entityRow['store_id'] = $storeId;
                        $entityRowsUp[] = $entityRow;
                    }
                }
            }

            if($entityRowsIn || $entityRowsUp) {
                if ($entityRowsIn) {
                    $this->_connection->insertMultiple($newsLetterTable, $entityRowsIn);
                }
                if ($entityRowsUp) {
                    $colsToUpdate = array(
                        'subscriber_id',
                        'change_status_at',
                        'store_id',
                        'subscriber_status',
                    );

                    $this->_connection->insertOnDuplicate(
                        $newsLetterTable,
                        $entityRowsUp,
                        $colsToUpdate
                    );
                }
            }
        }
    }

    protected function _generateListOfSubscribers()
    {
        $connection = $this->_connection;
        $sql = $connection->select()
            ->from('newsletter_subscriber', array('customer_id','store_id','subscriber_id'));

        $stmt = $connection->query($sql);
        $data = array();
        while ($row = $stmt->fetch(Zend_Db::FETCH_NUM)) {
            $data[$row[0]][$row[1]] = $row[2];
        }

        asort($data);
        $this->existingCustomerData['subscriberids'] = $data;
    }

    public function ifSubscriberRetrieveId($customerId = '', $storeId)
    {
        $subscriberId = false;
        if($customerId) {
            if(isset($this->existingCustomerData['subscriberids'][$customerId][$storeId])) {
                $subscriberId = $this->existingCustomerData['subscriberids'][$customerId][$storeId];
            }
        }

        return $subscriberId;
    }

    protected function _generateListCustomerIds()
    {
        $connection = $this->_connection;
        $sql = $connection->select()
            ->from('customer_entity', array('email','store_id','entity_id'));

        $stmt = $connection->query($sql);
        $data = array();
        while ($row = $stmt->fetch(Zend_Db::FETCH_NUM)) {
            $data[$row[0]][$row[1]] = $row[2];
        }

        asort($data);
        $this->existingCustomerData['customerids'] = $data;
    }

    /**
     * Update and insert data in entity table.
     *
     * Edit: Add Additional TGC entity attributes
     *
     * @param array $entityRowsIn Row for insert
     * @param array $entityRowsUp Row for update
     * @return Mage_ImportExport_Model_Import_Entity_Customer
     */
    protected function _saveCustomerEntity(array $entityRowsIn, array $entityRowsUp)
    {
        if ($entityRowsIn) {
            $this->_connection->insertMultiple($this->_entityTable, $entityRowsIn);
        }
        if ($entityRowsUp) {
            $colsToUpdate = array(
                'group_id',
                'store_id',
                'updated_at',
                'created_at',
                'dax_customer_id',
                'audio_format',
                'video_format',
            );

            foreach($this->_optionalColumns as $optionalColumnNameNeedsToBeAdded) {
                $colsToUpdate[] = $optionalColumnNameNeedsToBeAdded;
            }

            $this->_connection->insertOnDuplicate(
                $this->_entityTable,
                $entityRowsUp,
                $colsToUpdate
            );
        }
        return $this;
    }

    public function deleteBlankPhoneNumbers()
    {
        $eavAttributeConfig = Mage::getModel('eav/config');
        $attribute = $eavAttributeConfig->getAttribute('customer_address', 'telephone');

        $attributeId = $attribute->getId();
        $table = $attribute->getBackend()->getTable();

        if($table && $attributeId) {
            $where = array(
                'attribute_id = ?' => $attributeId,
                'value = ?'        => 0,
            );
            $this->_connection->delete($table, $where);
        }
    }

    /**
     * Validate data row.
     *
     * @param array $rowData
     * @param int $rowNum
     * @return boolean
     */
    public function validateRow(array $rowData, $rowNum)
    {
        static $email = null; // e-mail is remembered through all customer rows
        static $website = null; // website is remembered through all customer rows

        if (isset($this->_validatedRows[$rowNum])) { // check that row is already validated
            return !isset($this->_invalidRows[$rowNum]);
        }
        $this->_validatedRows[$rowNum] = true;

        $rowScope = $this->getRowScope($rowData);

        if (self::SCOPE_DEFAULT == $rowScope) {
            $this->_processedEntitiesCount++;
        }

        $this->validateFieldsInvalidCharacterSets($rowData, $rowNum);

        $this->validateCustomerOptionValues($rowData, $rowNum);

        $this->customFieldMapping($rowData);

        $email = $rowData[self::COL_EMAIL];
        $emailToLower = strtolower($rowData[self::COL_EMAIL]);
        $website = $rowData[self::COL_WEBSITE];

        $oldCustomersToLower = array_change_key_case($this->_oldCustomers, CASE_LOWER);
        $newCustomersToLower = array_change_key_case($this->_newCustomers, CASE_LOWER);

        // BEHAVIOR_DELETE use specific validation logic
        if (Mage_ImportExport_Model_Import::BEHAVIOR_DELETE == $this->getBehavior()) {
            if (self::SCOPE_DEFAULT == $rowScope
                && !isset($oldCustomersToLower[$emailToLower][$website])
            ) {
                $this->addRowError(self::ERROR_EMAIL_SITE_NOT_FOUND, $rowNum);
            }
        } elseif (self::SCOPE_DEFAULT == $rowScope) { // row is SCOPE_DEFAULT = new customer block begins
            if (!Zend_Validate::is($email, 'EmailAddress')) {
                $this->addRowError(self::ERROR_INVALID_EMAIL, $rowNum);
            } elseif (!isset($this->_websiteCodeToId[$website])) {
                $this->addRowError(self::ERROR_INVALID_WEBSITE, $rowNum);
            } else {
                if (isset($newCustomersToLower[$emailToLower][$website])) {
                    $this->addRowError(self::ERROR_DUPLICATE_EMAIL_SITE, $rowNum);
                }
                $this->_newCustomers[$email][$website] = false;

                if (!empty($rowData[self::COL_STORE]) && !isset($this->_storeCodeToId[$rowData[self::COL_STORE]])) {
                    $this->addRowError(self::ERROR_INVALID_STORE, $rowNum);
                }
                // check password
                if (isset($rowData['password']) && strlen($rowData['password'])
                    && Mage::helper('core/string')->strlen($rowData['password']) < self::MAX_PASSWD_LENGTH
                ) {
                    $this->addRowError(self::ERROR_PASSWORD_LENGTH, $rowNum);
                }

                if (isset($rowData[self::COL_WEB_USER_ID]) && $rowData[self::COL_WEB_USER_ID]) {
                    $webUserId = $rowData[self::COL_WEB_USER_ID];
                    if (isset($this->_oldCustomersWebId[$webUserId])
                        && !in_array($emailToLower, $this->_oldCustomersWebId[$webUserId])
                    ) {
                        $this->addRowError(self::ERROR_WEB_USER_ID_ALREADY_EXISTS, $rowNum);
                    } else if (isset($this->_newCustomersWebId[$webUserId])
                        && !in_array($emailToLower, $this->_newCustomersWebId[$webUserId])
                    ) {
                        $this->addRowError(self::ERROR_DUPLICATE_WEB_USER_ID, $rowNum);
                    }

                    if (!isset($this->_newCustomersWebId[$webUserId])) {
                        $this->_newCustomersWebId[$webUserId] = array();
                    }
                    $this->_newCustomersWebId[$webUserId][] = $emailToLower;
                } else if (isset($rowData[self::COL_WEB_USER_ID]) && isset($oldCustomersToLower[$emailToLower])) {
                    $this->addRowError(self::ERROR_WEB_USER_ID_EMPTY, $rowNum);
                }

                // check simple attributes
                foreach ($this->_attributes as $attrCode => $attrParams) {
                    if (in_array($attrCode, $this->_ignoredAttributes)) {
                        continue;
                    }
                    if (isset($rowData[$attrCode]) && strlen($rowData[$attrCode])) {
                        $this->isAttributeValid($attrCode, $attrParams, $rowData, $rowNum);
                    } elseif ($attrParams['is_required'] && !isset($oldCustomersToLower[$emailToLower][$website])
                        && !in_array($attrCode, $this->_attributesRelaxedForImport)) {
                        $this->addRowError(self::ERROR_VALUE_IS_REQUIRED, $rowNum, $attrCode);
                    }
                }
            }
            if (isset($this->_invalidRows[$rowNum])) {
                $email = false; // mark row as invalid for next address rows
            }
        } else {
            if(!$rowData['is_address_row']) { //if this is an address row, then no validation is needed for the email.
                if (null === $email) { // first row is not SCOPE_DEFAULT
                    $this->addRowError(self::ERROR_EMAIL_IS_EMPTY, $rowNum);
                } elseif (false === $email) { // SCOPE_DEFAULT row is invalid
                    $this->addRowError(self::ERROR_ROW_IS_ORPHAN, $rowNum);
                } elseif ('' == $email) { // SCOPE_DEFAULT row is invalid
                    $this->addRowError(self::ERROR_EMAIL_IS_EMPTY, $rowNum);
                }
            }
        }

        // validate row data by address entity
        $this->_addressEntity->validateRow($rowData, $rowNum);

        return !isset($this->_invalidRows[$rowNum]);
    }

}
