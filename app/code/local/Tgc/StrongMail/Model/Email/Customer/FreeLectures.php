<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category
 * @package
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_StrongMail_Model_Email_Customer_FreeLectures extends Tgc_StrongMail_Model_Email_Customer_Abstract
{
    const XML_PATH_MAILING_NAME_FREE_LECTURES = 'tgc_strongmail/customer/free_lectures';

    protected function _getAdditionalParams()
    {
        return array(
            'email_addr' => $this->getCustomer()->getEmail(),
            Mage::helper('tgc_customer')->getFreeLecturesConfirmationParameterId() => $this->getCustomer()->getId(),
            Mage::helper('tgc_customer')->getFreeLecturesConfirmationParameterToken() => $this->getCustomer()->getConfirmationGuid(),
        );
    }

    protected function _getMailingName()
    {
        return Mage::getStoreConfig(self::XML_PATH_MAILING_NAME_FREE_LECTURES, $this->getStoreId());
    }

    /**
     * Event listener that sends free lectures email by customer
     *
     * @param Varien_Event_Observer $observer
     * @throws InvalidArgumentException If event does not have customer
     */
    public function sendByEvent(Varien_Event_Observer $observer)
    {
        $this->setCustomer($this->_getCustomerFromEvent($observer->getEvent()))
             ->setStoreId(Mage::app()->getStore()->getId())
             ->send();
    }

    /**
     * Retreive customer from event
     *
     * @param Varien_Event $event Event
     * @throws InvalidArgumentException If event does not have customer
     * @return Mage_Customer_Model_Customer Customer
     */
    private function _getCustomerFromEvent(Varien_Event $event)
    {
        $customer = $event->getCustomer();
        if (!$customer instanceof Mage_Customer_Model_Customer) {
            throw new InvalidArgumentException('Event should contain customer.');
        }

        return $customer;
    }
}
