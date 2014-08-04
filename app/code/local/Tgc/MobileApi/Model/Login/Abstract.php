<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category
 * @package
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_MobileApi_Model_Login_Abstract extends Tgc_MobileApi_Model_Resource
{
    protected function _createSuccessResponse(Mage_Customer_Model_Customer $customer)
    {
        return array(
            'notification' => 'Login successfully!!',
            'user' => $this->_createUserAttribute($customer),
            'banner' => $this->_createBannerAttribute($customer),
        );
    }

    protected function _createFailResponse(Exception $e)
    {
        $message = ($e instanceof Mage_Core_Exception) ?
            $e->getMessage() :
            'Sorry, there was an error logging you in. Please try again.';

        return array(
            'notification' => 'failed',
            'message' => $message,
        );
    }

    private function _createUserAttribute(Mage_Customer_Model_Customer $customer)
    {
        return array(
            'firstName' => $customer->getFirstname(),
            'lastName'  => $customer->getLastname(),
            'token'     => $customer->getWebUserId(),
        );
    }

    private function _createBannerAttribute(Mage_Customer_Model_Customer $customer)
    {
        $caption = str_replace('{{email}}', $customer->getEmail(), $this->_getConfigData('caption'));

        return array(
            'id'       => (int)$this->_getConfigData('id'),
            'imageurl' => $this->_getConfigData('image_url'),
            'caption'  => $caption,
            'linkurl'  => $this->_getConfigData('link_url'),
       );
    }

    private function _getConfigData($key)
    {
        return Mage::getStoreConfig("mapi/banner/$key");
    }
}
