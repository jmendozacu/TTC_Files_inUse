<?php
/**
 * Module's helper
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Tgc_TaxOffice
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_TaxOffice_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * Has library class included or not.
     *
     * @var bool
     */
    private static $_isLibIncluded = false;

    /**
     * Includes TaxOffice library class.
     */
    public function includeLibraryClasses()
    {
        if (!self::$_isLibIncluded) {
            $libDir = Mage::getModuleDir('etc', 'Tgc_TaxOffice') . DS. '..' . DS . 'lib' . DS;
            require_once $libDir . 'STOService.php';
            self::$_isLibIncluded = true;
        }
    }
}