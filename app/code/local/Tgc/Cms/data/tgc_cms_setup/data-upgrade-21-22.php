<?php
/**
 * Cms additions
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Cms
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
/* @var $installer Tgc_Cms_Model_Resource_Setup */
/* @var $conn Varien_Db_Adapter_Interface */
$installer = $this;
$installer->startSetup();

$pageUrlsDelete= array(
    'about/ed-leon',
    'about/vanina-delobelle',
    'about/joseph-peckl',
    'about/kevin-lefew',
    'about/paul-suijk',
    'about/scott-abelman',
    'about/shana-jackson',
    'about/bruce-willis',
    'about/scott-ableman',
    'heritage',
    'careers',
    'press_releases',
    'ed_leon',
    'vanina_delobelle',
    'joseph_peckl',
    'kevin_lefew',
    'paul_suijk',
    'scott_abelman',
    'shana_jackson',
    'bruce_willis'
);

$conn->beginTransaction();

try {
    foreach ($pageUrlsDelete as $identifier) {
        $conn->delete(
            $installer->getTable('cms/page'),
            $conn->quoteInto('identifier = ?', $identifier)
        );
    }
    $conn->commit();
} catch (Exception $e) {
    $conn->rollBack();
    throw $e;
}

$installer->endSetup();
