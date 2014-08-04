<?php
/**
 * User: mhidalgo
 * Date: 27/03/14
 * Time: 14:57
 */

/**
 * @var $installer Mage_Catalog_Model_Resource_Setup
 */
$installer = $this;

$installer->startSetup();
$entity = Mage_Catalog_Model_Product::ENTITY;
$attrCodes = array();
$options = array();

$attrCodes[] = 'on_sale';
$attrCodes[] = 'collection';
$attrCodes[] = 'attribute_set_defined';

foreach ($attrCodes as $code) {
    $attr = $installer->getAttribute($entity, $code);
    if ($code != "attribute_set_defined") {
        $options[] = array(
            'attribute_id' => $attr['attribute_id'],
            'value' => array(
                array(0 => 'No')
            )
        );
        $options[] = array(
            'attribute_id' => $attr['attribute_id'],
            'value' => array(
                array(0 => 'Yes')
            )
        );
    } else {
        $attributeSetIds = $this->getAllAttributeSetIds($this->getDefaultAttributeSetId($entity));
        $values = array();
        foreach($attributeSetIds as $attributeSetId) {
            if ((int)$this->getDefaultAttributeSetId($entity) != (int)$attributeSetId) {
                $attributeSet = $this->getAttributeSet($entity,$attributeSetId);
                $options[] = array(
                    'attribute_id' => $attr['attribute_id'],
                    'value' => array(
                        array(0 => $attributeSet['attribute_set_name'])
                    )
                );
            }
        }

    }
}
foreach ($options as $option) {
    $this->addAttributeOption($option);
}

$installer->endSetup();