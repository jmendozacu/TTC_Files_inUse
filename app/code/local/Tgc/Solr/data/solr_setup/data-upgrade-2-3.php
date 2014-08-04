<?php
/**
 * Solr search
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Solr
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

/* @var $installer Tgc_Solr_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();

$db = $installer->getConnection();
$db->beginTransaction();
try {

    $attributeWeights = array(
        'course_id' => 9,
        'name' => 8,
        'professor' => 7,
        // 6 is for professor_teaching
        // 5 is for professor_alma_mater
        'short_description' => 4,
        'description' => 3
    );

    foreach ($attributeWeights as $attributeCode => $weight) {
        $installer->updateAttribute(
            Mage_Catalog_Model_Product::ENTITY,
            $attributeCode,
            'search_weight',
            $weight
        );
    }

    $db->commit();
} catch (Exception $e) {
    $db->rollBack();
    throw $e;
}

$installer->endSetup();
