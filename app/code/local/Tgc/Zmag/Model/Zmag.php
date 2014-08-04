<?php
/**
 * User: mhidalgo
 * Date: 05/03/14
 * Time: 08:46
 */

class Tgc_Zmag_Model_Zmag extends Mage_Core_Model_Abstract
{
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;

    protected function _construct()
    {
        $this->_init('tgc_zmag/zmag');
    }
}