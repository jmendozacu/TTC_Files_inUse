<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category
 * @package
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_MobileApi_Model_ForgotPassword extends Mage_Api2_Model_Resource
{
    protected function _retrieve()
    {
        try {
            $email = $this->getRequest()->getParam('email');
            $websiteId = $this->_getWebsiteId();
            Mage::getModel('tgc_customer/service_forgotPassword')->send($email, $websiteId);
            $message = 'Success';
        } catch (Exception $e) {
            Mage::logException($e);
            $message = $e->getMessage();
        }

        return array($message);
    }

    /**
     * Render data using registered Renderer
     *
     * @param mixed $data
     */
    protected function _render($data)
    {
        parent::_render(reset($data));
    }

    private function _getWebsiteId()
    {
        $code = $this->getRequest()->getParam('website');
        if (!$code) {
            return null;
        }

        $website = Mage::getModel('core/website')->load($code);
        if ($website->isObjectNew()) {
            throw new InvalidArgumentException('Invalid website code.');
        }

        return $website->getId();
    }
}