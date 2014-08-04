<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Lectures
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

$installer = $this;
$installer->startSetup();

$conn->addColumn($installer->getTable('lectures/lectures'), 'video_download_filesize_mac', array(
    'type'      => Varien_Db_Ddl_Table::TYPE_FLOAT,
    'nullable'  => true,
    'default'   => null,
    'comment'   => 'mac video filesize',
    'after'     => 'duration',
));

$conn->addColumn($installer->getTable('lectures/lectures'), 'video_download_filesize_pc', array(
    'type'      => Varien_Db_Ddl_Table::TYPE_FLOAT,
    'nullable'  => true,
    'default'   => null,
    'comment'   => 'pc video filesize',
    'after'     => 'duration',
));

$conn->addColumn($installer->getTable('lectures/lectures'), 'audio_download_filesize', array(
    'type'      => Varien_Db_Ddl_Table::TYPE_FLOAT,
    'nullable'  => true,
    'default'   => null,
    'comment'   => 'audio filesize',
    'after'     => 'duration',
));

$conn->addColumn($installer->getTable('lectures/lectures'), 'video_available', array(
    'type'      => Varien_Db_Ddl_Table::TYPE_BOOLEAN,
    'nullable'  => false,
    'default'   => 0,
    'comment'   => 'Video Available',
    'after'     => 'duration',
));

$conn->addColumn($installer->getTable('lectures/lectures'), 'audio_available', array(
    'type'      => Varien_Db_Ddl_Table::TYPE_BOOLEAN,
    'nullable'  => false,
    'default'   => 0,
    'comment'   => 'Audio Available',
    'after'     => 'duration',
));

$conn->addColumn($installer->getTable('lectures/lectures'), 'video_duration', array(
    'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
    'default'   => null,
    'nullable'  => true,
    'comment'   => 'Video Duration',
    'after'     => 'duration',
));

$conn->addColumn($installer->getTable('lectures/lectures'), 'audio_duration', array(
    'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
    'default'   => null,
    'nullable'  => true,
    'comment'   => 'Audio Duration',
    'after'     => 'duration',
));

$installer->endSetup();
