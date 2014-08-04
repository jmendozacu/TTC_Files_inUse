<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    The Great Courses
 * @package     Price
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */

//Creates a table to store add code that is associated with the URL.
$installer = $this;
$installer->startSetup();

$conn->addForeignKey(
    $installer->getFkName('enterprise_urlrewrite/url_rewrite','ad_code','tgc_price/adCode','code')
    ,$this->getTable('enterprise_urlrewrite/url_rewrite'),'ad_code'
    ,$this->getTable('tgc_price/adCode'),'code'
);

$installer->endSetup();