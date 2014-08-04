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

$pageUrlsChange = array(
    'professors' => 'about-us/professors',
    'heritage' => 'about-us/heritage',
    'customers' => 'about-us/customers',
    'guarantee' => 'about-us/guarantee',
    'team' => 'about-us/team',
    'careers' => 'about-us/careers',
    'press_releases' => 'about-us/press-releases',
    'about' => 'about-us',
    'ed_leon' => 'about/ed-leon',
    'vanina_delobelle' => 'about/vanina-delobelle',
    'joseph_peckl' => 'about/joseph-peckl',
    'kevin_lefew' => 'about/kevin-lefew',
    'paul_suijk' => 'about/paul-suijk',
    'scott_abelman' => 'about/scott-ableman',
    'shana_jackson' => 'about/shana-jackson',
    'bruce_willis' => 'about/bruce-willis'
);

$conn->beginTransaction();

try {
    foreach ($pageUrlsChange as $identifier => $newUrl) {
        $conn->update(
            $installer->getTable('cms/page'),
            array('identifier' => $newUrl),
            $conn->quoteInto('identifier = ?', $identifier)
        );
    }
    $conn->commit();
} catch (Exception $e) {
    $conn->rollBack();
    throw $e;
}

$installer->endSetup();
