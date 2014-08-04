<?php
/**
 * Boutique
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Boutique
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
/* @var $installer Tgc_Boutique_Model_Resource_Setup */
/* @var $conn Varien_Db_Adapter_Interface */
$installer = $this;
$installer->startSetup();

$storeIds = 0;
$widgets = array(
    0 => array(
        'type' => 'tgc_boutique/boutiqueHeroCarousel',
        'title' => 'Boutique Hero Carousel',
        'sort_order' => 0,
        'widget_parameters' => array (
            'display_type' => '2',
            'unique_id' => Mage::helper('core')->uniqHash(),
        ),
    ),
);

foreach ($widgets as $widget) {
    $data = array (
        'instance_type' => $widget['type'],
        'package_theme' => 'enterprise/tgc',
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
                    'layout_handle' => 'tgc_boutique_index_index',
                    'block' => 'content',
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
