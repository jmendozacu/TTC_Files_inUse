<?php
/**
 * Boutique
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Boutique
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
/* @var $installer Tgc_Boutique_Model_Resource_Setup */
/* @var $conn Varien_Db_Adapter_Interface */
$installer = $this;
$installer->startSetup();
$conn = $installer->getConnection();

$installer->run("
    DROP TABLE IF EXISTS `{$this->getTable('tgc_boutique/boutiqueHeroCarousel')}`;
");

$table = $conn->newTable($installer->getTable('tgc_boutique/boutiqueHeroCarousel'))
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'Entity ID')
    ->addColumn('description', Varien_Db_Ddl_Table::TYPE_TEXT, '64k', array(
    ), 'Description')
    ->addColumn('sort_order', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
    ), 'Sort Order')
    ->addColumn('store', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
    ), 'Store')
    ->addColumn('boutique_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
    ), 'Boutique ID')
    ->addColumn('boutique_page_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
    ), 'Boutique Page ID')
    ->addColumn('tab_title', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
    ), 'Tab Title')
    ->addColumn('is_active', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '1',
    ), 'Is Active')
    ->addColumn('user_type', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '2',
    ), 'User Type')
    ->addColumn('active_from', Varien_Db_Ddl_Table::TYPE_DATETIME, null, array(
        'nullable'  => true,
    ), 'Active From')
    ->addColumn('active_to', Varien_Db_Ddl_Table::TYPE_DATETIME, null, array(
    ), 'Active To')
    ->addColumn('tab_description', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
    ), 'Tab Description')
    ->addIndex($installer->getIdxName($installer->getTable('tgc_boutique/boutiqueHeroCarousel'), array('sort_order')),
        array('sort_order'))
    ->addIndex($installer->getIdxName($installer->getTable('tgc_boutique/boutiqueHeroCarousel'), array('boutique_id')),
        array('boutique_id'))
    ->addIndex($installer->getIdxName($installer->getTable('tgc_boutique/boutiqueHeroCarousel'), array('boutique_page_id')),
        array('boutique_page_id'))
    ->addIndex($installer->getIdxName($installer->getTable('tgc_boutique/boutiqueHeroCarousel'), array('store')),
        array('store'))
    ->addIndex($installer->getIdxName('tgc_boutique/boutiqueHeroCarousel', array('is_active')),
        array('is_active'))
    ->setComment('Boutique Hero Carousel Table');

$conn->createTable($table);

$installer->endSetup();
