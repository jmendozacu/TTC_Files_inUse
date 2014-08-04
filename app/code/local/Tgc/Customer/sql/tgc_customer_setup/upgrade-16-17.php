<?php
/**
 * @category    TGC
 * @package     Customer
 * @copyright   Copyright (c) 2014 Guidance
 * @author      Guidance Magento SuperTeam <magento@guidance.com>
 */

/* @var $installer Tgc_Customer_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();
$conn = $installer->getConnection();

$conn->modifyColumn(
    $installer->getTable('customer_entity'),
    'audio_format',
    array(
        'type'     => Varien_Db_Ddl_Table::TYPE_SMALLINT,
        'unsigned'  => true,
        'nullable'  => true,
        'default'   => null,
        'comment'  => 'Audio Format',
    )
);

$installer->updateAttribute(
    'customer',
    'audio_format',
    array(
        'default_value' => null,
    )
);

$conn->modifyColumn(
    $installer->getTable('customer_entity'),
    'video_format',
    array(
        'type'     => Varien_Db_Ddl_Table::TYPE_SMALLINT,
        'unsigned'  => true,
        'nullable'  => true,
        'default'   => null,
        'comment'  => 'Video Format',
    )
);

$installer->updateAttribute(
    'customer',
    'video_format',
    array(
        'default_value' => null,
    )
);

$installer->endSetup();
