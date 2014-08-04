<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Guidance
 * @package     Setup
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
/* @var $installer Tgc_Setup_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();

$installer->setConfigData('design/theme/template_ua_regexp', serialize(array('_1396521403140_140' => array('regexp' => 'iPhone|iPod|Android', 'value' => 'tgc_mob'))));
$installer->endSetup();