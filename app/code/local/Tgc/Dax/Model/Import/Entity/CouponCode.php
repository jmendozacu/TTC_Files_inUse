<?php
/**
 * Dax coupon code entity for importexport
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Dax
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Dax_Model_Import_Entity_CouponCode extends Tgc_Dax_Model_Import_Entity_Checksum_Base
{
    //coupon descriptors
    const COL_COUPON_ID                    = 'CouponID';
    const COL_COUPON_DESCRIPTION           = 'CouponDescription';
    const COL_COUPON_FROM_DATE             = 'CouponFromDate';
    const COL_COUPON_TO_DATE               = 'CouponToDate';
    const COL_COUPON_CODE                  = 'CouponCode';
    const COL_CURRENCY                     = 'CouponCurrency';
    //coupon requirements
    const COL_REQ_AMOUNT                   = 'ReqAmount';
    const COL_REQ_QUANTITY                 = 'ReqQuantity';
    const COL_REQ_COURSE_PARTS             = 'ReqCourseParts';
    const COL_REQ_PRICE_GT                 = 'ReqPriceGT';
    const COL_REQ_COURSE_TYPE              = 'ReqCourseType';
    const COL_REQ_SALE_STATUS              = 'ReqSaleStatus';
    const COL_REQ_COURSE_ID                = 'ReqCourseID';
    const COL_REQ_FORMAT                   = 'ReqFormat';
    const COL_REQ_CATEGORY                 = 'ReqCategory';
    //coupon benefits
    const COL_GET_AMOUNT                   = 'GetAmount';
    const COL_GET_QUANTITY                 = 'GetQuantity';
    const COL_GET_COURSE_PARTS             = 'GetCourseParts';
    const COL_GET_PRICE_GT                 = 'GetPriceGT';
    const COL_GET_COURSE_TYPE              = 'GetCourseType';
    const COL_GET_SALE_STATUS              = 'GetSaleStatus';
    const COL_GET_COURSE_ID                = 'GetCourseID';
    const COL_GET_FORMAT                   = 'GetFormat';
    const COL_GET_CATEGORY                 = 'GetCategory';
    const COL_GET_PERCENT_OFF              = 'GetPercentOff';
    const COL_GET_SHIPPING_AMOUNT          = 'GetShippingAmount';
    const COL_GET_SHIPPING_TYPE            = 'GetShippingType';
    const COL_GET_SPECIAL_SHIPPING         = 'GetSpecialShipping';

    //default values
    const SHOW_COUPON_IN_RSS_FEED          = 0;
    const USES_PER_COUPON                  = null;
    const USES_PER_CUSTOMER                = null;
    const APPLY_TO_SHIPPING                = 0;
    const IS_ACTIVE_BY_DEFAULT             = 1;
    const STOP_FURTHER_RULES_PROCESSING    = 1;
    const DEFAULT_SORT_ORDER               = 0;
    const COUPON_DEFAULT_TYPE              = 0;
    const MAIN_TABLE_COUPON_TYPE           = 2;
    const USE_AUTO_GENERATION              = 0;
    const SIMPLE_FREE_SHIPPING_TRUE        = 2;
    const SIMPLE_FREE_SHIPPING_FALSE       = 0;
    const DEFAULT_TIMES_USED               = 0;
    const IS_ADVANCED                      = 1;
    const IS_PRIMARY                       = 1;
    const SIMPLE_ACTION_PERCENT            = 'by_percent';
    const SIMPLE_ACTION_FIXED              = 'by_fixed';
    const SIMPLE_ACTION_CART_FIXED         = 'cart_fixed';
    const SIMPLE_ACTION_BUY_X_GET_Y        = 'buy_x_get_y';
    const ERROR_INVALID_CURRENCY           = 0;
    const IS_IMPORTED                      = 1;
    const DEFAULT_GROUP_ID                 = 0;
    const DEFAULT_LABEL_STORE_ID           = 0;

    //internal media formats for this import
    const TRANSCRIPT_BOOK                  = 'Transcript';
    const DIGITAL_TRANSCRIPT               = 'DownloadT';
    const DVD                              = 'DVD';
    const CD                               = 'CD';
    const VIDEO_DOWNLOAD                   = 'DownloadV';
    const AUDIO_DOWNLOAD                   = 'DownloadA';
    const CD_SOUNDTRACK                    = 'CD Soundtrack';
    const SOUNDTRACK_DOWNLOAD              = 'Soundtrack Download';

    private $_ruleTable;
    private $_couponTable;
    private $_ruleGroupTable;
    private $_ruleLabelTable;
    private $_ruleWebsiteTable;
    private $_ruleCustomerTable;
    private $_ruleAttributeTable;
    private $_categoryTable;
    private $_eavAttributeTable;
    private $_varcharAttributeTable;
    private $_storeTable;
    private $_currencyToWebsite;
    private $_ruleIds;
    private $_groupIds;
    private $_categoryNamesWebsitesToIds;
    private $_flatRateMethods;
    private $_mediaFormats;
    private $_attributeCodesToIds;
    private $_courseTypeCodes;
    private $_requiredAttributes;

    /**
     * Constructor
     */
    public function __construct()
    {
        /** @var _dataSourceModel Mage_ImportExport_Model_Resource_Import_Data */
        $this->_dataSourceModel       = Mage_ImportExport_Model_Import::getDataSourceModel();
        $this->_dataSourceModel->setEntityTypeCode($this->getEntityTypeCode());
        /** @var _connection Magento_Db_Adapter_Pdo_Mysql */
        $this->_connection            = Mage::getSingleton('core/resource')->getConnection('write');

        $this->_ruleTable             = Mage::getResourceModel('salesrule/rule')->getMainTable();
        $this->_couponTable           = Mage::getResourceModel('salesrule/coupon')->getMainTable();
        $this->_ruleGroupTable        = 'salesrule_customer_group';
        $this->_ruleLabelTable        = 'salesrule_label';
        $this->_ruleWebsiteTable      = 'salesrule_website';
        $this->_ruleCustomerTable     = 'salesrule_customer';
        $this->_ruleAttributeTable    = 'salesrule_product_attribute';
        $this->_categoryTable         = 'catalog_category_entity';
        $this->_eavAttributeTable     = 'eav_attribute';
        $this->_varcharAttributeTable = $this->_categoryTable . '_varchar';
        $this->_storeTable            = 'core_store';
        $this->_flatRateMethods       = Mage::helper('tgc_dax')->getFlatRateMethodsForRules();

        $this->_initCurrencyToWebsite();
        $this->_initCategoryNamesWebsitesToIds();
        $this->_initMediaFormats();
        $this->_initCourseTypeCodes();

        $this->_permanentAttributes = array(
            self::COL_COUPON_ID,
            self::COL_COUPON_DESCRIPTION,
            self::COL_COUPON_FROM_DATE,
            self::COL_COUPON_TO_DATE,
            self::COL_COUPON_CODE,
            self::COL_CURRENCY,
            self::COL_REQ_AMOUNT,
            self::COL_REQ_QUANTITY,
            self::COL_REQ_COURSE_PARTS,
            self::COL_REQ_PRICE_GT,
            self::COL_REQ_COURSE_TYPE,
            self::COL_REQ_SALE_STATUS,
            self::COL_REQ_COURSE_ID,
            self::COL_REQ_FORMAT,
            self::COL_REQ_CATEGORY,
            self::COL_GET_AMOUNT,
            self::COL_GET_QUANTITY,
            self::COL_GET_COURSE_PARTS,
            self::COL_GET_PRICE_GT,
            self::COL_GET_COURSE_TYPE,
            self::COL_GET_SALE_STATUS,
            self::COL_GET_COURSE_ID,
            self::COL_GET_FORMAT,
            self::COL_GET_CATEGORY,
            self::COL_GET_PERCENT_OFF,
            self::COL_GET_SHIPPING_AMOUNT,
            self::COL_GET_SHIPPING_TYPE,
            self::COL_GET_SPECIAL_SHIPPING,
        );

        //they are all 'particular' because they are camel-case
        $this->_particularAttributes = $this->_permanentAttributes;

        $this->_requiredAttributes = array(
            self::COL_COUPON_CODE,
            self::COL_COUPON_ID,
            self::COL_COUPON_DESCRIPTION,
        );
    }

    /**
     * @return string
     */
    public function getEntityTypeCode()
    {
        return 'couponcode';
    }

    /**
     * Validate a row data depending on the action
     *
     * @param array $rowData
     * @param int   $rowNum
     * @return bool
     * @throws InvalidArgumentException
     */
    public function validateRow(array $rowData, $rowNum)
    {
        try {

            $this->_validateRequiredAttributes($rowData);

            if (Mage_ImportExport_Model_Import::BEHAVIOR_DELETE == $this->getBehavior()) {
                return true;
            }

            if (Mage_ImportExport_Model_Import::BEHAVIOR_APPEND == $this->getBehavior()) {
                if ($this->_ruleExists($this->_getCouponName($rowData))) {
                    $message = Mage::helper('tgc_dax')->__(
                        'A rule with name: %s already exists',
                        $this->_getCouponName($rowData)
                    );
                    throw new InvalidArgumentException($message);
                }
                if ($this->_couponExists($rowData[self::COL_COUPON_CODE])) {
                    $message = Mage::helper('tgc_dax')->__(
                        'A coupon with code: %s already exists',
                        $rowData[self::COL_COUPON_CODE]
                    );
                    throw new InvalidArgumentException($message);
                }
            }

            $this->_mapRule($rowData);
            $this->_mapCoupon($rowData);
            $this->_mapGroup($rowData);
            $this->_mapWebsite($rowData);
            $this->_mapLabel($rowData);
            $this->_mapAttribute($rowData);
            return true;
        } catch (InvalidArgumentException $e) {
            $this->addRowError($e->getMessage(), $rowNum);
            return false;
        }
    }

    /**
     * Determine coupon name for a row
     *
     * @param array $row
     * @return string
     */
    private function _getCouponName(array $row)
    {
        return $row[self::COL_COUPON_ID] . ' ' . $row[self::COL_COUPON_DESCRIPTION];
    }

    /**
     * Import function
     *
     * @return bool|void
     */
    protected function _importData()
    {
        if (Mage_ImportExport_Model_Import::BEHAVIOR_DELETE == $this->getBehavior()) {
            $this->_deleteCouponCodes();
        } else if (Mage_ImportExport_Model_Import::BEHAVIOR_REPLACE == $this->getBehavior()) {
            $this->_updateCouponCodes();
        } else {
            $this->_saveCouponCodes();
        }

        return true;
    }

    /**
     * Update coupon codes
     */
    private function _updateCouponCodes()
    {
        while ($bunch = $this->_dataSourceModel->getNextBunch()) {
            foreach ($bunch as $rowNum => $rowData) {
                try {
                    $this->_connection->insertOnDuplicate($this->_ruleTable, $this->_mapRule($rowData));
                    $this->_connection->insertOnDuplicate($this->_couponTable, $this->_mapCoupon($rowData));
                    foreach ($this->_mapGroup($rowData) as $data) {
                        $this->_connection->insertOnDuplicate($this->_ruleGroupTable, $data);
                    }
                    foreach ($this->_mapWebsite($rowData) as $data) {
                        $this->_connection->insertOnDuplicate($this->_ruleWebsiteTable, $data);
                    }
                    $this->_connection->insertOnDuplicate($this->_ruleLabelTable, $this->_mapLabel($rowData));
                    foreach ($this->_mapAttribute($rowData) as $data) {
                        $this->_connection->insertOnDuplicate($this->_ruleAttributeTable, $data);
                    }
                } catch (InvalidArgumentException $e) {
                    $this->addRowError($e->getCode(), $rowNum);
                }
            }
        }
    }

    /**
     * Save coupon codes
     */
    private function _saveCouponCodes()
    {
        while ($bunch = $this->_dataSourceModel->getNextBunch()) {
            $ruleData      = array();
            $couponData    = array();
            $groupData     = array();
            $websiteData   = array();
            $labelData     = array();
            $attributeData = array();

            try {
                //we need to do this first so we create the rule id for the others
                foreach ($bunch as $rowData) {
                    $ruleData[] = $this->_mapRule($rowData);
                }
                $this->_connection->insertMultiple($this->_ruleTable, $ruleData);

                foreach ($bunch as $rowData) {
                    $couponData[] = $this->_mapCoupon($rowData);
                    foreach ($this->_mapGroup($rowData) as $group_data) {
                        $groupData[] = $group_data;
                    }
                    foreach ($this->_mapWebsite($rowData) as $website_data) {
                        $websiteData[] = $website_data;
                    }
                    $labelData[] = $this->_mapLabel($rowData);
                    foreach ($this->_mapAttribute($rowData) as $attribute_data) {
                        $attributeData[] = $attribute_data;
                    }
                }
                $this->_connection->insertMultiple($this->_couponTable, $couponData);
                $this->_connection->insertMultiple($this->_ruleGroupTable, $groupData);
                $this->_connection->insertMultiple($this->_ruleWebsiteTable, $websiteData);
                $this->_connection->insertMultiple($this->_ruleLabelTable, $labelData);
                $this->_connection->insertMultiple($this->_ruleAttributeTable, $attributeData);
            } catch (Exception $e) {
                Mage::logException($e);
            }
        }
    }

    /**
     * Delete coupon codes
     */
    private function _deleteCouponCodes()
    {
        try {
            while ($bunch = $this->_dataSourceModel->getNextBunch()) {
                $couponsToDelete = array();

                foreach ($bunch as $rowData) {
                    $couponsToDelete[] = $rowData[self::COL_COUPON_CODE];
                }

                $ruleIds = $this->_getRuleIdsByCouponCodes($couponsToDelete);

                if ($ruleIds) {
                    $tables = array(
                        $this->_ruleTable,
                        $this->_couponTable,
                        $this->_ruleGroupTable,
                        $this->_ruleWebsiteTable,
                        $this->_ruleCustomerTable,
                        $this->_ruleLabelTable,
                        $this->_ruleAttributeTable,
                    );

                    foreach ($tables as $table) {
                        $this->_connection->delete(
                            $table,
                            array($this->_connection->quoteInto("`rule_id` IN(?) ", $ruleIds))
                        );
                    }
                }
            }
        } catch (Exception $e) {
            Mage::logException($e);
        }
    }

    /**
     * Map the salesrule table
     *
     * @param array $row
     * @return array
     */
    protected function _mapRule(array $row)
    {
        $data = array(
            'rule_id'               => $this->_getRuleIdByCouponName($this->_getCouponName($row)) ? $this->_getRuleIdByCouponName($this->_getCouponName($row)) : null,
            'name'                  => $row[self::COL_COUPON_ID] . ' ' . $row[self::COL_COUPON_DESCRIPTION],
            'description'           => $row[self::COL_COUPON_DESCRIPTION],
            'from_date'             => date(Varien_Date::DATE_PHP_FORMAT, Mage::getModel('core/date')->timestamp(strtotime($row[self::COL_COUPON_FROM_DATE]))),
            'to_date'               => date(Varien_Date::DATE_PHP_FORMAT, Mage::getModel('core/date')->timestamp(strtotime($row[self::COL_COUPON_TO_DATE]))),
            'uses_per_customer'     => intval(self::USES_PER_CUSTOMER),
            'is_active'             => self::IS_ACTIVE_BY_DEFAULT,
            'conditions_serialized' => $this->_getConditionsSerialized($row),
            'actions_serialized'    => $this->_getActionsSerialized($row),
            'stop_rules_processing' => self::STOP_FURTHER_RULES_PROCESSING,
            'is_advanced'           => self::IS_ADVANCED,
            'product_ids'           => null,
            'sort_order'            => self::DEFAULT_SORT_ORDER,
            'simple_action'         => $this->_getSimpleAction($row),
            'discount_amount'       => $this->_getDiscountAmount($row),
            'discount_qty'          => $this->_getSimpleAction($row) == self::SIMPLE_ACTION_BUY_X_GET_Y ? null : $row[self::COL_GET_QUANTITY],
            'discount_step'         => $this->_getSimpleAction($row) == self::SIMPLE_ACTION_BUY_X_GET_Y ? $row[self::COL_GET_QUANTITY] : 0,
            'simple_free_shipping'  => $this->_getSimpleFreeShipping($row),
            'apply_to_shipping'     => self::APPLY_TO_SHIPPING,
            'times_used'            => self::DEFAULT_TIMES_USED,
            'is_rss'                => self::SHOW_COUPON_IN_RSS_FEED,
            'coupon_type'           => self::MAIN_TABLE_COUPON_TYPE,
            'use_auto_generation'   => self::USE_AUTO_GENERATION,
            'uses_per_coupon'       => intval(self::USES_PER_COUPON),
            'is_imported'           => self::IS_IMPORTED,
            'shipping_amount'       => $this->_getShippingAmount($row),
            'shipping_type'         => $this->_getShippingType($row),
        );

        return $data;
    }

    /**
     * Map the salesrule_coupon table
     *
     * @param array $row
     * @return array
     */
    protected function _mapCoupon(array $row)
    {
        $ruleId = $this->_getRuleIdByCouponName($this->_getCouponName($row));

        $data = array(
            'rule_id'            => $ruleId,
            'code'               => $row[self::COL_COUPON_CODE],
            'usage_limit'        => self::USES_PER_COUPON,
            'usage_per_customer' => self::USES_PER_CUSTOMER,
            'times_used'         => self::DEFAULT_TIMES_USED,
            'expiration_date'    => date(Varien_Date::DATETIME_PHP_FORMAT, Mage::getModel('core/date')->timestamp(strtotime($row[self::COL_COUPON_TO_DATE]))),
            'is_primary'         => self::IS_PRIMARY,
            'created_at'         => date(Varien_Date::DATETIME_PHP_FORMAT, Mage::getModel('core/date')->timestamp(time())),
            'type'               => self::COUPON_DEFAULT_TYPE,
        );

        return $data;
    }

    /**
     * Map the salesrule_customer_group table
     *
     * @param array $row
     * @return array
     */
    protected function _mapGroup(array $row)
    {
        $ruleId   = $this->_getRuleIdByCouponName($this->_getCouponName($row));
        $groupIds = $this->_getGroupIds();
        $data = array();

        foreach ($groupIds as $groupId) {
            $data[] = array(
                'rule_id'           => $ruleId,
                'customer_group_id' => $groupId,
            );
        }

        return $data;
    }

    /**
     * Map the salesrule website table
     *
     * @param array $row
     * @return array
     */
    protected function _mapWebsite(array $row)
    {
        $ruleId     = $this->_getRuleIdByCouponName($this->_getCouponName($row));
        $websiteIds = $this->_getWebsiteIds($row);
        $data = array();

        foreach ($websiteIds as $websiteId) {
            $data[] = array(
                'rule_id'    => $ruleId,
                'website_id' => $websiteId,
            );
        }

        return $data;
    }

    /**
     * Map the salesrule_label table
     *
     * @param array $row
     * @return array
     */
    private function _mapLabel(array $row)
    {
        $ruleId = $this->_getRuleIdByCouponName($this->_getCouponName($row));
        $labelId = $this->_getLabelIdByRuleId($ruleId);
        $data = array(
            'label_id'   => $labelId,
            'rule_id'    => $ruleId,
            'store_id'   => self::DEFAULT_LABEL_STORE_ID,
            'label'      => $row[self::COL_COUPON_DESCRIPTION],
        );

        return $data;
    }

    /**
     * Map the salesrule_product_attribute table
     *
     * @param array $row
     * @return array
     */
    protected function _mapAttribute(array $row)
    {
        $ruleId       = $this->_getRuleIdByCouponName($this->_getCouponName($row));
        $websiteIds   = $this->_getWebsiteIds($row);
        $attributeIds = $this->_getAttributeIds($row);
        $groupIds     = $this->_getGroupIds();
        $data = array();

        foreach ($websiteIds as $websiteId) {
            foreach ($groupIds as $groupId) {
                foreach ($attributeIds as $attributeId) {
                    $data[] = array(
                        'rule_id'           => $ruleId,
                        'website_id'        => $websiteId,
                        'customer_group_id' => $groupId,
                        'attribute_id'      => $attributeId,
                    );
                }
            }
        }

        return $data;
    }

    /**
     * Gets all of the attribute ids required for a row
     * to be used in the salesrule_product_attribute table
     *
     * @param array $row
     * @return array
     */
    private function _getAttributeIds(array $row)
    {
        $attributes = array();

        if (!empty($row[self::COL_REQ_COURSE_PARTS]) || !empty($row[self::COL_GET_COURSE_PARTS])) {
            $attributes[] = 'course_parts';
        }
        if (!empty($row[self::COL_REQ_COURSE_TYPE]) || !empty($row[self::COL_GET_COURSE_TYPE])) {
            $attributes[] = 'course_type_code';
        }
        if (!empty($row[self::COL_REQ_SALE_STATUS]) || !empty($row[self::COL_GET_SALE_STATUS])) {
            $attributes[] = 'special_price';
        }
        if (!empty($row[self::COL_REQ_COURSE_ID]) || !empty($row[self::COL_GET_COURSE_ID])) {
            $attributes[] = 'course_id';
        }
        if (!empty($row[self::COL_REQ_FORMAT]) || !empty($row[self::COL_GET_FORMAT])) {
            $attributes[] = 'media_format';
        }
        if (!empty($row[self::COL_REQ_CATEGORY]) || !empty($row[self::COL_GET_CATEGORY])) {
            $attributes[] = 'category_ids';
        }

        $attributeIds = $this->_getAttributeIdsFromCodes($attributes);

        return array_unique(array_filter($attributeIds));
    }

    /**
     * Determines the value to use for the shipping amount field
     *
     * @param array $row
     * @return null|float
     */
    private function _getShippingAmount(array $row)
    {
        if ($this->_getSimpleFreeShipping($row)
            || (empty($row[self::COL_GET_SHIPPING_AMOUNT]) && $row[self::COL_GET_SHIPPING_AMOUNT] != 0)) {
            return null;
        }

        return $row[self::COL_GET_SHIPPING_AMOUNT];
    }

    /**
     * Determines the value to be used in the shipping type field
     *
     * @param array $row
     * @return null|string
     * @throws InvalidArgumentException
     */
    private function _getShippingType(array $row)
    {
        if (!$row[self::COL_GET_SPECIAL_SHIPPING]) {
            return null;
        }

        if ($this->_getSimpleFreeShipping($row)
            || (empty($row[self::COL_GET_SHIPPING_AMOUNT]) && $row[self::COL_GET_SHIPPING_AMOUNT] != 0)
            || empty($row[self::COL_GET_SHIPPING_TYPE])) {
            return null;
        }

        $type = strtolower($row[self::COL_GET_SHIPPING_TYPE]);
        if (!isset($this->_flatRateMethods[$type])) {
            $message = Mage::helper('tgc_dax')->__(
                'No shipping method with name: %s has been mapped',
                $row[self::COL_GET_SHIPPING_TYPE]
            );
            throw new InvalidArgumentException($message);
        }

        return $row[self::COL_GET_SHIPPING_TYPE];
    }

    /**
     * Determines the value to be used in the simple free shipping field
     *
     * @param array $row
     * @return int 0 or 1
     * @throws InvalidArgumentException
     */
    private function _getSimpleFreeShipping(array $row)
    {
        if (!$row[self::COL_GET_SPECIAL_SHIPPING]) {
            return self::SIMPLE_FREE_SHIPPING_FALSE;
        }

        if (isset($row[self::COL_GET_SHIPPING_AMOUNT])
            && $row[self::COL_GET_SHIPPING_AMOUNT] < 0.0001
            && empty($row[self::COL_GET_SHIPPING_TYPE])) {

            return self::SIMPLE_FREE_SHIPPING_TRUE;
        }

        return self::SIMPLE_FREE_SHIPPING_FALSE;
    }

    /**
     * Get a label id from a rule id
     *
     * @param int $ruleId
     * @return int|null
     */
    private function _getLabelIdByRuleId($ruleId)
    {
        if (!$ruleId) {
            return null;
        }

        $select = $this->_connection->select()
            ->from($this->_ruleLabelTable, 'label_id')
            ->where('rule_id = :ruleId');

        $bind = array(
            ':ruleId' => (int)$ruleId,
        );

        $labelId = $this->_connection->fetchOne($select, $bind);

        return $labelId ? $labelId : null;
    }

    /**
     * Creates an associative array of currency to website ids
     */
    private function _initCurrencyToWebsite()
    {
        $this->_currencyToWebsite = array();
        $websites = Mage::getResourceModel('core/website_collection');

        foreach ($websites as $website) {
            $currency = $website->getConfig(Mage_Directory_Model_Currency::XML_PATH_CURRENCY_BASE);
            $this->_currencyToWebsite[$currency][] = $website->getId();
        }
    }

    /**
     * Takes an array of attribute codes and converts it to an array of attribute ids
     *
     * @param array $attributes attribute codes
     * @return array of attribute ids
     */
    private function _getAttributeIdsFromCodes(array $attributes)
    {
        $attributeIds = array();

        foreach ($attributes as $code) {
            if (!isset($this->_attributeCodesToIds[$code])) {
                $select = $this->_connection->select()
                    ->from($this->_eavAttributeTable, 'attribute_id')
                    ->where('attribute_code = :code');

                $bind = array(
                    ':code' => (string)$code,
                );

                $this->_attributeCodesToIds[$code] = (int)$this->_connection->fetchOne($select, $bind);
            }
            $attributeIds[] = $this->_attributeCodesToIds[$code];
        }

        return $attributeIds;
    }

    /**
     * Build an assoc array of cat names + website ids => cat ids
     * for fast lookup during import
     */
    private function _initCategoryNamesWebsitesToIds()
    {
        $this->_categoryNamesWebsitesToIds = array();

        $select = $this->_connection->select()
            ->from(array('cat' => $this->_categoryTable), array('cat.entity_id'))
            ->joinLeft(
                array('ea' => $this->_eavAttributeTable),
                '(ea.entity_type_id = cat.entity_type_id AND ea.attribute_code = \'name\')',
                array()
            )->joinLeft(
                array('attr' => $this->_varcharAttributeTable),
                '(ea.entity_type_id = attr.entity_type_id AND ea.attribute_id = attr.attribute_id AND cat.entity_id = attr.entity_id)',
                array('attr.value')
            )->joinLeft(
                array('store' => $this->_storeTable),
                '(attr.store_id = store.store_id)',
                array('store.website_id')
            );

        $stmt = $this->_connection->query($select);
        while ($row = $stmt->fetch(Zend_Db::FETCH_NUM)) {
            $this->_categoryNamesWebsitesToIds[$row[1] . '-' . $row[2]] = $row[0];
        }
    }

    /**
     * Get the website ids for a given currency
     *
     * @param array $row
     * @return array of website ids
     * @throws InvalidArgumentException
     */
    private function _getWebsiteIds(array $row)
    {
        $currencies = explode(',', $row[self::COL_CURRENCY]);
        $websiteIds = array();

        foreach ($currencies as $item) {
            if (!isset($this->_currencyToWebsite[$item])) {
                throw new InvalidArgumentException('Row contains unsupported currency', self::ERROR_INVALID_CURRENCY);
            }
            foreach ($this->_currencyToWebsite[$item] as $id) {
                $websiteIds[] = $id;
            }
        }

        return $websiteIds;
    }

    /**
     * Get all group ids that should be applied to the coupon
     * In reality we are just using the guest id
     * And the validation model will check if the coupon was created from import
     * Change the guest id to a wildcard, and validate for any group
     *
     * @return array
     */
    private function _getGroupIds()
    {
        if (isset($this->_groupIds)) {
            return $this->_groupIds;
        }

        $this->_groupIds = array(
            self::DEFAULT_GROUP_ID,
        );

        return $this->_groupIds;
    }

    /**
     * Determines the value to be used for the simple action field
     *
     * @param array $row
     * @return string the simple action
     */
    private function _getSimpleAction(array $row)
    {
        if (isset($row[self::COL_GET_PERCENT_OFF]) && $row[self::COL_GET_PERCENT_OFF] > 0) {
            return self::SIMPLE_ACTION_PERCENT;
        }

        $row[self::COL_GET_AMOUNT] = trim(str_replace('$', '', $row[self::COL_GET_AMOUNT]));
        if (isset($row[self::COL_GET_AMOUNT]) && $row[self::COL_GET_AMOUNT] > 0) {
            return self::SIMPLE_ACTION_CART_FIXED;
        }

        return self::SIMPLE_ACTION_BUY_X_GET_Y;
    }

    /**
     * Get the discount amount
     * determines the value to use for the discount amount field
     *
     * @param array $row
     * @return float|int
     */
    private function _getDiscountAmount(array $row)
    {
        if ($this->_getSimpleAction($row) == self::SIMPLE_ACTION_PERCENT) {
            return $row[self::COL_GET_PERCENT_OFF];
        }

        if ($this->_getSimpleAction($row) == self::SIMPLE_ACTION_FIXED
            || $this->_getSimpleAction($row) == self::SIMPLE_ACTION_CART_FIXED)
        {
            $row[self::COL_GET_AMOUNT] = trim(str_replace('$', '', $row[self::COL_GET_AMOUNT]));
            return isset($row[self::COL_GET_AMOUNT]) ? $row[self::COL_GET_AMOUNT] : 0;
        }

        return isset($row[self::COL_GET_QUANTITY]) ? $row[self::COL_GET_QUANTITY] : 0;
    }

    /**
     * Get a rule id from a given coupon name
     *
     * @param string $couponName
     * @return int rule id
     */
    private function _getRuleIdByCouponName($couponName)
    {
        if (isset($this->_ruleIds[$couponName]) && $this->_ruleIds[$couponName]) {
            return $this->_ruleIds[$couponName];
        }

        $select = $this->_connection->select()
            ->from($this->_ruleTable, 'rule_id')
            ->where('name = :couponName');

        $bind = array(
            ':couponName' => (string)$couponName,
        );

        $this->_ruleIds[$couponName] = $this->_connection->fetchOne($select, $bind);

        return $this->_ruleIds[$couponName];
    }

    /**
     * check if a rule already exists given the rule name
     *
     * @param string $ruleName
     * @return bool if the rule name exists
     */
    private function _ruleExists($ruleName)
    {
        return (bool)$this->_getRuleIdByCouponName($ruleName);
    }

    /**
     * A query to check if a coupon already exists given the coupon code
     *
     * @param string $couponCode
     * @return bool if the code exists
     */
    private function _couponExists($couponCode)
    {
        $select = $this->_connection->select()
            ->from($this->_couponTable, 'coupon_id')
            ->where('code = :couponCode');

        $bind = array(
            ':couponCode' => (string)$couponCode,
        );

        return (bool)$this->_connection->fetchOne($select, $bind);
    }

    /**
     * Get an array of rule ids from their corresponding coupon codes
     *
     * @param array $codes
     * @return array rule ids
     */
    private function _getRuleIdsByCouponCodes(array $codes)
    {
        $select = $this->_connection->select()
            ->from($this->_couponTable, 'rule_id')
            ->where('code IN(?)', $codes);

        return (array)$this->_connection->fetchCol($select);
    }

    /**
     * Prepare the conditions and serialize them
     *
     * @param array $row
     * @return string serialized data
     */
    private function _getConditionsSerialized(array $row)
    {
        $rule = Mage::getModel('salesrule/rule');

        $rule = $this->_addReqQuantityCondition($rule, $row);
        $rule = $this->_addReqAmountCondition($rule, $row);
        $rule = $this->_addItemFoundCondition($rule, $row);

        $result = serialize($rule->getConditions()->asArray());

        return $result;
    }

    /**
     * Add the min amount condition
     *
     * @param Mage_SalesRule_Model_Rule $rule
     * @param array $row
     * @return Mage_SalesRule_Model_Rule $rule
     */
    private function _addReqAmountCondition($rule, $row)
    {
        if (empty($row[self::COL_REQ_AMOUNT])) {
            return $rule;
        }

        $condition = Mage::getModel('salesrule/rule_condition_address')
            ->setType('salesrule/rule_condition_address')
            ->setAttribute('base_subtotal')
            ->setOperator('>=')
            ->setValue(str_replace('$', '', $row[self::COL_REQ_AMOUNT]));

        $rule->getConditions()->addCondition($condition);
        $rule->setReqAmountCondition($condition);

        return $rule;
    }

    /**
     * Add the min quantity condition
     *
     * @param Mage_SalesRule_Model_Rule $rule
     * @param array $row
     * @return Mage_SalesRule_Model_Rule $rule
     */
    private function _addReqQuantityCondition($rule, $row)
    {
        if (empty($row[self::COL_REQ_QUANTITY])) {
            return $rule;
        }

        $condition = Mage::getModel('salesrule/rule_condition_address')
            ->setType('salesrule/rule_condition_address')
            ->setAttribute('total_qty')
            ->setOperator('>=')
            ->setValue($row[self::COL_REQ_QUANTITY]);

        $rule->getConditions()->addCondition($condition);

        return $rule;
    }

    /**
     * Prepare the actions and serialize them
     *
     * @param array $row
     * @return string the serialized action data
     */
    private function _getActionsSerialized(array $row)
    {
        $rule = Mage::getModel('salesrule/rule');
        $rule = $this->_addItemFoundAction($rule, $row);

        $result = serialize($rule->getActions()->asArray());

        return $result;
    }

    /**
     * Add the item specific conditions for action
     *
     * @param Mage_SalesRule_Model_Rule $rule
     * @param array $row
     * @return Mage_SalesRule_Model_Rule $rule
     */
    private function _addItemFoundAction($rule, array $row)
    {
        if (empty($row[self::COL_GET_COURSE_PARTS])
            && empty($row[self::COL_GET_PRICE_GT])
            && empty($row[self::COL_GET_COURSE_TYPE])
            && empty($row[self::COL_GET_SALE_STATUS])
            && empty($row[self::COL_GET_COURSE_ID])
            && empty($row[self::COL_GET_FORMAT])
            && empty($row[self::COL_GET_CATEGORY])) {
            //no product specific requirements
            return $rule;
        }

        $itemFound = Mage::getModel('salesrule/rule_condition_product_found')
            ->setType('salesrule/rule_condition_product_found')
            ->setValue(1)
            ->setAggregator('all');
        $rule->getActions()->addCondition($itemFound);

        if ($coursePartsCondition = $this->_getCoursePartsCondition($row[self::COL_GET_COURSE_PARTS])) {
            $itemFound->addCondition($coursePartsCondition);
        }
        if ($priceGtCondition = $this->_getPriceGtCondition($row[self::COL_GET_PRICE_GT])) {
            $itemFound->addCondition($priceGtCondition);
        }
        if ($courseTypeCondition = $this->_getCourseTypeCondition($row[self::COL_GET_COURSE_TYPE])) {
            $itemFound->addCondition($courseTypeCondition);
        }
        if ($saleStatusCondition = $this->_getSaleStatusCondition($row[self::COL_GET_SALE_STATUS])) {
            $itemFound->addCondition($saleStatusCondition);
        }
        if ($courseIdCondition = $this->_getCourseIdCondition($row[self::COL_GET_COURSE_ID])) {
            $itemFound->addCondition($courseIdCondition);
        }
        if ($formatCondition = $this->_getFormatCondition($row[self::COL_GET_FORMAT])) {
            $itemFound->addCondition($formatCondition);
        }
        if ($categoryCondition = $this->_getCategoryCondition($row[self::COL_GET_CATEGORY], $row)) {
            $itemFound->addCondition($categoryCondition);
        }

        return $rule;
    }

    /**
     * Add the item specific conditions
     *
     * @param Mage_SalesRule_Model_Rule $rule
     * @param array $row
     * @return Mage_SalesRule_Model_Rule $rule
     */
    private function _addItemFoundCondition($rule, array $row)
    {
        if (empty($row[self::COL_REQ_COURSE_PARTS])
            && empty($row[self::COL_REQ_PRICE_GT])
            && empty($row[self::COL_REQ_COURSE_TYPE])
            && empty($row[self::COL_REQ_SALE_STATUS])
            && empty($row[self::COL_REQ_COURSE_ID])
            && empty($row[self::COL_REQ_FORMAT])
            && empty($row[self::COL_REQ_CATEGORY])) {
            //no product specific requirements
            return $rule;
        }

        $itemFound = Mage::getModel('salesrule/rule_condition_product_found')
            ->setType('salesrule/rule_condition_product_found')
            ->setValue(1)
            ->setAggregator('all');
        $rule->getConditions()->addCondition($itemFound);

        if ($coursePartsCondition = $this->_getCoursePartsCondition($row[self::COL_REQ_COURSE_PARTS])) {
            $itemFound->addCondition($coursePartsCondition);
        }
        if ($priceGtCondition = $this->_getPriceGtCondition($row[self::COL_REQ_PRICE_GT])) {
            $itemFound->addCondition($priceGtCondition);
        }
        if ($courseTypeCondition = $this->_getCourseTypeCondition($row[self::COL_REQ_COURSE_TYPE])) {
            $itemFound->addCondition($courseTypeCondition);
        }
        if ($saleStatusCondition = $this->_getSaleStatusCondition($row[self::COL_REQ_SALE_STATUS])) {
            $itemFound->addCondition($saleStatusCondition);
        }
        if ($courseIdCondition = $this->_getCourseIdCondition($row[self::COL_REQ_COURSE_ID])) {
            $itemFound->addCondition($courseIdCondition);
        }
        if ($formatCondition = $this->_getFormatCondition($row[self::COL_REQ_FORMAT])) {
            $itemFound->addCondition($formatCondition);
        }
        if ($categoryCondition = $this->_getCategoryCondition($row[self::COL_REQ_CATEGORY], $row)) {
            $itemFound->addCondition($categoryCondition);
        }

        return $rule;
    }

    /**
     * This creates the condition for category_ids attribute
     *
     * @param string $categories can be a comma separated value
     * @param array $row
     * @return Mage_SalesRule_Model_Rule_Condition_Product
     */
    private function _getCategoryCondition($categories, $row)
    {
        if (empty($categories)) {
            return false;
        }

        $categoryIds = $this->_getCategoryIdsFromNames($categories, $row);

        $condition = Mage::getModel('salesrule/rule_condition_product')
            ->setType('salesrule/rule_condition_product')
            ->setAttribute('category_ids')
            ->setOperator('()')
            ->setValue(join(',', $categoryIds));

        return $condition;
    }

    /**
     * Gets an array of category ids from a comma separated list of names
     *
     * @param string $categories
     * @param array $row
     * @return array of category ids
     * @throws InvalidArgumentException
     */
    private function _getCategoryIdsFromNames($categories, $row)
    {
        $categoryIds = array();
        $categoryNames = explode(',', $categories);
        $websiteIds = $this->_getWebsiteIds($row);
        foreach ($websiteIds as $id) {
            foreach ($categoryNames as $name) {
                $name = trim($name);
                if (!isset($this->_categoryNamesWebsitesToIds[$name . '-' . $id])) {
                    if (!isset($this->_categoryNamesWebsitesToIds[$name . '-0'])) {
                        $message = Mage::helper('tgc_dax')->__(
                            'A category with name: %s does not exist', $name
                        );
                        throw new InvalidArgumentException($message);
                    }
                    $categoryIds[] = $this->_categoryNamesWebsitesToIds[$name . '-0'];
                } else {
                    $categoryIds[] = $this->_categoryNamesWebsitesToIds[$name . '-' . $id];
                }
            }
        }

        return array_filter(array_unique($categoryIds));
    }

    /**
     * This creates the condition for the media_format attribute
     *
     * @param string $formats can be a comma separated value
     * @return Mage_SalesRule_Model_Rule_Condition_Product
     */
    private function _getFormatCondition($formats)
    {
        if (empty($formats)) {
            return false;
        }

        $formats    = $this->_prepareFormats($formats);
        $isMultiple = count($formats) > 1 ? true : false;

        $condition = Mage::getModel('salesrule/rule_condition_product')
            ->setType('salesrule/rule_condition_product')
            ->setAttribute('media_format')
            ->setOperator($isMultiple ? '()' : '==')
            ->setValue(join(',', $formats));

        return $condition;
    }

    /**
     * This is a mapping of our available course type codes to their option id
     */
    private function _initCourseTypeCodes()
    {
        $attribute = Mage::getSingleton('eav/config')->getAttribute(Mage_Catalog_Model_Product::ENTITY, 'course_type_code');
        $source    = $attribute->getSource();
        $this->_courseTypeCodes = array(
            '2' => $source->getOptionId('Set'),
            '1' => $source->getOptionId('Course'),
        );
    }

    /**
     * This is a mapping of our available media formats to their option id
     */
    private function _initMediaFormats()
    {
        $attribute = Mage::getSingleton('eav/config')->getAttribute(Mage_Catalog_Model_Product::ENTITY, 'media_format');
        $source    = $attribute->getSource();
        $this->_mediaFormats = array(
            self::DVD                 => $source->getOptionId(Tgc_DigitalLibrary_Model_Observer::DVD),
            self::CD                  => $source->getOptionId(Tgc_DigitalLibrary_Model_Observer::CD),
            self::AUDIO_DOWNLOAD      => $source->getOptionId(Tgc_DigitalLibrary_Model_Observer::AUDIO_DOWNLOAD),
            self::VIDEO_DOWNLOAD      => $source->getOptionId(Tgc_DigitalLibrary_Model_Observer::VIDEO_DOWNLOAD),
            self::CD_SOUNDTRACK       => $source->getOptionId(Tgc_DigitalLibrary_Model_Observer::CD_SOUNDTRACK),
            self::SOUNDTRACK_DOWNLOAD => $source->getOptionId(Tgc_DigitalLibrary_Model_Observer::SOUNDTRACK_DOWNLOAD),
            self::TRANSCRIPT_BOOK     => $source->getOptionId(Tgc_DigitalLibrary_Model_Observer::TRANSCRIPT_BOOK),
            self::DIGITAL_TRANSCRIPT  => $source->getOptionId(Tgc_DigitalLibrary_Model_Observer::DIGITAL_TRANSCRIPT),
        );
    }

    /**
     * Takes a list of comma separated formats and prepares them into an array of the option ids
     *
     * @param $formats
     * @return array
     * @throws InvalidArgumentException
     */
    private function _prepareFormats($formats)
    {
        $formats    = explode('|', $formats);
        $newFormats = array();
        foreach ($formats as $format) {
            $format = trim($format);
            if (!isset($this->_mediaFormats[$format])) {
                $message = Mage::helper('tgc_dax')->__(
                    'There is no format with name: %s',
                    $format
                );
                throw new InvalidArgumentException($message);
            }
            $newFormats[] = $this->_mediaFormats[$format];
            //soundtracks are simply cd or audio download
            if ($this->_mediaFormats[$format] == Tgc_DigitalLibrary_Model_Observer::CD || $this->_mediaFormats[$format] == Tgc_DigitalLibrary_Model_Observer::AUDIO_DOWNLOAD) {
                $newFormats[] = $this->_mediaFormats[Tgc_DigitalLibrary_Model_Observer::CD_SOUNDTRACK];
                $newFormats[] = $this->_mediaFormats[Tgc_DigitalLibrary_Model_Observer::SOUNDTRACK_DOWNLOAD];
            }
        }

        return array_filter(array_unique($newFormats));
    }

    /**
     * Takes a list of comma separated course type codes and prepares them into an array of the option ids
     *
     * @param $courseTypeCodes
     * @return array
     * @throws InvalidArgumentException
     */
    private function _prepareCourseTypeCodes($courseTypeCodes)
    {
        $codes    = explode('|', $courseTypeCodes);
        $newCodes = array();
        foreach ($codes as $code) {
            $codeConverted = trim(strtolower($code));
            if (!isset($this->_courseTypeCodes[$codeConverted])) {
                $message = Mage::helper('tgc_dax')->__(
                    'There is no course type code with name: %s',
                    $code
                );
                throw new InvalidArgumentException($message);
            }
            $newCodes[] = $this->_courseTypeCodes[$codeConverted];
        }

        return array_filter(array_unique($newCodes));
    }

    /**
     * This creates the condition for the course_id attribute
     *
     * @param string $courseIds can be a comma separated value
     * @return Mage_SalesRule_Model_Rule_Condition_Product
     */
    private function _getCourseIdCondition($courseIds)
    {
        if (empty($courseIds)) {
            return false;
        }

        $parts   = explode('|', $courseIds);
        $isArray = count($parts) > 1 ? true : false;

        $condition = Mage::getModel('salesrule/rule_condition_product')
            ->setType('salesrule/rule_condition_product')
            ->setAttribute('course_id')
            ->setOperator($isArray ? '()' : '==')
            ->setValue(join(',', $parts));

        return $condition;
    }

    /**
     * This creates the condition for whether a product has sale status
     *
     * @param string $saleStatus will be TRUE or FALSE
     * @return Mage_SalesRule_Model_Rule_Condition_Product
     */
    private function _getSaleStatusCondition($saleStatus)
    {
        if (empty($saleStatus)) {
            return false;
        }

        $condition = Mage::getModel('salesrule/rule_condition_product')
            ->setType('salesrule/rule_condition_product')
            ->setAttribute('on_sale_flag')
            ->setOperator('==')
            ->setValue('1');

        return $condition;
    }

    /**
     * This creates the condition for the course_type_code attribute
     *
     * @param string $courseTypes can be a comma separated value
     * @return Mage_SalesRule_Model_Rule_Condition_Product
     */
    private function _getCourseTypeCondition($courseTypes)
    {
        if (empty($courseTypes)) {
            return false;
        }

        $codes      = $this->_prepareCourseTypeCodes($courseTypes);
        $isMultiple = count($codes) > 1 ? true : false;

        $condition = Mage::getModel('salesrule/rule_condition_product')
            ->setType('salesrule/rule_condition_product')
            ->setAttribute('course_type_code')
            ->setOperator($isMultiple ? '()' : '==')
            ->setValue(join(',', $codes));

        return $condition;
    }

    /**
     * This creates the condition for the price attribute
     *
     * @param float $priceGt
     * @return Mage_SalesRule_Model_Rule_Condition_Product
     */
    private function _getPriceGtCondition($priceGt)
    {
        if (empty($priceGt)) {
            return false;
        }

        //will compare against the quote item price which is the final price in the cart
        $condition = Mage::getModel('salesrule/rule_condition_product')
            ->setType('salesrule/rule_condition_product')
            ->setAttribute('quote_item_price')
            ->setOperator('>=')
            ->setValue($priceGt);

        return $condition;
    }

    /**
     * This creates the condition for the course_parts attribute
     *
     * @param string $courseParts a numeric value
     * @return Mage_SalesRule_Model_Rule_Condition_Product
     */
    private function _getCoursePartsCondition($courseParts)
    {
        if (empty($courseParts)) {
            return false;
        }

        $condition = Mage::getModel('salesrule/rule_condition_product')
            ->setType('salesrule/rule_condition_product')
            ->setAttribute('course_parts')
            ->setOperator('>=')
            ->setValue($courseParts);

        return $condition;
    }

    private function _validateRequiredAttributes(array $row)
    {
        foreach ($this->_requiredAttributes as $attribute) {
            if (empty($row[$attribute])) {
                $message = Mage::helper('tgc_dax')->__(
                    'Column: %s is required and cannot be empty',
                    $attribute
                );
                throw new InvalidArgumentException($message);
            }
        }
    }
}
