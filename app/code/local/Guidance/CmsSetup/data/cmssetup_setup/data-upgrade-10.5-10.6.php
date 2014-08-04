<?php
/**
 * Guidance CmsSetup
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     CmsSetup
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

/* @var $installer Tgc_Bazaarvoice_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();

$blocks = Mage::getModel('cms/block')->getCollection()
    ->addFieldToFilter('identifier','footer_top_left_area_more_and_help');

if($blocks) {
    foreach($blocks as $block) {
        $block->delete();
    }
}

$installer->endSetup();
