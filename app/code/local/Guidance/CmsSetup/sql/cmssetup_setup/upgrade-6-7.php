<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category
 * @package
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

$installer = $this;
/* @var $installer Guidance_CmsSetup_Model_Resource_Setup */
/* @var $conn Varien_Db_Adapter_Interface */

$installer->startSetup();

$installer->changePageIdentifier('shopping', 'support/shopping')
    ->changePageIdentifier('ordering-shipping', 'support/ordering-shipping')
    ->changePageIdentifier('my-account', 'support/my-account')
    ->changePageIdentifier('downloads', 'support/downloads')
    ->changePageIdentifier('streaming', 'support/streaming')
    ->changePageIdentifier('browser-related-questions', 'support/browser-related-questions')
    ->changePageIdentifier('copyright-information', 'support/copyright-information')
    ->changePageIdentifier('privacy-policy', 'support/privacy-policy')
    ->changePageIdentifier('terms-conditions', 'support/terms-conditions');

$installer->endSetup();
