<?php
/**
 * Module's helper
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Tgc_StrongMail
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_StrongMail_Helper_Data extends Mage_Core_Helper_Abstract
{
    private static $_isLibIncluded = false;

    public function includeLibraryClasses()
    {
        if (!self::$_isLibIncluded) {
            $libDir = Mage::getModuleDir('etc', 'Tgc_StrongMail') . DS. '..' . DS . 'lib' . DS;
            require_once $libDir . 'MailingService.php';
            require_once $libDir . 'SecurityHeader.php';
            self::$_isLibIncluded = true;
        }
    }

}