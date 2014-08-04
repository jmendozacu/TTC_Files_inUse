<?php
/**
 * Abstract class for all transactional emails
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Tgc_StrongMail
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
abstract class Tgc_StrongMail_Model_Email_Abstract
{
    /**
     * Mailer factory
     *
     * @var Tgc_StrongMail_Model_Api_Mailer_Factory_Interface
     */
    private $_mailerFactory;

    /**
     * Constructor
     *
     * @param array $args 'mailer_factory' argument is optional
     * @throws InvalidArgumentException
     */
    public function __construct($args = array())
    {
        if (isset($args['mailer_factory'])) {
            if (!($args['mailer_factory'] instanceof Tgc_StrongMail_Model_Api_Mailer_Factory_Interface)) {
                throw new InvalidArgumentException(
                    "'mailer_factory' must be an instance of Tgc_StrongMail_Model_Api_Mailer_Factory_Interface"
                );
            }
            $this->_mailerFactory = $args['mailer_factory'];
        } else {
            $this->_mailerFactory = Mage::getModel('tgc_strongMail/api_mailer_factory_default');
        }
    }

    /**
     * Returns Mailer factory
     *
     * @return Tgc_StrongMail_Model_Api_Mailer_Factory_Interface
     */
    protected function _getMailerFactory()
    {
        return $this->_mailerFactory;
    }

    /**
     * Sends transactional email
     *
     * @return Tgc_StrongMail_Model_Email_Abstract
     * @throws Exception on error
     */
    abstract public function send();

    /**
     * Returns storeId.
     *
     * @return int
     */
    abstract public function getStoreId();

    /**
     * Prepares email information for mailer object
     *
     * @param Tgc_StrongMail_Model_Api_Mailer $mailer
     * @param string $receiverEmail
     * @param string $receiverName
     */
    protected function _prepareEmailInfo($mailer, $receiverEmail, $receiverName)
    {
        $emailInfo = Mage::getModel('core/email_info');
        $emailInfo->addTo($receiverEmail, $receiverName);
        $mailer->addEmailInfo($emailInfo);
    }

    /**
     * Creates Mailer object, using factory.
     *
     * @return Tgc_StrongMail_Model_Api_Mailer
     */
    protected function _createMailer()
    {
        return $this->_getMailerFactory()->create($this->getStoreId());
    }
}
