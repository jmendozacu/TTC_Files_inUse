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

$pageUrlsUpdate= array(
    'support/contact-us',
    'support/shopping',
    'support/ordering-shipping',
    'support/returns-exchanges',
    'support/my-account',
    'support/downloads',
    'support/streaming',
    'support/browser-related-questions',
    'support/copyright-information',
    'support/privacy-policy',
    'support/terms-conditions',
    'support/faqs'
);

$conn->beginTransaction();

try {
    foreach ($pageUrlsUpdate as $identifier) {
        $conn->update(
            $installer->getTable('cms/page'),
            array('root_template'=>'one_column_about'),
            $conn->quoteInto('identifier = ?', $identifier)
        );
    }
    $conn->commit();
} catch (Exception $e) {
    $conn->rollBack();
    throw $e;
}

$installer->endSetup();
