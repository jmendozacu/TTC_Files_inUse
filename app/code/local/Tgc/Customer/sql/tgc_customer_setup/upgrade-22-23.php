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
    'free_lect_date_unsubscribed',
    array(
        'type' => 'datetime',
        'label' => 'Free Lectures Date Unsubscribed',
        'input' => 'date',
        'frontend' => 'eav/entity_attribute_frontend_datetime',
        'backend' => 'eav/entity_attribute_backend_datetime',
        'visible' => false,
        'required' => false,
    )
);

$installer->endSetup();