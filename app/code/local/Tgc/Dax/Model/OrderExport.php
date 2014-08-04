<?php
/**
 * Dax integration
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Dax
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Dax_Model_OrderExport
{
    const ALREADY_RUNNING_MESSAGE = 'The DAX Order Export process is already running. Exiting';
    const LOCAL_EXPORT_PATH       = 'export/dax/orders';
    const LOCAL_ARCHIVE_PATH      = 'archive/dax/orders';
    const FILENAME_PREFIX         = 'daxOrderExport-';
    const FILE_DATE_FORMAT        = 'yyyy-MM-dd_HH-mm-ss';
    const FILE_EXTENSION          = '.xml';
    const SFTP_HOST_PATH          = 'dax/sftp/hostname';
    const SFTP_PORT_PATH          = 'dax/sftp/port';
    const SFTP_USER_PATH          = 'dax/sftp/username';
    const SFTP_PASSWORD_PATH      = 'dax/sftp/password';
    const SFTP_TIMEOUT_PATH       = 'dax/sftp/timeout';
    const SFTP_REMOTE_PATH        = 'dax/sftp/remote_path';
    const STATUS_HISTORY_COMMENT  = 'Order was sent to DAX';
    const STATUS_UNEXPORTED       = 'unexported';
    const STATUS_UNACKNOWLEDGED   = 'unacknowledged';
    const EXPORT_THRESHOLD        = 'dax/order_export/export_threshold';
    const ACKNOWLEDGE_THRESHOLD   = 'dax/order_export/acknowledge_threshold';
    const EXPORT_WARNING_ENABLED  = 'dax/order_export/warning_enabled';
    const EXPORT_WARNING_SENDTO   = 'dax/order_export/send_to';
    const EXPORT_WARNING_IDENTITY = 'dax/order_export/identity';
    const EXPORT_WARNING_TEMPLATE = 'dax/order_export/template';
    const COL_IS_EXPORTED         = 'is_exported';
    const COL_DAX_RECEIVED        = 'dax_received';
    const PHYSICAL_GC_SKU         = 'physical-gift-card';
    const VIRTUAL_GC_SKU          = 'virtual-gift-card';
    const DAX_GC_SKU              = 'cp2395';

    private $_isRunning           = false;
    private $_orderIds            = array();
    private $_fileName;
    private $_websiteNames        = array();
    private $_storeCodes          = array();
    private $_regions             = array();
    private $_gcBalanceAvailable;
    private $_lineNumber;
    private $_filesTransferred    = array();
    private $_gcSkus              = array(
        self::PHYSICAL_GC_SKU,
        self::VIRTUAL_GC_SKU,
    );

    private $_paymentMethod = array(
        'checkmo'       => array(1, 'Cash'),
        'paymentech'    => array(3, 'Credit Card'),
        'purchaseorder' => array(5, 'Purchase Order'),
    );

    private $_cardType = array(
        'VI' => array(1, 'VISA'),
        'MC' => array(2, 'MasterCard'),
        'AE' => array(3, 'American Express'),
        'DI' => array(4, 'Discover'),
        'SM' => array(6, 'Maestro'),
    );

    private $_shippingMethod = array(
        'premiumrate_Ground_Delivery'   => array(0,  'Standard Delivery', 'Best Way',       1),
        'premiumrate_2nd_Day_Express'   => array(4,	 '2nd Day Express',   'FEDEX 2 Day',    1),
        'premiumrate_Overnight_Express' => array(3,  'Overnight Express', 'FEDEX Next Day', 1),
        'freeshipping_freeshipping'     => array(0,  'Standard Delivery', 'Best Way',       1),
        'premiumrate_2nd_Day'           => array(11, 'UPS2Day',           'UPS 2 Day',      1),
        'premiumrate_Next_Day'          => array(10, 'UPS1Day',           'UPS Next Day',   1),
        'premiumrate_Standard'          => array(0,  'Standard Delivery', 'Best Way',       1),
        'tgc_flatrate_tgc_flatrate'     => array(0,  'Standard Delivery', 'Best Way',       1),
    );

    private $_websiteToCsrId = array(
        Tgc_Setup_Model_Resource_Setup::US_WEBSITE_CODE => 'webUS',
        Tgc_Setup_Model_Resource_Setup::UK_WEBSITE_CODE => 'webUK',
        Tgc_Setup_Model_Resource_Setup::AU_WEBSITE_CODE => 'webAU',
    );

    public function processExport()
    {
        if ($this->_isRunning) {
            return self::ALREADY_RUNNING_MESSAGE;
        }

        $this->_isRunning = true;

        try {
            $this->_statusCheck(self::STATUS_UNEXPORTED);
            $this->_writeExportFile();
            $this->_transferFile();
            $archivePath = $this->_archiveFile();
            $this->_postExport();
            $this->_statusCheck(self::STATUS_UNACKNOWLEDGED);
        } catch (Exception $e) {
            Mage::logException($e);
            return '<p><pre>' . $e->getMessage() . '</pre></p>';
        }

        return $this->_createSuccessDebugHtml($archivePath);
    }

    protected function _statusCheck($order_status)
    {
        if (!Mage::getStoreConfig(self::EXPORT_WARNING_ENABLED)) {
            return $this;
        }

        switch($order_status)
        {
            case self::STATUS_UNEXPORTED:
                $filter = self::COL_IS_EXPORTED;
                $value = 0;
                $threshold_config = self::EXPORT_THRESHOLD;
                break;
            case self::STATUS_UNACKNOWLEDGED:
                $filter = self::COL_DAX_RECEIVED;
                $value = 0;
                $threshold_config = self::ACKNOWLEDGE_THRESHOLD;
                break;
        }

        $collection = Mage::getResourceModel('sales/order_collection')
            ->addFieldToFilter($filter, $value);
        $size = $collection->getSize();

        $threshold = Mage::getStoreConfig($threshold_config);
        if($size > $threshold)
        {
            // Get the destination email addresses to send copies to
            $sendTo = $this->_getEmails(self::EXPORT_WARNING_SENDTO);

            // Retrieve corresponding email template id
            $templateId = Mage::getStoreConfig(self::EXPORT_WARNING_TEMPLATE);

            $mailer = Mage::getModel('core/email_template_mailer');
            $emailInfo = Mage::getModel('core/email_info');
            foreach ($sendTo as $email) {
                $emailInfo->addTo($email);
            }
            $mailer->addEmailInfo($emailInfo);

            // Set all required params and send emails
            $mailer->setSender(Mage::getStoreConfig(self::EXPORT_WARNING_IDENTITY));
            $mailer->setTemplateId($templateId);
            $mailer->setTemplateParams(array(
                    'size'         => $size,
                    'threshold'    => $threshold,
                    'filter'       => $filter,
                    'dateAndTime'  => Mage::getModel('core/date')->date(),
                    'value'        => $value
                )
            );
            $mailer->send();
        }
        return $this;
    }

    protected function _getEmails($configPath)
    {
        $data = Mage::getStoreConfig($configPath);
        if (!empty($data)) {
            return explode(',', $data);
        }
        return false;
    }

    private function _createSuccessDebugHtml($url)
    {
        $html = <<<HTML
<p>File has been successfully transferred and archived to: {$url}</p>
<p><a href="{$url}" title="Download file">Click here to download order export file generated</a></p>
HTML;

        return $html;
    }

    private function _writeExportFile()
    {
        $orderCollection = $this->_getOrderCollection();
        if (1 > count($orderCollection)) {
            throw new LogicException('There are no orders to be exported to DAX at this time');
        }

        $parentNode = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" ?><Orders></Orders>');
        foreach ($orderCollection as $order) {
            $orderNode  = $parentNode->addChild('Order');
            $this->_writeOrder($order, $orderNode);
            $this->_orderIds[] = $order->getEntityId();
        }

        if (file_put_contents($this->_getFileName(), Mage::helper('tgc_dax')->xmlpp($parentNode->asXML())) === false)
        {
            throw new DomainException('Unable to write the order export file');
        }
    }

    private function _writeOrder($order, SimpleXMLElement $node)
    {
        $billingAddress  = Mage::getModel('sales/order_address')->load($order->getBillingAddressId());
        $shippingAddress = Mage::getModel('sales/order_address')->load($order->getShippingAddressId());

        $customer = Mage::getModel('customer/customer')->load($order->getCustomerId());
        if ($customer->isObjectNew()) {
            $primaryShipping = $primaryBilling = 1;
        } else {
            $primaryBilling = ($billingAddress->getCustomerAddressId() == $customer->getDefaultBilling()) ? 1 : 0;
            $primaryShipping = ($shippingAddress->getCustomerAddressId() == $customer->getDefaultShipping()) ? 1 : 0;
        }

        $node->order_id = $order->getIncrementId();
        $node->dax_customer_id = $order->getDaxCustomerId();
        $node->web_user_id = $order->getWebUserId();
        $node->AffiliateSubID = $order->getAffiliateId() ? $order->getAffiliateId() : null;
        $node->email = $order->getCustomerEmail();
        $this->_addPaymentInfo($node, $order);
        $node->paymentech_profile_id = $order->getPaymentechProfileId();
        $this->_addShippingInfo($node, $order);
        $this->_addGiftInfo($node, $order);
        $node->CSRID = $this->_getCsrIdByStoreId($order->getStoreId());
        $node->TransactionReference = $order->getPaymentTransactionId();
        $node->AuthAmount = $order->getPaymentAuthAmount();
        $node->AuthResult = $order->getPaymentResponseCode();
        $node->AuthCode = $order->getPaymentAuthCode();
        $node->AuthDate = $order->getPaymentAuthDate();
        $node->AVSResponseCode = $order->getPaymentAvsResponse();
        $node->addChild('PONumber'); // Placeholder: possible future use, library customers?
        $node->addChild('TaxExemptFlag'); // Placeholder: possible future use, library customers?

        $node->billing_primary = $primaryBilling;
        $node->billing_prefix = $billingAddress->getPrefix();
        $node->billing_firstname = $billingAddress->getFirstname();
        $node->billing_middlename = $billingAddress->getMiddlename();
        $node->billing_lastname = $billingAddress->getLastname();
        $node->billing_suffix = $billingAddress->getSuffix();
        $this->_addStreet($node, $billingAddress, 'billing_street');
        $node->billing_city = $billingAddress->getCity();
        $node->billing_region = $this->_getRegionCodeByName($billingAddress->getRegion(), $billingAddress->getCountryId());
        $node->billing_country = $billingAddress->getCountryId();
        $node->billing_postcode = $billingAddress->getPostcode();
        $node->billing_telephone = $billingAddress->getTelephone();
        $node->billing_company = $billingAddress->getCompany();
        $node->billing_fax = $billingAddress->getFax();

        $node->shipping_primary = $primaryShipping;
        $node->shipping_prefix = $shippingAddress->getPrefix();
        $node->shipping_firstname = $shippingAddress->getFirstname();
        $node->shipping_middlename = $shippingAddress->getMiddlename();
        $node->shipping_lastname = $shippingAddress->getLastname();
        $node->shipping_suffix = $shippingAddress->getSuffix();
        $this->_addStreet($node, $shippingAddress, 'shipping_street');
        $node->shipping_city = $shippingAddress->getCity();
        $node->shipping_region = $this->_getRegionCodeByName($shippingAddress->getRegion(), $shippingAddress->getCountryId());
        $node->shipping_country = $shippingAddress->getCountryId();
        $node->shipping_postcode = $shippingAddress->getPostcode();
        $node->shipping_telephone = $shippingAddress->getTelephone();
        $node->shipping_company = $shippingAddress->getCompany();
        $node->shipping_fax = $shippingAddress->getFax();

        $node->created_at = $this->_convertDate($order->getCreatedAt());
        $node->updated_at = $this->_convertDate($order->getUpdatedAt());
        $node->GST_HSTAmount = $this->_formatNumber($order->getGstAmount());
        $node->PST_QSTAmount = $this->_formatNumber($order->getPstAmount());
        $node->TaxAmount = $this->_formatNumber($order->getTaxAmount());
        $node->shipping_amount = $this->_formatNumber($order->getShippingAmount());
        $node->shipping_tax_amount = $this->_formatNumber($order->getShippingTaxAmount());
        $node->ad_code = $customer->getAdcode();
        $node->gift_certificate = $this->_formatNumber($order->getGiftCardsAmount());
        $node->subtotal = $this->_formatNumber($order->getSubtotal());
        $node->grand_total = $this->_formatNumber($order->getGrandTotal());
        $node->currency = $order->getorderCurrencyCode();
        $node->order_status = $order->getStatus();

        $itemsNode  = $node->addChild('OrderItems');
        $orderItems = Mage::getResourceModel('tgc_dax/order_item_collection')->setOrderFilter($order);
        $this->_gcBalanceAvailable = (float)$order->getGiftCardsAmount();
        $this->_lineNumber = 0;

        foreach ($orderItems as $item) {
            if (count($item->getChildrenItems())) {
                continue;
            }
            $this->_lineNumber++;
            $itemNode  = $itemsNode->addChild('Item');
            $this->_writeOrderItem($item, $itemNode);
        }

        /* If coupon is present, add it as an item */
        if($order->getCouponCode())
        {
            $coupon = Mage::getModel('salesrule/coupon')->loadByCode($order->getCouponCode());
            $rule = Mage::getModel('salesrule/rule')->load($coupon->getRuleId());
            $matches = array();
            // Match primary coupon pattern, extract out of RuleName
            preg_match('/(CPN\-\d{6})/', $rule->getName(), $matches);
            if($matches[0])
            {
                $dax_coupon_id = $matches[0];
            }
            else
            {
                // Match secondary coupon pattern (largely obsolete)
                preg_match('/(CP\d{4})/', $rule->getName(), $matches);
                if($matches[0])
                {
                    $dax_coupon_id = $matches[0];
                }
                else
                {
                    //If no match, sent the coupon code
                    $dax_coupon_id = $order->getCouponCode();
                }
            }
            $this->_lineNumber++;
            $couponNode  = $itemsNode->addChild('Item');
            $couponNode->line_number = $this->_lineNumber;
            $couponNode->sku = $dax_coupon_id;
            $couponNode->name = '('.$coupon->getCode().') '.$rule->getDescription();
            $couponNode->qty = 1;
            $couponNode->final_price = $this->_formatNumber($order->getDiscountAmount());
            $couponNode->list_price = $this->_formatNumber($order->getDiscountAmount());
        }
    }

    private function _addOrderTaxes(SimpleXMLElement $node, Varien_Object $order)
    {
        $taxNode = $node->addChild('Taxes');
        $taxes = Mage::getResourceModel('sales/order_tax_collection')->loadByOrder($order);

        foreach ($taxes as $tax) {
            $taxItem = $taxNode->addChild('Tax');
            $taxItem->TaxID =    $tax->getCode();
            $taxItem->TaxName =  $tax->getTitle();
            $taxItem->TaxRate =  $this->_formatNumber($tax->getPercent());
            $taxItem->Tax =      $this->_formatNumber($tax->getAmount());
        }
    }

    private function _addOrderItemTaxes(SimpleXMLElement $node, $orderItemId)
    {
        $taxNode = $node->addChild('Taxes');
        $taxes = new Varien_Data_Collection_Db($this->_getConnection());
        $taxes->getSelect()
            ->from(array('ti' => 'sales_order_tax_item'), array('tax_percent', 'taxable_amount'))
            ->join(array('t' => 'sales_order_tax'), 't.tax_id = ti.tax_id', array('code', 'title'))
            ->where('ti.item_id = ?', $orderItemId);

        foreach ($taxes as $tax) {
            $ta = $tax->getTaxableAmount();
            $t = round($ta * $tax->getTaxPercent() / 100, 4);
            $taxItem = $taxNode->addChild('Tax');
            $taxItem->TaxID =          $tax->getCode();
            $taxItem->TaxName =        $tax->getTitle();
            $taxItem->TaxableAmount =  ($ta !== null) ? $this->_formatNumber($ta) : null;
            $taxItem->TaxRate =        $this->_formatNumber($tax->getTaxPercent());
            $taxItem->Tax =            $this->_formatNumber($t);
        }
    }

    /**
     * @return Varien_Db_Adapter_Interface
     */
    private function _getConnection()
    {
        return Mage::getSingleton('core/resource')->getConnection('read');
    }

    private function _formatNumber($number, $decimal = 4)
    {
        return number_format($number, $decimal, '.', '');
    }

    private function _addStreet(SimpleXMLElement $node, Varien_Object $address, $prefix)
    {
        $full = join(' ', $address->getStreet());
        $first = mb_substr($full, 0, 40);
        $second = mb_substr($full, 40, 40);
        $node->{$prefix . '_full'} = $full;
        $node->{$prefix . '_1'} = $first;
        $node->{$prefix . '_2'} = $second;
    }

    private function _addPaymentInfo(SimpleXMLElement $node, Varien_Object $order)
    {
        $methodId = $methodDesc = $typeId = $typeDesc = $cardExp = $cardType = $last4 = $mOrdId = null;
        $method = $order->getPaymentMethod();
        $type = $order->getPaymentCcType();

        if (isset($this->_paymentMethod[$method])) {
            list ($methodId, $methodDesc) = $this->_paymentMethod[$method];
        }
        if ($methodId == 3 && isset($this->_cardType[$type])) {
            list ($typeId, $typeDesc) = $this->_cardType[$type];
            $cardType = $typeDesc;
            $last4 = $order->getPaymentCcLast4();
            $mOrdId = $order->getIncrementId();
        } else {
            $typeId   = 0;
            $typeDesc = $methodDesc;
        }
        try {
            $xparams = $this->_decodeExtraPaymentParams($order);
            if (!empty($xparams['cc_exp_month']) && !empty($xparams['cc_exp_year'])) {
                $cardExp = new DateTime;
                $cardExp->setDate((int)$xparams['cc_exp_year'], (int)$xparams['cc_exp_month'], 1);
                $cardExp = $cardExp->format('m/y');
            } else {
                $cardExp = null;
            }
        } catch (InvalidArgumentException $e) {
            // Shit happens
        }

        $method = $node->addChild('Paymentmethod');
        $method->PaymentMethodID =   $methodId;
        $method->PaymentmethodDesc = $methodDesc;
        $method->PaymentType =       $typeId;
        $method->PaymentTypeDesc =   $typeDesc;

        $node->CardType =    $cardType;
        $node->CardExpire =  $cardExp;
        $node->CheckNumber = $last4;
        $node->MerchantOrderID = $mOrdId;
    }

    private function _decodeExtraPaymentParams(Varien_Object $order)
    {
        $data = $order->getPaymentAdditionalData();
        if (!$data) {
            throw new InvalidArgumentException('Empty additional data.');
        }
        $data = @unserialize($data);
        if (!is_array($data) || !isset($data['pbridge_data']['x_params'])) {
            throw new InvalidArgumentException('Incorrect additional data format.');
        }
        $data = @unserialize($data['pbridge_data']['x_params']);
        if (!$data) {
            throw new InvalidArgumentException('Empty additional data extra params.');
        }

        return $data;
    }

    private function _addShippingInfo(SimpleXMLElement $node, Varien_Object $order)
    {
        $code = $order->getShippingMethod();
        $id = $desc = $invDesc = $active = null;

        if ($code && isset($this->_shippingMethod[$code])) {
            list ($id, $desc, $invDesc, $active) = $this->_shippingMethod[$code];
        }

        $method = $node->addChild('ShipMethod');
        $method->ShippingMethodID = $id;
        $method->Description = $desc;
        $method->InvoiceDesc = $invDesc;
        $method->Active = $active;
    }

    private function _addGiftInfo(SimpleXMLElement $node, Varien_Object $order)
    {
        $node->Giftflag = $order->getGiftMessage() ? 1 : 0;
        $node->Giftmessage = $order->getGiftMessage();
    }

    private function _writeTax($tax, $node)
    {
        $node->code = $tax->getCode();
        $node->title = $tax->getTitle();
        $node->percent = $this->_formatNumber($tax->getpercent());
        $node->amount = $this->_formatNumber($tax->getAmount());
    }

    private function _writeOrderItem(Varien_Object $item, SimpleXMLElement $node)
    {
        $product = Mage::getResourceModel('catalog/product_collection')
            ->addAttributeToSelect('media_format')
            ->addAttributeToFilter('sku', $item->getSku())
            ->getFirstItem();

        $node->line_number = $this->_lineNumber;
        $node->sku = in_array($item->getSku(), $this->_gcSkus) ? self::DAX_GC_SKU : $item->getSku();
        $node->name = trim($item->getName());
        $node->qty = $this->_formatNumber($item->getQtyOrdered());

        $priceItem = $item->getParentItem() ? $item->getParentItem() : $item;
        $node->final_price = $this->_formatNumber($priceItem->getPrice());
        $node->list_price = $this->_formatNumber($priceItem->getOriginalPrice());
        $gcAmount = ($this->_gcBalanceAvailable > $priceItem->getRowTotal())
            ? $priceItem->getRowTotal()
            : $this->_gcBalanceAvailable;

        $node->media_format = $product->getAttributeText('media_format');

        $this->_gcBalanceAvailable -= $gcAmount;
        $node->gift_certificate = $this->_formatNumber($gcAmount);
        $this->_addGiftCertificateInfo($item, $node);

        $node->TaxableFlag = $priceItem->getTaxAmount() ? 1 : 0;
        $node->TaxRate = $this->_formatNumber($priceItem->getTaxPercent());
        $node->Tax = $this->_formatNumber($priceItem->getTaxAmount());
        $this->_addOrderItemTaxes($node, $priceItem->getId());
    }

    private function _addGiftCertificateInfo(Varien_Object $item, SimpleXMLElement $node)
    {
        $options = $item->getProductOptions();
        $code = $type = $rcptName = $rcptEmail = $sndrName = $message = null;

        if (in_array($item->getSku(), $this->_gcSkus))
        {
            $type = $item->getIsVirtual() ? 'email' : 'mail';

            if (is_array($options)) {
                if (isset($options['giftcard_created_codes']) && is_array($options['giftcard_created_codes'])) {
                    $code = reset($options['giftcard_created_codes']);
                }
                if (isset($options['giftcard_recipient_name'])) {
                    $rcptName = $options['giftcard_recipient_name'];
                }
                if (isset($options['giftcard_message'])) {
                    $message = $options['giftcard_message'];
                }
                if (isset($options['giftcard_recipient_email'])) {
                    $rcptEmail = $options['giftcard_recipient_email'];
                }
                if (isset($options['giftcard_sender_name'])) {
                    $sndrName = $options['giftcard_sender_name'];
                }
            }
        }

        $node->GiftcertCode = $code;
        $node->Giftcerttype = $type;
        $node->GiftcertRecipientFirstName = $rcptName;
        $node->addChild('GiftcertRecipientLastName');
        $node->Giftcertmessage = $message;
        $node->Giftcertrecipientemailaddress = $rcptEmail;
        $node->Giftcertsendername = $sndrName;
    }

    private function _transferFile()
    {
        $adapter      = new Tgc_Dax_Model_OrderExport_Io;
        $sftpSettings = $this->_getSftpSettings();
        $sourceFolder = $this->_getLocalFolder();

        $adapter->open($sftpSettings);
        $adapter->cd($sftpSettings['path']);
        foreach (glob($sourceFolder . DS . '*' . self::FILE_EXTENSION) as $file) {
            $source   = $file;
            $fileName = basename($file);
            if ($adapter->write($fileName, $source, NET_SFTP_LOCAL_FILE)) {
                $this->_filesTransferred[] = $file;
            }
        }

        $adapter->close();
    }

    private function _getSftpSettings()
    {
        return array(
            'host'     => Mage::getStoreConfig(self::SFTP_HOST_PATH)
                . ':' . Mage::getStoreConfig(self::SFTP_PORT_PATH),
            'username' => Mage::getStoreConfig(self::SFTP_USER_PATH),
            'password' => Mage::helper('core')->decrypt(Mage::getStoreConfig(self::SFTP_PASSWORD_PATH)),
            'timeout'  => max(Mage::getStoreConfig(self::SFTP_TIMEOUT_PATH), Varien_Io_Sftp::REMOTE_TIMEOUT),
            'path'     => Mage::getStoreConfig(self::SFTP_REMOTE_PATH),
        );
    }

    private function _archiveFile()
    {
        $archiveFolder = $this->_getArchiveFolder();
        $archiveFolder  .= DS . date('Y') . DS . date('m');

        if (!is_dir($archiveFolder)) {
            mkdir($archiveFolder, 0755, true);
        }

        foreach ($this->_filesTransferred as $file) {
            $source   = $file;
            $fileName = basename($file);
            $dest     = $archiveFolder . DS . $fileName;
            $data     = file_get_contents($source);
            $handle   = fopen($dest, 'w');
            $written  = fwrite($handle, $data);
            fclose($handle);

            if ($data === false) {
                throw new DomainException('Unable to open file ' . $source . ' for archiving.');
            } else if (false === $written || 0 == $written) {
                throw new DomainException('Unable to write file ' . $source . ' to archive.');
            }

            @chmod($dest, 0755);
            unlink($source);
        }

        return $this->_getWebArchiveFolder() . DS . $this->_fileName;
    }

    private function _getFileName()
    {
        $this->_fileName = self::FILENAME_PREFIX
            . Mage::app()->getLocale()->date()->toString(self::FILE_DATE_FORMAT)
            . self::FILE_EXTENSION;


        return $this->_getLocalFolder() . DS . $this->_fileName;
    }

    private function _getLocalFolder()
    {
        $localFolder = Mage::getBaseDir('var') . DS . self::LOCAL_EXPORT_PATH;

        if (!is_dir($localFolder)) {
            mkdir($localFolder, 0777, true);
        }

        return $localFolder;
    }

    private function _getArchiveFolder()
    {
        $archiveFolder = Mage::getBaseDir('var') . DS . self::LOCAL_ARCHIVE_PATH;

        if (!is_dir($archiveFolder)) {
            mkdir($archiveFolder, 0777, true);
        }

        return $archiveFolder;
    }

    private function _getWebArchiveFolder()
    {
        $webArchiveFolder = str_replace('/index.php', '', Mage::getBaseUrl());

        $webArchiveFolder .= 'var' . DS . self::LOCAL_ARCHIVE_PATH . DS . date('Y') . DS . date('m');

        return $webArchiveFolder;
    }

    private function _postExport()
    {
        $collection = Mage::getModel('sales/order')
            ->getCollection()
            ->addFieldToFilter('entity_id', array('in' => $this->_orderIds))
            ->addFieldToFilter('is_exported', array('eq' => 0));

        foreach ($collection as $order) {
            $order->setIsExported(1);
            $order->addStatusHistoryComment(
                self::STATUS_HISTORY_COMMENT
            );
            $order->save();
        }
    }

    private function _getOrderCollection()
    {
        /* @var $collection Mage_Sales_Model_Resource_Order_Collection */
        $collection = Mage::getResourceModel('sales/order_collection')
            ->addFieldToFilter('dax_received', 0);

        $collection->getSelect()->joinLeft(
            array('customer' => 'customer_entity'),
            'customer.entity_id = main_table.customer_id',
            array('customer_email' => 'email', 'dax_customer_id', 'web_user_id')
        );

        $collection->getSelect()->joinLeft(
            array('payment' => 'sales_flat_order_payment'),
            'payment.parent_id = main_table.entity_id',
            array(
                'paymentech_profile_id',
                'payment_method'  => 'method',
                'payment_cc_type' => 'cc_type',
                'payment_additional_data' => 'additional_data',
                'payment_cc_last4' => 'cc_last4',
                'payment_transaction_id' => 'gateway_transaction_id',
                'payment_auth_amount' => 'amount_authorized',
                'payment_response_code' => 'resp_code',
                'payment_auth_code' => 'authorization_code',
                'payment_auth_date' => 'resp_date_time',
                'payment_merchant_id' => 'merchant_id',
                'payment_avs_response' => 'avs_resp_code',
            )
        );

        $collection->getSelect()->joinLeft(
            array('gift' => 'gift_message'),
            'gift.gift_message_id = main_table.gift_message_id',
            array('gift_message' => 'message')

        );

        $gstCond = $collection->getConnection()->quoteInto(
            'gst.order_id = main_table.entity_id AND gst.code = ?',
            Tgc_TaxOffice_Model_Tax_Api_Operation_Calculate_TaxLineItem::CANADA_GST_TAX_CODE
        );
        $pstCond = $collection->getConnection()->quoteInto(
            'pst.order_id = main_table.entity_id AND pst.code = ?',
            Tgc_TaxOffice_Model_Tax_Api_Operation_Calculate_TaxLineItem::CANADA_PST_TAX_CODE
        );
        $collection->getSelect()->joinLeft(
            array('gst' => 'sales_order_tax'),
            $gstCond,
            array('gst_amount' => 'gst.amount')
        );
        $collection->getSelect()->joinLeft(
            array('pst' => 'sales_order_tax'),
            $pstCond,
            array('pst_amount' => 'pst.amount')
        );

        return $collection;
    }

    private function _getCsrIdByStoreId($storeId)
    {
        if (isset($this->_websiteNames[$storeId])) {
            return $this->_websiteNames[$storeId];
        }

        $store = Mage::getModel('core/store')->load($storeId);
        $websiteCode = $store->getWebsite()->getCode();
        $csrId = isset($this->_websiteToCsrId[$websiteCode]) ? $this->_websiteToCsrId[$websiteCode] : null;

        return $this->_websiteNames[$storeId] = $csrId;
    }

    private function _getStoreCodeFromStoreId($storeId)
    {
        if (isset($this->_storeCodes[$storeId])) {
            return $this->_storeCodes[$storeId];
        }

        $store = Mage::getModel('core/store')->load($storeId);
        $this->_storeCodes[$storeId] = $store->getCode();

        return $this->_storeCodes[$storeId];
    }

    private function _getRegionCodeByName($region, $countryId)
    {
        return ($this->_getRegion($region, $countryId));
    }

    private function _getRegion($region, $countryId)
    {
        if (!isset($this->_regions[$region])) {
            $this->_regions[$region] = Mage::getModel('directory/region')->loadByName($region, $countryId)->getCode();
        }

        return $this->_regions[$region];
    }

    private function _convertDate($date)
    {
        return date('n/j/Y  g:i:s A', strtotime($date));
    }
}
