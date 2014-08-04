<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Lectures
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_Lectures_Block_Freelectures_Subform extends Mage_Core_Block_Template
{
    public function _construct()
    {
        parent::_construct();
        $this->assign('helperFreelectures', $this->_freelecturesHelper());
    }

    /**
     * @return Mage_Core_Helper_Abstract
     */
    protected function _freelecturesHelper()
    {
        return Mage::helper('tgc_catalog/freemarketinglecture');
    }
}