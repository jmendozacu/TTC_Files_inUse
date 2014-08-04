<?php

/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     ManaPro
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
/* @var $installer Guidance_Setup_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();

$installer->setConfigData('mana/seo/additional_toolbar_orders', 'news_from_date,guest_bestsellers,authenticated_bestsellers,inline_rating');

$installer->endSetup();
