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

$installer->setConfigData('general/store_information/phone', '1-800-832-2412')
    ->setConfigData('trans_email/ident_support/email', 'custserv@thegreatcourses.com')
    ->setConfigData('design/footer/copyright', '&copy; The Teaching Company, LLC. All rights reserved.');

$installer->endSetup();