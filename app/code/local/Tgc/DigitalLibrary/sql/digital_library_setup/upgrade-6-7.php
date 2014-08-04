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
$conn = $installer->getConnection();

$conn->addColumn(
    $installer->getTable('tgc_dl/crossPlatformResume'),
    'watched',
    array(
        'type'      => Varien_Db_Ddl_Table::TYPE_SMALLINT,
        'length'    => null,
        'default'   => 0,
        'nullable'  => false,
        'comment'   => 'Watched',
    )
);

$conn->addColumn(
    $installer->getTable('tgc_dl/crossPlatformResume'),
    'format',
    array(
        'type'      => Varien_Db_Ddl_Table::TYPE_SMALLINT,
        'length'    => null,
        'default'   => 0,
        'nullable'  => false,
        'comment'   => 'Format',
    )
);

$conn->modifyColumn(
    $installer->getTable('tgc_dl/crossPlatformResume'),
    'stream_date',
    array(
        'type'     => Varien_Db_Ddl_Table::TYPE_DATETIME,
    )
);

$conn->modifyColumn(
    $installer->getTable('tgc_dl/crossPlatformResume'),
    'download_date',
    array(
        'type'     => Varien_Db_Ddl_Table::TYPE_DATETIME,
    )
);

$installer->endSetup();
