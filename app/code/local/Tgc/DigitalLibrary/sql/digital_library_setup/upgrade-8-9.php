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
    $installer->getTable('tgc_dl/accessRights'),
    'order_id',
    array(
        'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
        'length'    => 50,
        'comment'   => 'Increment ID'
    )
);

$collection = Mage::getModel('tgc_dl/crossPlatformResume')
    ->getCollection()
    ->addFieldToSelect('*');

$datas = array();

foreach ($collection as $item)
{
    $serialized = serialize(
        array(
            $item->getLectureId(),
            $item->getWebUserId(),
            $item->getFormat(),
        )
    );

    if (in_array($serialized, $datas)) {
        $item->delete();
    } else {
        $datas[] = $serialized;
    }
}

$conn->addIndex(
    $installer->getTable('tgc_dl/crossPlatformResume'),
    $installer->getIdxName(
        $installer->getTable('tgc_dl/crossPlatformResume'),
        array('lecture_id', 'web_user_id', 'format'),
        Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
    ),
    array('lecture_id', 'web_user_id', 'format'),
    Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
);

$installer->endSetup();
