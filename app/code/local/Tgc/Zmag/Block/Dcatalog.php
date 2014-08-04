<?php
/**
 * User: mhidalgo
 * Date: 06/03/14
 * Time: 12:29
 */
class Tgc_Zmag_Block_Dcatalog extends Mage_Core_Block_Template
{
    public function getZmag() {
        return Mage::helper('tgc_zmag')->getZmagCollection()->getFirstItem();
    }
}