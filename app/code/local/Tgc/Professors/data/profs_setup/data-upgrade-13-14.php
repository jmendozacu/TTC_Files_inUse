<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category
 * @package
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
/* @var $installer Mage_Core_Model_Resource_Setup */
/* @var $conn Varien_Db_Adapter_Interface */
$installer = $this;
$installer->startSetup();

$professors = Mage::getResourceModel('profs/professor_collection');

foreach ($professors as $professor) {
    /* @var $professor Tgc_Professors_Model_Professor */
    if ($professor->getUrlKey()) {
        continue;
    }

    $i = 0;
    $normalized = strtolower(preg_replace(
        array('|\s+|', '|[\W\._]|', '|-+|'), '-', $professor->getFirstName() . '-' . $professor->getLastName()
    ));

    do {
        $urlKey = $i ? "$normalized-$i" : $normalized;
        $i++;
    } while (!Mage::getModel('profs/professor')->load($urlKey)->isObjectNew());

    $professor->setUrlKey($urlKey)->save();
}

$installer->endSetup();