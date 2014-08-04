<?php

class Tgc_Checkout_Block_Onepage_Billing extends Mage_Checkout_Block_Onepage_Billing
{
    protected function _construct()
    {
        parent::_construct();
        $stepInfo = $this->getCheckout()->getStepData('billing');
        $stepInfo['label'] = Mage::helper('checkout')->__('Billing Address');
        $this->getCheckout()->setStepData('billing',$stepInfo);
    }
}
