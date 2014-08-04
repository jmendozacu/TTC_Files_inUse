<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Lectures
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

$installer = $this;
$installer->startSetup();

$conn->addColumn($installer->getTable('lectures/lectures'), 'original_lecture_number', array(
    'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
    'default'   => null,
    'nullable'  => true,
    'comment'   => 'Original Lecture Number',
    'after'     => 'product_id',
));

$conn->addColumn($installer->getTable('lectures/lectures'), 'professor', array(
    'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
    'length'    => 255,
    'default'   => null,
    'nullable'  => true,
    'comment'   => 'professor',
    'after'     => 'product_id',
));

$conn->addColumn($installer->getTable('lectures/lectures'), 'akamai_download_id', array(
    'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
    'length'    => 255,
    'default'   => null,
    'nullable'  => true,
    'comment'   => 'Akamai Download ID',
    'after'     => 'product_id',
));

$conn->addColumn($installer->getTable('lectures/lectures'), 'video_brightcove_id', array(
    'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
    'length'    => 255,
    'default'   => null,
    'nullable'  => true,
    'comment'   => 'Video Brightcove ID',
    'after'     => 'product_id',
));

$conn->addColumn($installer->getTable('lectures/lectures'), 'audio_brightcove_id', array(
    'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
    'length'    => 255,
    'default'   => null,
    'nullable'  => true,
    'comment'   => 'Audio Brightcove ID',
    'after'     => 'product_id',
));


$conn->dropColumn($installer->getTable('lectures/lectures'),'lecture_id');

$installer->endSetup();
