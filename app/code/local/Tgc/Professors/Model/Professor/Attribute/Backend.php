<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category
 * @package
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Professors_Model_Professor_Attribute_Backend extends Mage_Eav_Model_Entity_Attribute_Backend_Array
{
    public function beforeSave($object)
    {
        parent::beforeSave($object);

        $attributeCode = $this->getAttribute()->getAttributeCode();
        $data = $object->getData($attributeCode);

        if($object->getId()) {
            //the magento default import profile runs this while saving the attribute, magento has not yet saved the product, therefore
            //product id does not exist, and if this runs, it will throw an error.
            $ids = array_filter(explode(',', $data));
            Mage::getResourceModel('profs/professor')->linkProfessorsToProduct($object->getId(), $ids);
        }

        return $this;
    }
}