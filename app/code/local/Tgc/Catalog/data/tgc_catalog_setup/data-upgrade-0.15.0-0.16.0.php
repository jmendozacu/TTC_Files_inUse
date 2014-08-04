<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Catalog
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

$installer = $this;
$installer->startSetup();


/** @var $store Mage_Core_Model_Store */
$store = Mage::getModel('core/store')->load(1);

/** @var $rootCategory Mage_Catalog_Model_Category */
$rootCategory = Mage::getModel('catalog/category')->load($store->getRootCategoryId());

$rootCategory->addData(array('name' => 'All Courses & Sets', 'is_anchor' => '1', 'is_active' => '1', 'include_in_menu' => '0'))
                ->save();

$categories = array(
    array(
        'name' => 'Better Living',
        'children' => array(
            array('name' => 'Food & Wine'),
            array('name' => 'Health, Fitness, & Nutrition'),
            array('name' => 'Hobby & Leisure'),
            array('name' => 'Personal Development'),
            array('name' => 'Travel'),
            array('name' => 'Parenting'),
        ),
        'is_active' => '1',
        'is_anchor' => '1',
        'include_in_menu' => '1',
    ),
    array(
        'name' => 'Professional',
        'children' => array(
            array('name' => 'Business'),
            array('name' => 'Communication Skills'),
            array('name' => 'Education'),
            array('name' => 'Leadership Skills'),
            array('name' => 'Thinking Skills'),
        ),
        'is_active' => '1',
        'is_anchor' => '1',
        'include_in_menu' => '1',
    ),
    array(
        'name' => 'Economics & Finance',
        'children' => array(
            array('name' => 'Finance'),
            array('name' => 'Economics'),
        ),
        'is_active' => '1',
        'is_anchor' => '1',
        'include_in_menu' => '1',
    ),
    array(
        'name' => 'Fine Arts',
        'children' => array(
            array('name' => 'Art History'),
            array('name' => 'Studio Art'),
        ),
        'is_active' => '1',
        'is_anchor' => '1',
        'include_in_menu' => '1',
    ),
    array(
        'name' => 'High School',
        'children' => array(
            array('name' => 'History'),
            array('name' => 'Math'),
            array('name' => 'Science'),
            array('name' => 'Study Skills'),
            array('name' => 'Critical Thinking Skills'),
            array('name' => 'Reading & Writing'),
        ),
        'is_active' => '1',
        'is_anchor' => '1',
        'include_in_menu' => '1',
    ),
    array(
        'name' => 'History',
        'children' => array(
            array('name' => 'American History'),
            array('name' => 'Ancient History – Classical'),
            array('name' => 'Ancient History – World'),
            array('name' => 'Civilization & Culture'),
            array('name' => 'Medieval History'),
            array('name' => 'Modern History – Europe'),
            array('name' => 'Modern History – World'),
            array('name' => 'Renaissance & Early Modern History'),
            array('name' => 'Military History'),
        ),
        'is_active' => '1',
        'is_anchor' => '1',
        'include_in_menu' => '1',
    ),
    array(
        'name' => 'Literature & Language',
        'children' => array(
            array('name' => 'Ancient Literature & Mythology'),
            array('name' => 'American Literature'),
            array('name' => 'British Literature'),
            array('name' => 'Linguistics'),
            array('name' => 'World Literature'),
            array('name' => 'Writing'),
            array('name' => 'Genre'),
            array('name' => 'Literary Surveys'),
        ),
        'is_active' => '1',
        'is_anchor' => '1',
        'include_in_menu' => '1',
    ),
    array(
        'name' => 'Mathematics',
        'children' => array(
            array('name' => 'Applied Mathematics'),
            array('name' => 'History of Mathematics'),
            array('name' => 'Mathematical Theory'),
        ),
        'is_active' => '1',
        'is_anchor' => '1',
        'include_in_menu' => '1',
    ),
    array(
        'name' => 'Music',
        'children' => array(
            array('name' => 'Classical Music'),
            array('name' => 'Modern Music'),
            array('name' => 'Musical Theory'),
        ),
        'is_active' => '1',
        'is_anchor' => '1',
        'include_in_menu' => '1',
    ),
    array(
        'name' => 'Philosophy & Intellectual History',
        'children' => array(
            array('name' => 'Ancient Philosophy'),
            array('name' => 'Intellectual History'),
            array('name' => 'Medieval Philosophy'),
            array('name' => 'Modern Philosophy'),
            array('name' => 'Understanding the Mind'),
        ),
        'is_active' => '1',
        'is_anchor' => '1',
        'include_in_menu' => '1',
    ),
    array(
        'name' => 'Religion',
        'children' => array(
            array('name' => 'Christianity'),
            array('name' => 'Judaism'),
            array('name' => 'Eastern & World Religions'),
            array('name' => 'Comparative'),
            array('name' => 'Biblical Studies'),
        ),
        'is_active' => '1',
        'is_anchor' => '1',
        'include_in_menu' => '1',
    ),
    array(
        'name' => 'Science',
        'children' => array(
            array('name' => 'Astronomy & Space Science'),
            array('name' => 'Biology'),
            array('name' => 'Earth Sciences'),
            array('name' => 'Engineering & Technology'),
            array('name' => 'History & Philosophy of Science'),
            array('name' => 'Neuroscience & Psychology'),
            array('name' => 'Physics & Chemistry'),
            array('name' => 'Social Sciences'),
            array('name' => 'Medicine'),
        ),
        'is_active' => '1',
        'is_anchor' => '1',
        'include_in_menu' => '1',
    ),
);

foreach ($categories as $category) {
    $installer->createCategory($rootCategory, $category);
}



$installer->endSetup();

