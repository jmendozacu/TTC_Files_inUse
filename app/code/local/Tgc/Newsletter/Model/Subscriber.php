<?php
/**
 * Newsletter subscriber model
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Newsletter
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_Newsletter_Model_Subscriber extends Mage_Newsletter_Model_Subscriber
{
    const XML_PATH_SEND_SUCCESS = 'newsletter/subscription/send_success';
    const XML_PATH_SEND_UN = 'newsletter/subscription/send_un';

    /**
     * Sends out confirmation success email
     *
     * @return Tgc_Newsletter_Model_Subscriber
     */
    public function sendConfirmationSuccessEmail()
    {
        if (!Mage::getStoreConfig(self::XML_PATH_SEND_SUCCESS)) {
            return $this;
        }
        return parent::sendConfirmationSuccessEmail();
    }

    /**
     * Sends out unsubsciption email
     *
     * @return Tgc_Newsletter_Model_Subscriber
     */
    public function sendUnsubscriptionEmail()
    {
        if (!Mage::getStoreConfig(self::XML_PATH_SEND_UN)) {
            return $this;
        }
        return parent::sendUnsubscriptionEmail();
    }
}
