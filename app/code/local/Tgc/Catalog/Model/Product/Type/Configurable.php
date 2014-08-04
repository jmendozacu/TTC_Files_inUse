<?php
/**
 * Tgc Catalog
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Catalog
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */


class Tgc_Catalog_Model_Product_Type_Configurable extends Mage_Catalog_Model_Product_Type_Configurable
{
    /**
     * Retrieve related products collection
     *
     * @param  Mage_Catalog_Model_Product $product
     * @return Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Type_Configurable_Product_Collection
     */
    public function getUsedProductCollection($product = null)
    {
        $childProductTranscriptIds = $this->getChildTranscriptProductIds($product);

        $collection = Mage::getResourceModel('catalog/product_type_configurable_product_collection')
            ->setFlag('require_stock_items', true)
            ->setFlag('product_children', true)
            ->setProductFilter($this->getProduct($product));

        if(count($childProductTranscriptIds) > 0) {
            $collection->getSelect()->where('e.entity_id NOT IN (?)', $childProductTranscriptIds);
        }

        if (!is_null($this->getStoreFilter($product))) {
            $collection->addStoreFilter($this->getStoreFilter($product));
        }

        return $collection;
    }
    
    public function getChildTranscriptProductIds($product)
    {
        $connection = Mage::getSingleton('core/resource')->getConnection('write');
        $childProductTranscriptIds = null;
        $product = $this->getProduct($product);

        if($product) {
            $parentId = $product->getId();
            //all products that are skus are physical transcript have a sku beginning with PT
            //all products that are digital transcripts have an sku beginning with DT
            //Both these transcript ids are being excluded. WE don't want them to show in the drop down.

            $selectChildren = $connection->select()
                ->from(array('s' => 'catalog_product_super_link'), array('product_id'))
                ->joinLeft(array('c' => 'catalog_product_entity'), 's.product_id = c.entity_id')
                ->where("c.SKU LIKE 'PT%' or c.sku LIKE 'DT%'")
                ->where('s.parent_id = :parent_id');

            $childProductTranscriptIds = $connection->fetchCol($selectChildren, array('parent_id' => $parentId ));
        }

        return $childProductTranscriptIds;
    }

    /**
     * Before save process
     *
     * @param Mage_Catalog_Model_Product $product
     * @return Mage_Catalog_Model_Product_Type_Configurable
     */
    public function beforeSave($product = null)
    {
        $mageModelConfigurable = Mage::helper('Guidance_Reflection')->getGrandParentClassName(get_class());
        $mageModelConfigurable::beforeSave($product);

        $this->getProduct($product)->canAffectOptions(false);

        if ($this->getProduct($product)->getCanSaveConfigurableAttributes()) {
            $this->getProduct($product)->canAffectOptions(true);
            $data = $this->getProduct($product)->getConfigurableAttributesData();
            if (!empty($data)) {
                foreach ($data as $attribute) {
                    if (!empty($attribute['values'])) {
                        $this->getProduct($product)->setTypeHasOptions(true);
                        $this->getProduct($product)->setTypeHasRequiredOptions(true);
                        break;
                    }
                }
            }
        }
        foreach ($this->getConfigurableAttributes($product) as $attribute) {
            if (is_null($attribute->getProductAttribute())) {
                Mage::log("Product to Fix ID='".$this->getProduct($product)->getId()."' Name='".$this->getProduct($product)->getName()."'",Zend_Log::CRIT,"Tgc_Catalog_Model_Product_Type_Configurable.log");
            }else {
                $this->getProduct($product)->setData($attribute->getProductAttribute()->getAttributeCode(), null);
            }
        }

        return $this;
    }
}