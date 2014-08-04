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
    'is_freelect_prospect_confirmed',
    array(
        'type' => 'int',
        'label' => 'Is Free Lecture Prospect Confirmed?',
        'source' => 'eav/entity_attribute_source_boolean',
        'input' => 'boolean',
        'visible' => false,
        'required' => false,
        'default' => false,
    )
);

$installer->addAttribute(
    $entity,
    'free_lect_email_failed',
    array(
        'type' => 'int',
        'label' => 'Free Lecture Confirmation Email Failed to Send',
        'source' => 'eav/entity_attribute_source_boolean',
        'input' => 'boolean',
        'visible' => false,
        'required' => false,
        'default' => false,
    )
);

$installer->endSetup();