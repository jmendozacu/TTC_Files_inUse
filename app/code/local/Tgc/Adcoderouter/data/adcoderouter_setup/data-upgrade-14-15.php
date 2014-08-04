<?php
/**
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    The Great Courses
 * @package     Adcoderouter
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
/* @var $installer Mage_Core_Model_Resource_Setup */
/* @var $conn Varien_Db_Adapter_Interface */
$installer = $this;
$installer->startSetup();

$daxHelper = Mage::helper('tgc_dax');
$adCodeRedirects = Mage::getResourceModel('adcoderouter/redirects_collection')
    ->addFieldToSelect('pid')
    ->addFieldToSelect('id');

//Please note: pid is the same thing as the welcome message.
if($adCodeRedirects->count() > 0) {
    foreach($adCodeRedirects as $adCodeRedirect) {
        $pid = $adCodeRedirect->getPid();
        $urldecodedPid = $daxHelper->formatPid($pid);
        $adCodeRedirect->setPid($urldecodedPid);
    }

    $adCodeRedirects->save();
}


$installer->endSetup();
