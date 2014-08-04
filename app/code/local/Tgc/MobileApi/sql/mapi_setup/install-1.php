<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category
 * @package
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
/* @var $installer Mage_Eav_Model_Entity_Setup */
/* @var $conn Varien_Db_Adapter_Interface */
$installer = $this;
$installer->startSetup();

$installer->addAttribute('customer', 'signup_ad_code', array(
    'type'           => 'varchar',
    'input'          => 'text',
    'visible'        => true,
    'required'       => false,
));

$installer->addAttribute('customer', 'signup_user_agent', array(
        'type'           => 'varchar',
        'input'          => 'text',
        'visible'        => true,
        'required'       => false,
));

$installer->endSetup();