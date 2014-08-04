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

$conn->dropColumn($installer->getTable('customer_entity'), 'web_user_id');

$conn->addColumn(
    $installer->getTable('customer_entity'),
    'web_user_id',
    array(
        'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
        'length'   => 64,
        'nullable' => false,
        'comment'  => 'Web User ID',
    )
);

$installer->addAttribute(
    'customer',
    'web_user_id',
    array(
        'type' => 'static',				/* input type */
        'label' => 'Web User Id',	    /* Label for the user to read */
        'input' => 'text',				/* input type */
        'visible' => true,				/* users can see it */
        'required' => false,			    /* is it required, self-explanatory */
        'default_value' => '',	        /* default value */
        'adminhtml_only' => '1'			/* use in admin html only */
    )
);

// Enable Customer attribute on Adminhtml form
$attribute = Mage::getSingleton('eav/config')->getAttribute($this->getEntityTypeId('customer'), 'web_user_id');
$attribute->setData('used_in_forms', array('adminhtml_customer'));  // Must be passed as array!
$attribute->save();

$installer->endSetup();