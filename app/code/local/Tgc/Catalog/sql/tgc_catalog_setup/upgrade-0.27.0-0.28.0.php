<?php
/**
 * Dax integration
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Catalog
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
/* @var $installer Tgc_Dax_Model_Resource_Catalog_Setup */

$installer = $this;
$installer->startSetup();

$newAttributeSetName = "Free Lectures";
$baseAttributeSetName = "Courses";
$entityTypeId = $installer->getEntityTypeId(Mage_Catalog_Model_Product::ENTITY);

$attributesToRemoveFromNewSet = array(
    'professor',
    'bibliography',
    'course_summary',
    'recommended_links',
    'partner',
    'hero_headline',
    'hero_description',
    'hero_tab_text',
    'guidebook',
    'collection',
);

$countExistingSet = $this->retrieveRowFromAttributeSetTable($entityTypeId, $newAttributeSetName);
$baseAttributeSetData = $this->retrieveRowFromAttributeSetTable($entityTypeId, $baseAttributeSetName);

if($countExistingSet === 0 && $baseAttributeSetData) { //if statement checks to see if attribute set has already been created, if not, this if clause creates new set.
    $model  = Mage::getModel('eav/entity_attribute_set')
        ->setEntityTypeId($entityTypeId)->setAttributeSetName($newAttributeSetName);
    $model->validate();
    $model->save();

    $model->initFromSkeleton($baseAttributeSetData['attribute_set_id']);

    $model->save();

    $this->removeAttributesFromSet($attributesToRemoveFromNewSet, $model->getAttributeSetId());
}


$installer->endSetup();
