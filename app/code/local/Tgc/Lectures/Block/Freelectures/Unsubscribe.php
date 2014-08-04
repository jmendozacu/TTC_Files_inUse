<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Lectures
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_Lectures_Block_Freelectures_Unsubscribe extends Mage_Core_Block_Template
{
    public function _construct()
    {
        parent::_construct();
        $this->assign('helperLectures', $this->_lecturesHelper());
    }

    /**
     * @return Mage_Core_Helper_Abstract
     */
    protected function _lecturesHelper()
    {
        return Mage::helper('lectures/unsubscribe');
    }
}