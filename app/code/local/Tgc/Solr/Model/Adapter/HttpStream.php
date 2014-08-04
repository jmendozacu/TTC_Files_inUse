<?php
/**
 * Solr search
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Solr
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Solr_Model_Adapter_HttpStream extends Mana_Filters_Model_Solr_Adapter_HttpStream
{
    /**
     *
     */
    protected function _getAutoCompleteRequest($query, $params = array())
    {
        $autoComplete = true;
        $searchConditions = $this->prepareSearchConditions($query, $autoComplete);
        if (!$searchConditions) {
            return array();
        }

        $_params = $this->_defaultQueryParams;
        if (is_array($params) && !empty($params)) {
            $_params = array_intersect_key($params, $_params) + array_diff_key($_params, $params);
        }

        $offset = (isset($_params['offset'])) ? (int) $_params['offset'] : 0;
        $limit  = (isset($_params['limit']))
            ? (int) $_params['limit']
            : Enterprise_Search_Model_Adapter_Solr_Abstract::DEFAULT_ROWS_LIMIT;

        $languageSuffix = $this->_getLanguageSuffix($params['locale_code']);
        $searchParams   = array();

        if (!is_array($_params['fields'])) {
            $_params['fields'] = array($_params['fields']);
        }

        if (!is_array($_params['solr_params'])) {
            $_params['solr_params'] = array($_params['solr_params']);
        }

        /**
         * Add sort fields
         */
        $sortFields = $this->_prepareSortFields($_params['sort_by']);
        foreach ($sortFields as $sortField) {
            $searchParams['sort'][] = $sortField['sortField'] . ' ' . $sortField['sortType'];
        }

        //CUSTOM CODE
        //Magento bug: Request to Solr with sorting by multiple fields was prepared incorrectly:
        // Was: sort=field1 asc&sort=field2 asc
        // Should be: sort=field1 asc,field2 asc
        if (!empty($searchParams['sort'])) {
            $searchParams['sort'] = implode(',', $searchParams['sort']);
        }
        //CUSTOM CODE END

        /**
         * Fields to retrieve
         */
        if ($limit && !empty($_params['fields'])) {
            $searchParams['fl'] = implode(',', $_params['fields']);
        }

        /**
         * Now supported search only in fulltext and name fields based on dismax requestHandler (named as magento_lng).
         * Using dismax requestHandler for each language make matches in name field
         * are much more significant than matches in fulltext field.
         */
        if ($_params['ignore_handler'] !== true) {
            $_params['solr_params']['qt'] = 'magento' . $languageSuffix;
        }

        /**
         * Facets search
         */
        $useFacetSearch = (isset($params['solr_params']['facet']) && $params['solr_params']['facet'] == 'on');
        if ($useFacetSearch) {
            $searchParams += $this->_prepareFacetConditions($params['facet']);
        }

        /**
         * Suggestions search
         */
        $useSpellcheckSearch = isset($params['solr_params']['spellcheck'])
            && $params['solr_params']['spellcheck'] == 'true';

        if ($useSpellcheckSearch) {
            if (isset($params['solr_params']['spellcheck.count'])
                && (int) $params['solr_params']['spellcheck.count'] > 0
            ) {
                $spellcheckCount = (int) $params['solr_params']['spellcheck.count'];
            } else {
                $spellcheckCount = self::DEFAULT_SPELLCHECK_COUNT;
            }

            $_params['solr_params'] += array(
                'spellcheck.collate'         => 'true',
                'spellcheck.dictionary'      => 'magento_spell' . $languageSuffix,
                'spellcheck.extendedResults' => 'true',
                'spellcheck.count'           => $spellcheckCount
            );
        }

        /**
         * Specific Solr params
         */
        if (!empty($_params['solr_params'])) {
            foreach ($_params['solr_params'] as $name => $value) {
                $searchParams[$name] = $value;
            }
        }

        $searchParams['fq'] = $this->_prepareFilters($_params['filters']);

        /**
         * Store filtering
         */
        if ($_params['store_id'] > 0) {
            $searchParams['fq'][] = 'store_id:' . $_params['store_id'];
        }
        if (!Mage::helper('cataloginventory')->isShowOutOfStock()) {
            $searchParams['fq'][] = 'in_stock:true';
        }

        $searchParams['fq'] = implode(' AND ', $searchParams['fq']);

        try {
            $this->ping();
            $response = $this->_client->search(
                $searchConditions, $offset, $limit, $searchParams, Apache_Solr_Service::METHOD_POST
            );
            $data = json_decode($response->getRawResponse());

            $result = $data->highlighting;

            return $result;
        } catch (Exception $e) {
            Mage::logException($e);
        }
    }

    /**
     * Search documents in Solr index sorted by relevance
     *
     * @param string $query
     * @param array $params
     * @return array
     */
    public function getAutoCompleteRequest($query, $params = array())
    {
        return $this->_getAutoCompleteRequest($query, $params);
    }

    /**
     * Prepare search conditions from query
     *
     * @param string|array $query
     * @param bool $autoComplete
     *
     * @return string
     */
    protected function prepareSearchConditions($query, $autoComplete = false)
    {
        if (is_array($query)) {
            $searchConditions = array();
            foreach ($query as $field => $value) {
                if (is_array($value)) {
                    if ($field == 'price' || isset($value['from']) || isset($value['to'])) {
                        $from = (isset($value['from']) && strlen(trim($value['from'])))
                            ? $this->_prepareQueryText($value['from'])
                            : '*';
                        $to = (isset($value['to']) && strlen(trim($value['to'])))
                            ? $this->_prepareQueryText($value['to'])
                            : '*';
                        $fieldCondition = "$field:[$from TO $to]";
                    } else {
                        $fieldCondition = array();
                        foreach ($value as $part) {
                            $part = $this->_prepareFilterQueryText($part);
                            $fieldCondition[] = $field .':'. $part;
                        }
                        $fieldCondition = '('. implode(' OR ', $fieldCondition) .')';
                    }
                } else {
                    if ($value != '*') {
                        $value = $this->_prepareQueryText($value);
                    }
                    $fieldCondition = $field .':'. $value;
                }

                $searchConditions[] = $fieldCondition;
            }
            if ($autoComplete) {
                $searchConditions = implode(' OR ', $searchConditions);
            } else {
                $searchConditions = implode(' AND ', $searchConditions);
            }
        } else {
            $searchConditions = $this->_prepareQueryText($query);
        }

        return $searchConditions;
    }

    /**
     * Solr condition renderer.
     * The method was taken from Manadev extension.
     * One more option was added for having "empty" or "null" records in the result.
     *
     * @param string $field
     * @param array|string|int $parts
     * @return array
     */
    protected function _mRenderCondition($field, $parts)
    {
        $fieldCondition = array();
        foreach ($parts as $key => $part) {
            if (is_array($part) && (isset($part['from']) || isset($part['to']))) {
                if ($this->coreHelper()->startsWith($field, 'min-max:')) {
                    list($minField, $maxField) = explode(',', substr($field, strlen('min-max:')));
                    $fieldCondition[] = $this->_mRenderMinMaxRangeCondition($minField, $maxField, $part);
                } else {
                    $fieldCondition[] = $this->_mRenderRangeCondition($field, $part);
                }
            //CUSTOM CODE
            // processing of "null" => true parameter was added.
            // needed for adding rows to the result, which have also empty values of some attribute.
            } elseif (is_array($part) && !empty($part['null'])) {
                $fieldCondition[] = $this->_renderNullCondition($field);
            //CUSTOM CODE END
            //CUSTOM CODE
            // processing of "or" => array(), "and" => array() parameters was added.
            // needed for building multiple conditions with AND and OR.
            } elseif (is_array($part) && ($key == 'or' || $key == 'and')) {
                $fieldData = $this->_prepareFilters(array($field => array($key => $part)));
                if ($fieldData) {
                    $fieldCondition[] = $fieldData[0];
                }
            //CUSTOM CODE END
            } else {
                $fieldCondition[] = $this->_mRenderEqCondition($field, $part);
            }
        }

        return $fieldCondition;
    }

    /**
     * Renders Null condition for Solr.
     *
     * @param string $field
     * @return string
     */
    protected function _renderNullCondition($field)
    {
        return "(*:* AND -{$field}:[* TO *])";
    }

    /**
     * Asterisk should not be escaped
     *
     * @param string $value
     * @return string
     */
    public function _escape($value)
    {
        $pattern = '/(\+|-|&&|\|\||!|\(|\)|\{|}|\[|]|\^|"|~|\?|:|\\\)/';
        $replace = '\\\$1';

        return preg_replace($pattern, $replace, $value);
    }

    /**
     * Create Solr Input Documents by specified data
     *
     * @param   array $docData
     * @param   int $storeId
     *
     * @return  array
     */
    public function prepareDocsPerStore($docData, $storeId)
    {
        if (!is_array($docData) || empty($docData)) {
            return array();
        }

        //add courses info to sets
        $this->_addSetAttributes($docData, $storeId);
        $this->_addOnSaleData($docData, $storeId);

        return parent::prepareDocsPerStore($docData, $storeId);
    }

    protected function _addSetAttributes(&$docData, $storeId)
    {
        $productIds = array_keys($docData);
        $coursesData = Mage::getResourceSingleton('tgc_solr/index')
            ->getCourseDataForSets($productIds, $storeId);
        foreach ($docData as $productId => &$productIndexData) {
            if (isset($coursesData[$productId])) {
                foreach ($coursesData[$productId] as $courseTextData) {
                    foreach ($courseTextData as $attributeName => $attributeValue) {
                        if (!isset($productIndexData[$attributeName][$productId])) {
                            $productIndexData[$attributeName][$productId] = '';
                        }
                        $productIndexData[$attributeName][$productId] .= ' '.strip_tags($attributeValue);
                    }
                }
            }
        }
    }

    protected function _addOnSaleData(&$docData, $storeId)
    {
        //index all_types attribute for on_sale flag
        if (isset($this->_indexableAttributeParams['all_types'])) {
            $productIds = array_keys($docData);
            $onSaleData = Mage::getResourceSingleton('tgc_solr/index')
                ->getOnSaleIndexData($productIds, $storeId);

            foreach ($docData as $productId => &$doc) {
                $allTypesCurrentValues =
                    isset($doc['all_types']) && isset($doc['all_types'][$productId])
                    ? explode(',', $doc['all_types'][$productId]) : array();
                $allTypes = $allTypesCurrentValues;
                if (!empty($onSaleData[$productId])) {
                    $websiteId = Mage::app()->getStore($storeId)->getWebsiteId();
                    $onSale = $onSaleData[$productId];
                    foreach ($onSale as $customerGroupId => $value) {
                        $onSaleOption = Mage::helper('tgc_solr')->getOnSaleOptionValue($customerGroupId, $websiteId);
                        $allTypes[] = $onSaleOption;
                    }
                }
                if ($allTypes) {
                    $doc['all_types'][$productId] = $allTypes;
                }
            }
        }
    }

    protected function _prepareIndexProductData($productIndexData, $productId, $storeId)
    {
        if (!$this->isAvailableInIndex($productIndexData, $productId)) {
            return false;
        }

        $fulltextData = array();
        $fulltextSpell = array();
        $courseId = null;
        foreach ($productIndexData as $attributeCode => $value) {

            if ($attributeCode == 'course_id') {
                $courseId = isset($value[$productId]) ? $value[$productId] : null;
            }

            if ($attributeCode == 'visibility') {
                $productIndexData[$attributeCode] = $value[$productId];
                continue;
            }

            if ($attributeCode == 'institution') {
                if (!isset($value[$productId])) {
                    continue;
                }
                $productIndexData['attr_institution_en'] = $value[$productId];
                $fulltextSpell[] = $value[$productId];
                unset($productIndexData[$attributeCode]);
                continue;
            }

            if ($attributeCode == 'professor_teaching') {
                if (!isset($value[$productId])) {
                    continue;
                }
                $fulltextData[6][] = $value[$productId]; //6 is search weight
                unset($productIndexData[$attributeCode]);
                continue;
            }

            if ($attributeCode == 'professor_alma_mater') {
                if (!isset($value[$productId])) {
                    continue;
                }
                $fulltextData[5][] = $value[$productId]; //5 is search weight
                unset($productIndexData[$attributeCode]);
                continue;
            }

            if ($attributeCode == 'professor') {
                if (!isset($value[$productId])) {
                    continue;
                }
                $attribute = $this->_indexableAttributeParams[$attributeCode];
                $productIndexData['attr_professor_en'] = $value[$productId];
                $fulltextSpell[] = $value[$productId];
                $fulltextData[$attribute->getSearchWeight()][] = $value[$productId];
                unset($productIndexData[$attributeCode]);
                continue;
            }

            if ($attributeCode == 'attribute_set_id') {
                if (!empty($value)) {
                    $productIndexData['attr_sort_attribute_set_id_en'] = $value;
                }
                unset($productIndexData[$attributeCode]);
                continue;
            }

            if ($attributeCode == 'all_types') {
                continue;
            }

            // Prepare processing attribute info
            if (isset($this->_indexableAttributeParams[$attributeCode])) {
                /* @var $attribute Mage_Catalog_Model_Resource_Eav_Attribute */
                $attribute = $this->_indexableAttributeParams[$attributeCode];
            } else {
                $attribute = null;
            }

            // Prepare values for required fields
            if (!in_array($attributeCode, $this->_usedFields)) {
                unset($productIndexData[$attributeCode]);
            }

            if (!$attribute || $attributeCode == 'price' || empty($value)) {
                continue;
            }

            $attribute->setStoreId($storeId);

            // Preparing data for solr fields
            if ($attribute->getIsSearchable() || $attribute->getIsVisibleInAdvancedSearch()
                || $attribute->getIsFilterable() || $attribute->getIsFilterableInSearch()
                || $attribute->getUsedForSortBy()
            ) {
                $backendType = $attribute->getBackendType();
                $frontendInput = $attribute->getFrontendInput();

                if ($attribute->usesSource()) {
                    if ($frontendInput == 'multiselect') {
                        $preparedValue = array();
                        foreach ($value as $val) {
                            $preparedValue = array_merge($preparedValue, explode(',', $val));
                        }
                        $preparedNavValue = $preparedValue;
                    } else {
                        // safe condition
                        if (!is_array($value)) {
                            $preparedValue = array($value);
                        } else {
                            $preparedValue = array_unique($value);
                        }

                        $preparedNavValue = $preparedValue;
                        // Ensure that self product value will be saved after array_unique function for sorting purpose
                        if (isset($value[$productId])) {
                            if (!isset($preparedNavValue[$productId])) {
                                $selfValueKey = array_search($value[$productId], $preparedNavValue);
                                unset($preparedNavValue[$selfValueKey]);
                                $preparedNavValue[$productId] = $value[$productId];
                            }
                        }
                    }

                    foreach ($preparedValue as $id => $val) {
                        $preparedValue[$id] = $attribute->getSource()->getIndexOptionText($val);
                    }
                } else {
                    $preparedValue = $value;
                    if ($attributeCode == 'guest_bestsellers' || $attributeCode == 'authenticated_bestsellers') {
                        if (empty($preparedValue) || empty($preparedValue[$productId]) ||
                            (abs((float)$preparedValue[$productId]) >= 0 && abs((float)$preparedValue[$productId]) < 0.000001)
                        ) {
                            $preparedValue = null;
                        }
                    } elseif ($backendType == 'datetime') {
                        if (is_array($value)) {
                            $preparedValue = array();
                            //CUSTOM CODE
                            //MAGENTO BUG IN PRODUCT SORTING BY DATETIME ATTRIBUTE IN SOLR
                            //$key variable has been added in 'foreach()'
                            //and "$preparedValue = array_unique($preparedValue);" has been commented
                            //as we shouldn't lose current product ID.
                            //which is ten used within "if ($attribute->getUsedForSortBy())" condition block
                            foreach ($value as $key => &$val) {
                                $val = $this->_getSolrDate($storeId, $val);
                                if (!empty($val)) {
                                    $preparedValue[$key] = $val;
                                }
                            }
                            unset($val); //clear link to value
                            //$preparedValue = array_unique($preparedValue);
                            //CUSTOM CODE END

                        } else {
                            $preparedValue = $this->_getSolrDate($storeId, $value);
                        }
                    }
                }
            }

            // Preparing data for sorting field
            if ($attribute->getUsedForSortBy()) {
                if (!empty($preparedValue) && is_array($preparedValue)) {
                    if (isset($preparedValue[$productId])) {
                        $sortValue = $preparedValue[$productId];
                    } else {
                        $sortValue = null;
                    }
                }

                if (!empty($sortValue)) {
                    $fieldName = $this->getSearchEngineFieldName($attribute, 'sort');

                    if ($fieldName) {
                        $productIndexData[$fieldName] = $sortValue;
                    }
                }
                //CUSTOM CODE
                //MAGENTO BUG IN PRODUCT SORTING BY DATETIME ATTRIBUTE IN SOLR
                //$sortValue is dated from previous sortable attribute for the case, when current
                //sortable attribute has empty value for current product.
                unset($sortValue);
                //CUSTOM CODE END
            }

            // Adding data for advanced search field (without additional prefix)
            if (($attribute->getIsVisibleInAdvancedSearch() ||  $attribute->getIsFilterable()
                || $attribute->getIsFilterableInSearch())
            ) {
                if ($attribute->usesSource()) {
                    $fieldName = $this->getSearchEngineFieldName($attribute, 'nav');
                    if ($fieldName && !empty($preparedNavValue)) {
                        $productIndexData[$fieldName] = $preparedNavValue;
                    }
                } else {
                    $fieldName = $this->getSearchEngineFieldName($attribute);
                    if ($fieldName && !empty($preparedValue)) {
                        $productIndexData[$fieldName] = in_array($backendType, $this->_textFieldTypes)
                            ? implode(' ', array_unique((array)$preparedValue))
                            : $preparedValue ;
                    }
                }
            }

            // Adding data for fulltext search field
            if ($attribute->getIsSearchable() && !empty($preparedValue)) {
                $searchWeight = $attribute->getSearchWeight();
                if ($searchWeight) {
                    $fulltextData[$searchWeight][] = is_array($preparedValue)
                        ? implode(' ', array_unique($preparedValue))
                        : $preparedValue;
                }
            }

            unset($preparedNavValue, $preparedValue, $fieldName, $attribute);
        }

        // Preparing fulltext search fields
        foreach ($fulltextData as $searchWeight => $data) {
            $fieldName = $this->getAdvancedTextFieldName('fulltext', $searchWeight, $storeId);
            $productIndexData[$fieldName] = $this->_implodeIndexData($data);
            $fulltextSpell = array_merge($fulltextSpell, is_array($data) ? $data : array($data));
        }
        unset($fulltextData);

        // Preparing field with spell info
        $fulltextSpell = array_unique($fulltextSpell);
        $fieldName = $this->getAdvancedTextFieldName('spell', '', $storeId);
        $productIndexData[$fieldName] = $this->_implodeIndexData($fulltextSpell);
        unset($fulltextSpell);

        // Getting index data for price
        if (isset($this->_indexableAttributeParams['price'])) {
            $priceEntityIndexData = $this->_preparePriceIndexData($productId, $storeId);
            $productIndexData = array_merge($productIndexData, $priceEntityIndexData);
        }

        // Product category index data definition
        $productCategoryIndexData = $this->_prepareProductCategoryIndexData($productId, $storeId);
        $productIndexData = array_merge($productIndexData, $productCategoryIndexData);

        //index all_types attribute for on_sale flag
        if (isset($this->_indexableAttributeParams['all_types']) &&
            !empty($productIndexData['all_types'][$productId])
        ) {
            $attribute = $this->_indexableAttributeParams['all_types'];
            $productIndexData[$this->getSearchEngineFieldName($attribute, 'nav')] =
                $productIndexData['all_types'][$productId];
        }
        if (array_key_exists('all_types', $productIndexData)) {
            unset($productIndexData['all_types']);
        }

        //add courseId
        if ($courseId) {
            $productIndexData['course_id'] = $courseId;
        }

        // Define system data for engine internal usage
        $productIndexData['id'] = $productId;
        $productIndexData['store_id'] = $storeId;
        $productIndexData[self::UNIQUE_KEY] = $productId . '|' . $storeId;

        return $productIndexData;
    }

    /**
     * @param int $productId
     * @param int $storeId
     * @param array $allTypesCurrentValues
     * @return array
     */
    protected function _prepareAllTypesOnSaleIndexData($productId, $storeId, $allTypesCurrentValues)
    {
        $allTypes = $allTypesCurrentValues;

        $onSaleProductData = Mage::getResourceSingleton('tgc_solr/index')
            ->getOnSaleIndexData(array($productId), $storeId);

        if (isset($onSaleProductData[$productId])) {
            $onSaleProductData = $onSaleProductData[$productId];

            $websiteId = Mage::app()->getStore($storeId)->getWebsiteId();
            foreach ($onSaleProductData as $customerGroupId => $value) {
                $onSaleOption = Mage::helper('tgc_solr')->getOnSaleOptionValue($customerGroupId, $websiteId);
                $allTypes[] = $onSaleOption;
            }
        }

        return $allTypes;
    }

    /**
     * Simple Search interface
     *
     * @param string $query The raw query string
     * @param array $params Params can be specified like this:
     *        'offset'      - The starting offset for result documents
     *        'limit        - The maximum number of result documents to return
     *        'sort_by'     - Sort field, can be just sort field name (and asceding order will be used by default),
     *                        or can be an array of arrays like this: array('sort_field_name' => 'asc|desc')
     *                        to define sort order and sorting fields.
     *                        If sort order not asc|desc - asceding will used by default
     *        'fields'      - Fields names which are need to be retrieved from found documents
     *        'solr_params' - Key / value pairs for other query parameters (see Solr documentation),
     *                        use arrays for parameter keys used more than once (e.g. facet.field)
     *        'locale_code' - Locale code, it used to define what suffix is needed for text fields,
     *                        by which will be performed search request and sorting
     *
     * @return array
     */
    protected function _search($query, $params = array())
    {
        $searchConditions = $this->prepareSearchConditions($query);
        if (!$searchConditions) {
            return array();
        }

        $_params = $this->_defaultQueryParams;
        if (is_array($params) && !empty($params)) {
            $_params = array_intersect_key($params, $_params) + array_diff_key($_params, $params);
        }

        $offset = (isset($_params['offset'])) ? (int) $_params['offset'] : 0;
        $limit  = (isset($_params['limit']))
            ? (int) $_params['limit']
            : Enterprise_Search_Model_Adapter_Solr_Abstract::DEFAULT_ROWS_LIMIT;

        $languageSuffix = $this->_getLanguageSuffix($params['locale_code']);
        $searchParams   = array();

        if (!is_array($_params['fields'])) {
            $_params['fields'] = array($_params['fields']);
        }

        if (!is_array($_params['solr_params'])) {
            $_params['solr_params'] = array($_params['solr_params']);
        }

        /**
         * Add sort fields
         */
        $sortFields = $this->_prepareSortFields($_params['sort_by']);
        foreach ($sortFields as $sortField) {
            $searchParams['sort'][] = $sortField['sortField'] . ' ' . $sortField['sortType'];
        }

        //CUSTOM CODE
        //Magento bug: Request to Solr with sorting by multiple fields was prepared incorrectly:
        // Was: sort=field1 asc&sort=field2 asc
        // Should be: sort=field1 asc,field2 asc
        if (!empty($searchParams['sort'])) {
            $searchParams['sort'] = implode(',', $searchParams['sort']);
        }
        //CUSTOM CODE END

        /**
         * Fields to retrieve
         */
        if ($limit && !empty($_params['fields'])) {
            $searchParams['fl'] = implode(',', $_params['fields']);
        }

        /**
         * Now supported search only in fulltext and name fields based on dismax requestHandler (named as magento_lng).
         * Using dismax requestHandler for each language make matches in name field
         * are much more significant than matches in fulltext field.
         */
        if ($_params['ignore_handler'] !== true) {
            $_params['solr_params']['qt'] = 'magento' . $languageSuffix;
        }

        /**
         * Facets search
         */
        $useFacetSearch = (isset($params['solr_params']['facet']) && $params['solr_params']['facet'] == 'on');
        if ($useFacetSearch) {
            $searchParams += $this->_prepareFacetConditions($params['facet']);
        }

        /**
         * Suggestions search
         */
        $useSpellcheckSearch = isset($params['solr_params']['spellcheck'])
            && $params['solr_params']['spellcheck'] == 'true';

        if ($useSpellcheckSearch) {
            if (isset($params['solr_params']['spellcheck.count'])
                && (int) $params['solr_params']['spellcheck.count'] > 0
            ) {
                $spellcheckCount = (int) $params['solr_params']['spellcheck.count'];
            } else {
                $spellcheckCount = self::DEFAULT_SPELLCHECK_COUNT;
            }

            $_params['solr_params'] += array(
                'spellcheck.collate'         => 'true',
                'spellcheck.dictionary'      => 'magento_spell' . $languageSuffix,
                'spellcheck.extendedResults' => 'true',
                'spellcheck.count'           => $spellcheckCount
            );
        }

        /**
         * Specific Solr params
         */
        if (!empty($_params['solr_params'])) {
            foreach ($_params['solr_params'] as $name => $value) {
                $searchParams[$name] = $value;
            }
        }

        $searchParams['fq'] = $this->_prepareFilters($_params['filters']);

        /**
         * Store filtering
         */
        if ($_params['store_id'] > 0) {
            $searchParams['fq'][] = 'store_id:' . $_params['store_id'];
        }
        if (!Mage::helper('cataloginventory')->isShowOutOfStock()) {
            $searchParams['fq'][] = 'in_stock:true';
        }

        $searchParams['fq'] = implode(' AND ', $searchParams['fq']);

        try {
            $this->ping();
            $response = $this->_client->search(
                $searchConditions, $offset, $limit, $searchParams, Apache_Solr_Service::METHOD_POST
            );
            $data = json_decode($response->getRawResponse());

            if (!isset($params['solr_params']['stats']) || $params['solr_params']['stats'] != 'true') {
                if ($limit > 0) {
                    $result = array('ids' => $this->_prepareQueryResponse($data));
                }

                /**
                 * Extract facet search results
                 */
                if ($useFacetSearch) {
                    $result['faceted_data'] = $this->_prepareFacetsQueryResponse($data);
                }

                /**
                 * Extract suggestions search results
                 */
                if ($useSpellcheckSearch) {
                    $resultSuggestions = $this->_prepareSuggestionsQueryResponse($data);
                    /* Calc results count for each suggestion */
                    if (isset($params['spellcheck_result_counts']) && $params['spellcheck_result_counts']
                        && count($resultSuggestions)
                        && $spellcheckCount > 0
                    ) {
                        /* Temporary store value for main search query */
                        $tmpLastNumFound = $this->_lastNumFound;

                        unset($params['solr_params']['spellcheck']);
                        unset($params['solr_params']['spellcheck.count']);
                        unset($params['spellcheck_result_counts']);

                        $suggestions = array();
                        foreach ($resultSuggestions as $key => $item) {
                            $this->_lastNumFound = 0;
                            $this->search($item['word'], $params);
                            if ($this->_lastNumFound) {
                                $resultSuggestions[$key]['num_results'] = $this->_lastNumFound;
                                $suggestions[] = $resultSuggestions[$key];
                                $spellcheckCount--;
                            }
                            if ($spellcheckCount <= 0) {
                                break;
                            }
                        }

                        /* Return store value for main search query */
                        $this->_lastNumFound = $tmpLastNumFound;
                    } else {
                        $suggestions = array_slice($resultSuggestions, 0, $spellcheckCount);
                    }
                    $result['suggestions_data'] = $suggestions;
                }
            } else {
                $result = $this->_prepateStatsQueryResponce($data);
            }

            return $result;
        } catch (Exception $e) {
            Mage::logException($e);
        }
    }

    /**
     * Converts date into Solr's date. Public method.
     *
     * @param int $storeId
     * @param Zend_Date $date
     * @return string
     */
    public function getSolrDate($storeId, $date = null)
    {
        return $this->_getSolrDate($storeId, $date);
    }

    /**
     * Prepare sort fields
     *
     * @param array $sortBy
     * @return array
     */
    protected function _prepareSortFields($sortBy)
    {
        $result = array();

        $localeCode = Mage::app()->getStore()->getConfig(Mage_Core_Model_Locale::XML_PATH_DEFAULT_LOCALE);
        $languageSuffix = $this->_getLanguageSuffix($localeCode);

        /**
         * Support specifying sort by field as only string name of field
         */
        if (!empty($sortBy) && !is_array($sortBy)) {
            if ($sortBy == 'relevance') {
                $sortBy = 'score';
            } elseif ($sortBy == 'name') {
                $sortBy = 'alphaNameSort' . $languageSuffix;
            } elseif ($sortBy == 'position') {
                $sortBy = 'position_category_' . Mage::registry('current_category')->getId();
            } elseif ($sortBy == 'price') {
                $websiteId       = Mage::app()->getStore()->getWebsiteId();
                $customerGroupId = Mage::getSingleton('customer/session')->getCustomerGroupId();

                $sortBy = 'price_'. $customerGroupId .'_'. $websiteId;
            }

            $sortBy = array(array($sortBy => 'asc'));
        }

        foreach ($sortBy as $sort) {
            $_sort = each($sort);
            $sortField = $_sort['key'];
            $sortType = $_sort['value'];
            if ($sortField == 'relevance') {
                $sortField = 'score';
            } elseif ($sortField == 'position') {
                $sortField = 'position_category_' . Mage::registry('current_category')->getId();
            } elseif ($sortField == 'price') {
                $sortField = $this->getPriceFieldName();
            //code, which makes "Set" products be shown first, when courseId matches search query
            } elseif (substr($sortField, 0, 14) == 'course_id_map:') {
                $queryValue = trim(substr($sortField, 14));
                $sortField = "map(course_id,{$queryValue},{$queryValue},1,0)";
            } else {
                $sortField = $this->getSearchEngineFieldName($sortField, 'sort');
            }

            $result[] = array('sortField' => $sortField, 'sortType' => trim(strtolower($sortType)));
        }

        return $result;
    }
}
