<?php
/**
 * New Order Transactional Email model
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Tgc_StrongMail
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_StrongMail_Model_Email_Sales_Order_New extends Tgc_StrongMail_Model_Email_Sales_Order_Abstract
{
    const XML_PATH_MAILING_NAME= 'tgc_strongmail/sales/new_order_registered';

    /**
     * Sends new order transactional email to customer
     *
     * @return Tgc_StrongMail_Model_Email_Sales_Order_New
     * @throws Exception on error
     */
    public function send()
    {
        $order = $this->getOrder();

        $storeId = $order->getStoreId();

        // Retrieve corresponding mailing name and customer name
        $mailingName = Mage::getStoreConfig(self::XML_PATH_MAILING_NAME, $storeId);
        $customerName = $order->getCustomerName();

        $mailer = $this->_createMailer();
        $this->_prepareEmailInfo($mailer, $order->getCustomerEmail(), $customerName);

        // Set all required params and send emails
        $mailer->setTransactionalMailingName($mailingName);
        $mailer->setAdditionalParams($this->_getAdditionalParams());
        $mailer->send();
    }

    /**
     * Returns additional parameters for the transactional email.
     *
     * @return array
     */
    protected function _getAdditionalParams()
    {
        $order = $this->getOrder();
        return array(
            'ORDER_INCREMENT_ID' => $this->getOrder()->getIncrementId(),
            'FIRSTNAME' => $order->getCustomerFirstname()
        );
    }
}
