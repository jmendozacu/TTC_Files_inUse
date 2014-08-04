<?php
/**
* @category    TGC
* @package     Customer
* @copyright   Copyright (c) 2013 Guidance
* @author      Chris Lohman <clohm@guidance.com>
*/

/* @var $installer Tgc_Customer_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$installer->run("
    ALTER TABLE `{$installer->getTable('customer_entity')}` ADD `dax_customer_id` varchar(25) NULL DEFAULT '';
");

$installer->run("
    ALTER TABLE `{$installer->getTable('customer_entity')}` ADD `datamart_customer_pref` varchar(25) NULL DEFAULT '';
");

$installer->addAttribute(
    'customer',
    'dax_customer_id',
    array(
        'type' => 'static',				/* input type */
        'label' => 'DAX Customer Id',	/* Label for the user to read */
        'input' => 'text',				/* input type */
        'visible' => true,				/* users can see it */
        'required' => false,			/* is it required, self-explanatory */
        'default_value' => '',	        /* default value */
        'adminhtml_only' => '1'			/* use in admin html only */
    )
);

// Enable Customer attribute on Adminhtml form
$attribute = Mage::getSingleton('eav/config')->getAttribute($this->getEntityTypeId('customer'), 'dax_customer_id');
$attribute->setData('used_in_forms', array('adminhtml_customer'));  // Must be passed as array!
$attribute->save();

$installer->addAttribute(
    'customer',
    'datamart_customer_pref',
    array(
        'type'                 => 'static',
        'label'                => 'Datamart Customer Preference',
        'input'                => 'text',
        'visible'              => true,
        'required'             => false,
        'default_value'        => '',
        'adminhtml_only'       => 1,
    )
);

$attribute = Mage::getSingleton('eav/config')->getAttribute($this->getEntityTypeId('customer'), 'datamart_customer_pref');
$attribute->setData('used_in_forms', array('adminhtml_customer'));
$attribute->save();

$installer->endSetup();