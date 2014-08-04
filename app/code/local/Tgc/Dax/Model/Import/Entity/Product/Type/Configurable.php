<?php
/**
 * Dax configurable product type for importexport
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Dax
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_Dax_Model_Import_Entity_Product_Type_Configurable extends Mage_ImportExport_Model_Import_Entity_Product_Type_Configurable
{

    protected $_attributesCompositeSave;

    const DEFAULT_CONFIGURABLE_SUPER_ATTRIBUTE_NAME = 'media_format';
    /**
     * Save product type specific data.
     *
     * @throws Exception
     * @return Mage_ImportExport_Model_Import_Entity_Product_Type_Abstract
     */
    public function saveData()
    {
        $connection      = $this->_entityModel->getConnection();
        $mainTable       = Mage::getSingleton('core/resource')->getTableName('catalog/product_super_attribute');
        $labelTable      = Mage::getSingleton('core/resource')->getTableName('catalog/product_super_attribute_label');
        $priceTable      = Mage::getSingleton('core/resource')->getTableName('catalog/product_super_attribute_pricing');
        $linkTable       = Mage::getSingleton('core/resource')->getTableName('catalog/product_super_link');
        $relationTable   = Mage::getSingleton('core/resource')->getTableName('catalog/product_relation');
        $newSku          = $this->_entityModel->getNewSku();
        $oldSku          = $this->_entityModel->getOldSku();
        $productSuperData = array();
        $productData     = null;
        $nextAttrId      = Mage::getResourceHelper('importexport')->getNextAutoincrement($mainTable);

        if ($this->_entityModel->getBehavior() == Mage_ImportExport_Model_Import::BEHAVIOR_APPEND) {
            $this->_loadSkuSuperData();
        }
        $this->_loadSkuSuperAttributeValues();

        while ($bunch = $this->_entityModel->getNextBunch()) {
            $superAttributes = array(
                'attributes' => array(),
                'labels'     => array(),
                'pricing'    => array(),
                'super_link' => array(),
                'relation'   => array()
            );
            foreach ($bunch as $rowNum => $rowData) {
                if (!$this->_entityModel->isRowAllowedToImport($rowData, $rowNum)) {
                    continue;
                }
                // remember SCOPE_DEFAULT row data
                $scope = $this->_entityModel->getRowScope($rowData);
                if (Mage_ImportExport_Model_Import_Entity_Product::SCOPE_DEFAULT == $scope) {
                    $productData = $newSku[$rowData[Mage_ImportExport_Model_Import_Entity_Product::COL_SKU]];

                    if ($this->_type != $productData['type_id']) {
                        $productData = null;
                        continue;
                    }
                    $productId = $productData['entity_id'];

                    $this->_processSuperData($productSuperData, $superAttributes);

                    $productSuperData = array(
                        'product_id'      => $productId,
                        'attr_set_code'   => $productData['attr_set_code'],
                        'used_attributes' => empty($this->_skuSuperData[$productId])
                            ? array() : $this->_skuSuperData[$productId],
                        'assoc_ids'       => array()
                    );
                } elseif (null === $productData) {
                    continue;
                }

                /**
                 * Section One Line Configurable Products
                 * Following code enables a configurable product to be declared on one line.
                 * Magento will create a configurable product if
                 */

                $configurableRowDatas = array();
                $assocProductCounter = 0;
                
                if(!empty($rowData['associated'])) {
                    //if a configurable product is being imported that has only has one row in spreadsheet responsible creating entire config product, then this if statement runs.
                    $associatedConfigurableSkusUnvalidated = $this->_entityModel->userCSVDataAsArray($rowData['associated']);
                    $associatedConfigurableSkus = $this->_validateConfigurableSkus($associatedConfigurableSkusUnvalidated, $rowNum);
                    $attributeIdSuperSku = $this->_superAttributes[self::DEFAULT_CONFIGURABLE_SUPER_ATTRIBUTE_NAME]['id'];

                    if(count($associatedConfigurableSkus)) {
                        foreach($associatedConfigurableSkus as $associatedConfigurableSku) {
                            if(isset($newSku[$associatedConfigurableSku]['entity_id'])) {
                                $entityIdSuperSku = $newSku[$associatedConfigurableSku]['entity_id'];
                            } elseif(isset($oldSku[$associatedConfigurableSku]['entity_id'])) {
                                $entityIdSuperSku = $oldSku[$associatedConfigurableSku]['entity_id'];
                            }

                            //validation was performed in _validateConfigurableSkus, and this has already eliminated any invalid associated skus, thus validation does not need to be performed here for this.
                            //please note: store id is not in where clause of this array.  Assuming any product that exists in more one store has same value for media_format.
                            $option_id = $this->determineAssociatedProductMediaFormatValue($attributeIdSuperSku, $entityIdSuperSku);
                            if($option_id) {
                                $alloptions = $this->_superAttributes[self::DEFAULT_CONFIGURABLE_SUPER_ATTRIBUTE_NAME]['options'];
                                $optionLabel = array_search($option_id, $alloptions);

                                if(!$optionLabel) {
                                    $this->$this->_entityModel->addRowError(Tgc_Dax_Model_Import_Entity_Course::INVALID_OPTION_LABEL, $rowNum, 'media_format');
                                }

                                $configurableRowDatas[$assocProductCounter]['_super_products_sku'] = $associatedConfigurableSku;
                                $configurableRowDatas[$assocProductCounter]['_super_attribute_code'] = self::DEFAULT_CONFIGURABLE_SUPER_ATTRIBUTE_NAME;
                                $configurableRowDatas[$assocProductCounter]['_super_attribute_option'] = $optionLabel;
                            }
                            $assocProductCounter++;
                        }
                    }
                } else {
                    //if a configurable product is being imported that consists of more than one row on the spreadsheet, this ensures that this type of configurable product is handled correctly.
                    if(isset($rowData['_super_products_sku']) && isset($rowData['_super_attribute_code']) && isset($rowData['_super_attribute_option'])) {
                        if($rowData['_super_products_sku'] && $rowData['_super_attribute_code'] && $rowData['_super_attribute_option']) {
                            $configurableRowDatas[$assocProductCounter]['_super_products_sku'] = $rowData['_super_products_sku'];
                            $configurableRowDatas[$assocProductCounter]['_super_attribute_code'] = $rowData['_super_attribute_code'];
                            $configurableRowDatas[$assocProductCounter]['_super_attribute_option'] = $rowData['_super_attribute_option'];
                        }
                    }
                }

                /**
                 * End of section One line configurable products.
                 */

                foreach($configurableRowDatas as $configurableRowData) {
                    if (!empty($configurableRowData['_super_products_sku'])) {
                        if (isset($newSku[$configurableRowData['_super_products_sku']])) {
                            $productSuperData['assoc_ids'][$newSku[$configurableRowData['_super_products_sku']]['entity_id']] = true;
                        } elseif (isset($oldSku[$configurableRowData['_super_products_sku']])) {
                            $productSuperData['assoc_ids'][$oldSku[$configurableRowData['_super_products_sku']]['entity_id']] = true;
                        }
                    }
                    if (empty($configurableRowData['_super_attribute_code'])) {
                        continue;
                    }
                    $attrParams = $this->_superAttributes[$configurableRowData['_super_attribute_code']];
    
                    if ($this->_getSuperAttributeId($productId, $attrParams['id'])) {
                        $productSuperAttrId = $this->_getSuperAttributeId($productId, $attrParams['id']);
                    } elseif (!isset($superAttributes['attributes'][$productId][$attrParams['id']])) {
                        $productSuperAttrId = $nextAttrId++;
                        $superAttributes['attributes'][$productId][$attrParams['id']] = array(
                            'product_super_attribute_id' => $productSuperAttrId, 'position' => 0
                        );
                        $superAttributes['labels'][] = array(
                            'product_super_attribute_id' => $productSuperAttrId,
                            'store_id'    => 0,
                            'use_default' => 1,
                            'value'       => $attrParams['frontend_label']
                        );
                    }
                    if (isset($configurableRowData['_super_attribute_option']) && strlen($configurableRowData['_super_attribute_option'])) {
                        $optionId = $attrParams['options'][strtolower($configurableRowData['_super_attribute_option'])];
    
                        if (!isset($productSuperData['used_attributes'][$attrParams['id']][$optionId])) {
                            $productSuperData['used_attributes'][$attrParams['id']][$optionId] = false;
                        }
                        if (!empty($configurableRowData['_super_attribute_price_corr'])) {
                            $superAttributes['pricing'][] = array(
                                'product_super_attribute_id' => $productSuperAttrId,
                                'value_index'   => $optionId,
                                'is_percent'    => '%' == substr($configurableRowData['_super_attribute_price_corr'], -1),
                                'pricing_value' => (float) rtrim($configurableRowData['_super_attribute_price_corr'], '%'),
                                'website_id'    => 0
                            );
                        }
                    }
                }
            }
            // save last product super data
            $this->_processSuperData($productSuperData, $superAttributes);

            // remove old data if needed
            if ($this->_entityModel->getBehavior() != Mage_ImportExport_Model_Import::BEHAVIOR_APPEND
                && $superAttributes['attributes']) {
                $quoted = $connection->quoteInto('IN (?)', array_keys($superAttributes['attributes']));
                $connection->delete($mainTable, "product_id {$quoted}");
                $connection->delete($linkTable, "parent_id {$quoted}");
                $connection->delete($relationTable, "parent_id {$quoted}");
            }
            $mainData = array();

            foreach ($superAttributes['attributes'] as $productId => $attributesData) {
                foreach ($attributesData as $attrId => $row) {
                    $row['product_id']   = $productId;
                    $row['attribute_id'] = $attrId;
                    $mainData[]          = $row;
                }
            }
            if ($mainData) {
                $connection->insertOnDuplicate($mainTable, $mainData);
            }
            if ($superAttributes['labels']) {
                $connection->insertOnDuplicate($labelTable, $superAttributes['labels']);
            }
            if ($superAttributes['pricing']) {
                $connection->insertOnDuplicate(
                    $priceTable,
                    $superAttributes['pricing'],
                    array('is_percent', 'pricing_value')
                );
            }
            if ($superAttributes['super_link']) {
                $connection->insertOnDuplicate($linkTable, $superAttributes['super_link']);
            }
            if ($superAttributes['relation']) {
                $connection->insertOnDuplicate($relationTable, $superAttributes['relation']);
            }
        }

        $this->saveCompositeAttributeValues(); //this saves the set_members attribute. It must be saved differently because it is a composite.

        return $this;
    }

    protected function _validateConfigurableSkus($associatedConfigurableSkusUnvalidated, $rowNum = '')
    {
        $attributeIdSuperSku = $this->_superAttributes[self::DEFAULT_CONFIGURABLE_SUPER_ATTRIBUTE_NAME]['id'];
        $oldSku = $this->_entityModel->getOldSku();
        $newSku = $this->_entityModel->getNewSku();
        $allSku = array_merge($newSku, $oldSku);
        $options = $this->_attributes['Courses'][self::DEFAULT_CONFIGURABLE_SUPER_ATTRIBUTE_NAME]['options'];
        $optionsUsed = array();
        $validatedSkus = array();

        foreach($associatedConfigurableSkusUnvalidated as $associatedSku) {
            if(!isset($allSku[$associatedSku]['entity_id'])) {
                $this->_entityModel->addRowError(Tgc_Dax_Model_Import_Entity_Course::INVALID_MEDIA_FORMAT_CHILD_PRODUCT, $rowNum, 'associated');
                continue;
            }

            $associatedSkuEntityId = $allSku[$associatedSku]['entity_id'];

            $option_id = $this->determineAssociatedProductMediaFormatValue($attributeIdSuperSku, $associatedSkuEntityId, $rowNum);
            if($allSku[$associatedSku]['type_id'] == 'simple') { //only simple products can be associated with a configurable.
                if(array_search($option_id, $options)) { //ensures only valid option_ids are accepted.
                    if(!in_array($associatedSku, $validatedSkus)) {
                        if(!in_array($option_id, $optionsUsed)) {
                            $optionsUsed[] = $option_id;
                            $validatedSkus[] = $associatedSku;
                        } else {
                            $this->_entityModel->addRowError(Tgc_Dax_Model_Import_Entity_Course::INVALID_OPTION_DUPLICATED, $rowNum, 'media_format');
                        }
                    } else {
                        $this->_entityModel->addRowError(Tgc_Dax_Model_Import_Entity_Course::INVALID_SKU_IDENTICAL, $rowNum, 'associated');
                    }
                } else {
                    $this->_entityModel->addRowError(Tgc_Dax_Model_Import_Entity_Course::INVALID_OPTION_ASSIGNED, $rowNum, 'media_format');
                }
            } else {
                $this->_entityModel->addRowError(Tgc_Dax_Model_Import_Entity_Course::INVALID_ASSOCIATED_PRODUCT_TYPE, $rowNum, 'associated');
            }
        }

        return $validatedSkus;
    }

    /**
     * This saves set_members attributes for all records in all bunches.
     * Note: if an import ever needs to be done where store_id is different than 0, this will need to be
     * modified, to delete all values belonging to stores where data not being imported into.
     * @param $attrCode
     */
    public function saveCompositeAttributeValues()
    {
        //SaveCompositeAttributeValues only runs for sets. This function sets the attribute set_members, which only applies to sets.
        if($this->_entityModel->getEntityTypeCode() == Tgc_Dax_Model_Import_Entity_Set::ENTITY_TYPE_CODE) {
            $attributeData = $this->_entityModel->getCompositeAttributes();

            foreach($attributeData['list'] as $attrCode ) {
                $attribute = $this->_getAttribute($attrCode);
                $attributeConfig = $this->getAttributeConfigData($attribute);

                if($attributeConfig['table'] && $attributeConfig['id']) {
                    $connection      = $this->_entityModel->getConnection();
                    $data = $attributeData['data'][$attrCode];

                    foreach($data as $sku => $dataVals) {
                        $data[$sku]['value'] = implode(',',$data[$sku]['value']);
                        $data[$sku]['entity_id'] = $this->getEntityIdValueFromSku($sku);
                        $data[$sku]['attribute_id'] = $attributeConfig['id'];
                    }

                    $connection->insertOnDuplicate($attributeConfig['table'], $data, array('value'));
                }
            }
        }
    }

    /**
     * Contains attribute data that is needed when it comes time to save information to database.
     * @param $attribute
     * @return mixed
     */
    public function getAttributeConfigData($attribute)
    {
        $attrCode = $attribute->getAttributeCode();

        if(!isset($this->_attributesCompositeSave[$attrCode])) {
            $this->_attributesCompositeSave[$attrCode] = array(
                'id' => $attribute->getId(),
                'frontend_input' => $attribute->getFrontendInput(),
                'backend_table'  => $attribute->getBackend()->getTable(),
            );
        }

        $attributeConfig[$attrCode] = array(
            'table'       =>  $this->_attributesCompositeSave[$attrCode]['backend_table'],
            'id'          =>  $this->_attributesCompositeSave[$attrCode]['id'],
            'input'       =>  $this->_attributesCompositeSave[$attrCode]['frontend_input'],
        );

        return $attributeConfig[$attrCode];
    }

    /**
     * When an attribute is going to be saved, we need to know it's entity id, this function determines the entity_id
     * @param $sku
     * @return mixed
     */
    public function getEntityIdValueFromSku($sku)
    {
        $newSku          = $this->_entityModel->getNewSku();
        $oldSku          = $this->_entityModel->getOldSku();

        if(isset($newSku[$sku]['entity_id'])) {
            $entityIdSku = $newSku[$sku]['entity_id'];
        } elseif(isset($oldSku[$sku]['entity_id'])) {
            $entityIdSku = $oldSku[$sku]['entity_id'];
        }

        return $entityIdSku;
    }

    /**
     * Retrieve attribute by specified code
     *
     * @param string $code
     * @return Mage_Eav_Model_Entity_Attribute_Abstract
     */
    protected function _getAttribute($code)
    {
        $attribute = Mage::getSingleton('importexport/import_proxy_product_resource')->getAttribute($code);
        $backendModelName = (string)Mage::getConfig()->getNode(
            'global/importexport/import/catalog_product/attributes/' . $attribute->getAttributeCode() . '/backend_model'
        );
        if (!empty($backendModelName)) {
            $attribute->setBackendModel($backendModelName);
        }
        return $attribute;
    }

    /**
     * @param $attributeIdSuperSku
     * @param $entityId
     * @param int $rowNum
     * @return array|bool
     */
    public function determineAssociatedProductMediaFormatValue($attributeIdSuperSku, $entityId, $rowNum = 0)
    {
        $connection = $this->_entityModel->getConnection();
        $query = 'SELECT value FROM catalog_product_entity_int WHERE attribute_id = "' . $attributeIdSuperSku . '" AND entity_id = "' . $entityId . '"';
        $result = $connection->fetchCol($query);
        if(count($result) == 1) {
            $result = $result[0];
        } else {
            $this->_entityModel->addRowError(Tgc_Dax_Model_Import_Entity_Course::EMPTY_MEDIA_FORMAT_CHILD_PRODUCT, $rowNum, 'media_format');
            $result = false;
        }
        return $result;
    }
}