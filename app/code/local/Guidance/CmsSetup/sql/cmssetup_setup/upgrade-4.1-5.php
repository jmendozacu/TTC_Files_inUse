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

$installer->changePageIdentifier('returns-exchanges', 'support/returns-exchanges')
    ->changePageIdentifier('browser-related-questions', 'support/browser-related-questions')
    ->changePageIdentifier('faqs', 'support/faqs');

$installer->endSetup();
