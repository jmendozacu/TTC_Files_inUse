<?php
/**
 * Mailing API operation abstract class
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Tgc_StrongMail
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
abstract class Tgc_StrongMail_Model_Api_Mailer_Operation_Abstract
{
    protected $_operationSchema = 'http://www.strongmail.com/services/2009/03/02/schema';

    /**
     * Soap Client object
     *
     * @var SoapClient
     */
    private $_client;

    /**
     * Constructor
     *
     * @param array $args 'client' option is required
     * @throws InvalidArgumentException
     */
    public function __construct($args)
    {
        if (empty($args) || empty($args['client'])) {
            throw new InvalidArgumentException("'client' argument is required");
        }

        $this->_client = $args['client'];
    }

    /**
     * Sets SOAP client instance
     *
     * @return SoapClient
     */
    public function getClient()
    {
        return $this->_client;
    }

    /**
     * Returns SOAP client instance
     *
     * @param SoapClient $client
     * @return Tgc_StrongMail_Model_Api_Mailer_Operation_Abstract
     */
    public function setClient($client)
    {
        $this->_client = $client;
        return $this;
    }
}
