<?php
/**
 * "CalculateRequest" API method model
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Tgc_TaxOffice
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_TaxOffice_Model_Tax_Api_Operation_Calculate extends Tgc_TaxOffice_Model_Tax_Api_Operation_Abstract
{
    const SHIPPING_LINE_ITEM_SKU = 'PH';

    /**
     * Calculates tax amounts
     *
     * @param Mage_Sales_Model_Quote_Address $address
     * @param Tgc_TaxOffice_Model_Tax_Api_LineItemCollection $items
     * @param double $shippingAmount
     * @throws Exception
     * @return Tgc_TaxOffice_Model_Tax_Api_Operation_Calculate_Result
     */
    public function execute($address, $items, $shippingAmount)
    {
        $request = new Tgc_TaxOffice_CalculateRequest();
        $this->_prepareRequestDefaults($request);

        $shipFrom = new Tgc_TaxOffice_Address();
        $shipFrom->PostalCode = $this->_preparePostCode(
            trim($this->getConfig()->getShipFromZipCode()),
            trim($this->getConfig()->getShipFromCountryCode())
        );
        $shipFrom->CountryCode = trim($this->getConfig()->getShipFromCountryCode());

        $shipTo = new Tgc_TaxOffice_Address();
        $shipTo->PostalCode = $this->_preparePostCode(
            trim($address->getPostcode()),
            trim($address->getCountryId())
        );
        $shipTo->City = trim($address->getCity());
        $shipTo->CountryCode = trim($address->getCountryId());
        $shipTo->Line1 = trim($address->getStreet1());
        $shipTo->Line2 = trim($address->getStreet2());
        $shipTo->StateOrProvince = trim($address->getRegionCode());

        $orderInfo = new Tgc_TaxOffice_NexusInfo();
        $orderInfo->ShipToAddress = $shipTo;
        $orderInfo->ShipFromAddress = $shipFrom;

        $order = new Tgc_TaxOffice_Order();
        $order->CustomerType = $this->getConfig()->getCustomerType();
        $order->finalize = 0;
        $order->TestTransaction = $this->getConfig()->getTest();
        $order->TransactionID = 0;
        $order->ProviderType = $this->getConfig()->getProvideType();
        $order->InvoiceDate = Mage::app()->getLocale()->date()->toString('c');
        $order->NexusInfo = $orderInfo;
        $order->LineItems = $items->convertLineItemsIntoRequest();
        $order->LineItems[] = $this->_convertShipmentToLineItem($shippingAmount);

        $request->order = $order;

        if ($this->getConfig()->getDebugMode()) {
            $this->_debugData['Request'] = $request;
        }

        try {
            $result = $this->getClient()->CalculateRequest($request);
        } catch (Exception $e) {

            if ($this->getConfig()->getDebugMode()) {
                $this->_debugData['SOAP Request'] = $this->getClient()->__getLastRequest();
                $this->_debugData['SOAP Response'] = $this->getClient()->__getLastResponse();
                $this->_debugData['Exception'] = (string)$e;

                $this->_log();
            }

            throw $e;
        }

        if ($this->getConfig()->getDebugMode()) {
            $this->_debugData['Result'] = $result;
			$this->_debugData['SOAP Request'] = $this->getClient()->__getLastRequest();
			$this->_debugData['SOAP Response'] = $this->getClient()->__getLastResponse();
            $this->_log();
        }

        return Mage::getModel('tgc_taxOffice/tax_api_operation_calculate_result', array('result' => $result));
    }

    /**
     * Prepares postcode for the API request.
     * Is needed for avoiding submitting of wrong postcodes by customers.
     *
     * @param string $postcode
     * @param string $country
     * @return string
     */
    protected function _preparePostCode($postcode, $country)
    {
        $formatter = Mage::getSingleton('tgc_taxOffice/tax_api_operation_postcode_factory')
            ->getFormatter($country);
        if ($formatter) {
            return $formatter->format($postcode);
        }
        return $postcode;
    }

    /**
     * Converts shipping amount into tax line item
     *
     * @param double $shippingAmount
     * @return Tgc_TaxOffice_LineItem
     */
    protected function _convertShipmentToLineItem($shippingAmount)
    {
        $shippingLineItem = new Tgc_TaxOffice_LineItem();
        $shippingLineItem->Amount = $shippingAmount;
        $shippingLineItem->AvgUnitPrice = $shippingAmount;
        $shippingLineItem->Quantity = 1;
        $shippingLineItem->ID = self::SHIPPING_LINE_ITEM_SKU;
        $shippingLineItem->SKU = self::SHIPPING_LINE_ITEM_SKU;
        return $shippingLineItem;
    }
}
