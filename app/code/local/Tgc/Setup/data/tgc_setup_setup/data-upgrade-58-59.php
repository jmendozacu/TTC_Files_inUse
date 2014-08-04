<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Guidance
 * @package     Setup
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
/* @var $installer Tgc_Setup_Model_Resource_Setup */
$installer = $this;

/**
 * Fill table directory/country_region
 * Fill table directory/country_region_name for en_US locale
 */
$data = array(
    array('AU', 'New South Wales', 'New South Wales'),
    array('AU', 'Northern Territory', 'Northern Territory'),
    array('AU', 'Queensland', 'Queensland'),
    array('AU', 'South Australia', 'South Australia'),
    array('AU', 'Tasmania', 'Tasmania'),
    array('AU', 'Victoria', 'Victoria'),
    array('AU', 'Western Australia', 'Western Australia')
);

foreach ($data as $row) {
    $bind = array(
        'country_id'    => $row[0],
        'code'          => $row[1],
        'default_name'  => $row[2],
    );
    $installer->getConnection()->insert($installer->getTable('directory/country_region'), $bind);
    $regionId = $installer->getConnection()->lastInsertId($installer->getTable('directory/country_region'));

    $bind = array(
        'locale'    => 'en_US',
        'region_id' => $regionId,
        'name'      => $row[2]
    );
    $installer->getConnection()->insert($installer->getTable('directory/country_region_name'), $bind);
}

