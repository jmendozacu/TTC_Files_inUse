<?php
/**
 * Price mode setup
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Guidance
 * @package     Setup
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
/* @var $installer Tgc_Setup_Model_Resource_Setup */
/* @var $conn Varien_Db_Adapter_Interface */
$installer = $this;

$installer->startSetup();

$conn->update(
    $installer->getTable('cms/page'),
    array('identifier' => 'support/contact-us'),
    $conn->quoteInto('identifier = ?', 'contact-us')
);

$installer->endSetup();