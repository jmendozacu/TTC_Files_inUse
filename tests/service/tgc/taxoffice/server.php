<?php
/**
 * Description
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    ${MAGENTO_MODULE_NAMESPACE}
 * @package     ${MAGENTO_MODULE_NAMESPACE}_${MAGENTO_MODULE}
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
//require_once (dirname(__FILE__) . '/../../../../app/Mage.php');

if (empty($_SERVER) || empty($_SERVER['HTTP_HOST'])) {
    exit(1);
}

class SOAP_Service_Secure
{
     protected $class_name    = '';

     // -----

     public function __construct($class_name)
     {
         $this->class_name = $class_name;
     }

     public function __call($method_name, $arguments)
     {
        // file_put_contents(dirname(__FILE__) . '\\f.log', print_r($method_name, true) . "\n" . print_r($arguments, true), FILE_APPEND);

         if(!method_exists($this->class_name, $method_name)){
             throw new Exception('method not found: '.$this->class_name.':'.$method_name);
         }

         try {
            $result = call_user_func_array(array($this->class_name, $method_name), $arguments);
         } catch (SoapFault $e) {
             //file_put_contents(dirname(__FILE__) . '\\f.log', (string)$e, FILE_APPEND);
             throw $e;
         } catch (Exception $e) {
             //file_put_contents(dirname(__FILE__) . '\\f.log', (string)$e, FILE_APPEND);
             throw new SoapFault(999, $e->getMessage());
         }

         return $result;
     }
 }

class TaxOffice
{
    private static $entityId = 'AX STO';
    private static $divisionId = 'SCO';
    private static $shipFrom = '20151';
    private static $TestTransaction = 1;
    private static $CustomerType = "08";
    private static $ProviderType = "70";

    public function CalculateRequest($arg)
    {
        //file_put_contents(dirname(__FILE__) . '\\f.log', print_r($arg, true), FILE_APPEND);
        if ($arg->EntityID != self::$entityId) {
            throw new SoapFault(1, 'EntityID doesn\'t match');
        }

        if ($arg->DivisionID != self::$divisionId) {
            throw new SoapFault(2, 'DivisionID doesn\'t match');
        }

        if ($arg->order->NexusInfo->ShipFromAddress->PostalCode != self::$shipFrom) {
            throw new SoapFault(3, 'ShipFrom zip code doesn\'t match');
        }

        //validate ShipToAddress
        $address = array(
            'Line1' => '59 Lexington street',
            'Line2' => '',
            'City' => 'New York',
            'StateOrProvince' => 'NY',
            'PostalCode' => '10010',
            'CountryCode' => 'US'
        );

        $shipToAddress = $arg->order->NexusInfo->ShipToAddress;
        /*
        foreach ($address as $key => $val) {
            if ($shipToAddress->{$key} != $val) {
                throw new SoapFault(4, "ShipTo '$key' field is invalid");
            }
        }
        */

        if ($arg->order->TestTransaction != self::$TestTransaction ||
            $arg->order->CustomerType != self::$CustomerType ||
            $arg->order->ProviderType != self::$ProviderType
        ) {
            throw new SoapFault(5, "Order params are wrong");
        }

        $taxPercent = 0.07;

        $lineItems = $arg->order->LineItems->Struct;
        if (!is_array($lineItems)) {
            $lineItems = array($lineItems);
        }

        $taxes = new ArrayObject();
        $total = 0;
        foreach ($lineItems as $lineItem) {
            $totalItemTax = 0;
            if ($shipToAddress->CountryCode == 'CA') {
                //tax GST
                $taxDetails = new ArrayObject();
                $newlineItem = new stdClass();
                $newlineItem->AuthorityName = 'CANADA REVENUE AGENCY';
                $newlineItem->AuthorityType = '0';
                $newlineItem->TaxApplied = round($lineItem->Amount * $taxPercent, 2);
                $newlineItem->TaxName = 'GOODS AND SERVICES TAX (GST)-GENERAL MERCHANDISE';
                $newlineItem->TaxRate = $taxPercent;
                $newlineItem->TaxableAmount = $lineItem->Amount;
                $newlineItem->TaxableQuantity = $lineItem->Quantity;
                $taxDetails->append(new SoapVar($newlineItem, SOAP_ENC_OBJECT, 'TaxDetail', null, 'TaxDetail'));
                $total += $newlineItem->TaxApplied;
                $totalItemTax += $newlineItem->TaxApplied;

                //tax PST
                $newlineItem = new stdClass();
                $newlineItem->AuthorityName = 'ONTARIO, PROVINCE OF';
                $newlineItem->AuthorityType = '1';
                $newlineItem->TaxApplied = round($lineItem->Amount * (0.15), 2);
                $newlineItem->TaxName = 'PROVINCIAL SALES TAX (PST)-GENERAL MERCHANDISE';
                $newlineItem->TaxRate = (0.15);
                $newlineItem->TaxableAmount = $lineItem->Amount;
                $newlineItem->TaxableQuantity = $lineItem->Quantity;
                $taxDetails->append(new SoapVar($newlineItem, SOAP_ENC_OBJECT, 'TaxDetail', null, 'TaxDetail'));
                $total += $newlineItem->TaxApplied;
                $totalItemTax += $newlineItem->TaxApplied;
            } else {
                $newlineItem = new stdClass();
                $newlineItem->AuthorityName = 'INDIANA';
                $newlineItem->AuthorityType = '1';
                $newlineItem->TaxApplied = round($lineItem->Amount * $taxPercent, 2);
                $newlineItem->TaxName = 'US MERCHANDIZING TAX';
                $newlineItem->TaxRate = $taxPercent;
                $newlineItem->TaxableAmount = $lineItem->Amount;
                $newlineItem->TaxableQuantity = $lineItem->Quantity;

                $taxDetails = new stdClass();
                $taxDetails->TaxDetail = $newlineItem;
                $total += $newlineItem->TaxApplied;
                $totalItemTax += $newlineItem->TaxApplied;
            }

            $taxData = new stdClass();
            $taxData->CountryCode = $shipToAddress->CountryCode;
            $taxData->ID = $lineItem->ID;
            $taxData->StateOrProvince = $shipToAddress->StateOrProvince;
            $taxData->TaxDetails = $taxDetails;
            $taxData->TotalTaxApplied = $totalItemTax;

            $taxes->append(new SoapVar($taxData, SOAP_ENC_OBJECT, 'LineItemTax', null, 'LineItemTax'));
        }

        $taxResult = new stdClass();
        $taxResult->TransactionStatus = 4;
        $taxResult->TransactionID = 1000;
        $taxResult->TotalTaxApplied = round($total, 2);
        $taxResult->LineItemTaxes = $taxes;

        $response = new stdClass();
        $response->CalculateRequestResult = $taxResult;
        return $response;
    }
}


$host = $_SERVER['HTTP_HOST'];

if (!empty($_REQUEST['wsdl'])) {
    $wsdl = file_get_contents(dirname(__FILE__) . '/STOService3_5.xml');
    $wsdl = str_replace('{{{host}}}', $host, $wsdl);
    echo $wsdl;
} else {
    $wsdl = 'http://'. $host . '/tests/service/tgc/taxoffice/server.php?wsdl=1';

    $Service = new SOAP_Service_Secure('TaxOffice');

    $Server = new SoapServer($wsdl);

    $Server->setObject($Service);

    $Server->handle();
}