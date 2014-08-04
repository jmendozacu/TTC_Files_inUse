<?php
/**
 * API methods call processor
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Tgc_StrongMail
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_StrongMail_Model_Api_Mailer_Processor implements Tgc_StrongMail_Model_Api_Mailer_ProcessorInterface
{
    /**
     * API Config object
     *
     * @var Tgc_StrongMail_Model_Api_Mailer_Config
     */
    private $_config;

    /**
     * SOAP Client
     *
     * @var SoapClient
     */
    private $_soapClient;

    /**
     * Cached operation objects
     *
     * @var array
     */
    protected $_operations = array();

    /**
     * Constructor
     *
     * @param array $args 'config' is required
     * @throws InvalidArgumentException
     */
    public function __construct($args)
    {
        if (!isset($args['config'])) {
            throw new InvalidArgumentException("'config' argument is required");
        }

        Mage::helper('tgc_strongMail')->includeLibraryClasses();

        $this->_config = $args['config'];
        $this->_soapClient = new MailingService(
            $this->_config->getWsdl(),
            array('trace' => 1, 'exceptions' => 1, 'features' => SOAP_SINGLE_ELEMENT_ARRAYS)
        );
        $this->_prepareClient();
    }

    /**
     * Prepares SOAP client object.
     */
    protected function _prepareClient()
    {
        $soapHeader = new Tgc_StrongMail_SecurityHeader();
        $soapHeader->setSecurityHeader(
            $this->getClient(),
            $this->_getConfig()->getUsername(),
            $this->_getConfig()->getPassword(),
            $this->_getConfig()->getCompany()
        );
    }

    /**
     * Factory method for creating operation objects.
     *
     * @param string $code
     * @return Tgc_StrongMail_Model_Api_Mailer_Operation_Abstract
     */
    protected function _getOperation($code)
    {
        if (!isset($this->_operations[$code])) {
            $operation = Mage::getModel(
                'tgc_strongMail/api_mailer_operation_' . $code,
                array('client' => $this->getClient())
            );
            $this->_operations[$code] = $operation;
        }
        return $this->_operations[$code];
    }

    /**
     * SOAP client getter
     *
     * @return SoapClient
     */
    public function getClient()
    {
        return $this->_soapClient;
    }

    /**
     * API Config getter
     *
     * @return Tgc_StrongMail_Model_Api_Mailer_Config
     */
    protected function _getConfig()
    {
        return $this->_config;
    }

    /**
     * Returns Transactional mailing ID by name
     *
     * @param string $mailingName
     * @return int|false
     */
    public function getMailingId($mailingName)
    {
        return $this->_getOperation('list')
            ->getMailingId($mailingName);
    }

    /**
     * Sends Transactional e-mail
     *
     * @param Mage_Core_Model_Email_Info $emailInfo
     * @param int $mailingId
     * @param array $additionalParams
     */
    public function txnSend(Mage_Core_Model_Email_Info $emailInfo, $mailingId, $additionalParams = array())
    {
        return $this->_getOperation('txnSend')
            ->txnSend($emailInfo, $mailingId, $additionalParams);
    }
}
