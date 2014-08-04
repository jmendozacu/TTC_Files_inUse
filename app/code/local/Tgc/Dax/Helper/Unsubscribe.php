<?php
/**
 * Default helper
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Dax
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Dax_Helper_Unsubscribe extends Tgc_Lectures_Helper_Subscription_Abstract
{
    protected $_customerSubscriptionObject;

    /**
     * Retrieves email address that user has requested needs to be unsubscribed.
     * @return mixed
     */
    public function getEmailAddressToUnsubscribe()
    {
        return Mage::app()->getRequest()->getParam('em');
    }

    /**
     * Checks to see if the unsubscribe request is valid.
     * @param string $email
     * @param string $key
     * @return bool
     */
    public function isUnsubscribeRequestValid($email = 'request', $key = '')
    {
        $isUnsubscribeRequestValid = false;

        //Reason why we call getCustomerToUnsubscribe is because if a customer exists, the email is valid, and the email key is valid, it returns true.
        //this are exact same conditions that must be true for a request to be valid.
        $customerToUnsubscribe = $this->getCustomerToUnsubscribe($email, $key);
        if($customerToUnsubscribe) { //request must correspond to a customer who exists.
            if($this->getCustomerSubscription()) { //customer must exist in subscription table.
                $isUnsubscribeRequestValid = true;
            }
        }

        return $isUnsubscribeRequestValid;
    }

    /**
     * Checks to see if customer has unsubscribed.  If a user refreshes the page, function still understands
     * that user has unsubscribed even though there are no parameters in the url.  It can determine this using a session variable.
     * @return bool
     */
    public function hasUserBeenUnsubscrited()
    {
        $hasUserBeenUnsubscribed = false;
        $customerToUnsubscribe = $this->getCustomerToUnsubscribe();
        if($customerToUnsubscribe) {
            $subscription = $this->getCustomerSubscription();
            if($subscription) {
                if($subscription->getData('subscriber_status') == 3) {
                    $hasUserBeenUnsubscribed = true;
                }
            }
        }

        if($this->getEmailAddressToUnsubscribe()) {
            $this->resetUserAsUnsubscrited();
        }

        if(Mage::getModel('core/session')->getUserHasBeenUnsubscrited()) {
            $hasUserBeenUnsubscribed = true;
        }

        return $hasUserBeenUnsubscribed;
    }

    /**
     * Checks to see if user has been subscribed to free lectures.
     * @return bool
     */
    public function isSubscribed()
    {
        $isSubscribedFreelectures = false;

        $customerToUnsubscribe = $this->getCustomerToUnsubscribe();

        if($customerToUnsubscribe->getId()) {
            $subscription = $this->getCustomerSubscription();
            if($subscription) {
                if($subscription->getData('subscriber_status') == 1) { //1 represents Subscribed
                    $isSubscribedFreelectures = true;
                }
            }
        }

        return $isSubscribedFreelectures;
    }


    public function getCustomerSubscription($email = '')
    {
        if(is_null($this->_customerSubscriptionObject)) {
            $newsletterSubscriberObject = Mage::getModel('newsletter/subscriber');

            if(!$email) {
               $email = $this->getEmailToUnsubcribe();
            }

            if($email) {
                $newsletterSubscriberObject->loadByEmail($email);
                if($newsletterSubscriberObject->getId()) { //ensures a record was successfully loaded.
                    $this->_customerSubscriptionObject = $newsletterSubscriberObject;
                }
            }
        }

        return $this->_customerSubscriptionObject;
    }

    /**
     * Checks to see if a customer should be eligible to unsubscribe.  Only customers who are subsribed or unconfirmed are eligible to unsubscribe.
     * @return bool
     */
    public function isCustomerEligibleToUnsubscribe($email = '')
    {
        $isCustomerEligibleToUnsubscribe = false;

        $customerToUnsubscribe = $this->getCustomerToUnsubscribe($email);

        if($customerToUnsubscribe) {
            $subscription = $this->getCustomerSubscription($email);
            if($subscription) {
                if($subscription->getData('subscriber_status') != 3) {
                    $isCustomerEligibleToUnsubscribe = true;
                }
            }

        }

        return $isCustomerEligibleToUnsubscribe;
    }

    public function getUnsubscribePostUrl()
    {
        return Mage::getUrl('tgc/special/unsubscribeFromDax');
    }

    public function getWebKey()
    {
        return Mage::app()->getRequest()->getParam('cm_mmca1');
    }

    public function getAdCode()
    {
        return Mage::app()->getRequest()->getParam('ai');;
    }

    public function getEmailCampaign()
    {
        return Mage::app()->getRequest()->getParam('cm_mmc');
    }
}