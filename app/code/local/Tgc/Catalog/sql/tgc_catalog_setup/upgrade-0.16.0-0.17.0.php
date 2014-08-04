<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Catalog
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

$installer = $this;
$installer->startSetup();

//Removing the professor_information attribute
$installer->removeAttribute(Mage_Catalog_Model_Product::ENTITY, 'professor_information');

$installer->endSetup();

