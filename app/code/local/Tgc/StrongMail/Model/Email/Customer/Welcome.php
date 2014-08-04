<?php
/**
 * Welcome email sender
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Tgc_StrongMail
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_StrongMail_Model_Email_Customer_Welcome extends Tgc_StrongMail_Model_Email_Customer_Abstract
{
    const XML_PATH_MAILING_NAME_REGISTERED = 'tgc_strongmail/customer/welcome_registered';
    const XML_PATH_MAILING_NAME_CONFIRMED = 'tgc_strongmail/customer/welcome_confirmed';
    const XML_PATH_MAILING_NAME_CONFIRMATION = 'tgc_strongmail/customer/welcome_confirmation';

    /**
     * Welcome mailing types
     *
     * @var array
     */
    protected $_types = array(
        'registered'   => self::XML_PATH_MAILING_NAME_REGISTERED, // welcome email, when confirmation is disabled
        'confirmed'    => self::XML_PATH_MAILING_NAME_CONFIRMED, // welcome email, when confirmation is enabled
        'confirmation' => self::XML_PATH_MAILING_NAME_CONFIRMATION // email with confirmation link
    );

    private $_type;
    private $_backUrl;

    /**
     * Returns additional parameters for transactional email template in key-value style.
     * @todo review additional parameters for Reset Password email template, when the access to Message Studio will be granted.
     *
     * @return array
     */
    protected function _getAdditionalParams()
    {
        return array(
            'BACK_URL' => $this->getBackUrl()
        );
    }

    /**
     * Gets mailing name
     *
     * @return string
     */
    protected function _getMailingName()
    {
        return Mage::getStoreConfig($this->_types[$this->getType()], $this->getStoreId());
    }

    /**
     * Returns available mailing types
     *
     * @return array
     */
    public function getTypes()
    {
        return $this->_types;
    }

    /**
     * Set current transactional email type
     *
     * @param string $confirmationType
     * @return Tgc_StrongMail_Model_Email_Customer_Welcome
     * @throws InvalidArgumentException
     */
    public function setType($confirmationType)
    {
        if (!isset($this->_types[$confirmationType])) {
            throw new InvalidArgumentException('Wrong transactional account email type');
        }
        $this->_type = $confirmationType;
        return $this;
    }

    /**
     * Get current transactional email type
     *
     * @return string
     */
    public function getType()
    {
        return $this->_type;
    }

    /**
     * Sets backUrl
     *
     * @param string $backUrl
     * @return Tgc_StrongMail_Model_Email_Customer_Welcome
     */
    public function setBackUrl($backUrl)
    {
        $this->_backUrl = $backUrl;
        return $this;
    }

    /**
     * Gets backUrl
     *
     * @return string
     */
    public function getBackUrl()
    {
        return $this->_backUrl;
    }
}
