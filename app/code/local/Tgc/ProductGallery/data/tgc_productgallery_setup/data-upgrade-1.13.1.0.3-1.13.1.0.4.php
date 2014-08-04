<?php
 /**
 * Tgc ProductGallery
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     date-upgrade-1.13.1.0.3-1.13.1.0.4.php
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

/* @var $installer Tgc_Setup_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();

$installer->setConfigData('catalog/placeholder/drtv_trailer_placeholder', '');
$installer->setConfigData('catalog/placeholder/image_placeholder', '');
$installer->setConfigData('catalog/placeholder/small_image_placeholder', '');
$installer->setConfigData('catalog/placeholder/thumbnail_placeholder', '');

$installer->endSetup();