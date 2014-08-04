<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Catalog
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
$installer = $this;
$installer->startSetup();

$this->putAttributeBeforeOrAfterAnotherAttribute('new_release','news_from_date');

$installer->endSetup();
