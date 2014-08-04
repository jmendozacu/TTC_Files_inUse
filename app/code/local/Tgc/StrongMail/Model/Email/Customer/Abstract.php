<?php
/**
 * Transactional emails abstract class for customer related emails
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Tgc_StrongMail
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
abstract class Tgc_StrongMail_Model_Email_Customer_Abstract extends Tgc_StrongMail_Model_Email_Abstract
{
    /**
     * Customer
     *
     * @var Mage_Customer_Model_Customer
     */
    private $_customer;

    /**
     * Store ID
     *
     * @var int
     */
    private $_storeId;

    /**
     * @var Tgc_StrongMail_Model_Api_Mailer
     */
    private $_mailer;

    /**
     * Store ID setter
     *
     * @param int $storeId
     * @return Tgc_StrongMail_Model_Email_Customer_Abstract
     */
    public function setStoreId($storeId)
    {
        $this->_storeId = $storeId;
        return $this;
    }

    /**
     * Store ID getter
     *
     * @return int
     */
    public function getStoreId()
    {
        return $this->_storeId;
    }

    /**
     * Customer setter
     *
     * @param Tgc_StrongMail_Model_Customer $customer
     * @return Tgc_StrongMail_Model_Email_Customer_Abstract
     */
    public function setCustomer($customer)
    {
        $this->_customer = $customer;
        return $this;
    }

    /**
     * Customer getter
     *
     * @return Tgc_StrongMail_Model_Customer
     */
    public function getCustomer()
    {
        return $this->_customer;
    }

    /**
     * Sends transactional email to customer
     *
     * @return Tgc_StrongMail_Model_Email_Customer_Abstract
     * @throws Exception on error
     */
    public function send()
    {
        $mailingName = $this->_getMailingName();
        $customer = $this->getCustomer();

        $mailer = $this->getMailer();
        $this->_prepareEmailInfo($mailer, $customer->getEmail(), $customer->getName());

        // Set all required params and send emails
        $mailer->setTransactionalMailingName($mailingName);
        $mailer->setAdditionalParams($this->_getAdditionalParams());
        $mailer->send();

        return $this;
    }

    /**
     * @return Tgc_StrongMail_Model_Api_Mailer
     */
    public function getMailer()
    {
        if (!isset($this->_mailer)) {
            $this->_mailer = $this->_createMailer();
        }
        return $this->_mailer;
    }

    /**
     * Returns additional parameters for transactional email template in key-value style.
     *
     * @return array
     */
    abstract protected function _getAdditionalParams();

    /**
     * Gets mailing name. Usually it takes it from the config.
     *
     * @return string
     */
    abstract protected function _getMailingName();
}
