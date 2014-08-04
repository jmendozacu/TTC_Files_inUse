<?php
/**
 * Dax integration
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Dax
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Dax_Model_Cron
{
    public static function orderExport()
    {
        Mage::getSingleton('tgc_dax/orderExport')->processExport();
    }
}
