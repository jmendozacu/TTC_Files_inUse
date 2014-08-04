<?php
/**
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    The Great Courses
 * @package     Tgc_Customer
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */

/**
 * Create username attribute
 */

$installer = $this;
/* @var $installer Tgc_Customer_Model_Resource_Setup */

$installer->startSetup();

$installer->addAttribute('customer', 'username', array(
    'type'           => 'static',
    'label'          => 'Username',
    'input'          => 'text',
    'validate_rules' => 'a:1:{s:15:"max_text_length";i:255;}',
    'required'       => false,
    'sort_order'     => 0,
    'position'       => 0
));

$installer->getConnection()->addColumn(
    $installer->getTable('customer/entity'),
    'username',
    array(
        'type'    => Varien_Db_Ddl_Table::TYPE_TEXT,
        'length'  => 255,
        'comment' => 'Customer Username'
    )
);

$installer->getConnection()->addIndex(
    $installer->getTable('customer/entity'),
    $installer->getIdxName('customer/entity', array('username')),
    array('username')
);


$attributeId = $installer->getAttributeId('customer', 'username');
if ($attributeId) {
    $installer->getConnection()->insertMultiple(
        $installer->getTable('customer/form_attribute'),
        array(
            array('form_code' => 'adminhtml_customer', 'attribute_id' => $attributeId)
        )
    );
}

$installer->endSetup();
