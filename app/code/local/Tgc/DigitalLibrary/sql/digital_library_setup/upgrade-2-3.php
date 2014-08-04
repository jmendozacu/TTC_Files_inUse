<?php
/**
 * Digital Library
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DigitalLibrary
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
/* @var $installer Tgc_DigitalLibrary_Model_Resource_Setup */
/* @var $conn Varien_Db_Adapter_Interface */
$installer = $this;
$installer->startSetup();

$installer->getConnection()->addColumn(
    $installer->getTable('tgc_dl/accessRights'),
    'is_downloadable',
    "TINYINT(1) UNSIGNED NOT NULL DEFAULT '0'"
);

$conn->addIndex(
    $installer->getTable('tgc_dl/accessRights'),
    $installer->getIdxName(
        $installer->getTable('tgc_dl/accessRights'),
        array('is_downloadable'),
        Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX
    ),
    array('is_downloadable'),
    Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX
);

$installer->endSetup();
