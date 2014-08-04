<?php

class Tgc_Lectures_Model_Eav_Entity_Attribute_Backend_Freemarketinglecture extends Mage_Eav_Model_Entity_Attribute_Backend_Datetime
{

    protected $_freeMarketingLectureAttributeCodes = array('marketing_free_lecture_from','marketing_free_lecture_to');

    protected $_freeLectureRegistryKey = 'free_marketing_lecture_product_id';

    protected $_adapterFreemarketingLecture;
    
    protected $_productEntityIdToSku;

    protected $_validatationEnforceOnlyOneFreeMarketingLecture = false;

    public function __construct()
    {
        $this->_adapterFreemarketingLecture = Mage::getSingleton('core/resource')->getConnection('write');
        $this->_productEntityIdToSku = $this->_adapterFreemarketingLecture->fetchPairs("SELECT entity_id, sku FROM catalog_product_entity");
    }

    public function validate($object)
    {
        $isValid = parent::validate($object);

        if($this->_validatationEnforceOnlyOneFreeMarketingLecture) {
            $attrCode = $this->getAttribute()->getAttributeCode();
            $value = $object->getData($attrCode);

            if ($isValid && $value) { //only performs validation check if user types in variable into the free marketing field.
                $productId = Mage::app()->getRequest()->getParam('id');
                $isValid = $this->validateFreemarketingLectureAlreadyExists($productId);
            }
        }

        return $isValid;
    }

    public function validateFreemarketingLectureAlreadyExists($productId)
    {
        $isValid = true;
        $productIdOfFreeMarketingLecturesArray = $this->determineProductIdOfExistingFreemarketingLecture($productId);

        if($productIdOfFreeMarketingLecturesArray) {
            $this->addProductIdOfFreeMarketingLectureToRegistry($productIdOfFreeMarketingLecturesArray);
            $freemarketingSkus = $this->getSkuOfFreeMarketingLecture($productIdOfFreeMarketingLecturesArray);
            $isValid = false;
            Mage::throwException(
                Mage::helper('lectures')->__('Only one product can have a marketing free lecture date.  The following sku identifies the product that has this value: ' . $freemarketingSkus)
            );
        }

        return $isValid;
    }

    public function getSkuOfFreeMarketingLecture($productIdOfFreeMarketingLecturesArray)
    {
        $skusFreeMarketingLecturesArray = array();
        foreach($productIdOfFreeMarketingLecturesArray as $productId) {
            $skusFreeMarketingLecturesArray[] = $this->_productEntityIdToSku[$productId];
        }

        $skusFreeMarketingLecturesList = implode(',', $skusFreeMarketingLecturesArray);
        return $skusFreeMarketingLecturesList;
    }

    public function determineProductIdOfExistingFreemarketingLecture($productId)
    {
        $productIdOfExistingFreeMarketingLecture = Mage::registry($this->_freeLectureRegistryKey); //grabs it from registry, IF it already exists.

        if(!$productIdOfExistingFreeMarketingLecture) {
            $table ='catalog_product_entity_datetime';
            $attributeIds = $this->_freemarketinglectureHelper()->getFreeMarketingLectureAttributeIds();

            if($table && $attributeIds) {
                $selectAttributeValue = $this->_adapterFreemarketingLecture->select()
                    ->from($table, array('entity_id'))
                    ->where('attribute_id IN (?)', $attributeIds)
                    ->where('entity_id != :entity_id')
                    ->where('value IS NOT NULL');

                $productIdOfExistingFreeMarketingLecture = array_unique($this->_adapterFreemarketingLecture->fetchCol($selectAttributeValue, array('entity_id' => $productId)));
            }
        }

        return $productIdOfExistingFreeMarketingLecture;
    }

    public function addProductIdOfFreeMarketingLectureToRegistry($productIdOfFreeMarketingLecturesArray)
    {
        if(count($productIdOfFreeMarketingLecturesArray) == 1) { //Only one product can have a free marketing lecture date, if for some reason there is more than one, this if statment prevents being saved to regsitry.
            Mage::register($this->_freeLectureRegistryKey, $productIdOfFreeMarketingLecturesArray[0]);
        }
    }

    public function _freemarketinglectureHelper()
    {
        return Mage::helper('tgc_catalog/freemarketinglecture');
    }

}