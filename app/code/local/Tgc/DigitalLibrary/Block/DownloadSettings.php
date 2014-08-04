<?php
/**
 * Digital Library Download Settings Page
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DigitalLibrary
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_DigitalLibrary_Block_DownloadSettings extends Mage_Core_Block_Template
{
    private function _getCustomer()
    {
        $customer = Mage::getSingleton('customer/session')->getCustomer();

        return $customer;
    }

    public function getCustomerAudioFormat()
    {
        $customer = $this->_getCustomer();
        $audioOptions = Mage::getModel('tgc_dl/source_audio_format')->toOptionArray();
        $setting = $customer->getAudioFormat();

        return is_null($setting) ? false : $audioOptions[$setting];
    }

    public function getCustomerVideoFormat()
    {
        $customer = $this->_getCustomer();
        $videoOptions = Mage::getModel('tgc_dl/source_video_format')->toOptionArray();
        $setting = $customer->getVideoFormat();

        return is_null($setting) ? false : $videoOptions[$setting];
    }

    public function isConfigured()
    {
        $customer = $this->_getCustomer();

        return !(is_null($customer->getAudioFormat()) || is_null($customer->getVideoFormat()));
    }
}
