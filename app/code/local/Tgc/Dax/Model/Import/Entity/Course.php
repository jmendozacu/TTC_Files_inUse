<?php
/**
 * Dax adcode entity for importexport
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Dax
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 * PREVIOUSLY USED CHECKSUM INTERFACE
 */
class Tgc_Dax_Model_Import_Entity_Course extends Tgc_Dax_Model_Import_Entity_Checksum_Enterprise_Product
{
    const ENTITY_TYPE_CODE = 'course';

    const ATTRIBUTE_SET_NAME = 'Courses';

    /************************************GENERAL SETTINGS**********************************************/
    /**************************************************************************************************/
    const IS_DISABLED_IMPORT_CATEGORY = true;
    const IS_DISABLED_IMPORT_IMAGES = false;
    const IS_DISABLED_VALIDATION_DESCRIPTION = true;
    const IS_DISABLED_ATTRIBUTE_ADD_OPTION_IFNOT_EXIST = true;
    const SETS_PRODUCTS_STATUS_MARK_DISABLED = true;

    //All attributes in this array, if changed in the magento admin, will not changed if a courses import is done.

    //note: website does not get overwritten, there is a function called hasWebsiteAttributeValue that ensures that is not overwritten.
    protected $_attributesNoOverwrite = array('name','description','short_description','visibility','publish_date','meta_description','meta_title','meta_keyword',
        'monthly_free_lecture_from','monthly_free_lecture_to','marketing_free_lecture_from','marketing_free_lecture_to','bibliography','course_summary',
        'recommended_links','partner','guidebook','status'
    );

    protected $_fieldsNeedTrimmed = array(
        'clearance_flag'
    );

    /**************************************************************************************************/

    //Names of column titles on the csv spreadsheet. Arrays in this class use these constants for key values.
    const COL_STORE    = 'store';
    const COL_ATTR_SET = '_attribute_set'; //Col Attr Set
    const COL_TYPE     = 'type'; //Changed from _type to type
    const COL_CATEGORY = 'category_id';
    const COL_CATEGORIES = 'categories';
    const COL_ROOT_CATEGORY = '_root_category'; //Col Root Category
    const COL_MEDIA_IMAGE = "_media_image";
    const COL_ASSOCIATED_CONFIGURABLE_PRODUCTS = 'associated_configurable_skus';
    const COL_MEDIA_GALLERY = 'media_gallery';
    const COL_PHYSICAL_TRANSCRIPT_SKU = 'physical_transcript_sku';
    const COL_DIGITAL_TRANSCRIPT_SKU = 'digital_transcript_sku';

    //Data related to transcript product options
    const DIGITAL_TRANSCRIPT_TITLE  = 'Include Digital Transcript';
    const PHYSICAL_TRANSCRIPT_TITLE = 'Include Physical Transcript';
    const DIGITAL_TRANSCRIPT_MEDIA_FORMAT = 'Digital Transcript';
    const PHYSICAL_TRANSCRIPT_MEDIA_FORMAT = 'Transcript Book';
    const TRANSCRIPT_TITLE = 'Include Transcript';

    const ERROR_INVALID_WEBSITE = 1;
    const MEDIA_GALLERY_ATTRIBUTE_CODE = 'media_gallery';

    //Several columns may contain comma seperated values.  This specifies the delimeters for those columns.
    const QUOTE_DELIMETER = '"';
    const ROW_DELIMITER = ',';
    const DEFAULT_ENTITY_TYPE_ID = 4;


    /********************************************MAPPINGS**********************************************/
    /**************************************************************************************************/
    protected $_storeCodeMappings = array(
        'us' => 'default',
        'uk' => 'uk_en',
        'au' => 'au_en',
    );

    protected $_linkSkuColumnNameToId = array(
        'related'   => Mage_Catalog_Model_Product_Link::LINK_TYPE_RELATED,
        'crosssell' => Mage_Catalog_Model_Product_Link::LINK_TYPE_CROSSSELL,
        'upsell'    => Mage_Catalog_Model_Product_Link::LINK_TYPE_UPSELL
    );
    /**************************************************************************************************/

    //Array contains a list of all fields being used.  This array is used in a foreach loop to create an array element (equal to null) for each attribute that does not exist
    //this prevents undefined index errors.
    private $_listRowDataFields = array(
        'category_id','sku','name','meta_title','meta_description','image','small_image','thumbnail','media_gallery','country_of_manufacture',
        'course_id','copyright_year','content_length','course_parts','primary_subject','professor_information','guidebook',
        'url_key','price','special_price','status','clearance_flag','media_format','free_streaming','coursedescription','short_description','meta_keyword',
        'product_name','categories','related','upsell','crosssell','associated','coursedescription','description','tax_class_id','store','_store','websites','_product_websites',
        '_root_category','config_attributes','store_id','product_type_id','weight','is_decimal_divided','has_transcript','digital_transcript_sku',
        'physical_transcript_sku','course_type_code','_super_products_sku','_super_attribute_code','_super_attribute_option','_custom_option_row_title','_custom_option_store',
        '_custom_option_row_sort','_custom_option_row_sku','_custom_option_row_price','_media_image','physical_transcript_price','digital_transcript_price',
        'visibility','publish_date','coursename','monthly_free_lecture_from','monthly_free_lecture_to','marketing_free_lecture_from','marketing_free_lecture_to','bibliography',
        'course_summary','recommended_links','recommended_links','partner','hero_headline','hero_description','hero_tab_text'
    );


    protected $_eventPrefix = 'course_import';
    protected $_websitesCache = array();
    protected $_entityTypeCode = 'course';
    private $_entityTable;
    private $_storesCache = array();
    protected $_attributesAddOptionIfNotExist = array('media_format');
    protected $_imagesAlreadyImportedByEntityId;
    protected $_imagesAlreadyImported;
    protected $existingProfoessorIds;
    protected $rowsWithInvalidProfessorInformationIds;
    protected $_rowsWithInvalidCharsetCharacters;
    protected $associatedProductData;
    protected $listAttributeSetsIdsAndNames;
    protected $_additionalBunchRows;
    protected $_attributesOverwriteData;
    protected $_attributesOverwriteDataInputTypesByCode;
    protected $_attributesOverwriteDataOptionsByCode;
    protected $_attributesProtectedOverwrittenByNulls;
    protected $_compositeAttributes = array(
      'list'    => array('set_members'),
    );

    public $skuToProductEntityId;


    //The value of each of these constants is a code that represents each different type of error message.
    //The constants below that begin with the prefix SET, only pertain to Sets.
    const INVALID_CATEGORY = 'categoryisinvalid';
    const EMPTY_MEDIA_FORMAT = 'emptymediaformat';
    const INVALID_WEBSITE = 'invalidwebsite';
    const INVALID_ASSOCIATED_SKU = 'invalidassociatedsku';
    const INVALID_OPTION_LABEL = 'invalidoptionlabel';
    const INVALID_OPTION_DUPLICATED = 'invalidduplicateoption';
    const INVALID_OPTION_ASSIGNED = 'invalidoptionassigned';
    const INVALID_ASSOCIATED_PRODUCT_TYPE = 'invalidassociatedproducttype';
    const INVALID_MEDIA_FORMAT_CHILD_PRODUCT = 'invalidmediaformatchild';
    const INVALID_CHARSET_DETECTED = 'charactersininvalidcharset';
    const EMPTY_DESCRIPTION = 'emptydescription';
    const EMPTY_MEDIA_FORMAT_CHILD_PRODUCT = 'emptymediaformatchildproduct';
    const INVALID_SKU_IDENTICAL = 'invalidskuidentical';
    const INVALID_PARENT_PRODUCT_NOT_IMPORTED = 'parentproductnotimported';
    const INVALID_BUNDLED_SKU = 'invalidbundledsku';
    const SET_INVALID_PRODUCT_TYPE = 'setinvalidproducttype';
    const SET_NO_ASSOCIATED_DATA = 'setnoassocaiteddata';
    const SET_NOT_ALL_PRODUCTS_CONFIGS = 'setnotallproductsconfig';
    const SET_NO_ASSOCIATED_PRODUCTS = 'setnoassociatedproducts';
    const SET_PARENT_NOT_LOADED = 'setparentnotloaded';
    const SET_INVALID_MEDIA_FORMAT = 'setinvalidmediaformat';
    const SET_COURSE_INVALID = 'setcontainsinvalidcourse';

    //Associates an error message with each error code.  This is the message that appears to the user when an error occurs.
    protected $_messageCourseTemplates = array(
        self::INVALID_CATEGORY          => 'A category this course is being assigned to does not exist.',
        self::INVALID_WEBSITE           =>    'The websites field contains an invalid value',
        self::EMPTY_MEDIA_FORMAT        => 'media_format is a required field. It is missing for this product',
        self::EMPTY_DESCRIPTION         => 'All description fields are blank.  One of the following fields must be filled in: coursedescription, description, or short_description',
        self::INVALID_ASSOCIATED_SKU    => 'A record attempted to associate a simple product whose sku did not exist with a configurable product',
        self::INVALID_OPTION_LABEL      => 'The record could not be imported because the configurable product does not have a label for the media_format field.',
        self::INVALID_OPTION_DUPLICATED => '[%s column is invalid] A child product could not be associated with a configurable product, because two or more children have the same media format, ',
        self::INVALID_OPTION_ASSIGNED   => '[%s column is invalid] A child product could not be associated with a configurable product, because the child products media format does not exist in magento, ',
        self::INVALID_ASSOCIATED_PRODUCT_TYPE   => '[%s column is invalid] A child product could not be associated with a configurable product, because the child is not a simple product, ',
        self::INVALID_SKU_IDENTICAL             => '[%s column is invalid] A child product could not be associated with a configurable product, because the same child is trying to be added two or more times, ',
        self::INVALID_MEDIA_FORMAT_CHILD_PRODUCT => '[%s column is invalid] A child product could not be associated with a configurable product, because the child has not yet been imported, ',
        self::EMPTY_MEDIA_FORMAT_CHILD_PRODUCT => '[%s column is invalid] A child product could not be associated with a configurable product, because the child does not have a media format',
        self::INVALID_PARENT_PRODUCT_NOT_IMPORTED => '[%s column is invalid] Bundled options could not be added to the product, because another error prevented the product from being created.',
        self::INVALID_BUNDLED_SKU               => '[%s column is invalid] Bundled options could not be added to the product, because the entity id is missing. Another error prevented the product from being created.',
        self::SET_INVALID_PRODUCT_TYPE          => '[%s column is invalid] The imported product has been imported previously.  This product is not a set and thus cannot be imported using this profile, ',
        self::SET_NO_ASSOCIATED_DATA            => '[%s column is invalid] The set could not be imported because no products have been associated with it.  Please fill in the associated column.',
        self::SET_NOT_ALL_PRODUCTS_CONFIGS      => '[%s column is invalid] This set contains one or more associated products that are not configurable.  Those invalid products are not associated with this set.',
        self::SET_NO_ASSOCIATED_PRODUCTS        => '[%s column is invalid] This set was associated with products that did not have any children.  Therefore, no products are associated with this set.',
        self::SET_PARENT_NOT_LOADED             => '[%s column is invalid] The entity_id of the set being imported could not be loaded, ',
        self::SET_INVALID_MEDIA_FORMAT          => '[%s column is invalid] Child products could not be added to the set, because the media format is invalid, ',
        self::SET_COURSE_INVALID                => 'The number within the \'%s\' field does not refer to an existing course',
    );

    public function __construct()
    {
        $this->setEntityTypeCode('catalog_product'); //temporarily setting entity type code to catalog product, parent constructor needs it to retrieve entityTypeId!
        parent::__construct();
        $this->setEntityTypeCode(self::ENTITY_TYPE_CODE); //entity type id not referenced in any significant way in this class or in any parent, therefore, okay to change!

        $this->existingProfoessorIds = $this->_connection->fetchCol("SELECT professor_id FROM professor");

        $this->listAttributeSetsIdsAndNames = $this->retrieveAttributesetNameToIdArray();

        $this->skuToProductEntityId = $this->_connection->fetchPairs("SELECT sku, entity_id FROM catalog_product_entity");

        $this->pullDataNeededForPreventingAttributesBeingOverwritten();

        $this->intializeAttributesNotBeOverwrittenByNulls();

        $this->_dataSourceModel->setEntityTypeCode($this->getEntityTypeCode());

        //Adding course related error messages to existing error messages.
        $this->_messageTemplates = array_merge(
            $this->_messageTemplates,
            $this->_messageCourseTemplates
        );
    }

    public function intializeAttributesNotBeOverwrittenByNulls()
    {
        $this->_attributesProtectedOverwrittenByNulls = array('professor','image','small_image','thumbnail');

        foreach($this->_attributesProtectedOverwrittenByNulls as $attributeCode) {
            if(!in_array($attributeCode, $this->_attributesNoOverwrite)) { //if already intialized, we don't need to initailize again. Both overwrite features in this module use same data.
                $this->generateArraySavedAttributeValues($attributeCode);
            }
        }
    }

    public function setEntityTypeCode($entityTypeCode)
    {
        $this->_entityTypeCode = $entityTypeCode;
    }

    public function getEntityTypeCode()
    {
        return $this->_entityTypeCode;
    }

    public function getStoreCodeMappings()
    {
        return $this->_storeCodeMappings;
    }

    public function getExistingRedirects()
    {
        return $this->_existingRedirects;
    }

    public function getCompositeAttributes()
    {
        return $this->_compositeAttributes;
    }

    public function getProfileAttributeSet()
    {
        return 'Courses';
    }

    protected function _initStores()
    {
        $storeCodeMappings = $this->getStoreCodeMappings();

        foreach (Mage::app()->getStores() as $store) {
            $storeCode = $store->getCode();
            $simplifiedStoreCode = array_search($storeCode, $storeCodeMappings);
            if($simplifiedStoreCode) {
                $this->_storeCodeToId[$simplifiedStoreCode] = $store->getId();
                $this->_storeIdToWebsiteStoreIds[$store->getId()] = $store->getWebsite()->getStoreIds();
            }
        }
        return $this;
    }

    /**
     * Returns a store object.  It is used to retrieve store id.
     *
     * @param $fakeCode
     * @return mixed
     * @throws InvalidArgumentException
     */
    protected function _getStoreByCode($fakeCode)
    {
        if (!isset($this->_storesCache[$fakeCode])) {
            $mappings = $this->getStoreCodeMappings();
            $code = $mappings[$fakeCode];

            $store = Mage::getModel('core/store')->load($code);
            if (!$store->getId()) {
                throw new InvalidArgumentException("Store $code does not exist.", self::ERROR_INVALID_WEBSITE);
            }
            $this->_storesCache[$fakeCode] = $store;
        }

        return $this->_storesCache[$fakeCode];
    }

    public function pullDataNeededForPreventingAttributesBeingOverwritten()
    {
        $attributesNoOverwriteData = $this->_attributesNoOverwrite;
        array_push($attributesNoOverwriteData, 'has_admin_changed'); //adding has_admin_changed to list columns to be queried.

        foreach($attributesNoOverwriteData as $attrCode) {
            $this->generateArraySavedAttributeValues($attrCode);
        }

        //websites must be generated seperately, because table structure is different than other attributes
        $this->generateArrayWebsiteAttributeValues();
    }

    /**
     * @param $rowData
     */
    public function filterRowData(&$rowData)
    {
        $this->_filterRowData($rowData);
    }

    /**
     * Used to format and derive number of different product related variables before saving a product.
     * @param array $rowData
     */
    protected function _filterRowData(&$rowData)
    {

        if(!isset($rowData['undefined_indexes_set'])) {
            $this->_setUndefinedIndexes($rowData);
        }

        foreach($this->_fieldsNeedTrimmed as $fieldNeedBeTrimmedName) {
            $rowData[$fieldNeedBeTrimmedName] = trim($rowData[$fieldNeedBeTrimmedName]);
        }

        // Exceptions - for sku - put them back in
        if (!isset($rowData[self::COL_SKU])) {
            $rowData[self::COL_SKU] = null;
        }

        $data = array();

        if($rowData[self::COL_DIGITAL_TRANSCRIPT_SKU] || $rowData[self::COL_PHYSICAL_TRANSCRIPT_SKU]) {
            $data['has_transcript'] = 1;
        } else {
            $data['has_transcript'] = 0;
        }

        $courseTypeCode = self::ENTITY_TYPE_CODE; //all products are considered to have type code of course unless they are a set!

        if($this->getEntityTypeCode() == self::ENTITY_TYPE_CODE) {
            if($rowData['associated']) {
                $productType = 'configurable';
            } else {
                $productType = 'simple';
            }
        } elseif($this->getEntityTypeCode() == Tgc_Dax_Model_Import_Entity_Set::ENTITY_TYPE_CODE) {
            if(is_numeric($rowData['sku'])) {
                $productType = 'configurable';
                if(self::SETS_PRODUCTS_STATUS_MARK_DISABLED) {
                    $rowData['status'] = 'Disabled'; //note , non-configurable products are set to 'Not Visible Individually', decided not disable those.
                }
            } else {
                $productType = 'simple';
                $data['set_members'] = $this->_formatSetMembers($rowData['associated']); //validation ensures that $rowData Associated must have a value, otherwise code would never reach this line.
                $rowData['associated'] = null; //the column associated for simple products, does not list children, if it is not set to null, magento try to add these products as children.
            }

            if($rowData['coursename']) {
                $rowData['name'] = $rowData['coursename']; //on the sets spreadsheet, the product's name is placed in the column called coursename.
            }
        }

        if(!$rowData['media_format']) {
            $rowData['media_format'] = $this->_deriveMediaFormat($rowData['sku'], $rowData['media_format']); // $rowData['media_format'] is always blank, function is passing value back by reference.
        }


        if($this->getProfileAttributeSet() == 'Sets') {
            $courseTypeCode = 'set';
            $data[self::COL_ATTR_SET] = $data['attribute_set'] = Tgc_Dax_Model_Import_Entity_Set::PROFILE_ATTRIBUTE_SET;
        } else {
            $data[self::COL_ATTR_SET] = $data['attribute_set'] = Tgc_Dax_Model_Import_Entity_Course::ATTRIBUTE_SET_NAME;
        }

        //Behavior append is only performed for FIRST Import, for all subsequent imports, primary_subject is ignored.
        if (Mage_ImportExport_Model_Import::BEHAVIOR_APPEND == $this->getBehavior()) {
            //Adds primary_subject to the categories column
            $categoryPrefix = '';
            if($rowData[self::COL_CATEGORIES]) {
                $categoryPrefix = ", ";
            }

            //Disabling feature that saves primary_subject as categories.
            /*
            if($rowData['primary_subject']) {
                $rowData[self::COL_CATEGORIES] = str_replace('  ','',$rowData[self::COL_CATEGORIES] . $categoryPrefix . $rowData['primary_subject']);
            } elseif($rowData['primary_subject']) {
                $rowData[self::COL_CATEGORIES] = str_replace('  ','',$rowData[self::COL_CATEGORIES] . $categoryPrefix . $rowData['primary_subject']);
            }
            */
        }

        //Evaluating Status
        if(trim($rowData['status']) == 'Enabled') {
            $data['status'] = Mage_Catalog_Model_Product_Status::STATUS_ENABLED;
        } elseif(trim($rowData['status']) == 'Disabled') {
            $data['status'] = Mage_Catalog_Model_Product_Status::STATUS_DISABLED;
        } else { //if a status is not specified, or not recognized, status set to enabled.
            $data['status'] = Mage_Catalog_Model_Product_Status::STATUS_ENABLED;
        }

        if(!$rowData['price']) {
            $data['price'] = 0.00;
        }

        $data[Mage_ImportExport_Model_Import_Entity_Product::COL_TYPE] = $data['type'] = $productType;

        $data['page_layout'] = '';
        $data['options_container'] = 'Block after Info Column';
        $data['msrp_enabled'] = 'Use config';
        $data['msrp_display_actual_price_type'] = 'Use config';
        $data['is_returnable'] = 'Use config';
        $data['use_config_min_qty'] = 1;
        $data['use_config_backorders'] = 1;
        $data['use_config_min_sale_qty'] = 1;
        $data['use_config_max_sale_qty'] = 1;
        $data['use_config_notify_stock_qty'] = 1;
        $data['use_config_manage_stock'] = 1;
        $data['use_config_qty_increments'] = 1;
        $data['use_config_enable_qty_inc'] = 1;
        $data['use_config_enable_qty_increments'] = 1;

        $description = null;
        if($rowData['description']) {
            $description = $rowData['description'];
        } elseif($rowData['coursedescription']) {
            $description = $rowData['coursedescription'];
        } elseif($rowData['short_description']) {
            $description = $rowData['short_description'];
        }

        $data['description'] = $description;
        $data['is_in_stock'] = true; //all products are in stock.
        $data['has_options'] = $data['type'] == 'configurable' ? 1 : 0;

        if(!$rowData['visibility']) {
            $data['visibility'] = $data['type'] == 'configurable' ? Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH : Mage_Catalog_Model_Product_Visibility::VISIBILITY_NOT_VISIBLE;
        }

        $data['tax_class_id'] = $rowData['tax_class_id'] == '' ? 2 : $rowData['tax_class_id']; //2 is equivalent to 'Taxable Goods', see table tax_class
        $data['_store'] =  $rowData['store'] == '' ? 'us' : $rowData['_store'];
        $data['websites'] = empty($rowData['websites']) ? 'base, uk, au' : $rowData['websites'];
        $data['_product_websites'] = empty($rowData['_product_websites']) ? $data['websites'] : $rowData['_product_websites'];
        $data['_root_category'] = 'Default Category';
        $data['config_attributes'] = $data['type'] == 'configurable' ? 'media_format' : '';
        $data['store_id'] = 0;
        $data['product_type_id'] = $data['type'];
        $data['weight'] = 1;
        $data['name'] = $rowData['name'] != '' ? strip_tags($rowData['name']) : '';
        $data['is_decimal_divided'] = 0;



        $data['course_type_code'] = $courseTypeCode;
        $data['associated_professors'] = $rowData['professor_information'];

        $rowData = array_merge($rowData, $data);
    }

    /**
     * If an attributes value is blank, it creates an element in the $rowData array with a value null.  This prevents undefined index notices.
     *
     * @param $rowData
     */
    protected function _setUndefinedIndexes(&$rowData)
    {
        $rowData = array_filter($rowData, 'strlen');

        foreach($this->_listRowDataFields as $listAllRowDataFieldsName) {
            if(!isset($rowData[$listAllRowDataFieldsName])) {
                $rowData[$listAllRowDataFieldsName] = null; //prevents undefined index errors occuring, by setting all undefined to null.
            }
        }

        $rowData['undefined_indexes_set'] = true;
    }

    /**
     * Is all of data valid?  This also displays a notice if professor information is not valid.
     *
     * @return bool
     */
    public function isDataValid()
    {
        $isDataValid = parent::isDataValid();

        $this->processNotices(); //this displays a notice if professor information is not valid.

        return $isDataValid;
    }

    /**
     * Displays notices when data is not valid.  This does not prevent any records from being imported.  All rows with notices are still imported.
     *
     */
    public function processNotices() {
        if(count($this->rowsWithInvalidProfessorInformationIds) > 0) {
            $stringListInvalidProfessorIds = implode(',', array_unique($this->rowsWithInvalidProfessorInformationIds));
            $this->_notices[] = "The professor_information field contains the following invalid professor_id's: " . $stringListInvalidProfessorIds . "<br />";
        }

        if(count($this->_rowsWithInvalidCharsetCharacters) > 0) {
            foreach($this->_rowsWithInvalidCharsetCharacters as $rowAttributeName => $rowLineNumberArray) {
                $rowLineNumberValsListed = null;
                foreach($rowLineNumberArray as $rowLineNumber) {
                    $rowLineNumberValsListed[] = $rowLineNumber;
                }

                $stringListInvalidCharsetRows = implode(',', array_unique($rowLineNumberValsListed));
                $this->_notices[] = "The $rowAttributeName field contains data from an invalid character set in rows: " . $stringListInvalidCharsetRows . "<br />";
            }
        }
    }

    /**
     * Performs validation
     *
     * @param array $rowData
     * @param int $rowNum
     * @return bool
     */
    public function validateRow(array $rowData, $rowNum)
    {
        $isRowValid = true;

        $this->_setUndefinedIndexes($rowData);

        $this->_filterRowData($rowData);

        $this->validateFieldsInvalidCharacterSets($rowData, $rowNum);

        $this->validateMediaFormat($rowData, $rowNum, $isRowValid); //this function must come after parent::validateRow, the parent calls filterRowData, which derives media_formats from the sku.

        $this->_isProfessorInformationFieldValid($rowData['associated_professors']);

        if(!self::IS_DISABLED_VALIDATION_DESCRIPTION) {
            if (Mage_ImportExport_Model_Import::BEHAVIOR_APPEND == $this->getBehavior()) {
                if(!$rowData['coursedescription'] && !$rowData['description'] && !$rowData['short_description']) {
                    $this->addRowError(self::EMPTY_DESCRIPTION, $rowNum);
                }
            }
        }

        $parentValidationResult = parent::validateRow($rowData, $rowNum);
        if(!$parentValidationResult) {
            $isRowValid = false;
        }

        return $isRowValid;
    }

    public function validateFieldsInvalidCharacterSets(&$rowData, $rowNum)
    {
        foreach($rowData as $rowDataKey => $rowDataValue) {
            if($rowDataValue) {
                if(!mb_detect_encoding($rowDataValue)) {
                    $this->_rowsWithInvalidCharsetCharacters[$rowDataKey][] = $rowNum;
                    $rowData[$rowDataKey] = null;
                }
            }
        }
    }

    /**
     * If professor field has been filled in, then it calls the retrieveValidProfessorsOnly function which stores invalid profesor ids to a class variable.  After validation code
     * processes, the processNotices function runs which prints out invalid professor ids in a notice.
     *
     * @param $associatedProfessors
     */
    protected function _isProfessorInformationFieldValid($associatedProfessors)
    {
        if(!$associatedProfessors) {
            //this field is not required, if nothing is entered, it is valid, therefore nothing is done here.
        } elseif($associatedProfessors) {
            //at least one of the professor ids needs to correspond to a professor who exists in the database, if none of the ids correspond, then it is invalid
            $this->retrieveValidProfessorsOnly($associatedProfessors);
        }
    }

    /**
     * This function is used duration validation to determine what rows contain invalid professor data.  It
     * It is also used during saving, if a field has at least one valid professor, it will discard the invalid professors ids, and will ensure valid ones are returned.
     * the valid professors that are returned will be saved.
     *
     * @param $associatedProfessors
     * @return array
     */
    public function retrieveValidProfessorsOnly($associatedProfessors)
    {
        $associatedProfessorsArray = $this->userCSVDataAsArray($associatedProfessors);
        $validProfessorIds = array();

        if(count($associatedProfessorsArray) > 0) {
            foreach($associatedProfessorsArray as $associatedProfessorId) {
                if(in_array($associatedProfessorId, $this->existingProfoessorIds)) {
                    $validProfessorIds[] = $associatedProfessorId;
                } else {
                    $this->rowsWithInvalidProfessorInformationIds[] = $associatedProfessorId; //keeps a list of invliad professor ids, these are displayed in a notice.
                }
            }
        }

        return $validProfessorIds;
    }

    /**
     * Ferrets out invalid professor_ids and returns
     *
     * @param $associatedIds
     * @param $professorInformation
     */
    protected function _setProfessorInformation($associatedIds, &$professorInformation)
    {
        if($associatedIds) {
            $validIds = $this->retrieveValidProfessorsOnly($associatedIds);
            $professorInformation = implode(',', $validIds);
        }
    }

    protected function setWebsitesCache()
    {
        if(!$this->_websitesCache) {
            $websites = Mage::app()->getWebsites();
            foreach($websites as $website) {
                $this->_websitesCache[$website->getCode()] = $website->getId();
            }
            $this->_websitesCache['us'] = 1;
        }
    }

    /**
     * If during an import, spreadsheet data for a select or multiselect field contains an option that does not exist in
     * magento database (eav_attribute_option and eav_attribute_option_value), this function automatically add that new option to the database.
     *
     * @param string $attrCode
     * @param array $attrParams
     * @param array $rowData
     * @param int $rowNum
     * @return bool
     */
    public function isAttributeValid($attrCode, array $attrParams, array $rowData, $rowNum)
    {
        if(!self::IS_DISABLED_ATTRIBUTE_ADD_OPTION_IFNOT_EXIST) {
            if(in_array($attrCode, $this->_attributesAddOptionIfNotExist)) {
                if(($attrParams['type'] == 'select' || $attrParams['type'] == 'multiselect') && $rowData[self::COL_TYPE] == 'simple') {
                    $valid = isset($attrParams['options'][strtolower($rowData[$attrCode])]);
                    if(!$valid) {
                        $adapter            =   $this->_connection;
                        $coreResource       =   Mage::getSingleton('core/resource');
                        $optionTable        =   $coreResource->getTableName('eav/attribute_option');
                        $optionValueTable   =   $coreResource->getTableName('eav/attribute_option_value');

                        $attributeOption = array(
                            'attribute_id' => $attrParams['id'],
                            'sort_order'    => 100, //any custom value assigned to be last.
                        );

                        $adapter->insertOnDuplicate(
                            $optionTable,
                            $attributeOption,
                            array('attribute_id', 'sort_order')
                        );

                        $intOptionId = $adapter->lastInsertId($optionTable);

                        $attributeOptionValue = array(
                            'option_id' => $intOptionId,
                            'store_id'    => 0, //store value set to 0, that way shows in all stores.
                            'value'     => $rowData[$attrCode],
                        );

                        $adapter->insertOnDuplicate(
                            $optionValueTable,
                            $attributeOptionValue,
                            array('option_id', 'store_id','value')
                        );

                        $attrValue = strtolower($rowData[$attrCode]);
                        //first two lines add new option to list, this will allow other records to recognize this option, third line makes current record recognize this option.
                        $this->_productTypeModels['simple']->addNewCustomAttributeOptions($attrCode, $attrValue, $intOptionId);
                        $attrParams['options'][strtolower($rowData[$attrCode])] = $intOptionId;
                    }
                }
            }
        }

        return parent::isAttributeValid($attrCode, $attrParams, $rowData, $rowNum);
    }

    /**
     * Overwrites default magento _isProductWebsiteValid function so that the websites field in spreadsheet can contain multiple website ids, seperated by a comma.
     *
     * @param array $rowData
     * @param int $rowNum
     * @return bool
     */
    protected function _isProductWebsiteValid(array $rowData, $rowNum)
    {
        $this->setWebsitesCache();

        if (!empty($rowData['_product_websites'])) { // 2. Product-to-Website phase
            $websitesCodesAssociatedWithProduct = $this->userCSVDataAsArray($rowData['websites']);

            foreach($websitesCodesAssociatedWithProduct as $websiteCodeAssociatedWithProduct) {
                if(!isset($this->_websitesCache[$websiteCodeAssociatedWithProduct])) {
                    $this->addRowError(self::INVALID_WEBSITE, $rowNum);
                    return false;
                }

                if(!$this->_websitesCache[$websiteCodeAssociatedWithProduct]) {
                    $this->addRowError(self::INVALID_WEBSITE, $rowNum);
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Check product category validity. Overwrites default magento function so that multiple categories can be added to a single field, sperated by a comma.
     * This function runs during validation and will throw an error, if invalid category entered. However, we have relaxed this validation so that error is never thrown.
     *
     * @param array $rowData
     * @param int $rowNum
     * @return bool
     */
    protected function _isProductCategoryValid(array $rowData, $rowNum)
    {
        if($rowData[self::COL_CATEGORIES]) {
            $allCategories = $this->userCSVDataAsArray($rowData[self::COL_CATEGORIES]);
            foreach($allCategories as $category) {
                $this->_isIndividualCategoryValid($category, $rowNum);
            }
        }
    }

    /**
     * Determines if an indivdiual category is valid.  Since multiple categories can be entered into a field, seperated by a comma, the function below
     * is used to determine if each of category entered is valid.
     *
     * @param string $category
     * @param $rowNum
     * @return bool
     */
    protected function _isIndividualCategoryValid($category = '', $rowNum)
    {
        $categoryIsValid = false;
        if($category) {
            if(isset($this->_categories[trim($category)])) {
                $categoryId = $this->_categories[trim($category)];
                if($categoryId) {
                    $categoryIsValid = true;
                }
            }
            if(!$categoryIsValid) {
                if(!self::IS_DISABLED_IMPORT_CATEGORY) {
                    $this->addRowError(self::INVALID_CATEGORY, $rowNum);
                }
            }
        }

        return $categoryIsValid;
    }

    /**
     * Gather and save information about product entities.
     *
     * @return Mage_ImportExport_Model_Import_Entity_Product
     */
    protected function _saveProducts()
    {
        $priceIsGlobal  = Mage::helper('catalog')->isPriceGlobal();
        $productLimit   = null;
        $productsQty    = null;
        $this->generateListAlreadyImportedImages(); //populates two arrays used to determine if an image has already been uploaded.

        $connection = $this->getConnection();
        $query = 'SELECT attribute_id FROM eav_attribute WHERE attribute_code = "' . self::MEDIA_GALLERY_ATTRIBUTE_CODE . '"';
        $result = $connection->fetchCol($query);
        $mediaGalleryAttributeId = !empty($result) ? $result[0] : '';

        while ($bunch = $this->_dataSourceModel->getNextBunch()) {
            $entityRowsIn = array();
            $entityRowsUp = array();
            $attributes   = array();
            $websites     = array();
            $categories   = array();
            $tierPrices   = array();
            $groupPrices  = array();
            $mediaGallery = array();
            $uploadedGalleryFiles = array();
            $previousType = null;
            $previousAttributeSet = null;
            $oldSku = $this->getOldSku();
            $newSku = $this->getNewSku();
            $allSku = array_merge($oldSku, $newSku);

            foreach ($bunch as $rowNum => &$rowData) {
                    $this->_filterRowData($rowData);

                    if (!$this->validateRow($rowData, $rowNum)) {
                        continue;
                    }

                    $rowScope = $this->getRowScope($rowData);

                    if (self::SCOPE_DEFAULT == $rowScope) {
                        $rowSku = $rowData[self::COL_SKU];

                        // 1. Entity phase
                        if (isset($this->_oldSku[$rowSku])) { // existing row
                            $entityRowsUp[] = array(
                                'updated_at' => now(),
                                'entity_id'  => $this->_oldSku[$rowSku]['entity_id']
                            );
                            unset($rowData['price']);
                            unset($rowData['special_price']);
                        } else { // new row
                            if (!$productLimit || $productsQty < $productLimit) {
                                $entityRowsIn[$rowSku] = array(
                                    'entity_type_id'   => $this->_entityTypeId,
                                    'attribute_set_id' => $this->_newSku[$rowSku]['attr_set_id'],
                                    'type_id'          => $this->_newSku[$rowSku]['type_id'],
                                    'sku'              => $rowSku,
                                    'created_at'       => now(),
                                    'updated_at'       => now()
                                );

                                $productsQty++;
                            } else {
                                $rowSku = null; // sign for child rows to be skipped
                                $this->_rowsToSkip[$rowNum] = true;
                                continue;
                            }
                        }
                    } elseif (null === $rowSku) {
                        $this->_rowsToSkip[$rowNum] = true;
                        continue; // skip rows when SKU is NULL
                    } elseif (self::SCOPE_STORE == $rowScope) { // set necessary data from SCOPE_DEFAULT row
                        $rowData[self::COL_TYPE]     = $this->_newSku[$rowSku]['type_id'];
                        $rowData['attribute_set_id'] = $this->_newSku[$rowSku]['attr_set_id'];
                        $rowData[self::COL_ATTR_SET] = $this->_newSku[$rowSku]['attr_set_code'];
                    }

                    $this->processCategories($rowData, $categories, $rowSku, $rowNum);

                    $websiteAlreadyExists = $this->hasWebsiteAttributeValue($rowSku);
                    if (!empty($rowData['_product_websites']) && !$websiteAlreadyExists) { // 2. Product-to-Website phase, website ONLY saves first time product is imported!
                        $websitesCodesAssociatedWithProduct = $this->userCSVDataAsArray($rowData['websites']);

                        foreach($websitesCodesAssociatedWithProduct as $websiteCodeAssociatedWithProduct) {
                            $websiteIdRequested = $this->_websitesCache[$websiteCodeAssociatedWithProduct];
                            if($websiteIdRequested) {
                                $websites[$rowSku][$websiteIdRequested] = true;
                            }
                        }
                    }

                    if (!empty($rowData['_tier_price_website'])) { // 4.1. Tier prices phase
                        $tierPrices[$rowSku][] = array(
                            'all_groups'        => $rowData['_tier_price_customer_group'] == self::VALUE_ALL,
                            'customer_group_id' => ($rowData['_tier_price_customer_group'] == self::VALUE_ALL)
                                ? 0 : $rowData['_tier_price_customer_group'],
                            'qty'               => $rowData['_tier_price_qty'],
                            'value'             => $rowData['_tier_price_price'],
                            'website_id'        => (self::VALUE_ALL == $rowData['_tier_price_website'] || $priceIsGlobal)
                                ? 0 : $this->_websiteCodeToId[$rowData['_tier_price_website']]
                        );
                    }
                    if (!empty($rowData['_group_price_website'])) { // 4.2. Group prices phase
                        $groupPrices[$rowSku][] = array(
                            'all_groups'        => $rowData['_group_price_customer_group'] == self::VALUE_ALL,
                            'customer_group_id' => ($rowData['_group_price_customer_group'] == self::VALUE_ALL)
                                ? 0 : $rowData['_group_price_customer_group'],
                            'value'             => $rowData['_group_price_price'],
                            'website_id'        => (self::VALUE_ALL == $rowData['_group_price_website'] || $priceIsGlobal)
                                ? 0 : $this->_websiteCodeToId[$rowData['_group_price_website']]
                        );
                    }

                    $imageUploadDatas = array();
                    $imagesList = array();
                    if(!self::IS_DISABLED_IMPORT_IMAGES) {
                        /**
                         * if statement below, and foreach are responsible for formatting the media_gallery field correctly.
                         * the media_gallery field is also already in exact format and has same data as _media_image field.
                         * Therefore, media_gallery is copied to _media_image
                         */

                        if($rowData[self::COL_MEDIA_GALLERY]) {
                            $rowData[self::COL_MEDIA_IMAGE] = $rowData[self::COL_MEDIA_GALLERY];
                            if(strpos($rowData[self::COL_MEDIA_GALLERY], self::ROW_DELIMITER)) {
                                $mediaGalleryImages = $this->userCSVDataAsArray($rowData[self::COL_MEDIA_GALLERY]);
                            } else {
                                $mediaGalleryImages[] = $rowData[self::COL_MEDIA_GALLERY];
                            }

                            foreach($mediaGalleryImages as $mediaGalleryImagesKey => $mediaGalleryImageItem) {
                                $mediaGalleryImageItem = strtolower($mediaGalleryImageItem);
                                if(substr($mediaGalleryImageItem, 0,1) != "/") {
                                    $mediaGalleryImages[$mediaGalleryImagesKey] = "/" . $mediaGalleryImageItem;
                                }
                            }

                            $rowData[self::COL_MEDIA_GALLERY] = implode(self::ROW_DELIMITER,$mediaGalleryImages);
                        }

                        if($rowData[self::COL_MEDIA_IMAGE] && strpos($rowData[self::COL_MEDIA_IMAGE], self::ROW_DELIMITER) > 0) {
                            foreach($this->_imagesArrayKeys as $imageCol) {
                                $mediaImages = $this->userCSVDataAsArray($rowData[$imageCol]);
                                $imageUploadDatasCounter = 0;
                                if($mediaImages && count($mediaImages) > 0) {
                                    foreach($mediaImages as $mediaImageUpload) {
                                        $imageUploadDatas[$imageUploadDatasCounter][$imageCol] = $mediaImageUpload;
                                        $imageUploadDatasCounter++;
                                    }
                                }
                            }
                        } else {
                            $imageUploadDatas[] = $rowData;
                        }

                        $entityId = false;
                        if(isset($allSku[$rowSku]['entity_id'])) {
                            $entityId = $allSku[$rowSku]['entity_id'];
                        }
                    }

                    foreach($imageUploadDatas as $imageUploadData) {
                        foreach ($this->_imagesArrayKeys as $imageCol) {
                            if (!empty($imageUploadData[$imageCol])) { // 5. Media gallery phase
                                $formattedImageFilename = $this->_deriveFilename($imageUploadData[$imageCol]);
                                if(!in_array($formattedImageFilename, $this->_imagesAlreadyImported)) { //prevents images, imported at an earlier date, from being imported more than once.
                                    if (!array_key_exists($imageUploadData[$imageCol], $uploadedGalleryFiles)) { //prevents an image on spreadsheet currently being imported, from being uploaded more than once.
                                        $uploadedGalleryFiles[$imageUploadData[$imageCol]] = $this->_uploadMediaFiles($imageUploadData[$imageCol]);
                                    }
                                    $rowData[$imageCol] = $uploadedGalleryFiles[$imageUploadData[$imageCol]];
                                } else {
                                    $rowData[$imageCol] = $formattedImageFilename; //clause executes if images has already been uploaded, prevents file from being uploaded more than once.
                                }


                                if($imageCol == self::COL_MEDIA_IMAGE) {
                                    $importImage = false;
                                    if(!$entityId) {
                                        $importImage = true;
                                    }

                                    if(isset($rowData[$imageCol]) && isset($this->_imagesAlreadyImportedByEntityId[$entityId])) {
                                        //making sure image has not yet been imported for a product.
                                        if(!in_array($rowData[$imageCol], $this->_imagesAlreadyImportedByEntityId[$entityId])) {
                                            $importImage = true;
                                        }
                                    } elseif(isset($rowData[$imageCol]) && !isset($this->_imagesAlreadyImportedByEntityId[$entityId])) {
                                        //this condition runs if no images have yet to be associated with a product.
                                        $importImage = true;
                                    }


                                    if($importImage) {
                                        $imagesList[] = $rowData[$imageCol];
                                    }
                                }
                            }
                        }
                    }

                    $imagesList = array_unique($imagesList);

                    if($imagesList && count($imagesList ) > 0) {
                        $positionOfImage = 0;
                        foreach($imagesList  as $imageListItem ) {
                            $mediaGallery[$rowSku][] = array(
                                'attribute_id'      => $mediaGalleryAttributeId,
                                'label'             => '',
                                'position'          => $positionOfImage,
                                'disabled'          => 0,
                                'value'             => $imageListItem
                            );
                            $positionOfImage++;
                        }
                    }

                    /* The following if statement would never be run, even if it was not commented out
                       This code is shown to illustrate that their are a few fields on the spreadsheet, that are no longer used, they are automatically set in $mediaGallery variable above.
                        if (!empty($rowData['_media_image'])) {
                            $mediaGallery[$rowSku][] = array(
                                'attribute_id'      => $rowData['_media_attribute_id'],
                                'label'             => $rowData['_media_lable'],
                                'position'          => $rowData['_media_position'],
                                'disabled'          => $rowData['_media_is_disabled'],
                                'value'             => $rowData['_media_image']
                            );
                        }
                    */

                    //Collecting Composite Attributes - some configurable attributes are composed of attribute values taken from children simple products.
                    $this->collectCompositeAttributes($rowData, $rowSku);

                    // 6. Attributes phase
                    //if a user enters data in magento admin, this will be overwritten during an import, unless variable is specified in the _attributesNoOverwrite variable
                    $this->preventNullsFromOverwrittingProtectedFields($rowData, $rowSku); //Note, this function must come before one called in next line, otherwise there will be a conflict.
                    $this->preventAttributesBeingOverwritten($rowData, $rowSku);
                    $associatedProfessors = $rowData['associated_professors'];

                    $rowStore     = self::SCOPE_STORE == $rowScope ? $this->_storeCodeToId[$rowData[self::COL_STORE]] : 0;
                    $productType  = isset($rowData[self::COL_TYPE]) ? $rowData[self::COL_TYPE] : null;
                    if (!is_null($productType)) {
                        $previousType = $productType;
                    }
                    if (!is_null($rowData[self::COL_ATTR_SET])) {
                        $previousAttributeSet = $rowData[Mage_ImportExport_Model_Import_Entity_Product::COL_ATTR_SET];
                    }
                    if (self::SCOPE_NULL == $rowScope) {
                        // for multiselect attributes only
                        if (!is_null($previousAttributeSet)) {
                            $rowData[Mage_ImportExport_Model_Import_Entity_Product::COL_ATTR_SET] = $previousAttributeSet;
                        }
                        if (is_null($productType) && !is_null($previousType)) {
                            $productType = $previousType;
                        }
                        if (is_null($productType)) {
                            continue;
                        }
                    }
                    $rowData = $this->_productTypeModels[$productType]->prepareAttributesForSave(
                        $rowData,
                        !isset($this->_oldSku[$rowSku])
                    );

                    /*
                    _setProfessorInformation must come after prepareAttributesforSave, prepareAttributes for Save checks to see if data is valid.
                    However, prepareAttributes for save cannot accept more than one value for a multiselect.  therefore, data for this attribute must be added manually after prepareAttributeForSave
                    therefore, the necessary processing and validation, for this one attribute, is performed here.
                    */
                    $this->_setProfessorInformation($associatedProfessors, $rowData['professor']);
                    if(!$rowData['professor']) {
                       unset($rowData['professor']); //a blank value will be added to the database unless this array element is unset.
                    }

                    try {
                        $attributes = $this->_prepareAttributesCustom($rowData, $rowScope, $attributes, $rowSku, $rowStore, $productType);
                    } catch (Exception $e) {
                        Mage::logException($e);
                        continue;
                    }
            }


            $this->_saveProductEntity($entityRowsIn, $entityRowsUp)
                ->_saveProductWebsites($websites)
                ->_saveProductCategories($categories)
                ->_saveProductTierPrices($tierPrices)
                ->_saveProductGroupPrices($groupPrices)
                ->_saveMediaGallery($mediaGallery)
                ->_saveProductAttributes($attributes);
        }

        $this->_fixUrlKeys();
        return $this;
    }


    protected function retrieveAttributesetNameToIdArray()
    {
        $select = $this->_connection->select()
            ->from('eav_attribute_set', array('attribute_set_name','attribute_set_id'))
            ->where('entity_type_id = :entity_type_id');

        $attributeSetsList = $this->_connection->fetchPairs($select, array('entity_type_id' => 4));

        return $attributeSetsList;
    }


    /**
     * Prepare attributes data.  Ovewrwritten so that modifications could be changed to how urlKey is saved so that configurable products would have clean urls.
     *
     * @param array $rowData
     * @param int $rowScope
     * @param array $attributes
     * @param string|null $rowSku
     * @param int $rowStore
     * @return array
     */
    protected function _prepareAttributesCustom($rowData, $rowScope, $attributes, $rowSku, $rowStore, $rowProductType)
    {
        $rowData = $this->_prepareUrlKeyCustom($rowData, $rowScope, $rowSku, $rowProductType);
        return Mage_ImportExport_Model_Import_Entity_Product::_prepareAttributes($rowData, $rowScope, $attributes, $rowSku, $rowStore);
    }

    /**
     * If a user enters data in the magento admin, this will be overwritten during an import, unless that variable is specified in the _attributesNoOverwrite variable.
     *
     * @param $rowData
     * @param $rowSku
     */
    public function preventAttributesBeingOverwritten(&$rowData, $rowSku)
    {
        if(isset($this->skuToProductEntityId[$rowSku])) { //checks to see if product has previously been saved. If not, no need to determine if overwritten.
            $entityId = $this->skuToProductEntityId[$rowSku];
            if(isset($this->_attributesOverwriteData['has_admin_changed'][$entityId])) { //the only need to check if a product has been overwritten, is if an administrator has changed product information at some point in time.
                if($this->_attributesOverwriteData['has_admin_changed'][$entityId]) {
                    foreach($this->_attributesNoOverwrite as $attrCode) {
                        if(!$rowData[$attrCode]) {
                            continue; //if value does not exist, continue.
                        }

                        $attributeValue = null;
                        if(isset($this->_attributesOverwriteData[$attrCode][$entityId])) {
                            $attributeValue = $this->_attributesOverwriteData[$attrCode][$entityId];
                        }

                        if($attributeValue) {
                            //convertAttributeValueToOptionValue only changes value to the option text if it is a multislect or select, otherwise it just returns the original attribute value.
                            $attributeValue = $this->convertAttributeValueToOptionValue($attrCode, $attributeValue);
                            $rowData[$attrCode] = $attributeValue;
                        }
                    }
                }
            }
        }
    }

    /**
     * If a value exists in a previous import, and another spreadsheet is used with null fields, the nulls will overwrite existin fields.
     * This protects against this.
     *
     * @param $rowData
     * @param $rowSku
     */
    public function preventNullsFromOverwrittingProtectedFields(&$rowData, $rowSku)
    {
        if(isset($this->skuToProductEntityId[$rowSku])) { //checks to see if product has previously been saved. If not, no need to determine if overwritten.
            $entityId = $this->skuToProductEntityId[$rowSku];
            if($entityId) {
                foreach($this->_attributesProtectedOverwrittenByNulls as $attrCode) {
                    if(isset($rowData[$attrCode]) && $rowData[$attrCode]) {
                        continue; //We are protecting against nulls overwriting existing data, since value is not null, don't need to worry.
                    }

                    if($attrCode == 'professor' && isset($rowData['professor_information']) && $rowData['professor_information']) {
                            continue; //professor maps differently, which is reason why this is needed.
                    }

                    $attributeValue = null;
                    if(isset($this->_attributesOverwriteData[$attrCode][$entityId])) {
                        $attributeValue = $this->_attributesOverwriteData[$attrCode][$entityId];
                    }

                    if($attributeValue) {
                        if($attrCode == 'professor') {
                            $attrCode = 'professor_information'; //professor maps differnetly than others, it is first stored in $rowData[professor_information] before being transferred to $rowData[professor].  Otherwise magento overwrites it every time.
                        }
                        //convertAttributeValueToOptionValue only changes value to the option text if it is a multislect or select, otherwise it just returns the original attribute value.
                        $attributeValue = $this->convertAttributeValueToOptionValue($attrCode, $attributeValue);
                        $rowData[$attrCode] = $attributeValue;
                    }
                }
            }
        }
    }

    public function generateArraySavedAttributeValues($attrCode)
    {
        if(!$attrCode instanceof Mage_Eav_Model_Entity_Attribute) {
            $attribute = $this->_getAttribute($attrCode);
        } else {
            $attribute = $attrCode;
        }

        $table = $attribute->getBackend()->getTable();
        $attributeId = $attribute->getId();
        $attributeCode = $attribute->getAttributeCode();
        $frontendInput = $attribute->getFrontendInput();
        $this->_attributesOverwriteDataInputTypesByCode[$attrCode] = $attribute->getFrontendInput();
        $this->_attributesOverwriteData[$attributeCode] = array();

        if($frontendInput == 'select' || $frontendInput == 'multiselect') {
            $this->_attributesOverwriteDataOptionsByCode[$attributeCode] = $this->getAttributeOptions($attribute);
        }

        if($table && $attributeId) {
            $selectAttributeValue = $this->_connection->select()
                ->from($table, array('entity_id','value'))
                ->where('attribute_id = :attribute_id')
                ->where('value IS NOT NULL');

            $result = $this->_connection->fetchPairs($selectAttributeValue, array('attribute_id'=> $attributeId));
            if(count($result) > 0) {
                $this->_attributesOverwriteData[$attributeCode] = $result;
            }
        }
    }

    public function getHasAttributeValueChanged($attrCode, $rowSku)
    {
        $hasAdminChanged = false;
        if(isset($this->skuToProductEntityId[$rowSku])) { //checks to see if product has previously been saved. If not, no need to determine if overwritten.
            $entityId = $this->skuToProductEntityId[$rowSku];
            if(isset($this->_attributesOverwriteData[$attrCode][$entityId])) { //the only need to check if a product has been overwritten, is if an administrator has changed product information at some point in time.
                $hasAdminChanged = $this->_attributesOverwriteData[$attrCode][$entityId];
            }
        }

        return $hasAdminChanged;
    }

    public function getHasAdminChanged($rowSku)
    {
        return $this->getHasAttributeValueChanged('has_admin_changed', $rowSku);
    }

    public function hasWebsiteAttributeValue($rowSku)
    {
        $websiteAttributeValue = $this->getHasAttributeValueChanged('websites', $rowSku);
        //if exists, it will always be greater than zero, because it always returns product id (entity_id) when true, and product id always greater than zero.
        $hasWebsiteAttributeValue = $websiteAttributeValue ? true : false;
        return $hasWebsiteAttributeValue;
    }

    public function generateArrayWebsiteAttributeValues()
    {
        //Note: this query does not need to determine all of websites associated with each product.
        //It only needs to determine if a product is associated with any websites.

        $selectWebsiteAttributeValue = $this->_connection->select()
            ->from('catalog_product_website', array('product_id'));

        $this->_attributesOverwriteData['websites'] = array();

        $result = $this->_connection->fetchCol($selectWebsiteAttributeValue);

        if(count($result) > 0) {
            $this->_attributesOverwriteData['websites'] = $result;
        }
    }

    public function convertAttributeValueToOptionValue($attrCode, $attributeValue)
    {
        //Only converts attribute value to the option text if type is select or multiselect.
        if(isset($this->_attributesOverwriteDataInputTypesByCode[$attrCode]) &&
           isset($this->_attributesOverwriteDataInputTypesByCode[$attrCode])) {
            if($this->_attributesOverwriteDataInputTypesByCode[$attrCode] == 'select'
                || $this->_attributesOverwriteDataInputTypesByCode[$attrCode] == 'multiselect'
                && $attrCode !='professor' && $attrCode != 'professor_information') { //Note professor is handled differently from other attributes.
                //Sometimes a user will put the actual value in the database into the spreadsheet, magento needs the option label, in order to save.  This finds the option label.
                $options = $this->_attributesOverwriteDataOptionsByCode[$attrCode];
                if($options) {
                    $attributeValue = is_numeric($attributeValue) ? (int) $attributeValue : $attributeValue; //numbers won't match in array_search strict unless they are cast.
                    $newValue = array_search($attributeValue, $options, true);
                    $attributeValue = $newValue ? $newValue : $attributeValue; //if the value is not found, it just tries with value on the spreadsheet.
                }
            }
        }

        return $attributeValue;
    }

    /**
     * Converts a string seperated by commas into an array.
     *
     * @param $data
     * @return array
     */
    public function userCSVDataAsArray($data)
    {
        $count_quotes = substr_count($data, self::QUOTE_DELIMETER);
        $arrayValues = array();
        if($count_quotes > 0 && $count_quotes % 2 == 0) {
            //if statement only executes if number quotes string is divisble by 2, an string with an odd number cannot be parsed!
            preg_match_all('/"([^"]+)"/', $data, $matches);
            $arrayValues = $matches[0];
            $this->userCSVDataFormatArray($arrayValues);
        } elseif($count_quotes == 0) {
            $arrayValues = explode(',', trim($data));
            $this->userCSVDataFormatArray($arrayValues);
        } else {
            //error statement saying number fields incorrect.
        }

        return $arrayValues;
    }

    public function userCSVDataFormatArray(&$arrayValues) {
        foreach($arrayValues as $arrayValueKey => $arrayValue) {
            $arrayValues[trim($arrayValueKey)] = trim(trim($arrayValue), '"');
        }
    }

    /**
     * @param $arrayValues
     */
    public function userCSVDataProcessUnformmatedField(&$arrayValues)
    {
        foreach($arrayValues as $key => $value) {
            $arrayValues[trim($key)] = trim($value);
        }
        array_filter($arrayValues);
    }

    /**
     * By default magento not allow categories to be entered seperated by comas.  The code in this importer alters magento functionality to
     * allow categories to be entered seperated by a comma.  This function is designed to performs validation for a comma seperated categories field.
     *
     * @param $rowData
     * @param $categories
     * @param $sku
     * @param $rowNum
     */
    private function processCategories($rowData, &$categories, $sku, $rowNum)
    {
        if(!self::IS_DISABLED_IMPORT_CATEGORY) {
            if($rowData[self::COL_CATEGORIES]) {
                $allCategories = $this->userCSVDataAsArray($rowData[self::COL_CATEGORIES]);
                foreach($allCategories as $category) {
                    $isCategoryValid = $this->_isIndividualCategoryValid($category, $rowNum);
                    if($isCategoryValid) {
                        $categoryId = $this->_categories[trim($category)];
                        $categories[$sku][$categoryId] = true;
                    }
                }
            }
        }
    }

    /**
     * Gather and save information about product links.  This function differs from the _saveLinks function in Mage_ImportExport_Model_Import_Entity_Product
     * By default, Magento requires a new line to be entered on the spreadsheet if there is more than one related, upsell, or crossell.
     * This function is different because it allows the user to enter all related, upsell, or crosssell products on ONE line, sperated by a comma, and will save them.
     * Must be called after ALL products saving done.
     *
     * @return Mage_ImportExport_Model_Import_Entity_Product
     */
    protected function _saveLinks()
    {
        $resource       = Mage::getResourceModel('catalog/product_link');
        $mainTable      = $resource->getMainTable();
        $positionAttrId = array();
        /** @var Varien_Db_Adapter_Interface $adapter */
        $adapter = $this->_connection;

        // pre-load 'position' attributes ID for each link type once
        foreach ($this->_linkSkuColumnNameToId as $linkName => $linkId) {
            $select = $adapter->select()
                ->from(
                    $resource->getTable('catalog/product_link_attribute'),
                    array('id' => 'product_link_attribute_id')
                )
                ->where('link_type_id = :link_id AND product_link_attribute_code = :position');
            $bind = array(
                ':link_id' => $linkId,
                ':position' => 'position'
            );
            $positionAttrId[$linkId] = $adapter->fetchOne($select, $bind);
        }
        $nextLinkId = Mage::getResourceHelper('importexport')->getNextAutoincrement($mainTable);
        while ($bunch = $this->_dataSourceModel->getNextBunch()) {
            $productIds   = array();
            $linkRows     = array();
            $positionRows = array();

            foreach ($bunch as $rowNum => $rowData) {
                $this->_filterRowData($rowData);
                if (!$this->isRowAllowedToImport($rowData, $rowNum)) {
                    continue;
                }
                if (self::SCOPE_DEFAULT == $this->getRowScope($rowData)) {
                    $sku = $rowData[self::COL_SKU];
                }

                $productId    = $this->_newSku[$sku]['entity_id'];
                $productIds[] = $productId;

                foreach ($this->_linkSkuColumnNameToId as $linkName => $linkId) {
                    if (isset($rowData[$linkName])) {
                            $allLinkSkus = $this->userCSVDataAsArray($rowData[$linkName]);

                        foreach($allLinkSkus as $linkSkuCustom) {
                            if ((isset($this->_newSku[$linkSkuCustom]) || isset($this->_oldSku[$linkSkuCustom]))
                                && $linkSkuCustom != $sku) {

                                if (isset($this->_newSku[$linkSkuCustom])) {
                                    $linkedId = $this->_newSku[$linkSkuCustom]['entity_id'];
                                } else {
                                    $linkedId = $this->_oldSku[$linkSkuCustom]['entity_id'];
                                }

                                $linkKey = "{$productId}-{$linkedId}-{$linkId}";

                                if (!isset($linkRows[$linkKey])) {
                                    $linkRows[$linkKey] = array(
                                        'link_id'           => $nextLinkId,
                                        'product_id'        => $productId,
                                        'linked_product_id' => $linkedId,
                                        'link_type_id'      => $linkId
                                    );
                                    if (!empty($rowData[$linkName . '_position'])) {
                                        $positionRows[] = array(
                                            'link_id'                   => $nextLinkId,
                                            'product_link_attribute_id' => $positionAttrId[$linkId],
                                            'value'                     => $rowData[$linkName . '_position']
                                        );
                                    }
                                    $nextLinkId++;
                                }
                            }
                        }
                    }
                }
            }

            if (Mage_ImportExport_Model_Import::BEHAVIOR_APPEND != $this->getBehavior() && $productIds) {
                $adapter->delete(
                    $mainTable,
                    $adapter->quoteInto('product_id IN (?)', array_unique($productIds))
                );
            }
            if ($linkRows) {
                $adapter->insertOnDuplicate(
                    $mainTable,
                    $linkRows,
                    array('link_id')
                );
                $adapter->changeTableAutoIncrement($mainTable, $nextLinkId);
            }
            if ($positionRows) { // process linked product positions
                $adapter->insertOnDuplicate(
                    $resource->getAttributeTypeTable('int'),
                    $positionRows,
                    array('value')
                );
            }
        }
        return $this;
    }

    /**
     * Custom options save.
     *
     * @return Mage_ImportExport_Model_Import_Entity_Product
     */
    protected function _saveCustomOptions()
    {
        /** @var $coreResource Mage_Core_Model_Resource */
        $coreResource   = Mage::getSingleton('core/resource');
        $productTable   = $coreResource->getTableName('catalog/product');
        $optionTable    = $coreResource->getTableName('catalog/product_option');
        $priceTable     = $coreResource->getTableName('catalog/product_option_price');
        $titleTable     = $coreResource->getTableName('catalog/product_option_title');
        $typePriceTable = $coreResource->getTableName('catalog/product_option_type_price');
        $typeTitleTable = $coreResource->getTableName('catalog/product_option_type_title');
        $typeValueTable = $coreResource->getTableName('catalog/product_option_type_value');
        $nextOptionId   = Mage::getResourceHelper('importexport')->getNextAutoincrement($optionTable);
        $nextValueId    = Mage::getResourceHelper('importexport')->getNextAutoincrement($typeValueTable);
        $priceIsGlobal  = Mage::helper('catalog')->isPriceGlobal();
        $type           = null;
        $typeSpecific   = array(
            'date'      => array('price', 'sku'),
            'date_time' => array('price', 'sku'),
            'time'      => array('price', 'sku'),
            'field'     => array('price', 'sku', 'max_characters'),
            'area'      => array('price', 'sku', 'max_characters'),
            //'file'      => array('price', 'sku', 'file_extension', 'image_size_x', 'image_size_y'),
            'drop_down' => true,
            'radio'     => true,
            'checkbox'  => true,
            'multiple'  => true
        );

        while ($bunch = $this->_dataSourceModel->getNextBunch()) {
            $customOptions = array(
                'product_id'    => array(),
                $optionTable    => array(),
                $priceTable     => array(),
                $titleTable     => array(),
                $typePriceTable => array(),
                $typeTitleTable => array(),
                $typeValueTable => array()
            );

            foreach ($bunch as $rowNum => $rowData) {
                $this->_filterRowData($rowData);
                if (!$this->isRowAllowedToImport($rowData, $rowNum)) {
                    continue;
                }
                if (self::SCOPE_DEFAULT == $this->getRowScope($rowData)) {
                    $productId = $this->_newSku[$rowData[self::COL_SKU]]['entity_id'];
                } elseif (!isset($productId)) {
                    continue;
                }
                if (!empty($rowData['_custom_option_store'])) {
                    if (!isset($this->_storeCodeToId[$rowData['_custom_option_store']])) {
                        continue;
                    }
                    $storeId = $this->_storeCodeToId[$rowData['_custom_option_store']];
                } else {
                    $storeId = Mage_Catalog_Model_Abstract::DEFAULT_STORE_ID;
                }
                if (!empty($rowData['_custom_option_type'])) { // get CO type if its specified
                    if (!isset($typeSpecific[$rowData['_custom_option_type']])) {
                        $type = null;
                        continue;
                    }
                    $type = $rowData['_custom_option_type'];
                    $rowIsMain = true;
                } elseif($rowData['has_transcript']) {
                    $type = 'checkbox';
                    $rowIsMain = true;
                } else {
                    if (null === $type) {
                        continue;
                    }
                    $rowIsMain = false;
                }
                if (!isset($customOptions['product_id'][$productId])) { // for update product entity table
                    $customOptions['product_id'][$productId] = array(
                        'entity_id'        => $productId,
                        'has_options'      => 0,
                        'required_options' => 0,
                        'updated_at'       => now()
                    );
                }
                if ($rowIsMain) {
                    $solidParams = array(
                        'option_id'      => $nextOptionId,
                        'sku'            => '',
                        'max_characters' => 0,
                        'file_extension' => null,
                        'image_size_x'   => 0,
                        'image_size_y'   => 0,
                        'product_id'     => $productId,
                        'type'           => $type,
                        'is_require'     => empty($rowData['_custom_option_is_required']) ? 0 : 1,
                        'sort_order'     => empty($rowData['_custom_option_sort_order'])
                            ? 0 : abs($rowData['_custom_option_sort_order'])
                    );

                    if (true !== $typeSpecific[$type]) { // simple option may have optional params
                        $priceTableRow = array(
                            'option_id'  => $nextOptionId,
                            'store_id'   => Mage_Catalog_Model_Abstract::DEFAULT_STORE_ID,
                            'price'      => 0,
                            'price_type' => 'fixed'
                        );

                        foreach ($typeSpecific[$type] as $paramSuffix) {
                            if (isset($rowData['_custom_option_' . $paramSuffix])) {
                                $data = $rowData['_custom_option_' . $paramSuffix];

                                if (array_key_exists($paramSuffix, $solidParams)) {
                                    $solidParams[$paramSuffix] = $data;
                                } elseif ('price' == $paramSuffix) {
                                    if ('%' == substr($data, -1)) {
                                        $priceTableRow['price_type'] = 'percent';
                                    }
                                    $priceTableRow['price'] = (float) rtrim($data, '%');
                                }
                            }
                        }
                        $customOptions[$priceTable][] = $priceTableRow;
                    }
                    $customOptions[$optionTable][] = $solidParams;
                    $customOptions['product_id'][$productId]['has_options'] = 1;

                    if (!empty($rowData['_custom_option_is_required'])) {
                        $customOptions['product_id'][$productId]['required_options'] = 1;
                    }
                    $prevOptionId = $nextOptionId++; // increment option id, but preserve value for $typeValueTable
                }

                /**
                 * Section: Transcript Custom Options
                 * Allows Transcript Custom Options to be automatically added as long as price AND title are filled in.
                 *
                 */

                $this->_deleteCustomOptionsByProductId($productId); //deletes custom options so that duplicates are not created.

                if($rowData['has_transcript']) {
                    $transcriptPriceDigital = $rowData['digital_transcript_price'] != '' ? $rowData['digital_transcript_price'] : 0;
                    $transcriptPricePhysical = $rowData['physical_transcript_price'] != '' ? $rowData['physical_transcript_price'] : 0;
                    $rowData['_custom_option_title'] = self::TRANSCRIPT_TITLE;

                    $optionValues = array();
                    if($rowData[self::COL_DIGITAL_TRANSCRIPT_SKU]) {
                        $optionValues[] = array(
                            '_custom_option_row_title' => self::DIGITAL_TRANSCRIPT_TITLE,
                            '_custom_option_store' => '',
                            '_custom_option_row_sort' => '',
                            '_custom_option_row_sku' => $rowData[self::COL_DIGITAL_TRANSCRIPT_SKU],
                            '_custom_option_row_price' => $transcriptPriceDigital,
                        );
                    }

                    if($rowData[self::COL_PHYSICAL_TRANSCRIPT_SKU]) {
                        $optionValues[] = array(
                            '_custom_option_row_title' => self::PHYSICAL_TRANSCRIPT_TITLE,
                            '_custom_option_store' => '',
                            '_custom_option_row_sort' => '',
                            '_custom_option_row_sku' => $rowData[self::COL_PHYSICAL_TRANSCRIPT_SKU],
                            '_custom_option_row_price' => $transcriptPricePhysical,
                        );
                    }
                } else {
                    if($rowData['_custom_option_row_title'] && $rowData['_custom_option_store'] && $rowData['_custom_option_row_sku']) {
                        $optionValues = array(
                            0 => array(
                                '_custom_option_row_title' => $rowData['_custom_option_row_title'],
                                '_custom_option_store' => $rowData['_custom_option_store'],
                                '_custom_option_row_sort' => $rowData['_custom_option_row_sort'],
                                '_custom_option_row_sku' => $rowData['_custom_option_row_sku'],
                                '_custom_option_row_price' => $rowData['_custom_option_row_price'],
                            )
                        );
                    } else {
                        $optionValues = array(); //if user doesn't enter in any custom options, then array is blank, meaning no custom options.
                    }
                }

                foreach($optionValues as $optionValueid => $optionValue) {
                    if ($typeSpecific[$type] === true && !empty($optionValue['_custom_option_row_title'])
                        && empty($optionValue['_custom_option_store'])) {
                        // complex CO option row
                        $customOptions[$typeValueTable][$prevOptionId][] = array(
                            'option_type_id' => $nextValueId,
                            'sort_order'     => empty($optionValue['_custom_option_row_sort'])
                                ? 0 : abs($optionValue['_custom_option_row_sort']),
                            'sku'            => !empty($optionValue['_custom_option_row_sku'])
                                ? $optionValue['_custom_option_row_sku'] : ''
                        );
                        if (!isset($customOptions[$typeTitleTable][$nextValueId][0])) { // ensure default title is set
                            $customOptions[$typeTitleTable][$nextValueId][0] = $optionValue['_custom_option_row_title'];
                        }
                        $customOptions[$typeTitleTable][$nextValueId][$storeId] = $optionValue['_custom_option_row_title'];
    
                        if (!empty($optionValue['_custom_option_row_price'])) {
                            $typePriceRow = array(
                                'price'      => (float) rtrim($optionValue['_custom_option_row_price'], '%'),
                                'price_type' => 'fixed'
                            );
                            if ('%' == substr($optionValue['_custom_option_row_price'], -1)) {
                                $typePriceRow['price_type'] = 'percent';
                            }
                            if ($priceIsGlobal) {
                                $customOptions[$typePriceTable][$nextValueId][0] = $typePriceRow;
                            } else {
                                // ensure default price is set
                                if (!isset($customOptions[$typePriceTable][$nextValueId][0])) {
                                    $customOptions[$typePriceTable][$nextValueId][0] = $typePriceRow;
                                }
                                $customOptions[$typePriceTable][$nextValueId][$storeId] = $typePriceRow;
                            }
                        }
                        $nextValueId++;
                    }
                }

                /**
                 * End of Transcript Custom Options section.
                 *
                 */


                if (!empty($rowData['_custom_option_title'])) {
                    if (!isset($customOptions[$titleTable][$prevOptionId][0])) { // ensure default title is set
                        $customOptions[$titleTable][$prevOptionId][0] = $rowData['_custom_option_title'];
                    }
                    $customOptions[$titleTable][$prevOptionId][$storeId] = $rowData['_custom_option_title'];
                }
            }
            if ($this->getBehavior() != Mage_ImportExport_Model_Import::BEHAVIOR_APPEND) { // remove old data?
                $this->_connection->delete(
                    $optionTable,
                    $this->_connection->quoteInto('product_id IN (?)', array_keys($customOptions['product_id']))
                );
            }
            // if complex options does not contain values - ignore them
            foreach ($customOptions[$optionTable] as $key => $optionData) {
                if ($typeSpecific[$optionData['type']] === true
                    && !isset($customOptions[$typeValueTable][$optionData['option_id']])
                ) {
                    unset($customOptions[$optionTable][$key], $customOptions[$titleTable][$optionData['option_id']]);
                }
            }

            if ($customOptions[$optionTable]) {
                $this->_connection->insertMultiple($optionTable, $customOptions[$optionTable]);
            } else {
                continue; // nothing to save
            }
            $titleRows = array();

            foreach ($customOptions[$titleTable] as $optionId => $storeInfo) {
                foreach ($storeInfo as $storeId => $title) {
                    $titleRows[] = array('option_id' => $optionId, 'store_id' => $storeId, 'title' => $title);
                }
            }
            if ($titleRows) {
                $this->_connection->insertOnDuplicate($titleTable, $titleRows, array('title'));
            }
            if ($customOptions[$priceTable]) {
                $this->_connection->insertOnDuplicate(
                    $priceTable,
                    $customOptions[$priceTable],
                    array('price', 'price_type')
                );
            }
            $typeValueRows = array();

            foreach ($customOptions[$typeValueTable] as $optionId => $optionInfo) {
                foreach ($optionInfo as $row) {
                    $row['option_id'] = $optionId;
                    $typeValueRows[]  = $row;
                }
            }
            if ($typeValueRows) {
                $this->_connection->insertMultiple($typeValueTable, $typeValueRows);
            }
            $optionTypePriceRows = array();
            $optionTypeTitleRows = array();

            foreach ($customOptions[$typePriceTable] as $optionTypeId => $storesData) {
                foreach ($storesData as $storeId => $row) {
                    $row['option_type_id'] = $optionTypeId;
                    $row['store_id']       = $storeId;
                    $optionTypePriceRows[] = $row;
                }
            }
            foreach ($customOptions[$typeTitleTable] as $optionTypeId => $storesData) {
                foreach ($storesData as $storeId => $title) {
                    $optionTypeTitleRows[] = array(
                        'option_type_id' => $optionTypeId,
                        'store_id'       => $storeId,
                        'title'          => $title
                    );
                }
            }
            if ($optionTypePriceRows) {
                $this->_connection->insertOnDuplicate(
                    $typePriceTable,
                    $optionTypePriceRows,
                    array('price', 'price_type')
                );
            }
            if ($optionTypeTitleRows) {
                $this->_connection->insertOnDuplicate($typeTitleTable, $optionTypeTitleRows, array('title'));
            }
            if ($customOptions['product_id']) { // update product entity table to show that product has options
                $this->_connection->insertOnDuplicate(
                    $productTable,
                    $customOptions['product_id'],
                    array('has_options', 'required_options', 'updated_at')
                );
            }
        }
        return $this;
    }

    /**
     * Converts skus to product ids.
     * @param $userData
     * @param $product
     * @return array
     */
    public function skusToIds($userData, $product)
    {
        $productIds = array();
        foreach ($this->userCSVDataAsArray($userData ) as $sku) {
            $productIds[] = (int)$product->getIdBySku($sku);
        }

        return array_filter($productIds);
    }

    /**
     * Magento product images have a prefix (such as testproduct.jpg would have prefix of /t/e/testproduct.jpg), this returns the filename with prefix
     * which is needed for saving.
     *
     * @param $filename
     * @return string
     */
    protected function _deriveFilename($filename)
    {
        $uploader = $this->_getUploader();
        $filename = $uploader->correctFileNameCase($filename);
        $pathPrefix = strtolower($uploader->getDispretionPath($filename));
        $filename = $pathPrefix . "/" . $filename;
        return $filename;
    }

    /**
     * Generates a list of images already imported and stores to an array.  This variable is used to ensure images are not uploaded more than once.
     */
    protected function generateListAlreadyImportedImages()
    {
        $stmt = $this->_connection->query("SELECT entity_id, value_id, value FROM catalog_product_entity_media_gallery");
        $data = array();
        while ($row = $stmt->fetch(Zend_Db::FETCH_NUM)) {
            $data[$row[0]][$row[1]] = $row[2];
        }

        $this->_imagesAlreadyImportedByEntityId = $data;
        $this->_imagesAlreadyImported = array_unique($this->_connection->fetchCol("SELECT value FROM catalog_product_entity_media_gallery"));
    }

    /**
     * For every import, after the first one, magento would create duplicates of every custom option.  This creates duplicate custom options
     * from being created each time magento importer is run.
     * @param $productId
     */
    protected function _deleteCustomOptionsByProductId($productId)
    {
        $connection = $this->_connection;
        $sql = $connection->select()
            ->from('catalog_product_option', array('option_id'))
            ->where('product_id = :product_id');
        $bind = array('product_id' => $productId);
        $stmt = $connection->query($sql, $bind);

        if($stmt->rowCount() > 0) {
            $where = array(
                'product_id = ?' => $productId
            );

            $connection->delete('catalog_product_option', $where);
        }
    }

    /**
     * Add url key (for default store).  For Great Courses, Simple products many times have same exact names of configurable products.
     * Magento by default will add a hash to a url_key, if another product has the same name.
     * Function has been overwritten so that a 4 character hash is placed at end of ALL simple products no matter what.
     * Therefore, when a configurable product is created, no duplicate will exist for that product, and therefore the configurable will have a clean url.
     * A reason behind this is that simple products are never shown on their own page, this way configurable products always have a clean url.
     *
     * @param array $rowData
     * @param int $rowScope
     * @param string $sku
     * @return array
     */
    protected function _prepareUrlKeyCustom($rowData, $rowScope, $sku, $rowProductType)
    {
        if (self::SCOPE_DEFAULT != $rowScope) {
            return $rowData;
        }
        if (!empty($rowData['name']) && empty($rowData['url_key']) && array_search($sku, $this->_urlKeys) === false) {
            $rowData['url_key'] = Mage::getModel('catalog/product')->formatUrlKey($rowData['name']);
            if(!isset($this->_urlKeys[$rowData['url_key']]) && $rowProductType == 'simple') {
                $rowData['url_key'] = sprintf(
                    '%s-%s',
                    $rowData['url_key'],
                    substr(Mage::helper('core')->uniqHash(), 0, 4)
                );
            }

            if (isset($this->_urlKeys[$rowData['url_key']])) {
                $rowData['url_key'] = sprintf(
                    '%s-%s',
                    $rowData['url_key'],
                    substr(Mage::helper('core')->uniqHash(), 0, 6)
                );
            }
            $this->_urlKeys[$rowData['url_key']] = $sku;
        }
        return $rowData;
    }

    protected function _deriveMediaFormat($sku, $mediaFormat)
    {
        if(!is_numeric($mediaFormat)) {
            $mediaPrefix = substr($sku, 0,2);
            switch($mediaPrefix) {
                case 'PT':
                    $mediaFormat = "Transcript Book";
                    break;
                case 'DT':
                    $mediaFormat = "Digital Transcript";
                    break;
                case 'DA':
                    $mediaFormat = "Audio Download";
                    break;
                case 'PD':
                    $mediaFormat = "DVD";
                    break;
                case 'PC':
                    $mediaFormat = "CD";
                    break;
                case 'DV':
                    $mediaFormat = "Video Download";
                    break;
            }
        }

        return $mediaFormat;
    }

    /**
     * @param $rowData
     * @param $rowNum
     * @param $isRowValid
     */
    public function validateMediaFormat($rowData, $rowNum, &$isRowValid)
    {
        if($this->getEntityTypeCode() == Tgc_Dax_Model_Import_Entity_Set::ENTITY_TYPE_CODE) {
            //the media formats are determined by looking at the first two letters of the sku. The first two letters always represent a certain media type.
            $mediaFormatValid = false;
            if(is_numeric($rowData['sku'])) { //if this is true, that means it is a configurable product, media_format not required for set configurables.
                $mediaFormatValid = true;
            }

            if(isset($rowData['media_format'])) { //Simple products must have a value for media_format in order to be true.
                if($rowData['media_format']) {
                    $mediaFormatValid = true;
                }
            }

            if(!$mediaFormatValid) {
                $this->addRowError(self::EMPTY_MEDIA_FORMAT, $rowNum);
                $isRowValid = false;
            }
        } elseif($this->getEntityTypeCode() == self::ENTITY_TYPE_CODE) {
            if($rowData['type'] == 'simple' && empty($rowData['media_format'])) {
                $this->addRowError(self::EMPTY_MEDIA_FORMAT, $rowNum);
                $isRowValid = false;
            }
        }
    }

    protected function _formatSetMembers($setMembers)
    {
        //we know that all of these members refer to valid course.  THe reason that we know, is if even one course was invalid, an error would have been thrown which would
        //have prevented code from even reaching this point.

        if($setMembers) {
            $coursesList = array();
            $coursesArray = $this->userCSVDataAsArray($setMembers);
            foreach($coursesArray as $course) {
                $coursesList[] = $this->_helper()->stripNonAlphaNumeric($course);
            }
            $setMembers = implode(",", $coursesList);
        }

        return $setMembers;
    }

    /**
     * Set valid attribute set and product type to rows with all scopes
     * to ensure that existing products doesn't changed.
     *
     * @param array $rowData
     * @return array
     */
    protected function _prepareRowForDbCustom(array $rowData, $rowNum)
    {
        $rowData = parent::_prepareRowForDb($rowData);

        $this->validateFieldsInvalidCharacterSets($rowData, $rowNum);

        return $rowData;
    }

    /**
     * Some configurable attributes are composed of values taken from one or more of its child products.  That is why they are called composite.
     *
     * @param $rowData
     * @param $rowSku
     */
    public function collectCompositeAttributes($rowData, $rowSku)
    {
        //Courses do not have any composite attributes.  Sets can have composite attributes. Example = Set Members
    }

    /**
     * Returns Dax helper object
     * @return Mage_Core_Helper_Abstract
     */
    protected function _helper()
    {
        return Mage::helper('tgc_dax');
    }
}