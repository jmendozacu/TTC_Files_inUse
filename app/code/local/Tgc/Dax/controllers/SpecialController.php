<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Dax
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Dax_SpecialController extends Mage_Core_Controller_Front_Action
{
    private $_requiredValues;

    public function _construct()
    {
        parent::_construct();

        $this->_requiredValues = array(
            'web_key',
            'email',
            'email_campaign',
        );
    }

    public function unsubscribeAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function unsubscribeFromDaxAction()
    {
        $request        = $this->getRequest();
        $resource       = Mage::getResourceModel('tgc_dax/emailUnsubscribe');
        $email          = $request->getParam(Tgc_Dax_Model_EmailUnsubscribe::EMAIL_PARAM);
        $daxCustomerIds = $resource->getDaxCustomerIdsByEmail($email);
        $hasError       = false;
        $errorMessage   = '';
        $data           = array();
        $session        = $this->_getCoreSession();
        $redirectPath   = 'tgc/special/unsubscribe';

        $webKey         = $request->getParam(Tgc_Dax_Model_EmailUnsubscribe::WEB_KEY_PARAM);
        if (empty($webKey)) {
            $webKey = $request->getParam(Tgc_Dax_Model_EmailUnsubscribe::ALT_WEB_KEY_PARAM);
        }

        if (empty($daxCustomerIds)) {
            $message = Mage::helper('tgc_dax')->__(
                'The email address %s is not associated with any account',
                $errorMessage
            );
            $session->addError($message);
            $this->_redirect($redirectPath);
            return;
        }

        foreach ($daxCustomerIds as $daxId) {
            $data = array(
                'web_key'          => $webKey,
                'customer_id'      => $daxId,
                'email'            => $email,
                'unsubscribe_date' => now(),
                'email_campaign'   => $request->getParam(Tgc_Dax_Model_EmailUnsubscribe::EMAIL_CAMPAIGN_PARAM),
            );

            try {
                $this->_validateUnsubscribeData($data);
                Mage::getModel('tgc_dax/emailUnsubscribe')
                    ->addData($data)
                    ->save();

                $customer = Mage::getModel('customer/customer')->loadByEmail($email);
                if ($customer->getId()) {
                    $customer->setIsSubscribed(0);
                    $customer->save();
                }

            } catch (Exception $e) {
                Mage::logException($e);
                $hasError = true;
                $errorMessage .= $e->getMessage();
            }
        }

        if ($hasError) {
            $message = Mage::helper('tgc_dax')->__(
                'An error occurred while unsubscribing you: %s',
                $errorMessage
            );
            $session->addError($message);
        } else {
            //The unsubscribe page prints a success message, no global notice needs to be created here to indicate it is successful.
            $this->_lecturesHelper()->markUserAsUnsubscribed();
        }

        $this->_redirect($redirectPath);
    }

    private function _validateUnsubscribeData($data)
    {
        foreach ($data as $key => $value) {
            if (empty($value) && in_array($key, $this->_requiredValues)) {
                throw new InvalidArgumentException(
                    Mage::helper('tgc_dax')->__(
                        'The value for %s is missing or invalid',
                        $key
                    )
                );
            }
        }
    }

    /**
     * Retrieve customer session model object
     *
     * @return Mage_Customer_Model_Session
     */
    protected function _getSession()
    {
        return Mage::getSingleton('customer/session');
    }

    /**
     * Retrieve core session model object
     *
     * @return Mage_Core_Model_Session
     */
    protected function _getCoreSession()
    {
        return Mage::getSingleton('core/session');
    }

    /**
     * Returns the free marketing lecture
     * @return Mage_Core_Helper_Abstract
     */
    public function _freemarketinglectureHelper()
    {
        return Mage::helper('tgc_catalog/freemarketinglecture');
    }

    /**
     * Returns the unsubscribe helper.
     * @return Mage_Core_Helper_Abstract
     */
    protected function _lecturesHelper()
    {
        return Mage::helper('lectures/unsubscribe');
    }
}
