<?php
/**
 * Cms additions
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Cms
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
/* @var $installer Tgc_Cms_Model_Resource_Setup */
/* @var $conn Varien_Db_Adapter_Interface */
$installer = $this;
$installer->startSetup();

$storeIds = 0;
$widgets = array(
    0 => array(
        'type' => 'tgc_dl/resumePlaying',
        'title' => 'AU Continue Watching Block',
        'sort_order' => 0,
        'widget_parameters' => array (
            'start_watching_text' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation.',
            'unique_id' => Mage::helper('core')->uniqHash(),
        ),
    ),
    1 => array(
        'type' => 'tgc_cms/heroCarousel',
        'title' => 'AU Homepage Hero Carousel',
        'sort_order' => 10,
        'widget_parameters' => array (
            'display_type' => '2',
            'unique_id' => Mage::helper('core')->uniqHash(),
        ),
    ),
    2 => array(
        'type' => 'tgc_cms/homepageCategory',
        'title' => 'AU Homepage Categories (Guests and Prospects only)',
        'sort_order' => 20,
        'widget_parameters' => array (
            'display_type' => '0',
            'title' => 'Categories',
            'unique_id' => Mage::helper('core')->uniqHash(),
        ),
    ),
    3 => array(
        'type' => 'tgc_cms/partners',
        'title' => 'AU Partners (Guests and Prospects only)',
        'sort_order' => 30,
        'widget_parameters' => array (
            'display_type' => '0',
            'title' => 'Who We Work With',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation.',
            'use_sort_order' => '1',
            'unique_id' => Mage::helper('core')->uniqHash(),
        ),
    ),
    4 => array(
        'type' => 'tgc_cms/quotes',
        'title' => 'AU Quotes (Guests and Prospects only)',
        'sort_order' => 40,
        'widget_parameters' => array (
            'display_type' => '0',
            'title' => 'What People Are Saying',
            'use_sort_order' => '1',
            'unique_id' => Mage::helper('core')->uniqHash(),
        ),
    ),
    5 => array(
        'type' => 'tgc_cms/bestSellers',
        'title' => 'AU Bestsellers (Guests and Prospects only)',
        'sort_order' => 50,
        'widget_parameters' => array (
            'display_type' => '0',
            'title' => 'Best Sellers',
            'use_sort_order' => '1',
            'is_responsive' => '0',
            'products_count' => '30',
            'unique_id' => Mage::helper('core')->uniqHash(),
        ),
    ),
);

foreach ($widgets as $widget) {
    $data = array (
        'instance_type' => $widget['type'],
        'package_theme' => 'enterprise/au',
        'title' => $widget['title'],
        'store_ids' => array($storeIds),
        'type' => $widget['type'],
        'sort_order' => $widget['sort_order'],
        'widget_parameters' => $widget['widget_parameters'],
        'page_groups' => array(
            array (
                'page_group' => 'pages',
                'anchor_categories' => array(
                    'page_id' => 0,
                    'layout_handle' => 'default,catalog_category_layered',
                    'for' => 'all',
                    'is_anchor_only' => 1,
                    'product_type_id' => '',
                    'entities' => '',
                ),
                'notanchor_categories' => array(
                    'page_id' => 0,
                    'layout_handle' => 'default,catalog_category_default',
                    'for' => 'all',
                    'is_anchor_only' => 0,
                    'product_type_id' => '',
                    'entities' => '',
                ),
                'all_products' => array(
                    'page_id' => 0,
                    'layout_handle' => 'default,catalog_product_view',
                    'for' => 'all',
                    'is_anchor_only' => 0,
                    'product_type_id' => '',
                    'entities' => '',
                ),
                'simple_products' => array(
                    'page_id' => 0,
                    'layout_handle' => 'default,catalog_product_view,PRODUCT_TYPE_simple',
                    'for' => 'all',
                    'is_anchor_only' => '',
                    'product_type_id' => 'simple',
                    'entities' => '',
                ),
                'grouped_products' => array (
                    'page_id' => 0,
                    'layout_handle' => 'default,catalog_product_view,PRODUCT_TYPE_grouped',
                    'for' => 'all',
                    'is_anchor_only' => '',
                    'product_type_id' => 'grouped',
                    'entities' => '',
                ),
                'configurable_products' => array(
                    'page_id' => 0,
                    'layout_handle' => 'default,catalog_product_view,PRODUCT_TYPE_configurable',
                    'for' => 'all',
                    'is_anchor_only' => '',
                    'product_type_id' => 'configurable',
                    'entities' => '',
                ),
                'virtual_products' => array(
                    'page_id' => 0,
                    'layout_handle' => 'default,catalog_product_view,PRODUCT_TYPE_virtual',
                    'for' => 'all',
                    'is_anchor_only' => '',
                    'product_type_id' => 'virtual',
                    'entities' => '',
                ),
                'bundle_products' => array(
                    'page_id' => 0,
                    'layout_handle' => 'default,catalog_product_view,PRODUCT_TYPE_bundle',
                    'for' => 'all',
                    'is_anchor_only' => '',
                    'product_type_id' => 'bundle',
                    'entities' => '',
                ),
                'downloadable_products' => array(
                    'page_id' => 0,
                    'layout_handle' => 'default,catalog_product_view,PRODUCT_TYPE_downloadable',
                    'for' => 'all',
                    'is_anchor_only' => '',
                    'product_type_id' => 'downloadable',
                    'entities' => '',
                ),
                'giftcard_products' => array(
                    'page_id' => 0,
                    'layout_handle' => 'default,catalog_product_view,PRODUCT_TYPE_giftcard',
                    'for' => 'all',
                    'is_anchor_only' => '',
                    'product_type_id' => 'giftcard',
                    'entities' => '',
                ),
                'all_pages' => array(
                    'page_id' => 0,
                    'layout_handle' => 'default',
                    'for' => 'all',
                ),
                'pages' => array(
                    'page_id' => 0,
                    'for' => 'all',
                    'layout_handle' => 'cms_index_index',
                    'block' => 'top-content',
                ),
            ),
        ),
    );

$model = $this->_initWidgetInstance('enterprise', 'tgc', $widget['type']);
$model->load($widget['title'], 'title')
      ->addData($data)
      ->save();
}

$installer->endSetup();
