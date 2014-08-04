<?php

/**
* Dax lectures entity for importexport
*
* @author      Guidance Magento Team <magento@guidance.com>
* @category    Tgc
* @package     Dax
* @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
*/

class Tgc_Dax_Model_Import_Entity_CustomerAcknowledgement extends Tgc_Dax_Model_Import_Entity_Checksum_Customer
{
    const COL_DAX_CUSTOMER_ID                       = 'dax_customer_id';
    const COL_DAX_CUSTOMER_ID_MAPTO                 = 'dax_customer_id';
    const COL_WEB_CUSTOMER_ID                       = 'web_customer_id'; // this is never saved.  Rather, it is used to derive the primary key.
    const COL_WEB_CUSTOMER_ID_MAPTO                 = 'web_user_id';
    const COL_DAX_CUSTOMER_CREATED_UCTIME           = 'dax_customer_created_utctime';
    const COL_DAX_CUSTOMER_CREATED_UCTIME_MAPTO     = 'dax_customer_created_utctime';
    const COL_CUSTOMER_ID                           = 'customer_id'; //this field IS NOT on the spreadsheet.  It is derived from the web customer id.
    const COL_CUSTOMER_ID_MAPTO                     = 'entity_id';

    const BLANK_DATE                                = '0000-00-00 00:00:00';
    const UNIX_START_DATE                           = '1970-01-01 00:00:00';

    protected $_listCustomerIdsProcessed;

    protected $_listWebUserIdsToCustomerIds;

    protected $listAttributesData;

    protected $_customerAcknowledgementAttributes = array('dax_customer_created_uctime');

    protected $_listOfSpreadsheetFields = array(
        self::COL_DAX_CUSTOMER_ID,
        self::COL_WEB_CUSTOMER_ID,
        self::COL_DAX_CUSTOMER_CREATED_UCTIME,
    );

    protected $_permanentAttributes = array(
        self::COL_DAX_CUSTOMER_ID,
        self::COL_WEB_CUSTOMER_ID,
        self::COL_DAX_CUSTOMER_CREATED_UCTIME,
    );

    protected $_requiredFields = array(
        self::COL_DAX_CUSTOMER_ID,
        self::COL_WEB_CUSTOMER_ID,
        self::COL_DAX_CUSTOMER_CREATED_UCTIME,
    );

    protected $_numberFields = array(
        self::COL_DAX_CUSTOMER_ID,
    );

    public function __construct()
    {
        //parent::__construct();
        /** @var _dataSourceModel Mage_ImportExport_Model_Resource_Import_Data */
        $this->_dataSourceModel       = Mage_ImportExport_Model_Import::getDataSourceModel();
        $this->_dataSourceModel->setEntityTypeCode($this->getEntityTypeCode());
        /** @var _connection Magento_Db_Adapter_Pdo_Mysql */
        $this->_connection            = Mage::getSingleton('core/resource')->getConnection('write');
        $this->_listWebUserIdsToCustomerIds = $this->_connection->fetchPairs("SELECT web_user_id, entity_id FROM customer_entity");
        $this->_entityTypeId = 1; //customer has a value of 1.
        $this->_generateAttributeValuesList();
        $this->_entityTable = 'customer_entity';
    }


    public function getEntityTypeCode()
    {
        return 'customer_acknowledgetment';
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
            Mage::throwException(Mage::helper('importexport')->__('The delete action does not exist for Customer Acknowledgements. No records have been deleted.'));
        } else {
            $this->_saveCustomerAcknowledgements();
        }
        return true;
    }

    public function validateRow(array $rowData, $rowNum)
    {
        //this eliminates php notice that occurs in strict mode, that occurs when the processor tries to retrieve an element in the array that does not exist.

        try {
            $this->eliminateUndefinedIndexError($rowData);

            $this->performConversions($rowData, $rowNum);

            $this->determineExceptions($rowData, $rowNum);

            $this->registerProcessedWebuserIds($rowData[self::COL_WEB_CUSTOMER_ID], $rowNum);

            return !isset($this->_invalidRows[$rowNum]);

        } catch (InvalidArgumentException $e) {
            $this->addRowError($e->getMessage(), $rowNum);
            return false;
        }
    }


    public function performConversions(&$row, $rowNum)
    {
        if($row[self::COL_WEB_CUSTOMER_ID]) {
            if(isset($this->_listWebUserIdsToCustomerIds[$row[self::COL_WEB_CUSTOMER_ID]])) {
                $row[self::COL_CUSTOMER_ID] = $this->_listWebUserIdsToCustomerIds[$row[self::COL_WEB_CUSTOMER_ID]];
            }
        }

        if($row[self::COL_DAX_CUSTOMER_CREATED_UCTIME]) {
            //$row['dax_customer_created_uctime'] was included so that when this attribute saves to $row['dax_customer_created_uctime'] NOT $row['dax_customer_created_utctime'], utctime does not exist so we need to make sure its not saved there.
            //mappint must be handled in an awkward way like this because both names need to be used interchangeable in order to retrieve and save this value.
            $row['dax_customer_created_uctime'] = $row[self::COL_DAX_CUSTOMER_CREATED_UCTIME] = $this->_daxDataHelper()->formatDateDMY2Datetime($row[self::COL_DAX_CUSTOMER_CREATED_UCTIME]);
        }
    }

    public function determineExceptions($row, $rowNum)
    {
        foreach($this->_requiredFields as $requiredFieldName)
        {
            if(!$row[$requiredFieldName]) {
                $requiredFieldExceptionCode = 'requiredfield' . $requiredFieldName;
                $this->_messageTemplates[$requiredFieldExceptionCode] = 'The record cannot be imported because the required field "' . $requiredFieldName . '" is blank,';
                throw new InvalidArgumentException($requiredFieldExceptionCode, $rowNum);
            }
        }

        if($row[self::COL_DAX_CUSTOMER_CREATED_UCTIME] == self::BLANK_DATE) {
            $this->_messageTemplates['datecustomercreateductimeblank'] = 'The record cannot be imported becuase the field "' . self::COL_DAX_CUSTOMER_CREATED_UCTIME . '" is not a properly formatted date. (correct format =  yyyy-mm-dd hh:mm:ss),';
            throw new InvalidArgumentException('datecustomercreateductimeblank', $rowNum);
        }

        if(!isset($row[self::COL_CUSTOMER_ID]) || !$row[self::COL_CUSTOMER_ID]) {
            $this->_messageTemplates['webuseridinvalid'] = 'The record cannot not be imported becuase the field "' . self::COL_WEB_CUSTOMER_ID . '" must correspond to an existing customer,';
            throw new InvalidArgumentException('webuseridinvalid', $rowNum);
        }

        foreach($this->_numberFields as $numberFieldName) {
            if(!Zend_Validate::is($row[$numberFieldName], 'Digits')) {
                $numberFieldCode = 'numberfield' . $numberFieldName;
                $this->_messageTemplates[$numberFieldCode] = 'The record cannot be import because the field "' . $numberFieldName . '" must be an integer,';
                throw new InvalidArgumentException($numberFieldCode, $rowNum);
            }
        }

        $this->checkoutForDuplicateKeyValueInSpreadsheet($row, $rowNum);
    }

    /**
     * Gather and save information about customer entities.
     *
     * @return Mage_ImportExport_Model_Import_Entity_Customer
     */
    protected function _saveCustomerAcknowledgements()
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

                $this->performConversions($rowData, $rowNum);

                $customerEntityId = $rowData[self::COL_CUSTOMER_ID];

                if(!$customerEntityId) {
                    continue;
                }

                $entityRowsUp[] = array(
                    self::COL_CUSTOMER_ID_MAPTO                     => $customerEntityId,
                    self::COL_DAX_CUSTOMER_ID_MAPTO                 => $rowData[self::COL_DAX_CUSTOMER_ID],
                );

                // attribute values
                foreach ($this->_customerAcknowledgementAttributes as $attrCode) {
                        /** @var $attribute Mage_Customer_Model_Attribute */
                    $value = $rowData[$attrCode];
                    $attribute  = $resource->getAttribute($attrCode);
                    $backModel  = $attribute->getBackendModel();
                    $attributeId = $this->listAttributesData[$attrCode]['id'];

                    if ($backModel) {
                        $attribute->getBackend()->beforeSave($resource->setData($attrCode, $value));
                        $value = $resource->getData($attrCode);
                    }

                    $attributes[$attribute->getBackend()->getTable()][$customerEntityId][$attributeId] = $value;
                    $attribute->setBackendModel($backModel);
                }
            }
            $this->_saveCustomerEntity($entityRowsIn, $entityRowsUp)->_saveCustomerAttributes($attributes);
        }
        return $this;
    }

    protected function _saveCustomerEntity(array $entityRowsIn, array $entityRowsUp)
    {
        if ($entityRowsUp) {
            $colsToUpdate = array(
                'dax_customer_id',
            );

            $this->_connection->insertOnDuplicate(
                $this->_entityTable,
                $entityRowsUp,
                $colsToUpdate
            );
        }

        return $this;
    }

    protected function _generateAttributeValuesList()
    {
        $connection = $this->_connection;
        $sql = $connection->select()
            ->from('eav_attribute', array('attribute_code','attribute_id'))
            ->where('entity_type_id = ?', 1);

        $stmt = $connection->query($sql);
        $data = array();
        while ($row = $stmt->fetch(Zend_Db::FETCH_NUM)) {
            $data[$row[0]]['id'] = $row[1];
        }

        asort($data);
        $this->listAttributesData = $data;
    }

    public function eliminateUndefinedIndexError(&$row)
    {
        foreach($this->_listOfSpreadsheetFields as $spreadsheetField) {
            if(!isset($row[$spreadsheetField])) {
                $row[$spreadsheetField] = null;
            }
        }
    }

    public function checkoutForDuplicateKeyValueInSpreadsheet($row, $rowNum)
    {
        if($row[self::COL_WEB_CUSTOMER_ID] && count($this->_listCustomerIdsProcessed) > 0) {
            if(in_array($row[self::COL_WEB_CUSTOMER_ID], $this->_listCustomerIdsProcessed)) {
                $this->_messageTemplates['DUPLICATE_INSPREADSHEET'] = 'The record cannot be imported because another row on spreadsheet has the exact same "' . self::COL_WEB_CUSTOMER_ID . '"';
                throw new InvalidArgumentException('DUPLICATE_INSPREADSHEET', $rowNum);
            }
        }
    }

    public function registerProcessedWebuserIds($webUserId = '', $rowNum)
    {
        if($webUserId) {
            $this->_listCustomerIdsProcessed[$rowNum]  = $webUserId;
        }
    }
    
    protected function _helper()
    {
        return Mage::helper('tgc_dax');
    }
    
}