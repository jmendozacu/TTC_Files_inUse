<?php
/**
 * API processor interface
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Tgc_StrongMail
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
interface Tgc_StrongMail_Model_Api_Mailer_ProcessorInterface
{
    /**
     * Returns Transactional mailing ID by name
     *
     * @param string $mailingName
     * @return int|false
     */
    public function getMailingId($mailingName);

    /**
     * Sends Transactional e-mail
     *
     * @param Mage_Core_Model_Email_Info $emailInfo
     * @param int $mailingId
     * @param array $additionalParams
     */
    public function txnSend(Mage_Core_Model_Email_Info $emailInfo, $mailingId, $additionalParams = array());
}
