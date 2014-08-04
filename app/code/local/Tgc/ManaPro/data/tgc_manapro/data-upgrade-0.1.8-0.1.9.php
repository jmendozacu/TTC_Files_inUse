<?php
/* @var $installer Guidance_Setup_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();

$db = $installer->getConnection();

$filterTable = $installer->getTable('mana_filters/filter2');

$db->update($filterTable, array('type' => 'all_types'), $db->quoteInto('code = ?', 'all_types'));

$installer->endSetup();
