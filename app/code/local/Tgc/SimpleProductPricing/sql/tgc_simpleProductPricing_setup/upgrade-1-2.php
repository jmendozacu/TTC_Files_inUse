<?php
/**
 * Description
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Tgc_SimpleProductPricing
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();

$db = $installer->getConnection();
$select = $db->select()
    ->from(
        $installer->getTable('catalog_product_relation'),
        array(
            'version' => new Zend_Db_Expr('NULL'),
            'parent_id' => 'parent_id'
        )
    )
    ->where('child_id = NEW.entity_id');

$inserts = array(
    $installer->getConnection()->insertFromSelect($select, $installer->getTable('catalogsearch_fulltext_cl'), array(), Varien_Db_Adapter_Interface::INSERT_IGNORE),
    $installer->getConnection()->insertFromSelect($select, $installer->getTable('catalog_product_index_price_cl'), array(), Varien_Db_Adapter_Interface::INSERT_IGNORE),
    $installer->getConnection()->insertFromSelect($select, $installer->getTable('catalog_product_flat_cl'), array(), Varien_Db_Adapter_Interface::INSERT_IGNORE)
);

$triggerBody = implode(";\n", $inserts) . ';';

$targetTable = $installer->getTable('catalog_product_entity_decimal');

$triggersData = array(
    array(
        'event' => Magento_Db_Sql_Trigger::SQL_EVENT_UPDATE,
        'time' => Magento_Db_Sql_Trigger::SQL_TIME_AFTER,
        'body' => $triggerBody
    ),
    array(
        'event' => Magento_Db_Sql_Trigger::SQL_EVENT_INSERT,
        'time' => Magento_Db_Sql_Trigger::SQL_TIME_AFTER,
        'body' => $triggerBody
    ),
     array(
        'event' => Magento_Db_Sql_Trigger::SQL_EVENT_DELETE,
        'time' => Magento_Db_Sql_Trigger::SQL_TIME_AFTER,
        'body' => str_replace('NEW.entity_id', 'OLD.entity_id', $triggerBody)
    ),
);

$metadata = Mage::getModel('enterprise_mview/metadata')
    ->load('catalog_product_index_price_view', 'view_name');
$subscribers = Mage::getModel('enterprise_mview/subscriber')->getCollection()
    ->addFieldToFilter('metadata_id', $metadata->getId());

foreach ($triggersData as $triggerItem) {
    $trigger = new Magento_Db_Sql_Trigger();

    $trigger->setEvent($triggerItem['event'])
        ->setTime($triggerItem['time'])
        ->setTarget($targetTable)
        ->setBody($triggerItem['body']);

    $triggerObject = new Magento_Db_Object_Trigger($db, $trigger->getName());
    if ($triggerObject->isExists()) {
        $data = $triggerObject->describe();
        $currentBody = substr($data[$trigger->getName()]['action_statement'], 5, -3);
        $trigger->setBody(trim($currentBody, " ;\r\n") . ";\n" . implode("\n", $trigger->getBody()));
        $triggerObject->drop();
    }
    $db->query($trigger->assemble());

    foreach ($subscribers as $subscriber) {
        $db->insertOnDuplicate(
            $installer->getTable('tgc_mview/custom_trigger'),
            array(
                'subscriber_id' => $subscriber->getId(),
                'event_name' => $triggerItem['event'],
                'trigger_body' => implode("\n", $trigger->getBody())
            )
        );
    }
}

$installer->endSetup();
