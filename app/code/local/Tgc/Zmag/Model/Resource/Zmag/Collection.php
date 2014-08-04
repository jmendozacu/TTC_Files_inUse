<?php
/**
 * User: mhidalgo
 * Date: 05/03/14
 * Time: 08:49
 */

class Tgc_Zmag_Model_Resource_Zmag_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    protected function _construct()
    {
        $this->_init('tgc_zmag/zmag');
    }
}