<?php
/**
 * Class for 'txnSend()' API operation.
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Tgc_StrongMail
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_StrongMail_Model_Api_Mailer_Operation_TxnSend extends Tgc_StrongMail_Model_Api_Mailer_Operation_Abstract
{
    /**
     * Sends transactional email.
     *
     * @param Mage_Core_Model_Email_Info $emailInfo
     * @param int $mailingId
     * @param array $additionalParams
     * @return bool
     * @throws UnexpectedValueException
     */
    public function txnSend(Mage_Core_Model_Email_Info $emailInfo, $mailingId, $additionalParams = array())
    {
        $handle = $this->_getMailingHandle($mailingId);

        if (!$handle) {
            throw new UnexpectedValueException("Mail handler was not found for the mailing id '{$mailingId}'");
        }

        // Create request and set mailingId
        $request = new Tgc_StrongMail_TxnSendRequest();
        $request->handle = $handle;

        // Create record
        $record = new Tgc_StrongMail_SendRecord();
        $record->field = array();

        $mailingIdPair = new Tgc_StrongMail_NameValuePair();
        $mailingIdPair->name = "ID";
        $mailingIdPair->value = $mailingId;
        $record->field[] = $mailingIdPair;

        $emailPair = new Tgc_StrongMail_NameValuePair();
        $emails = $emailInfo->getToEmails();
        $emailPair->name = "EMAIL_ADDRESS";
        $emailPair->value = $emails[0];
        $record->field[] = $emailPair;

        $namePair = new Tgc_StrongMail_NameValuePair();
        $names = $emailInfo->getToNames();
        $namePair->name = "NAME";
        $namePair->value = $names[0];
        $record->field[] = $namePair;

        foreach ($additionalParams as $paramName => $paramValue) {
            $pair = new Tgc_StrongMail_NameValuePair();
            $pair->name = $paramName;
            $pair->value = $paramValue;
            $record->field[] = $pair;
        }

        // Add record and make call
        $request->sendRecord = array();
        $request->sendRecord[] = $record;
        $result = $this->getClient()->txnSend($request);

        if (!empty($result) && isset($result->success)) {
            return (bool)$result->success;
        }

        return false;
    }

    /**
     * Creates right SOAPVar object for Name-Value pair data
     *
     * @param string $name
     * @param string $value
     * @return SoapVar
     */
    protected function _getSoapVarForNameValuePair($name, $value)
    {
        $object = new stdClass();
        $object->name = $name;
        $object->value = (string)$value;

        return new SoapVar($object, SOAP_ENC_OBJECT, 'NameValuePair', null, 'field');
    }

    /**
     * Gets Mailing Handle from the webservice for transactional mailing ID
     *
     * @param int $mailingId
     * @return bool
     */
    protected function _getMailingHandle($mailingId)
    {
        $request = new Tgc_StrongMail_GetTxnMailingHandleRequest();
        $objectId = new Tgc_StrongMail_TransactionalMailingId();
        $objectId->id = $mailingId;
        $request->mailingId = new SoapVar(
            $objectId, SOAP_ENC_OBJECT, 'TransactionalMailingId', $this->_operationSchema, NULL, NULL
        );

        $result = $this->getClient()->getTxnMailingHandle($request);

        if (!empty($result) && isset($result->handle)) {
            return $result->handle;
        }

        return false;
    }
}
