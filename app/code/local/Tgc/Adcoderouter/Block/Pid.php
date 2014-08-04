<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Guidance
 * @package     Adcoderouter
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_Adcoderouter_Block_Pid extends Mage_Core_Block_Template
{
    public function __construct()
    {
        $this->assign('adcodeHelper', Mage::helper('adcoderouter'));
    }

    private function _helper()
    {
        return Mage::helper('adcoderouter');
    }
}