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
$entity = 'customer';

$installer->addAttribute(
    $entity,
    'web_prospect_id',
    array(
        'type' => 'int',
        'label' => 'Web Prospect ID',
        'input' => 'int',
        'visible' => false,
        'required' => false,
        'default_value' => NULL,
    )
);

$installer->addAttribute(
    $entity,
    'free_lectures_date_collected',
    array(
        'type' => 'datetime',
        'label' => 'Free Lectures Date Collected',
        'input' => 'date',
        'frontend' => 'eav/entity_attribute_frontend_datetime',
        'backend' => 'eav/entity_attribute_backend_datetime',
        'visible' => false,
        'required' => false,
    )
);

$installer->addAttribute(
    $entity,
    'free_lect_last_date_collected',
    array(
        'type' => 'datetime',
        'label' => 'Free Lectures Last Date Collected',
        'input' => 'date',
        'frontend' => 'eav/entity_attribute_frontend_datetime',
        'backend' => 'eav/entity_attribute_backend_datetime',
        'visible' => false,
        'required' => false,
    )
);

$installer->addAttribute(
    $entity,
    'free_lectures_initial_source',
    array(
        'type' => 'int',
        'label' => 'Free Lectures Initial Source',
        'input' => 'int',
        'visible' => false,
        'required' => false,
        'default_value' => NULL,
    )
);

$installer->addAttribute(
    $entity,
    'free_lect_initial_user_agent',
    array(
        'type'          => 'varchar',
        'label'         => 'Free Lecture Initial User Agent',
        'input'         => 'text',
        'visible'       => false,
        'required'      => false,
        'default_value' => NULL,
    )
);

$installer->addAttribute(
    $entity,
    'email_verified',
    array(
        'type' => 'int',
        'label' => 'Email Verified?',
        'source' => 'eav/entity_attribute_source_boolean',
        'input' => 'boolean',
        'visible' => false,
        'required' => false,
        'default_value' => NULL,
    )
);

$installer->addAttribute(
    $entity,
    'date_verified',
    array(
        'type' => 'datetime',
        'label' => 'Date Verified',
        'input' => 'date',
        'frontend' => 'eav/entity_attribute_frontend_datetime',
        'backend' => 'eav/entity_attribute_backend_datetime',
        'visible' => false,
        'required' => false,
    )
);

$installer->addAttribute(
    $entity,
    'confirmation_guid',
    array(
        'type'          => 'varchar',
        'label'         => 'Confirmation GUID',
        'input'         => 'text',
        'visible'       => false,
        'required'      => false,
        'default_value' => NULL,
    )
);

$installer->addAttribute(
    $entity,
    'is_account_at_signup',
    array(
        'type' => 'int',
        'label' => 'Is Account at Signup?',
        'source' => 'eav/entity_attribute_source_boolean',
        'input' => 'boolean',
        'visible' => false,
        'required' => false,
        'default_value' => NULL,
    )
);

$installer->endSetup();