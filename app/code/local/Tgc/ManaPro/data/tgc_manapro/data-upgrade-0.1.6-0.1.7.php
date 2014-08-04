<?php
$installer = $this;
$installer->startSetup();

$installer->setConfigData('mana/seo/additional_toolbar_orders', 'news_from_date,guest_bestsellers,authenticated_bestsellers');

$installer->endSetup();
