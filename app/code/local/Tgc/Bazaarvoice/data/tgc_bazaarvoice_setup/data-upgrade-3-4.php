<?php
/**
 * Bazaarvoice dataflow profile setup
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Bazaarvoice
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

/* @var $installer Tgc_Bazaarvoice_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$actionsXml = <<<XML
<action type="tgc_bv/convert_adapter_io" method="load">
    <var name="type">ftp</var>
    <var name="path">var/import</var>
    <var name="filename"><![CDATA[bv_teachco_ratings.xml]]></var>
    <var name="host"><![CDATA[ftp-stg.bazaarvoice.com]]></var>
    <var name="port"><![CDATA[23]]></var>
    <var name="passive">true</var>
    <var name="file_mode">2</var>
    <var name="user"><![CDATA[teachco]]></var>
    <var name="password"><![CDATA[5Hvb$--6]]></var>
    <var name="format"><![CDATA[excel_xml]]></var>
</action>

<action type="tgc_bv/convert_parser_review" method="parse">
    <var name="single_sheet"><![CDATA[]]></var>
    <var name="store"><![CDATA[0]]></var>
    <var name="number_of_records">1</var>
    <var name="decimal_separator"><![CDATA[.]]></var>
    <var name="adapter">tgc_bv/convert_adapter_review</var>
    <var name="method">parse</var>
</action>
XML;

$profile = array(
    'name'          => Tgc_Bazaarvoice_Model_Resource_Setup::PROFILE_NAME,
    'entity_type'   => Tgc_Bazaarvoice_Model_Convert_Adapter_Review::ENTITY,
    'actions_xml'   => $actionsXml,
    'gui_data'      => 'a:3:{s:6:"import";a:2:{s:17:"number_of_records";s:1:"1";s:17:"decimal_separator";s:1:".";}s:4:"file";a:8:{s:4:"type";s:3:"ftp";s:8:"filename";s:22:"bv_teachco_ratings.xml";s:4:"path";s:10:"var/import";s:4:"host";s:26:"ftp-stg.bazaarvoice.com:23";s:4:"user";s:7:"teachco";s:8:"password";s:8:"5Hvb$--6";s:9:"file_mode";s:1:"2";s:7:"passive";s:4:"true";}s:5:"parse";a:5:{s:4:"type";s:9:"excel_xml";s:12:"single_sheet";s:0:"";s:9:"delimiter";s:1:",";s:7:"enclose";s:0:"";s:10:"fieldnames";s:0:"";}}',
    'direction'     => 'import',
    'store_id'      => 0,
    'data_transfer' => 'file',
);

$model = Mage::getModel('dataflow/profile')
    ->getCollection()
    ->addFieldToFilter('name', array('eq' => Tgc_Bazaarvoice_Model_Resource_Setup::PROFILE_NAME))
    ->getFirstItem();

if (!$model->getId()) {
    $model = Mage::getModel('dataflow/profile');
}

$model->setData($profile)
    ->save();

$installer->endSetup();
