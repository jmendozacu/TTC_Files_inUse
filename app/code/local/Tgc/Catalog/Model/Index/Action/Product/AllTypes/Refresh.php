<?php
/**
 * Description
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Tgc_Catalog
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Catalog_Model_Index_Action_Product_AllTypes_Refresh
    extends Enterprise_Index_Model_Action_Abstract
{
    /**
     * Product helper, contains some useful functions for operations with attributes
     *
     * @var Enterprise_Catalog_Helper_Product
     */
    protected $_productHelper;

    protected $_attributeSetNames = array();

    protected $_attributeSources = array();

    /**
     * Object initialization
     *
     * @param array $argv
     */
    public function __construct(array $argv)
    {
        parent::__construct($argv);
        $this->_productHelper = Mage::helper('enterprise_catalog/product');
    }

    /**
     * Execute "all_types" attribute value refresh
     *
     * @return Tgc_Catalog_Model_Index_Action_Product_AllTypes_Refresh
     * @throws Enterprise_Index_Model_Action_Exception
     */
    public function execute()
    {
        try {
            $this->_getCurrentVersionId();
            $this->_metadata->setInProgressStatus()->save();
            $stores = Mage::app()->getStores(true);
            foreach ($stores as $store) {
                $this->_reindex($store->getId());
            }
            $this->_setChangelogValid();
        } catch (Exception $e) {
            $this->_metadata->setInvalidStatus()->save();
            throw new Enterprise_Index_Model_Action_Exception($e->getMessage(), $e->getCode(), $e);
        }
        return $this;
    }

    /**
     * Returns Attribute Set Name by ID
     *
     * @param int $setId
     * @return string
     */
    protected function _getAttributeSetName($setId)
    {
        if (!isset($this->_attributeSetNames[$setId])) {
            $attributeSetModel = Mage::getModel("eav/entity_attribute_set");
            $attributeSetModel->load($setId);
            $this->_attributeSetNames[$setId] = $attributeSetModel->getAttributeSetName();
        }
        return $this->_attributeSetNames[$setId];
    }

    /**
     * Returns attribute Source model
     *
     * @param string $attributeCode
     * @return Mage_Eav_Model_Entity_Attribute_Source_Abstract
     */
    protected function _getAttributeSourceModel($attributeCode)
    {
        if (!isset($this->_attributeSources[$attributeCode])) {
            $sourceModel = Mage::getModel('catalog/product')
                ->getResource()->getAttribute($attributeCode)->getSource();
            $this->_attributeSources[$attributeCode] = $sourceModel;
        }
        return $this->_attributeSources[$attributeCode];
    }

    /**
     * Updates attribute value faster
     *
     * @param string $attribute
     * @param string|int $value
     * @param int $storeId
     * @param Mage_Catalog_Model_Product $product
     */
    protected function _updateAttributeValue($attribute, $value, $storeId, $product)
    {
        $attributeModel = $product->getResource()->getAttribute($attribute);
        $table = $attributeModel->getBackendTable();
        $connection = $product->getResource()->getWriteConnection();
        $connection->insertOnDuplicate($table, array(
            'entity_type_id' => $product->getResource()->getEntityType()->getEntityTypeId(),
            'attribute_id' => $attributeModel->getAttributeId(),
            'store_id' => $storeId,
            'entity_id' => $product->getId(),
            'value' => $value
        ));
    }

    /**
     * Rebuild attribute index from scratch
     *
     * @param int $storeId
     * @param array $changedIds
     *
     * @return Enterprise_Catalog_Model_Index_Action_Product_Flat_Refresh
     * @throws Exception
     */
    protected function _reindex($storeId, array $changedIds = array())
    {
        ini_set('memory_limit', '2048M');

        $productCollection = Mage::getResourceModel('catalog/product_collection')
            ->setStore(Mage::app()->getStore($storeId))
            ->joinAttribute('collection',
                'catalog_product/collection',
                'entity_id',
                null,
                'left'
            );

        if (!empty($changedIds)) {
            $productCollection->addFieldToFilter('entity_id', array('in' => $changedIds));
        }

        /* @var $product Mage_Catalog_Model_Product */
        $product = Mage::getModel('catalog/product')->setStoreId($storeId);
        $connection = $product->getResource()->getWriteConnection();

        $select = $productCollection->getSelect();
        $statement = $connection->query($select);
        $i = 0;
        $connection->beginTransaction();
        while ($row = $statement->fetch()) {
            try {
                /* @var $product Mage_Catalog_Model_Product */
                $product->unsetData()->setStoreId($storeId)->addData($row);
                $attributeSetName = $this->_getAttributeSetName($product->getAttributeSetId());

                // Merge Attributes in All Types for filter
                $attrSourceModel = $this->_getAttributeSourceModel("all_types");
                $valuesText = array();
                if ($attributeSetName == "Courses" || $attributeSetName == "Sets" ) {
                    if ($attributeSetName == "Courses") {
                        $valuesText[] = "Courses Only";
                    } else {
                        $valuesText[] = "Sets Only";
                    }
                }
                if ($optId = $product->getData('collection')) {
                    $optText = $this->_getAttributeSourceModel("collection")->getOptionText($optId);
                    if ($optText == "Yes") {
                        $valuesText[] = "Collections";
                    }
                }
                $valuesIds = array_map(array($attrSourceModel, 'getOptionId'), $valuesText);
                $product->setData("all_types", $valuesIds);

                //update values
                $this->_updateAttributeValue('all_types', implode(',', $product->getData('all_types')), $storeId, $product);

                if ($i > 1000) {
                    $connection->commit();
                    $connection->beginTransaction();
                    $i = 0;
                }
                ++$i;
            } catch (Exception $e) {
                $connection->rollBack();
                throw $e;
            }
        }
        $connection->commit();
        return $this;
    }
}