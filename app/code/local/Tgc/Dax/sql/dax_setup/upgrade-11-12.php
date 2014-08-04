<?php
/**
 * Dax integration
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Dax
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
/* @var $installer Tgc_Dax_Model_Resource_Catalog_Setup */

$installer = $this;
$installer->startSetup();

$newAttributeSetName = "Sets";
$baseAttributeSetName = "Courses";
$entityTypeId = $installer->getEntityTypeId(Mage_Catalog_Model_Product::ENTITY);

$select = $this->_conn->select()
    ->from($this->getTable('eav/attribute_set'))
    ->where('entity_type_id = :entity_type_id')
    ->where('attribute_set_name = :attribute_set_name');
$countExistingSet = $this->_conn->fetchAll($select, array('entity_type_id' => $entityTypeId, 'attribute_set_name' => $newAttributeSetName));

if(count($countExistingSet) == 0) { //if statement checks to see if attribute set has already been created, if not, this if clause creates new set.
    $model  = Mage::getModel('eav/entity_attribute_set')
        ->setEntityTypeId($entityTypeId)->setAttributeSetName($newAttributeSetName);
    $model->validate();
    $model->save();

    $select = $this->_conn->select()
        ->from($this->getTable('eav/attribute_set'))
        ->where('entity_type_id = :entity_type_id')
        ->where('attribute_set_name = :attribute_set_name');
    $sets = $this->_conn->fetchAll($select, array('entity_type_id' => $entityTypeId, 'attribute_set_name' => $baseAttributeSetName));
    $courseAttributeSet = $sets[0];

    $model->initFromSkeleton($courseAttributeSet['attribute_set_id']);

    $model->save();
}


$installer->endSetup();
