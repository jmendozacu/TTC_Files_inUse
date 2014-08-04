<?php
/**
 * User: mhidalgo
 * Date: 25/04/14
 * Time: 14:41
 */

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();

$table = $installer->getTable('profs/professor');

$installer->run("
    UPDATE `{$table}`
    SET `rank` = 160
    WHERE `rank` = 0
");

$installer->run("
    UPDATE `{$table}`
    SET `rank` = 0
    WHERE   `first_name` = 'Patrick'        AND `last_name` = 'Grim'        OR
            `first_name` = 'Michael'        AND `last_name` = 'Starbird'    OR
            `first_name` = 'Robert G.'      AND `last_name` = 'Fovell'      OR
            `first_name` = 'Neil deGrasse'  AND `last_name` = 'Tyson'       OR
            `first_name` = 'Vejas Gabriel'  AND `last_name` = 'Liulevicius' OR
            `first_name` = 'Arthur T.'      AND `last_name` = 'Benjamin'
");

$installer->endSetup();