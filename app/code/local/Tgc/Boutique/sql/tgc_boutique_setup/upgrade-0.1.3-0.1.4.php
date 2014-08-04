<?php
/**
 * Boutique
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Boutique
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
/* @var $installer Tgc_Boutique_Model_Resource_Setup */
/* @var $conn Varien_Db_Adapter_Interface */
$installer = $this;
$installer->startSetup();
$conn = $installer->getConnection();

$conn->dropForeignKey(
    $installer->getTable('tgc_boutique/boutiquePages'),
    $installer->getFkName('tgc_boutique/boutiquePages', 'footer_block', 'cms/block', 'block_id')
);

$installer->endSetup();
