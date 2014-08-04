<?php
/**
 * User: mhidalgo
 * Date: 05/03/14
 * Time: 08:48
 */

class Tgc_Zmag_Model_Resource_Zmag extends Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct()
    {
        $this->_init('tgc_zmag/zmag', 'zmag_id');
    }
}