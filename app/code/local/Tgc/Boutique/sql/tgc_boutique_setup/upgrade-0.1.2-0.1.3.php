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

$conn->addColumn(
    $installer->getTable('tgc_boutique/boutiquePages'),
    'disable_carousel',
    array(
        'type'     => Varien_Db_Ddl_Table::TYPE_SMALLINT,
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
        'comment'  => 'Disable Hero Carousel for Page?',
    )
);

$conn->addColumn(
    $installer->getTable('tgc_boutique/boutique'),
    'disable_carousel',
    array(
        'type'     => Varien_Db_Ddl_Table::TYPE_SMALLINT,
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
        'comment'  => 'Disable Hero Carousel for Boutique?',
    )
);

$installer->endSetup();
