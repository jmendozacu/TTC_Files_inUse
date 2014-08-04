<?php
/**
 * @category    TGC
 * @package     Customer
 * @copyright   Copyright (c) 2014 Guidance
 * @author      Guidance Magento Team <magento@guidance.com>
 */

/* @var $installer Tgc_Customer_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();
$conn = $installer->getConnection();

$conn->addColumn(
    $installer->getTable('customer_entity'),
    'audio_format',
    array(
        'type'     => Varien_Db_Ddl_Table::TYPE_SMALLINT,
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '1',
        'comment'  => 'Audio Format',
    )
);

$installer->addAttribute(
    'customer',
    'audio_format',
    array(
        'type' => 'static',
        'label' => 'Audio Format',
        'input' => 'int',
        'visible' => false,
        'required' => false,
        'default_value' => '1',
        'adminhtml_only' => '1'
    )
);

$conn->addColumn(
    $installer->getTable('customer_entity'),
    'video_format',
    array(
        'type'     => Varien_Db_Ddl_Table::TYPE_SMALLINT,
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '1',
        'comment'  => 'Video Format',
    )
);

$installer->addAttribute(
    'customer',
    'video_format',
    array(
        'type' => 'static',
        'label' => 'Video Format',
        'input' => 'int',
        'visible' => false,
        'required' => false,
        'default_value' => '1',
        'adminhtml_only' => '1'
    )
);

$installer->endSetup();
