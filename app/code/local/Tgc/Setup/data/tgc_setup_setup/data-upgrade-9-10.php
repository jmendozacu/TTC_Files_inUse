<?php
/**
 * Theme Setup
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Guidance
 * @package     Setup
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
/* @var $installer Tgc_Setup_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$installer->setConfigData('ultimo/category_grid/hover_effect', 0)
    ->setConfigData('ultimo/category_grid/display_rating', 2)
    ->setConfigData('ultimo/category_grid/display_addtocart', 0)
    ->setConfigData('ultimo/category_grid/display_addtolinks', 0)
    ->setConfigData('ultimo/category_grid/addtolinks_simple', 0)
    ->setConfigData('ultimo/category_grid/hover_effect', 0)
    ->setConfigData('catalog/frontend/list_mode', 'list-grid');

$installer->endSetup();