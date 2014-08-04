<?php
/**
 * Transactional e-mails sender
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Tgc_StrongMail
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_StrongMail_Model_Api_Mailer
{
    /**
     * Transactional Mailing name
     *
     * @var string
     */
    private $_mailingName;

    /**
     * Additional parameters, which may be needed for email templates, etc.
     *
     * @var array
     */
    private $_additionalParams;

    /**
     * Email info - the list of e-mails, which we want to notify by a transactional e-mail
     *
     * @var array
     */
    private $_emailInfo = array();

    /**
     * API processor
     *
     * @var Tgc_StrongMail_Model_Api_Mailer_ProcessorInterface
     */
    private $_apiProcessor;

    /**
     * Constructor
     *
     * @param $args 'processor' api processor is required
     * @throws InvalidArgumentException
     */
    public function __construct($args)
    {
        if (!isset($args['processor'])) {
            throw new InvalidArgumentException("'processor' argument is required");
        }
        if (!($args['processor'] instanceof Tgc_StrongMail_Model_Api_Mailer_ProcessorInterface)) {
            throw new InvalidArgumentException("'processor' must be an instance of Tgc_StrongMail_Model_Api_Mailer_ProcessorInterface");
        }

        $this->_apiProcessor = $args['processor'];
    }

    /**
     * Adds email info to the object
     *
     * @param Mage_Core_Model_Email_Info $emailInfo
     * @return Tgc_StrongMail_Model_Api_Mailer
     */
    public function addEmailInfo(Mage_Core_Model_Email_Info $emailInfo)
    {
        $this->_emailInfo[] = $emailInfo;
        return $this;
    }

    /**
     * Gets all emails info
     *
     * @return array
     */
    public function getEmailInfo()
    {
        return $this->_emailInfo;
    }

    /**
     * Setter for email info
     *
     * @param array $emailInfo
     * @return Tgc_StrongMail_Model_Api_Mailer
     */
    public function setEmailInfo(array $emailInfo)
    {
        $this->_emailInfo = $emailInfo;
        return $this;
    }

    /**
     * Setter for additional parameters
     *
     * @param array $additionalParams
     * @return Tgc_StrongMail_Model_Api_Mailer
     */
    public function setAdditionalParams($additionalParams)
    {
        $this->_additionalParams = $additionalParams;
        return $this;
    }

    /**
     * Getter for additional parameter
     *
     * @return array
     */
    public function getAdditionalParams()
    {
        return $this->_additionalParams;
    }

    /**
     * Setter for transactional mailing name
     *
     * @param string $mailingName
     * @return Tgc_StrongMail_Model_Api_Mailer
     */
    public function setTransactionalMailingName($mailingName)
    {
        $this->_mailingName = $mailingName;
        return $this;
    }

    /**
     * Getter for transactional mailing name
     *
     * @return string
     */
    public function getTransactionalMailingName()
    {
        return $this->_mailingName;
    }

    /**
     * Returns API processor object
     *
     * @return Tgc_StrongMail_Model_Api_Mailer_ProcessorInterface
     */
    public function getApiProcessor()
    {
        return $this->_apiProcessor;
    }

    /**
     * Sends transactions emails
     *
     * @throws UnexpectedValueException
     * @throws DomainException
     */
    public function send()
    {
        if (!$this->getTransactionalMailingName()) {
            throw new DomainException(
                'The e-mail cannot be sent: transactional mailing name is empty'
            );
        }
        if (!$this->getEmailInfo()) {
            throw new DomainException(
                'The e-mail cannot be sent: recipient was not found'
            );
        }
        $mailingId = $this->getApiProcessor()
            ->getMailingId($this->getTransactionalMailingName());
        if (!$mailingId) {
            throw new UnexpectedValueException(
                "The e-mail cannot be sent: '{$this->getTransactionalMailingName()}' mailing was not found"
            );
        }

        foreach ($this->getEmailInfo() as $emailInfo) {
            /* @var $emailInfo Mage_Core_Model_Email_Info */
            $this->getApiProcessor()
                ->txnSend(
                    $emailInfo,
                    $mailingId,
                    $this->getAdditionalParams()
                );
        }
    }
}
