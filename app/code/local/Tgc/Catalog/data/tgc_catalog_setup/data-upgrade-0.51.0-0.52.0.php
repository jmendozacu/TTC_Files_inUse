<?php
/**
 * User: mhidalgo
 * Date: 07/05/14
 * Time: 10:36
 */

/**
 * @var $installer Mage_Catalog_Model_Resource_Setup
 */
$installer = $this;

$installer->startSetup();

$collection = Mage::getModel('catalog/product')->getCollection()
    ->addAttributeToSelect('professor')
    ->addAttributeToFilter('professor',array('notnull' => 0));

$collection->getSelect()->where("`e`.`entity_id` NOT IN (SELECT `product_id` FROM {$this->getTable('profs/product')})");

foreach ($collection as $product) {
    $professors = $product->getProfessor();
    $arrayProfs = explode(',',$professors);
    foreach ($arrayProfs as $professorId) {
        $installer->run("INSERT into {$this->getTable('profs/product')} (`professor_id`,`product_id`) values ('".$professorId."','".$product->getId()."');");
    }
}

$installer->endSetup();