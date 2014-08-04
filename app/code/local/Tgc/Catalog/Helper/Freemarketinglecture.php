<?php
/**
 * Catalog helper
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Catalog
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_Catalog_Helper_Freemarketinglecture extends Mage_Core_Helper_Abstract
{
    protected $_adapterFreemarketingLecture;

    protected $_freeMarketingLectureAttributeCodes = array('marketing_free_lecture_from','marketing_free_lecture_to');

    protected $_customerSession;

    protected $_coreSession;

    protected $_freeLectureFormToDisplay;

    protected $_freeMarketingLectureSubmittedEmail;

    protected $_flagNewCustomerAlreadyExists = false;

    protected $_newCustomerAlreadyExistsErrorText;

    const FORM_NOTSIGNEDIN_NEW_CUSTOMER = 'formnewcustomer';

    const FORM_NOTSIGNEDIN_EXISTING_CUSTOMER = 'existincustomer';

    const FORM_SIGNEDIN_SUBSCRIBED = 'signedinsubscribed';

    const FORM_SIGNEDIN_UNSUBSCRIBED = 'signedinunsubscribed';

    const FORM_FORGOTPASSWORD = 'forgotpassword';

    const FORM_SUCCESS_NEWACCOUNT = 'formsuccessnewaccount';

    public function __construct()
    {
        $this->_adapterFreemarketingLecture = Mage::getSingleton('core/resource')->getConnection('write');
        $this->_customerSession = Mage::getSingleton('customer/session');
        $this->_coreSession =  Mage::getSingleton('core/session');
    }

    public function retrieveFreeMarketingLectureId()
    {
        $table ='catalog_product_entity_datetime';
        $attributeIds = $this->getFreeMarketingLectureAttributeIds();
        $freeMarketingLectureId = null;

        if($table && $attributeIds) {
            $selectAttributeValue = $this->_adapterFreemarketingLecture->select()
                ->from($table, array('entity_id'))
                ->where('attribute_id IN (?)', $attributeIds)
                ->where('value IS NOT NULL');

            $freeMarketingLectureCandidates = array_unique($this->_adapterFreemarketingLecture->fetchCol($selectAttributeValue));
            if($freeMarketingLectureCandidates > 0) { //this should never be greater than one, because only 1 free marketing lecture is allowed.
                if(isset($freeMarketingLectureCandidates[0]) && $freeMarketingLectureCandidates[0]) {
                    $freeMarketingLectureId = $freeMarketingLectureCandidates[0];
                }
            }
        }

        return $freeMarketingLectureId;
    }

    public function getFreeMarketingLectureAttributeIds()
    {
        $selectMarketingLectureAttributeIds = $this->_adapterFreemarketingLecture->select()
            ->from('eav_attribute', array('attribute_id'))
            ->where('attribute_code IN (?)', $this->_freeMarketingLectureAttributeCodes);
        $marketingLectureAttributeIds = $this->_adapterFreemarketingLecture->fetchCol($selectMarketingLectureAttributeIds);
        return $marketingLectureAttributeIds;
    }

    public function giveProspectDigitalLibraryAccessRights($customerId = '', $webUserId)
    {
        if($customerId && $webUserId) {
            $accessRightsTypes = array(0 ,1); //0 and 1 represent audio and video media formats.
            $freeMarketingLectureId = $this->retrieveFreeMarketingLectureId();

            $data = array(
                'web_user_id' => $webUserId,
                'date_purchased' => date('Y-m-d H:i:s'),
                'is_downloadable' => 0,
                'digital_transcript_purchased' => 0,
            );

            if($freeMarketingLectureId) {
                $data['course_id'] = $freeMarketingLectureId;
            }

            foreach($accessRightsTypes as $accessRightType) {
                $data['format'] = $accessRightType;
                $accessRightsModel = Mage::getModel('tgc_dl/accessRights');
                $accessRightsModel->setData($data);
                $accessRightsModel->save();
            }
        }
    }

    public function signupCustomerFreelecture(Mage_Customer_Model_Customer $customer, $adCode = null, $userAgent = null)
    {
        if(!$customer->getWebProspectId()) {
            //web_prospect_id is only set first time a user signs up for free lectures.
            $newWebProspectId = $this->getMaxWebProspectId();
            $customer->setWebProspectId($newWebProspectId);
        }

        $customer->setFreeLectSubscribeStatus(2); //2 is unconfirmed.

        //a customer can subscribe, and then unsbuscribe, and then subscribe again.
        //these variables need to be reset every time a customer subscribes.
        if (!$customer->getFreeLectureProspect()) {
            $customer->setEmailVerified(false);
            $customer->setDateVerified(null);
            $customer->setIsFreelectProspectConfirmed(false);
            $customer->setFreeLectEmailFailed(false);
            $customer->setFreeLectDateUnsubscribed(null);
            // this prevents a user from being added to digital library access rights table twice.
        }

        if(!$adCode) {
            $adCode = Mage::getModel('core/cookie')->get(Tgc_CookieNinja_Model_Ninja::COOKIE_AD_CODE);
        }

        $customer->setFreeLecturesInitialSource($adCode);

        if(!$userAgent) {
            $userAgent = $_SERVER['HTTP_USER_AGENT'];
        }

        $customer->setFreeLectInitialUserAgent($userAgent);

        if(!$customer->getFreeLecturesDateCollected()) { //if statement makes it so value is only set first time custoemr signs up free lectures.
            $customer->setFreeLecturesDateCollected(now());
        }

        if(!$customer->getConfirmationGuid()) {
            $newsletterSubscriberObject = Mage::getModel('newsletter/subscriber');
            $guid = $newsletterSubscriberObject->randomSequence();
            $customer->setConfirmationGuid($guid);
        }

        $customer->setFreeLectLastDateCollected(now());

        try {
            Mage::dispatchEvent('free_lectures_signup', array('customer' => $customer));
        } catch (Exception $e) {
            // Because it's not main functinality of the method we suppress errors from the listeners
            Mage::logException($e);
        }

        return true;
    }

    public function logInTheCustomer(Mage_Customer_Model_Customer $customer)
    {
        $session = Mage::getSingleton('customer/session');
        $session->setCustomerAsLoggedIn($customer);
        $session->renewSession();
    }

    public function addFreelectureGlobalError($message)
    {
        //$this->_customerSession->addError($message); //error messages will show up in all needed places if added to core session.  THat is what magento looks at when pulling messages from session.
        $this->_coreSession->addError($message);
    }

    public function addFreelectureGlobalSuccess($message)
    {
        //$this->_customerSession->addSuccess($message); //error messages will show up in all needed places if added to core session.  THat is what magento looks at when pulling messages from session.
        $this->_coreSession->addSuccess($message);
    }

    public function getMaxWebProspectId()
    {
        $maxId = false;
        $maxWebProspectIdCollection = Mage::getModel('customer/customer')->getCollection()
            ->addExpressionAttributeToSelect('max_web_prospect_id','MAX(at_web_prospect_id.value)',array('web_prospect_id'))
            ->load();

        $maxWebProspectIdCollectionItem = $maxWebProspectIdCollection->getFirstItem();
        $maxId = $maxWebProspectIdCollectionItem->getMaxWebProspectId();

        if($maxId && $maxId >= 10000) {
            $maxId += 1;
        } else {
            $maxId = 10000;
        }

        return $maxId;
    }

    public function updateNewsletterSubscriptionStatus($email = '', $updateType = 'subscribe')
    {
        $newsletterSubscriberObject = Mage::getModel('newsletter/subscriber');
        if($email) {
            $newsletterSubscriberObject->loadByEmail($email);
            if($newsletterSubscriberObject->getId()) { //ensures a record was successfully loaded.
                if($updateType == 'subscribe') {
                    $newsletterSubscriberObject->setSubscriberStatus(1)->setNeedsExport(1);
                } elseif($updateType == 'unsubscribe') {
                    $newsletterSubscriberObject->setSubscriberStatus(3)->setNeedsExport(0);
                }
                $newsletterSubscriberObject->save();
            }
        }
    }

    public function getFreelectureFormToDisplay()
    {
        if(is_null($this->_freeLectureFormToDisplay)) {
            $freeLectureFormToDisplay = $this->_coreSession->getFreeMarketingFormToDisplay();
            $this->_coreSession->setFreeMarketingFormToDisplay(null); //session variable should expire after each page load.

            $this->getFreelectureSubmittedEmail(); //this sets the freemarketing lecture submitted email.
            $this->_getSessionCustomer()->setFreelectureSubmittedEmail(null); //session variable should expire after each page load.

            $this->getFlagNewCustomerAlreadyExists();
            $this->_getSessionCustomer()->setFlagNewCustomerAlreadyExists(null);

            if(!$freeLectureFormToDisplay) {
                $customerHelper = Mage::helper('customer');
                $isLoggedIn = $customerHelper->isLoggedIn();

                if($isLoggedIn) {
                    $customerId = Mage::getSingleton('customer/session')->getCustomerId();
                    if($customerId) {
                        $customer = Mage::getModel('customer/customer')->load($customerId);
                        if($customer->getId()) {
                            if(in_array($customer->getData('free_lect_subscribe_status'), array(1,2)) ) {
                                $freeLectureFormToDisplay = self::FORM_SIGNEDIN_SUBSCRIBED;
                            } else {
                                $freeLectureFormToDisplay = self::FORM_SIGNEDIN_UNSUBSCRIBED;
                            }
                        }
                    }
                }
            }

            if(!$freeLectureFormToDisplay) {
                $freeLectureFormToDisplay = self::FORM_NOTSIGNEDIN_NEW_CUSTOMER;
            }

            $this->_freeLectureFormToDisplay = $freeLectureFormToDisplay;
        }

        return $this->_freeLectureFormToDisplay;
    }

    public function getFreelectureSubmittedEmail()
    {
        if($freeMarketingLectureSubmittedEmail = $this->_getSessionCustomer()->getFreelectureSubmittedEmail()) {
            $this->_freeMarketingLectureSubmittedEmail = $freeMarketingLectureSubmittedEmail;
        }

        return $this->_freeMarketingLectureSubmittedEmail;
    }

    public function setFreelectureSubmittedEmail($email)
    {
        $this->_getSessionCustomer()->setFreelectureSubmittedEmail($email);
        $this->_freeMarketingLectureSubmittedEmail = $email;
    }

    public function setFreeMarketingFormToDisplay($formToDisplay = '')
    {
        $this->_coreSession->setFreeMarketingFormToDisplay($formToDisplay);
    }

    public function setFlagNewCustomerAlreadyExists($value)
    {
        $this->_getSessionCustomer()->setFlagNewCustomerAlreadyExists($value);
        $this->_flagNewCustomerAlreadyExists = $value;
    }

    public function getFlagNewCustomerAlreadyExists()
    {
        if($flagNewCustomerAlreadyExists = $this->_getSessionCustomer()->getFlagNewCustomerAlreadyExists()) {
            $this->_flagNewCustomerAlreadyExists = $flagNewCustomerAlreadyExists;
        }

        return $this->_flagNewCustomerAlreadyExists;
    }

    protected function _getSessionCustomer()
    {
        return Mage::getSingleton('customer/session');
    }

    protected function _getSession()
    {
        return Mage::getSingleton('core/session');
    }
}
