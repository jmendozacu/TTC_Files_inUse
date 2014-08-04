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
    'is_prospect',
    array(
        'type'     => Varien_Db_Ddl_Table::TYPE_SMALLINT,
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
        'comment'  => 'Is Customer a Prospect?',
    )
);

$installer->addAttribute(
    'customer',
    'is_prospect',
    array(
        'type' => 'static',
        'label' => 'Is Customer a Prospect?',
        'input' => 'int',
        'visible' => false,
        'required' => false,
        'default_value' => '0',
        'adminhtml_only' => '1'
    )
);

$conn->addColumn(
    $installer->getTable('customer_entity'),
    'free_lecture_prospect',
    array(
        'type'     => Varien_Db_Ddl_Table::TYPE_SMALLINT,
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
        'comment'  => 'Is Customer a Free Lecture Prospect?',
    )
);

$installer->addAttribute(
    'customer',
    'free_lecture_prospect',
    array(
        'type' => 'static',
        'label' => 'Is Customer a Free Lecture Prospect?',
        'input' => 'int',
        'visible' => false,
        'required' => false,
        'default_value' => '0',
        'adminhtml_only' => '1'
    )
);

$installer->endSetup();
