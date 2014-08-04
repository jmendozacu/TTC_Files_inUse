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

$conn->addIndex(
    $installer->getTable('tgc_dl/crossPlatformResume'),
    $installer->getIdxName(
        $installer->getTable('tgc_dl/crossPlatformResume'),
        array('lecture_id', 'web_user_id'),
        Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX
    ),
    array('lecture_id', 'web_user_id'),
    Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX
);

$installer->endSetup();
