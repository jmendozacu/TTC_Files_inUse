<?php
/**
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    The Great Courses
 * @package     Tgc_Customer
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */

$installer = $this;
$installer->startSetup();

$installer->addAttribute(
    'customer',
    'dax_customer_created_uctime',
    array(
        'type' => 'datetime',
        'label' => 'Dax Customer Created Uctime',
        'input' => 'date',
        'frontend' => 'eav/entity_attribute_frontend_datetime',
        'backend' => 'eav/entity_attribute_backend_datetime',
        'visible' => false,
        'required' => false,
    )
);

$installer->addAttribute(
    'customer',
    'new_customer_sent_to_dax',
    array(
        'type' => 'int',
        'label' => 'New Customer Sent To Dax?',
        'input' => 'int',
        'visible' => false,
        'required' => false,
        'default_value' => '0',
    )
);

$installer->addAttribute(
    'customer',
    'new_customer_confirmed_by_dax',
    array(
        'type' => 'int',
        'label' => 'New Customer Confirmed By Dax?',
        'input' => 'int',
        'visible' => false,
        'required' => false,
        'default_value' => '0',
    )
);

$installer->addAttribute(
    'customer',
    'dax_address_record',
    array(
        'type' => 'int',
        'label' => 'Dax Address Record',
        'input' => 'int',
        'visible' => false,
        'required' => false,
    )
);


$installer->endSetup();