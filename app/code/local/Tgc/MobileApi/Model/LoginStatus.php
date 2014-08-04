<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category
 * @package
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_MobileApi_Model_LoginStatus extends Tgc_MobileApi_Model_Login_Abstract
{
    protected function _retrieve()
    {
        $id = $this->getRequest()->getParam('id');

        try {
            $customer = $this->_loadCustomerById($id);
            $response = $this->_createSuccessResponse($customer);
        } catch (Exception $e) {
            Mage::logException($e);
            $response = $this->_createFailResponse($e);
        }

        return $response;
    }
}