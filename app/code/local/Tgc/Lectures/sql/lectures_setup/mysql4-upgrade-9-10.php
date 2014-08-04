<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Lectures
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

$installer = $this;
$installer->startSetup();

$installer->getConnection()->truncateTable($installer->getTable('lectures/lectures')); //table must be truncated before unique ids are created.

$installer->getConnection()->addIndex(
    $installer->getTable('lectures/lectures'),
    $installer->getIdxName(
        'lectures/lectures',
        array('akamai_download_id'),
        Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
    ),
    array('akamai_download_id'),
    Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
);

$installer->getConnection()->addIndex(
    $installer->getTable('lectures/lectures'),
    $installer->getIdxName(
        'lectures/lectures',
        array('video_brightcove_id'),
        Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
    ),
    array('video_brightcove_id'),
    Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
);

$installer->getConnection()->addIndex(
    $installer->getTable('lectures/lectures'),
    $installer->getIdxName(
        'lectures/lectures',
        array('audio_brightcove_id'),
        Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
    ),
    array('audio_brightcove_id'),
    Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
);

$installer->endSetup();
