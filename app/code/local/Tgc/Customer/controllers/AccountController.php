<?php
require_once (Mage::getModuleDir('controllers', 'Mage_Customer') . DS . 'AccountController.php');
/**
 * Customer login
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Customer
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_Customer_AccountController extends Mage_Customer_AccountController
{
    protected $_freeLectureUrlRedirectToSelf = 'freelectures';

    protected $_freeLectureUrlRedirectOnExistingCustomerSuccess = 'digital-library/account'; //redirects to the home page.

    protected $_freeLectureUrlRedirectDigitalLibrary = 'digital-library/account';

    protected $_redirectDigitalLibraryPath = 'digital-library/account';

    const FREE_LECTURES_SUCCESS_MESSAGE = 'Thank you. You are now signed up for free lectures.';

    const FREE_LECTURES_EXISTINGUSER_SIGNUP = 'Thank you! You have been enrolled with Free Lectures';

    const MDL_REFERRING_ELEMENT = 'mydigitallibrary';

    protected function _construct() {
        Mage::getSingleton('core/session', array('name' => 'frontend'));
        parent::_construct();
    }

    /**
     * Action predispatch
     *
     * Check customer authentication for some actions
     */
    public function preDispatch()
    {
        if (!$this->isAjax()) {
            // a brute-force protection here would be nice

            if (!$this->getRequest()->isDispatched()) {
                return;
            }

            $action = $this->getRequest()->getActionName();
            $openActions = array(
                'create',
                'login',
                'headerLogin',
                'logout',
                'logoutsuccess',
                'forgotpassword',
                'forgotpasswordpost',
                'resetpassword',
                'resetpasswordpost',
                'confirm',
                'confirmation',
                'freelecturePost',
                'freelectureForgotpassword',
            );
            $pattern = '/^(' . implode('|', $openActions) . ')/i';

            if (!preg_match($pattern, $action)) {
                if (!$this->_getSession()->authenticate($this)) {
                    $this->setFlag('', 'no-dispatch', true);
                }
            } else {
                $this->_getSession()->setNoReferer(true);
            }

            Mage_Core_Controller_Front_Action::preDispatch();
        }
    }




    /**
     * Action postdispatch
     *
     * Remove No-referer flag from customer session after each action
     */
    public function postDispatch()
    {
        if (!$this->isAjax()) {
            parent::postDispatch();
        }
    }

    /**
     * Check is Request from AJAX
     *
     * @return boolean
     */
    public function isAjax()
    {
        if ($this->isXmlHttpRequest()) {
            return true;
        }

        return false;
    }

    /**
     * Is the request a Javascript XMLHttpRequest?
     *
     * Should work with Prototype/Script.aculo.us, possibly others.
     *
     * @return boolean
     */
    public function isXmlHttpRequest()
    {
        return ($this->getRequest()->getHeader('X_REQUESTED_WITH') == 'XMLHttpRequest');
    }

    /**
     * Customer login form page
     */
    public function loginAction()
    {
        if (!$this->isAjax()) {
            parent::loginAction();
        }
    }

    /**
     * Login post action
     */
    public function loginPostAction()
    {
        if (!$this->isAjax()) {
            return $this->nonAjaxLoginPostAction();
        }

        $session = $this->_getSession();

        if ($this->getRequest()->isPost()) {
            $login = $this->getRequest()->getPost('login');
            if (!empty($login['username']) && !empty($login['password'])) {
                try {
                    if ($this->_isAccountLocked($login['username'])) {
                        throw new Mage_Core_Exception(
                            $this->_getHelper('customer')->__('This account is temporarily locked')
                        );
                    }
                    Mage::app()->loadAreaPart(Mage_Core_Model_App_Area::AREA_FRONTEND, Mage_Core_Model_App_Area::PART_EVENTS);
                    $this->_setKeepLoggedIntoSession();
                    $session->login($login['username'], $login['password']);
                    Mage::getSingleton('core/session')->unsVisitorData();
                } catch (Mage_Core_Exception $e) {
                    switch ($e->getCode()) {
                        case Mage_Customer_Model_Customer::EXCEPTION_EMAIL_NOT_CONFIRMED:
                            $value = $this->_getHelper('customer')->getEmailConfirmationUrl($login['username']);
                            $message = $this->_getHelper('customer')->__('This account is not confirmed. <a href="%s">Click here</a> to resend confirmation email.', $value);
                            break;
                        case Mage_Customer_Model_Customer::EXCEPTION_INVALID_EMAIL_OR_PASSWORD:
                            $this->_loginFailure($login['username']);
                            $message = $e->getMessage();
                            if ($warning = $this->_getAccountLockWarning($login['username'])) {
                                $message .= ' ' . $warning;
                            }
                            break;
                        default:
                            $message = $e->getMessage();
                    }
                    $response = array('status' => 'failure', 'message' => $message);
                    $this->_sendAjaxResponse($response);
                    return;
                } catch (Exception $e) {
                    $response = array('status' => 'failure', 'message' => 'An unspecified error occurred');
                    $this->_sendAjaxResponse($response);
                    return;
                }
            } else {
                $response = array('status' => 'failure', 'message' => $this->__('Login and password are required.'));
                $this->_sendAjaxResponse($response);
                return;
            }
        }

        $response = array('status' => 'success', 'html' => Mage::helper('tgc_customer')->getLoggedInHeaderHtml(), 'refresh' => '1');
        $this->_sendAjaxResponse($response);
    }

    /**
     * Customer logout action
     */
    public function logoutAction()
    {
        $session = $this->_getSession();
        $session->logout()
            ->renewSession()
            ->setBeforeAuthUrl($this->_getRefererUrl());

        if ($session->needsLogoutRedirect()) {
            $this->_redirect('*/*/logoutSuccess');
        } else {
            $this->getResponse()->setRedirect($this->_getRefererUrl());
            return $this;
        }
    }

    /**
     * Customer register form page
     */
    public function createAction()
    {
        if (!$this->isAjax()) {
            parent::createAction();
        }
    }

    public function freelecturePostAction()
    {
        $customerHelper = Mage::helper('customer');
        $isLoggedIn = $customerHelper->isLoggedIn();
        $session = $this->_getSession();
        $isFormValid = true;
        $customRedirectPath = null;
        $exceptionErrorExists = false;
        $customRedirectErrorPath = null;
        $formData = $this->getRequest()->getPost('freelectures');
        $redirectFormToDisplay = null;
        $redirectFormToDisplayCurrentRequest = isset($formData['current_freelectures_form']) ? $formData['current_freelectures_form'] : null;
        $isNewCustomer = null;
        $customerEmailAddress = isset($formData['email_address']) ? $formData['email_address'] : null;

        if(!$isLoggedIn) { //customers who already exist don't need to fill in these fields.
            $errors = array();
            $customer = Mage::getModel('customer/customer');
            $validationModel = Mage::getModel('lectures/freelectures_validate');
            $alreadyLectureProspect = false;

            //if a customer has clicked 'Existing Customer' button, the 'Re-enter password field disapears', the lien below can be used to remove it from required fields validation.
            $isExistingCustomer = isset($formData['should_log_customer_in']) && $formData['should_log_customer_in'] ? true : false; //this is a hidden form field, if user clicks existing customer button it is set to true, ELSE it is false.
            $isNewCustomer = !$isExistingCustomer;
            $requiredFieldsValidationExceptions = $isNewCustomer ? array('password_confirm') : array();

            if($formData) {
                $validationModel->validateRequiredFields($formData, $errors, $isFormValid, $requiredFieldsValidationExceptions);
                $validationModel->validateEmail($formData, $errors, $isFormValid, $isExistingCustomer);
                $validationModel->validatePassword($formData, $errors, $isFormValid, $isExistingCustomer);
                //not doing php validation for email and password, javaScript validation is taking care of that.
            } else {
                $errors[] = 'All fields on this form were blank.  Please fill in the missing items.';
                $isFormValid = false;
            }

            //prevents a new user from logging in as an existing user.  It redirects them to already member form.
            if($isNewCustomer && $isFormValid && $customerEmailAddress) {
                $doesEmailAlreadyExist = Mage::getModel('customer/customer')->getCollection()
                    ->addAttributeToFilter('email', $customerEmailAddress)
                    ->count();

                if($doesEmailAlreadyExist) {
                    $isFormValid = false;
                    $this->_freemarketinglectureHelper()->setFlagNewCustomerAlreadyExists(true);
                    $redirectFormToDisplay = Tgc_Catalog_Helper_Freemarketinglecture::FORM_NOTSIGNEDIN_EXISTING_CUSTOMER;
                }
            }

            if($isExistingCustomer && $isFormValid) {
                //if customer already exists, logInCustomerFreelecturesForm logs them in and makes them a prospect (if they haven't been given rights digital library they are given them.)
                $this->logInCustomerFreelecturesForm($formData);
                $existingCustomerId = $session->getCustomerId();
                if($existingCustomerId) {
                    $customer->load($existingCustomerId);
                    if(!$customer->getId()) {
                        $isFormValid = false; //this occurs if existing customer not loaded successfully
                    } elseif($this->_helperLectures()->isCustomerFreeLectureProspect($customer)) { //if they are already signed up, display a message telling them already signed up.
                        $alreadyLectureProspect = true;
                    }
                } else {
                    $errors[] = 'The customer could not be logged in because the username and password did not match an existing user.';
                    $isFormValid = false; //this means an error occured when logInCustomerFreelecturesForm tried to log the customer in.
                }
            }

            if($isFormValid) {
                if($isNewCustomer) { //this conditions means that they are a new customer, when new customers created fields below need to be set.
                    $customer->setEmail($formData['email_address']);
                    $customer->setPassword($formData['password']);
                    $customer->setIsAccountAtSignup(false);
                    $customer->setConfirmation($formData['password_confirm']);
                    $customer->save(); //new customer needs to be saved, because when save action occurs, web_user_id is generated and saved function signupCustomerFreelecture needs that data.
                } else {
                    $customer->setIsAccountAtSignup(true);
                }

                try {
                    //Line below, sets customer as a prospect and, if cust not already have digital library access rights, it gives it to them.
                    $this->_freemarketinglectureHelper()->signupCustomerFreelecture($customer);
                    $currentCustomerId = $customer->getId();
                    $customer->save();
                    if($isNewCustomer) {
                        $this->_freemarketinglectureHelper()->logInTheCustomer($customer);
                    }
                } catch (Mage_Core_Exception $e) {
                    $this->_freemarketinglectureHelper()->addFreelectureGlobalError($e->getMessage());
                    $isFormValid = false;
                    $exceptionErrorExists = true;
                } catch (Exception $e) {
                    if(strpos($e->getMessage(),'e-mail') || strpos($e->getMessage(),'email')) {
                        $customerFailed = Mage::getModel('customer/customer')->load($currentCustomerId);
                        if($customerFailed->getId()) {
                            $customerFailed->setFreeLectEmailFailed(true);
                            $customerFailed->save();
                        }
                        $emailFailedSendMessage = 'Your account has been created, but no free lectures can be viewed just quite yet.<br /> This is because the email asking you to confirm your free lecture subscription failed to send. <br />The Great Courses has record of this and will attempt to re-send the confirmation email shortly.';
                        $this->_freemarketinglectureHelper()->addFreelectureGlobalError($emailFailedSendMessage);
                        Mage::logException($e);
                    } else {
                        $this->_freemarketinglectureHelper()->addFreelectureGlobalError('Cannot save the customer.');
                    }
                    $isFormValid = false;
                    $exceptionErrorExists = true;
                }
            }

            $formattedErrorMessages = $validationModel->formatErrors($errors);
            if($formattedErrorMessages) {
                $isFormValid = false; //if there are any error messages, form is invalid.
                $this->_freemarketinglectureHelper()->addFreelectureGlobalError(Mage::helper('lectures')->__($formattedErrorMessages));
            }

            if($isFormValid && !$alreadyLectureProspect) {
                //success message removed because a phtml file displays the success text.
            } elseif($isFormValid && $alreadyLectureProspect) { //evaluates to true if user has a web_prospect_id
                if(!$customer->getFreeLectureProspect()) {
                    //The following lines of code confirms existing customers who log in view free lecture page.
                    $customer->setIsFreelectProspectConfirmed(true);
                    $customer->setFreeLectSubscribeStatus(1); //1 is subscribed.
                    $customer->setDateVerified(now());
                    $customer->setEmailVerified(true);
                    $customer->setFreeLectureProspect(true);
                    $customer->save();
                    $this->_freemarketinglectureHelper()->updateNewsletterSubscriptionStatus($customer->getEmail());
                    $this->_freemarketinglectureHelper()->addFreelectureGlobalSuccess(self::FREE_LECTURES_EXISTINGUSER_SIGNUP);
                }
            }
        } else {
            $customerId = $session->getCustomerId();
            if($customerId) {
                $customer = Mage::getModel('customer/customer')->load($customerId);
                if($customer->getId()) {
                    $this->_freemarketinglectureHelper()->signupCustomerFreelecture($customer); //sets customer as a prospect and gives them digital library access rights
                    //the following lines mark as customer as confirmed. (mean susbscribe status set to 1 as well as other related fields)
                    $customer->setIsAccountAtSignup(true);
                    $customer->setIsFreelectProspectConfirmed(true);
                    $customer->setFreeLectSubscribeStatus(1); //1 is subscribed.
                    $customer->setDateVerified(now());
                    $customer->setEmailVerified(true);
                    $customer->setFreeLectureProspect(true);
                    $customer->save();
                    $this->_freemarketinglectureHelper()->updateNewsletterSubscriptionStatus($customer->getEmail());
                    $this->_freemarketinglectureHelper()->addFreelectureGlobalSuccess(self::FREE_LECTURES_EXISTINGUSER_SIGNUP);
                }
            }
        }

        $numberOfErrorMessages = $this->_getSession()->getMessages()->count(Mage_Core_Model_Message::ERROR);
        $noErrors = $numberOfErrorMessages == 0  && !$exceptionErrorExists && $isFormValid;
        $errorsExist = !$noErrors;

        if($customRedirectPath && $noErrors) {
            $redirectPath = $customRedirectPath;
        } elseif($customRedirectErrorPath && $errorsExist) {
            $redirectPath = $customRedirectErrorPath;
        } elseif($errorsExist) {
            $redirectPath = $this->_freeLectureUrlRedirectToSelf;
        } else { //if there is no custom redirect path, and there are no errors, and form is valid.
            if($isNewCustomer) { //Clause below shows new customers message saying look for confirmation email.
                $redirectPath = $this->_freeLectureUrlRedirectToSelf;
                $redirectFormToDisplay = Tgc_Catalog_Helper_Freemarketinglecture::FORM_SUCCESS_NEWACCOUNT;
                $this->_freemarketinglectureHelper()->setFreelectureSubmittedEmail($formData['email_address']);
            } else { //existing customer who not logged in AND customer who register who are logged in, are redirected with line below.
                $redirectPath = $this->_freeLectureUrlRedirectOnExistingCustomerSuccess;
            }
        }

        if(!$redirectFormToDisplay) {
            $redirectFormToDisplay = $redirectFormToDisplayCurrentRequest;
        }

        $this->_freemarketinglectureHelper()->setFreeMarketingFormToDisplay($redirectFormToDisplay);

        $redirectUrl = Mage::getUrl($redirectPath);
        $this->getResponse()->setRedirect($redirectUrl);
    }


    /**
     * Reset forgotten password
     * Used to handle data recieved from reset forgotten password form
     */
    public function resetPasswordPostAction()
    {
        $resetPasswordLinkToken = (string) $this->getRequest()->getQuery('token');
        $customerId = (int) $this->getRequest()->getQuery('id');
        $password = (string) $this->getRequest()->getPost('password');
        $passwordConfirmation = (string) $this->getRequest()->getPost('confirmation');

        try {
            $this->_validateResetPasswordLinkToken($customerId, $resetPasswordLinkToken);
        } catch (Exception $exception) {
            $this->_getSession()->addError( $this->_getHelper('customer')->__('Your password reset link has expired.'));
            $this->_redirect('*/*/');
            return;
        }

        $errorMessages = array();
        if (iconv_strlen($password) <= 0) {
            array_push($errorMessages, $this->_getHelper('customer')->__('New password field cannot be empty.'));
        }
        /** @var $customer Mage_Customer_Model_Customer */
        $customer = $this->_getModel('customer/customer')->load($customerId);

        $customer->setPassword($password)
                ->setIsNameRequired(false);
        $customer->setConfirmation($passwordConfirmation);
        $validationErrorMessages = $customer->validate();
        if (is_array($validationErrorMessages)) {
            $errorMessages = array_merge($errorMessages, $validationErrorMessages);
        }

        if (!empty($errorMessages)) {
            $this->_getSession()->setCustomerFormData($this->getRequest()->getPost());
            foreach ($errorMessages as $errorMessage) {
                $this->_getSession()->addError($errorMessage);
            }
            $this->_redirect('*/*/resetpassword', array(
                'id' => $customerId,
                'token' => $resetPasswordLinkToken
            ));
            return;
        }

        try {
            // Empty current reset password token i.e. invalidate it
            $customer->setRpToken(null);
            $customer->setRpTokenCreatedAt(null);
            $customer->setConfirmation(null);
            $customer->save();
            $this->_getSession()->addSuccess( $this->_getHelper('customer')->__('Your password has been updated.'));
            $this->_redirect('*/*/login');
        } catch (Exception $exception) {
            $this->_getSession()->addException($exception, $this->__('Cannot save a new password.'));
            $this->_redirect('*/*/resetpassword', array(
                'id' => $customerId,
                'token' => $resetPasswordLinkToken
            ));
            return;
        }
    }

    /**
     * All this function does is log in an existing customer.
     */
    public function logInCustomerFreelecturesForm($formData)
    {
        $session = Mage::getSingleton('customer/session');
        $this->getRequest()->setPost('login', array('username' => $formData['email_address'], 'password' => $formData['password']));
        $this->getRequest()->setParam('form_key', Mage::getSingleton('core/session')->getFormKey());
        $urlCurrentPage = Mage::getUrl('*/*/*');
        $session->setBeforeAuthUrl($urlCurrentPage)->setAfterAuthUrl($urlCurrentPage); //this is helpful, because if an error occurs with login it will redirect back to this page.
        Mage_Customer_AccountController::loginPostAction();
    }

    /**
     * Create customer account action
     */
    public function createPostAction()
    {
        if (!$this->isAjax()) {
            return parent::createPostAction();
        }

        /** @var $session Mage_Customer_Model_Session */
        $session = $this->_getSession();
        $customer = $this->_getCustomer();

        try {
            $errors = $this->_getCustomerErrors($customer);

            if (empty($errors)) {
                $customer->save();
                $this->_dispatchRegisterSuccess($customer);
                if ($this->_successProcessRegistration($customer)) {
                    Mage::app()->loadAreaPart(Mage_Core_Model_App_Area::AREA_FRONTEND, Mage_Core_Model_App_Area::PART_EVENTS);
                    $session->setCustomerAsLoggedIn($customer);
                    $session->renewSession();
                    $response = array('status' => 'success', 'html' => Mage::helper('tgc_customer')->getLoggedInHeaderHtml(), 'refresh' => '1');
                    $this->_sendAjaxResponse($response);
                    return;
                } else {
                    $message = $this->__('Account confirmation is required. Please, check your email for the confirmation link. To resend the confirmation email please <a href="%s">click here</a>.',
                        $this->_getHelper('customer')->getEmailConfirmationUrl($customer->getEmail()));
                    $response = array('status' => 'confirmation', 'message' => $message);
                    $this->_sendAjaxResponse($response);
                    return;
                }
            } else {
                $response = array('status' => 'failure', 'message' => join(', ', $errors));
                $this->_sendAjaxResponse($response);
                return;
            }
        } catch (Mage_Core_Exception $e) {
            if ($e->getCode() === Mage_Customer_Model_Customer::EXCEPTION_EMAIL_EXISTS) {
                $message = $this->__('There is already an account with this email address. If you are sure that it is your email address, <a href="#" class="forgot-pass-link">click here</a> to get your password and access your account.');
            } else {
                $message = $e->getMessage();
            }
        } catch (Exception $e) {
            $message = $this->__('Cannot save the customer.');
        }

        $response = array('status' => 'failure', 'message' => $message);
        $this->_sendAjaxResponse($response);
        return;
    }

    /**
     * Success Registration
     *
     * @param Mage_Customer_Model_Customer $customer
     * @return Mage_Customer_AccountController
     */
    protected function _successProcessRegistration(Mage_Customer_Model_Customer $customer)
    {
        if (!$this->isAjax()) {
            return parent::_successProcessRegistration($customer);
        }

        $session = $this->_getSession();
        if ($customer->isConfirmationRequired()) {
            /** @var $app Mage_Core_Model_App */
            $app = $this->_getApp();
            /** @var $store  Mage_Core_Model_Store*/
            $store = $app->getStore();
            $customer->sendNewAccountEmail(
                'confirmation',
                $session->getBeforeAuthUrl(),
                $store->getId()
            );
            return false;
        } else {
            return true;
        }
    }

    /**
     * Change customer password action
     */
    public function editPostAction()
    {
        parent::editPostAction();
        //this makes it so that if a user is redirected to customer edit page from checkout, they are automatically redirected back to checkout when they
        //finish filling out the form.
        $beforeAuthUrl = $this->_getSession()->getBeforeAuthUrl();
        if(strpos($beforeAuthUrl, 'checkout/onepage/index')) {
            $this->_redirect('checkout/onepage/index');
        }
    }

    /**
     * Add welcome message and send new account email.
     * Returns success URL
     *
     * @param Mage_Customer_Model_Customer $customer
     * @param bool $isJustConfirmed
     * @return string
     */
    protected function _welcomeCustomer(Mage_Customer_Model_Customer $customer, $isJustConfirmed = false)
    {
        $this->_getSession()->addSuccess(
            $this->__('Thank you for registering with %s.', Mage::app()->getStore()->getFrontendName())
        );
        if ($this->_isVatValidationEnabled()) {
            // Show corresponding VAT message to customer
            $configAddressType =  $this->_getHelper('customer/address')->getTaxCalculationAddressType();
            $userPrompt = '';
            switch ($configAddressType) {
                case Mage_Customer_Model_Address_Abstract::TYPE_SHIPPING:
                    $userPrompt = $this->__('If you are a registered VAT customer, please click <a href="%s">here</a> to enter you shipping address for proper VAT calculation',
                        $this->_getUrl('customer/address/edit'));
                    break;
                default:
                    $userPrompt = $this->__('If you are a registered VAT customer, please click <a href="%s">here</a> to enter you billing address for proper VAT calculation',
                        $this->_getUrl('customer/address/edit'));
            }
            $this->_getSession()->addSuccess($userPrompt);
        }

        $session = $this->_getSession();
        if ($customer->isConfirmationRequired()) {
            /** @var $app Mage_Core_Model_App */
            $app = $this->_getApp();
            /** @var $store  Mage_Core_Model_Store*/
            $store = $app->getStore();
            $customer->sendNewAccountEmail(
                'confirmation',
                $session->getBeforeAuthUrl(),
                $store->getId()
            );
        }

        $successUrl = $this->_getUrl('*/*/index', array('_secure' => true));
        if ($this->_getSession()->getBeforeAuthUrl()) {
            $successUrl = $this->_getSession()->getBeforeAuthUrl(true);
        }
        return $successUrl;
    }

    /**
     * Forgot customer password page
     */
    public function forgotPasswordAction()
    {
        if (!$this->isAjax()) {
            parent::forgotPasswordAction();
        }
    }

    /**
     * Forgot customer password action
     */
    public function forgotPasswordPostAction()
    {
        if (!$this->isAjax()) {
            $email = (string) $this->getRequest()->getPost('email');
            if ($email) {
                if (!Zend_Validate::is($email, 'EmailAddress')) {
                    $this->_getSession()->setForgottenEmail($email);
                    $this->_getSession()->addError($this->__('Invalid email address.'));
                    $this->_redirect('*/*/forgotpassword');
                    return;
                }

                /** @var $customer Mage_Customer_Model_Customer */
                $customer = $this->_getModel('customer/customer')
                    ->setWebsiteId(Mage::app()->getStore()->getWebsiteId())
                    ->loadByEmail($email);

                if ($customer->getId()) {
                    try {
                        $newResetPasswordLinkToken =  $this->_getHelper('customer')->generateResetPasswordLinkToken();
                        $customer->changeResetPasswordLinkToken($newResetPasswordLinkToken);
                        $customer->sendPasswordResetConfirmationEmail();
                    } catch (Exception $exception) {
                        $this->_getSession()->addError($exception->getMessage());
                        $this->_redirect('*/*/forgotpassword');
                        return;
                    }
                }
                $this->_getSession()
                    ->addSuccess( $this->_getHelper('customer')
                        ->__('If there is an account associated with %s you will receive an email with a link to reset your password.',
                            $this->_getHelper('customer')->escapeHtml($email)));
                if ($successUrl = (string) $this->getRequest()->getPost('success_url')) {
                    $this->_redirect($successUrl);
                } else {
                    $this->_redirect('*/*/');
                }
                return;
            } else {
                $this->_getSession()->addError($this->__('Please enter your email.'));
                $this->_redirect('*/*/forgotpassword');
                return;
            }
        }

        try {
            $email = (string)$this->getRequest()->getPost('email');
            $websiteId = Mage::app()->getStore()->getWebsiteId();

            if (!$email) {
                throw new Mage_Core_Exception('Please enter your email.');
            }

            $this->_getModel('tgc_customer/service_forgotPassword')->send($email, $websiteId);
            $response = $this->_createSuccessAjaxResponse($this->__(
                'If there is an account associated with %s you will receive an email with a link to reset your password.',
                $this->_getHelper('customer')->escapeHtml($email)
            ));
        } catch (Mage_Core_Exception $e) {
            $response = $this->_createFailAjaxResponse($this->__($e->getMessage()));
        } catch (Exception $e) {
            Mage::logException($e);
            $response = $this->_createFailAjaxResponse($this->__('Error occured.'));
        }

        $this->_sendAjaxResponse($response);
    }


    /**
     * Forgot Free lectures password action
     */
    public function freelectureForgotpasswordAction()
    {
        $email = (string) $this->getRequest()->getPost('freelectures-forgotpassword');
        $redirectUrl = 'freelectures';
        if ($email) {
            if (!Zend_Validate::is($email, 'EmailAddress')) {
                $this->_getCoreSession()->setForgottenEmail($email);
                $this->_getCoreSession()->addError($this->__('Invalid email address.'));
                $this->_redirect($redirectUrl);
                return;
            }

            /** @var $customer Mage_Customer_Model_Customer */
            $customer = $this->_getModel('customer/customer')
                ->setWebsiteId(Mage::app()->getStore()->getWebsiteId())
                ->loadByEmail($email);

            if ($customer->getId()) {
                try {
                    $newResetPasswordLinkToken =  $this->_getHelper('customer')->generateResetPasswordLinkToken();
                    $customer->changeResetPasswordLinkToken($newResetPasswordLinkToken);
                    $customer->sendPasswordResetConfirmationEmail();
                } catch (Exception $exception) {
                    $this->_getCoreSession()->addError($exception->getMessage());
                    $this->_redirect($redirectUrl);
                    return;
                }
            }
            $this->_getCoreSession()
                ->addSuccess( $this->_getHelper('customer')
                    ->__('If there is an account associated with %s you will receive an email with a link to reset your password.',
                        $this->_getHelper('customer')->escapeHtml($email)));
            $this->_redirect($redirectUrl);
            return;
        } else {
            $this->_getCoreSession()->addError($this->__('Please enter your email.'));
            $this->_redirect($redirectUrl);
            return;
        }
    }


    private function _createSuccessAjaxResponse($message)
    {
        return array('status' => 'success', 'message' => $message);
    }

    private function _createFailAjaxResponse($message)
    {
        return array('status' => 'failure', 'message' => $message);
    }

    /**
     * Display reset forgotten password form
     *
     * User is redirected on this action when he clicks on the corresponding link in password reset confirmation email
     *
     */
    public function resetPasswordAction()
    {
        if (!$this->isAjax()) {
            parent::resetPasswordAction();
        }
    }

    private function _sendAjaxResponse($response)
    {
        $jsonData = Zend_Json::encode($response);
        $this->getResponse()
            ->clearHeaders()
            ->setHeader('Content-Type', 'application/json')
            ->setBody($jsonData);
    }

    /**
     * Customer verify account page
     */
    public function verifyAction()
    {
        if (!$this->_getSession()->isLoggedIn()) {
            $this->_redirect('home');
            return;
        }
        $this->getResponse()->setHeader('Login-Required', 'true');
        $this->loadLayout();
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('catalog/session');
        $this->renderLayout();
    }

    /**
     * Verify account post action
     */
    public function verifyPostAction()
    {
        if (!$this->_validateFormKey()) {
            $this->_redirect('*/*/');
            return;
        }

        $session = $this->_getSession();

        if ($this->getRequest()->isPost()) {
            $login = $this->getRequest()->getPost('login');
            if (!empty($login['username']) && !empty($login['password'])) {
                try {
                    if ($this->_isAccountLocked($login['username'])) {
                        throw new Mage_Core_Exception(
                            $this->_getHelper('customer')->__('This account is temporarily locked')
                        );
                    }
                    $session->login($login['username'], $login['password']);
                } catch (Mage_Core_Exception $e) {
                    switch ($e->getCode()) {
                        case Mage_Customer_Model_Customer::EXCEPTION_INVALID_EMAIL_OR_PASSWORD:
                            $this->_loginFailure($login['username']);
                            $message = $e->getMessage();
                            if ($warning = $this->_getAccountLockWarning($login['username'])) {
                                $session->addError($warning);
                            }
                            break;
                        default:
                            $message = $e->getMessage();
                    }
                    $session->addError($message);
                    $this->_redirect('customer/account/verify');
                    return;
                } catch (Exception $e) {
                    // Mage::logException($e); // PA DSS violation: this exception log can disclose customer password
                    $this->_redirect('customer/account/verify');
                    return;
                }
            } else {
                $session->addError($this->__('Login and password are required.'));
                $this->_redirect('customer/account/verify');
                return;
            }
        }

        $this->_loginPostRedirect();
    }

    private function _isAccountLocked($email)
    {
        $customer = Mage::getModel('customer/customer')
            ->setWebsiteId(Mage::app()->getStore()->getWebsiteId())
            ->loadByEmail($email);
        if (!$customer) {
            return false;
        }

        $expires = $customer->getLockExpires();
        if (!$expires) {
            return false;
        }

        if ($expires < time()) {
            $customer->addData(
                array(
                    'lock_expires' => null,
                    'num_failures' => null,
                )
            )->save();
        }

        return $expires > time();
    }

    private function _loginFailure($email)
    {
        $customer = Mage::getModel('customer/customer')
            ->setWebsiteId(Mage::app()->getStore()->getWebsiteId())
            ->loadByEmail($email);
        if (!$customer->getId()) {
            return false;
        }

        $numFailures = $customer->getNumFailures();
        $numFailures = intval($numFailures) + 1;
        $customer->setNumFailures($numFailures)->save();

        if ($numFailures >= Tgc_Customer_Model_ActiveSession::MAX_LOGIN_FAILURES) {
            $this->_lockUserAccount($customer);
        }
    }

    private function _lockUserAccount($customer)
    {
        $lockExpires = time() + Tgc_Customer_Model_ActiveSession::ACCOUNT_LOCK_DURATION;
        $customer->setLockExpires($lockExpires)->save();

        $this->_getSession()->endActiveSession();
    }

    private function _getAccountLockWarning($email)
    {
        $customer = Mage::getModel('customer/customer')
            ->setWebsiteId(Mage::app()->getStore()->getWebsiteId())
            ->loadByEmail($email);
        if (!$customer) {
            return false;
        }

        $numFailures = intval($customer->getNumFailures());

        if ($numFailures == Tgc_Customer_Model_ActiveSession::MAX_LOGIN_FAILURES - 1) {
            $message = $this->__('Your account will be locked after 1 more unsuccessful login attempt.');
        } else if ($numFailures >= Tgc_Customer_Model_ActiveSession::MAX_LOGIN_FAILURES) {
            $message = $this->__('Your account has been locked for %s minutes', Tgc_Customer_Model_ActiveSession::ACCOUNT_LOCK_DURATION / 60);
        } else {
            $message = false;
        }

        return $message;
    }

    public function footerJsAction()
    {
        $session  = $this->_getSession();
        $active   = $session->isCustomerActive();
        $response = array();

        if ($session->isLoggedIn() && $session->needsLogoutRedirect()) {
            if ($session->needsExtension()) {
                $response['mode'] = 'redirect-extended';
            } else {
                $response['mode'] = 'redirect';
            }
        } else if ($active) {
            $response['mode'] = 'timer';
        } else if ($session->isLoggedIn()) {
            $response['mode'] = 'lockdown';
        }

        $this->_sendAjaxResponse($response);
    }

    public function expireAction()
    {
        if (!$this->isAjax()) {
            $this->_redirect('home');
            return;
        }

        Mage::app()->loadAreaPart(Mage_Core_Model_App_Area::AREA_FRONTEND, Mage_Core_Model_App_Area::PART_EVENTS);
        $session = $this->_getSession();
        $session->endActiveSession();

        $session->addError(
            $this->_getHelper('customer')->__(
                'Your session has expired due to inactivity. As a security precaution, you have been logged out'
            )
        );
    }

    /**
     * Login from modal action
     */
    public function modalLoginAction()
    {
        if (!$this->isAjax()) {
            $this->_redirect('home');
            return;
        }

        $session = $this->_getSession();

        if ($this->getRequest()->isPost()) {
            $login = $this->getRequest()->getPost('login');
            if (!empty($login['username']) && !empty($login['password'])) {
                try {
                    if ($this->_isAccountLocked($login['username'])) {
                        throw new Mage_Core_Exception(
                            $this->_getHelper('customer')->__('This account is temporarily locked')
                        );
                    }
                    Mage::app()->loadAreaPart(Mage_Core_Model_App_Area::AREA_FRONTEND, Mage_Core_Model_App_Area::PART_EVENTS);
                    $session->login($login['username'], $login['password']);
                } catch (Mage_Core_Exception $e) {
                    switch ($e->getCode()) {
                        case Mage_Customer_Model_Customer::EXCEPTION_INVALID_EMAIL_OR_PASSWORD:
                            $this->_loginFailure($login['username']);
                            $message = $e->getMessage();
                            if ($warning = $this->_getAccountLockWarning($login['username'])) {
                                $message .= ' ' . $warning;
                            }
                            break;
                        default:
                            $message = $e->getMessage();
                    }
                    $response = array('status' => 'failure', 'message' => $message);
                    $this->_sendAjaxResponse($response);
                    return;
                } catch (Exception $e) {
                    $message = $this->_getHelper('customer')->__('There was an error logging you in');
                    $response = array('status' => 'failure', 'message' => $message);
                    $this->_sendAjaxResponse($response);
                    return;
                }
            } else {
                $message = $this->__('Login and password are required.');
                $response = array('status' => 'failure', 'message' => $message);
                $this->_sendAjaxResponse($response);
                return;
            }
        }

        $response = array('status' => 'success');
        $this->_sendAjaxResponse($response);
        return;
    }

    public function updateLinksAction()
    {
        $cartHtml = Mage::app()->getLayout()
            ->createBlock('checkout/cart_sidebar')
            ->setTemplate('checkout/cart/mini.phtml')
            ->toHtml();

        $wishlistHtml = Mage::app()->getLayout()
            ->createBlock('wishlist/customer_sidebar')
            ->setTemplate('wishlist/mini.phtml')
            ->toHtml();

        $response = array('status' => 'success', 'wishlistHtml' => $wishlistHtml, 'cartHtml' => $cartHtml);
        $this->_sendAjaxResponse($response);
        return;
    }

    private function _setKeepLoggedIntoSession()
    {
        $session = $this->_getSession();
        $cookie  = $session->getCookie();

        $keepLogged = 1;
        if ($this->getRequest()->isPost()) {
            $login = $this->getRequest()->getPost('login');
            if (!isset($login['keep_logged']) || $login['keep_logged'] != 1) {
                $keepLogged = 0;
            }
        }

        $cookie->set(Tgc_Customer_Model_ActiveSession::KEEP_LOGGED_COOKIE_NAME, $keepLogged);
        $_COOKIE[Tgc_Customer_Model_ActiveSession::KEEP_LOGGED_COOKIE_NAME] = $keepLogged;
    }

    /**
     * Login post action
     */
    public function nonAjaxLoginPostAction()
    {
        if (!$this->_validateFormKey()) {
            $this->_redirect('*/*/');
            return;
        }

        if ($this->_getSession()->isLoggedIn()) {
            $this->_redirect('*/*/');
            return;
        }
        $session = $this->_getSession();

        if ($this->getRequest()->isPost()) {
            $login = $this->getRequest()->getPost('login');
            if (!empty($login['username']) && !empty($login['password'])) {
                try {
                    if ($this->_isAccountLocked($login['username'])) {
                        throw new Mage_Core_Exception(
                            $this->_getHelper('customer')->__('This account is temporarily locked')
                        );
                    }
                    $this->_setKeepLoggedIntoSession();
                    $session->login($login['username'], $login['password']);
                    if ($session->getCustomer()->getIsJustConfirmed()) {
                        $this->_welcomeCustomer($session->getCustomer(), true);
                    }
                } catch (Mage_Core_Exception $e) {
                    switch ($e->getCode()) {
                        case Mage_Customer_Model_Customer::EXCEPTION_EMAIL_NOT_CONFIRMED:
                            $value = $this->_getHelper('customer')->getEmailConfirmationUrl($login['username']);
                            $message = $this->_getHelper('customer')->__('This account is not confirmed. <a href="%s">Click here</a> to resend confirmation email.', $value);
                            break;
                        case Mage_Customer_Model_Customer::EXCEPTION_INVALID_EMAIL_OR_PASSWORD:
                            $this->_loginFailure($login['username']);
                            $message = $e->getMessage();
                            if ($warning = $this->_getAccountLockWarning($login['username'])) {
                                $message .= ' ' . $warning;
                            }
                            break;
                        default:
                            $message = $e->getMessage();
                    }

                    //the customer session ($session) only displays error messages on certain pages. The customer session ($session) will not display errors on the checkout or free lecture page.
                    //When $coreSession->addError is called, the error message will always be displayed on the next page that is requested.
                    $this->_getCoreSession()->addError($message);
                    $session->setUsername($login['username']);
                } catch (Exception $e) {
                    // Mage::logException($e); // PA DSS violation: this exception log can disclose customer password
                }
            } else {
                $session->addError($this->__('Login and password are required.'));
            }
        }

        $this->_loginPostRedirect();
    }

    public function headerLoginAction()
    {
        if ($this->_getSession()->isLoggedIn()) {
            $this->_redirect('*/*/');
            return;
        }
        $session = $this->_getSession();

        if ($this->getRequest()->isPost()) {
            $login = $this->getRequest()->getPost('login');
            if (!empty($login['username']) && !empty($login['password'])) {
                try {
                    if ($this->_isAccountLocked($login['username'])) {
                        throw new Mage_Core_Exception(
                            $this->_getHelper('customer')->__('This account is temporarily locked')
                        );
                    }
                    $this->_setKeepLoggedIntoSession();
                    $session->login($login['username'], $login['password']);
                    if ($session->getCustomer()->getIsJustConfirmed()) {
                        $this->_welcomeCustomer($session->getCustomer(), true);
                    }
                } catch (Mage_Core_Exception $e) {
                    switch ($e->getCode()) {
                        case Mage_Customer_Model_Customer::EXCEPTION_EMAIL_NOT_CONFIRMED:
                            $value = $this->_getHelper('customer')->getEmailConfirmationUrl($login['username']);
                            $message = $this->_getHelper('customer')->__('This account is not confirmed. <a href="%s">Click here</a> to resend confirmation email.', $value);
                            break;
                        case Mage_Customer_Model_Customer::EXCEPTION_INVALID_EMAIL_OR_PASSWORD:
                            $this->_loginFailure($login['username']);
                            $message = $e->getMessage();
                            if ($warning = $this->_getAccountLockWarning($login['username'])) {
                                $message .= ' ' . $warning;
                            }
                            break;
                        default:
                            $message = $e->getMessage();
                    }

                    $this->_getCoreSession()->addError($message);
                    $session->setUsername($login['username']);
                } catch (Exception $e) {}
            } else {
                $session->addError($this->__('Login and password are required.'));
            }
        }

        if($session->isLoggedIn() && $this->getRequest()->getPost('referringelement') == self::MDL_REFERRING_ELEMENT) {
            $this->_redirectUrl(Mage::getUrl($this->_redirectDigitalLibraryPath));
        } else {
            $this->_redirectReferer();
        }
    }

    /**
     * Confirm customer account by id and confirmation key
     */
    public function confirmFreelectureAction()
    {
        $session = $this->_getSession();
        $freeLecturesSuccessUrl = Mage::getUrl('digital-library/account');
        if ($session->isLoggedIn()) {
            $this->_getSession()->logout()->regenerateSessionId();
        }
        try {
            $id = $this->getRequest()->getParam(Mage::helper('tgc_customer')->getFreeLecturesConfirmationParameterId(), false);
            $token = $this->getRequest()->getParam(Mage::helper('tgc_customer')->getFreeLecturesConfirmationParameterToken(), false);
            if (empty($id) || empty($token)) {
                throw new Exception($this->__('Bad request.'));
            }

            // load customer by id (try/catch in case if it throws exceptions)
            try {
                $customer = $this->_getModel('customer/customer')->load($id);
                if ((!$customer) || (!$customer->getId())) {
                    throw new Exception('Failed to load customer by id.');
                }
            }
            catch (Exception $e) {
                throw new Exception($this->__('Wrong customer account specified.'));
            }

            // check if it is not yet confirmed as a free lecture prospect, they receive this id.
            if ($customer->getConfirmationGuid()) {
                if ($customer->getConfirmationGuid() !== $token) {
                    throw new Exception($this->__('Wrong confirmation key.'));
                }

                // activate customer
                try {
                    $customer->setIsFreelectProspectConfirmed(true);
                    $customer->setFreeLectSubscribeStatus(1); //1 is subscribed.
                    $customer->setDateVerified(now());
                    $customer->setEmailVerified(true);
                    $customer->setFreeLectureProspect(true);
                    $customer->save();
                    $this->_freemarketinglectureHelper()->updateNewsletterSubscriptionStatus($customer->getEmail());
                }
                catch (Exception $e) {
                    throw new Exception($this->__('Failed to confirm customer account.'));
                }

                $session->renewSession();
                // log in, then die happy
                $session->setCustomerAsLoggedIn($customer);

                $this->_freemarketinglectureHelper()->addFreelectureGlobalSuccess('You are now confirmed for Free Lectures');
                $this->_redirectSuccess($freeLecturesSuccessUrl);
                return;
            } elseif($customer->getFreeLectureProspect()) {
                $this->_freemarketinglectureHelper()->addFreelectureGlobalSuccess('You have already been confirmed for Free Lectures');
                $session->setCustomerAsLoggedIn($customer);
                $this->_redirectSuccess($freeLecturesSuccessUrl);
            } else {
                // die happy
                $this->_redirectSuccess($this->_getUrl('*/*/index', array('_secure' => true)));
            }


            return;
        }
        catch (Exception $e) {
            // die unhappy
            $this->_getSession()->addError($e->getMessage());
            $this->_redirectError($this->_getUrl('*/*/index', array('_secure' => true)));
            return;
        }
    }

    public function _freemarketinglectureHelper()
    {
        return Mage::helper('tgc_catalog/freemarketinglecture');
    }

    public function downloadPrefsAction()
    {
        $this->loadLayout();
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('catalog/session');
        $this->renderLayout();
    }

    protected function _helperAdcoderouter()
    {
        return Mage::helper('adcoderouter');
    }

    protected function _helperLectures()
    {
        return Mage::helper('lectures');
    }

    /**
     * Retrieves core session object.
     * @return Mage_Core_Model_Abstract
     */
    protected function _getCoreSession()
    {
        return Mage::getSingleton('core/session');
    }
}
