<?php
/* @var $installer Guidance_Setup_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();

$db = $installer->getConnection();

$filterTable = $installer->getTable('mana_filters/filter2');

$select = $db->select()->from($filterTable, 'id')
    ->where('code = ?', 'price');
$id = $db->fetchOne($select);

$model = Mage::getModel('mana_filters/filter2')->load($id);

$useDefault = array(
    'is_enabled',
    'is_enabled_in_search',
    'display',
    'position',
    'show_more_item_count',
    'show_more_method',
    'show_in',
    'disable_no_result_options',
    'slider_number_format',
    'url_position'
);

$fields = array(
    'range_step' => '50.0000'
);

$model->addEditedData($fields, $useDefault);
$model->validateKeys();
Mage::helper('mana_db')->replicateObject($model, array(
    $model->getEntityName() => array('saved' => array($model->getId()))
));
$model->validate();

// do save
$model->save();
Mage::dispatchEvent('m_saved', array('object' => $model));

$installer->endSetup();
