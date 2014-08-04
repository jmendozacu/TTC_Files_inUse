<?php
/**
 * Re-import static redirects
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Guidance
 * @package     Setup
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
/* @var $installer Tgc_Setup_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();

$installer->getConnection()->beginTransaction();
try {
    $installer->getConnection()->delete(
        $installer->getTable('enterprise_urlrewrite/redirect'),
        $installer->getConnection()->quoteInto('identifier LIKE ?', 'tgc/About Us%')
    );

    $installer->uploadUrlRewritesFromCsv(
        dirname(__FILE__) . '/../missing-Static-Redirects.csv',
        'RP',
        Mage::app()->getStore('default')->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB, false)
    );

    $installer->getConnection()->commit();
} catch (Exception $e) {
    $installer->getConnection()->rollBack();
    throw $e;
}

$installer->endSetup();