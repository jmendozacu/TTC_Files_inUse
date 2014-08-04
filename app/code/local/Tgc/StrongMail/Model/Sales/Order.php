<?php
/**
 * Order model.
 * Has been overridden, because we need to send new order transactional email, using StrongMail
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Tgc_StrongMail
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_StrongMail_Model_Sales_Order extends Mage_Sales_Model_Order
{

    protected $_disableNewOrderEmail = true;
    /**
     * Returns new order transactional email model
     *
     * @return Tgc_StrongMail_Model_Email_Sales_Order_New
     */
    protected function _getNewOrderEmailSender()
    {
        return Mage::getModel('tgc_strongMail/email_sales_order_new');
    }

    /**
     * Sends new Order transactional email
     *
     * @return Mage_Sales_Model_Order
     */
    public function sendNewOrderEmail()
    {
        if(!$this->_disableNewOrderEmail) {
            $storeId = $this->getStore()->getId();

            if (!Mage::helper('sales')->canSendNewOrderEmail($storeId)) {
                return $this;
            }

            $emailSentAttributeValue = $this->load($this->getId())->getData('email_sent');
            $this->setEmailSent((bool)$emailSentAttributeValue);
            if ($this->getEmailSent()) {
                return $this;
            }

            $emailSender = $this->_getNewOrderEmailSender();
            $emailSender->setOrder($this);
            $emailSender->send();

            $this->setEmailSent(true);
            $this->_getResource()->saveAttribute($this, 'email_sent');
        }

        return $this;
    }
}