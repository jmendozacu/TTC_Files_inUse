<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category
 * @package
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Guidance_Paymentech_Model_Payment_Method extends Enterprise_Pbridge_Model_Payment_Method_Abstract
{
    protected $_code  = 'paymentech';

    protected $_isGateway               = true;
    protected $_canAuthorize            = true;
    protected $_canCapture              = true;
    protected $_canCapturePartial       = true;
    protected $_canRefund               = true;
    protected $_canRefundInvoicePartial = true;
    protected $_canVoid                 = true;
    protected $_canUseInternal          = true;
    protected $_canUseCheckout          = true;
    protected $_canUseForMultishipping  = true;
    protected $_canSaveCc               = false;
    protected $_canFetchTransactionInfo = false;

    public function getCode()
    {
        return Mage_Payment_Model_Method_Abstract::getCode();
    }

    public function authorize(Varien_Object $payment, $amount)
    {
        parent::authorize($payment, $amount);

        $payment->setIsTransactionClosed($this->getConfigData('authorize_close_transaction') ? 1 : 0);

        return $this;
    }
}
