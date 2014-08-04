<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category
 * @package
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_MobileApi_Model_Resource extends Mage_Api2_Model_Resource
{
    protected function _loadCustomerById($id)
    {
        $customer = Mage::getResourceModel('customer/customer_collection')
            ->addAttributeToFilter('web_user_id', $id)
            ->fetchItem();

        if (!$customer) {
            throw new InvalidArgumentException('Invalid customer ID.');
        }
        $customer->load($customer->getId());

        return $customer;
    }
}