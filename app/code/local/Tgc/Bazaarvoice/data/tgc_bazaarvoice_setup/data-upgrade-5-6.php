<?php
/**
 * Bazaarvoice api setup
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Bazaarvoice
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

/* @var $installer Tgc_Bazaarvoice_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

//$password = Mage::helper('core')->encrypt('');
//$installer->setConfigData('bazaarvoice/conversations_api/password', $password);

$installer->endSetup();
