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
    'free_lect_subscribe_status',
    array(
        'type' => 'int',
        'label' => 'Free Lecture Status',
        'source' => 'lectures/eav_entity_attribute_source_freelectstatus',
        'input' => 'select',
        'visible' => false,
        'required' => false,
        'default_value' => NULL,
    )
);