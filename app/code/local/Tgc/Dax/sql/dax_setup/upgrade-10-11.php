<?php
/**
 * Dax integration
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Dax
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
/* @var $installer Tgc_Dax_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();

$conn->dropColumn($installer->getTable('salesrule/rule'), 'special_item');
$conn->dropColumn($installer->getTable('salesrule/rule'), 'special_item_type');

$installer->endSetup();
