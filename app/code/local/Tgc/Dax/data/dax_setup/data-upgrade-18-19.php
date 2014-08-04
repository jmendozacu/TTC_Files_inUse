<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Guidance
 * @package     Dax
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
/* @var $installer Mage_Core_Model_Resource_Setup */

$installer = $this;
$installer->startSetup();

$encrypted = Mage::helper('core')->encrypt('Great-Courses!');
$installer->setConfigData(Tgc_Dax_Model_OrderExport::SFTP_PASSWORD_PATH, $encrypted);

$installer->endSetup();
