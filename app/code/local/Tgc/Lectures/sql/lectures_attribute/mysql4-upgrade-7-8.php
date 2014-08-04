<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Lectures
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

$installer = $this;
$installer->startSetup();

$installer->removeAttribute(Mage_Catalog_Model_Product::ENTITY, 'num_lecture');

$installer->endSetup();
