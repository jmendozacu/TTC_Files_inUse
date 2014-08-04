<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Lectures
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_Lectures_Block_Freelectures_Form extends Mage_Core_Block_Template
{
    public function getIsLectureFreeProspect()
    {
        $session = Mage::getSingleton('customer/session');
        $customerId = $session->getCustomerId();
        $isFreeLectureProspect = true;

        if($customerId) {
            $customer = Mage::getModel('customer/customer')->load($customerId);
            if($customer->getId()) {
                $subscribeStatus = $customer->getFreeLectSubscribeStatus();
                $isFreeLectureProspect = $subscribeStatus != 1 && $subscribeStatus !=2 ? true: false; //3 means unsubscribed
            }
        }

        return $isFreeLectureProspect;
    }
}