<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Dax
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Dax_Block_Subscription_Unsubscribe extends Mage_Core_Block_Template
{
    public function _construct()
    {
        parent::_construct();
        $this->assign('helperDaxUnsubscribe', $this->_daxUnsubscribeHelper());
    }

    /**
     * @return Mage_Core_Helper_Abstract
     */
    protected function _daxUnsubscribeHelper()
    {
        return Mage::helper('tgc_dax/unsubscribe');
    }
}