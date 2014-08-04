<?php
/**
 * User: mhidalgo
 * Date: 28/03/14
 * Time: 10:41
 */

/**
 * @var $installer Mage_Catalog_Model_Resource_Setup
 */
$installer = $this;

$installer->startSetup();
$entity = Mage_Catalog_Model_Product::ENTITY;
$attrCodes = array();
$options = array();

$code = 'all_types';
$optToAdd[] = 'On Sale';
$optToAdd[] = 'Collections';
$optToAdd[] = 'Courses Only';
$optToAdd[] = 'Sets Only';
$attr = $installer->getAttribute($entity, $code);
foreach ($optToAdd as $opt) {
    $options[] = array(
        'attribute_id' => $attr['attribute_id'],
        'value' => array(
            array(0 => $opt)
        )
    );
}
foreach ($options as $option) {
    $this->addAttributeOption($option);
}

// Add Attribute to Mana Filters
Mage::helper('mana_db')->replicate(array(
    'trackKeys' => true,
    'filter' => array('eav/attribute' => array('saved' => array($attr['attribute_id']))),
));

$installer->endSetup();