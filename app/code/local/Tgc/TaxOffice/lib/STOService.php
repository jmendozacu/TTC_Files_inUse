<?php

if (!class_exists("Tgc_TaxOffice_CalculateProcurementRequest", false)) {
/**
 * Tgc_TaxOffice_CalculateProcurementRequest
 */
class Tgc_TaxOffice_CalculateProcurementRequest {
	/**
	 * @access public
	 * @var string
	 */
	public $EntityID;
	/**
	 * @access public
	 * @var string
	 */
	public $DivisionID;
	/**
	 * @access public
	 * @var Tgc_TaxOffice_ProcurementOrder
	 */
	public $ProcurementOrder;
}}

if (!class_exists("Tgc_TaxOffice_CalculateProcurementRequestResponse", false)) {
/**
 * Tgc_TaxOffice_CalculateProcurementRequestResponse
 */
class Tgc_TaxOffice_CalculateProcurementRequestResponse {
	/**
	 * @access public
	 * @var Tgc_TaxOffice_TaxResponse
	 */
	public $CalculateProcurementRequestResult;
}}

if (!class_exists("Tgc_TaxOffice_UnattributedProcurementReturnRequest", false)) {
/**
 * Tgc_TaxOffice_UnattributedProcurementReturnRequest
 */
class Tgc_TaxOffice_UnattributedProcurementReturnRequest {
	/**
	 * @access public
	 * @var string
	 */
	public $EntityID;
	/**
	 * @access public
	 * @var string
	 */
	public $DivisionID;
	/**
	 * @access public
	 * @var Tgc_TaxOffice_ProcurementOrder
	 */
	public $ProcurementOrder;
}}

if (!class_exists("Tgc_TaxOffice_UnattributedProcurementReturnRequestResponse", false)) {
/**
 * Tgc_TaxOffice_UnattributedProcurementReturnRequestResponse
 */
class Tgc_TaxOffice_UnattributedProcurementReturnRequestResponse {
	/**
	 * @access public
	 * @var Tgc_TaxOffice_TaxResponse
	 */
	public $UnattributedProcurementReturnRequestResult;
}}

if (!class_exists("Tgc_TaxOffice_CalculateRequest", false)) {
/**
 * Tgc_TaxOffice_CalculateRequest
 */
class Tgc_TaxOffice_CalculateRequest {
	/**
	 * @access public
	 * @var string
	 */
	public $EntityID;
	/**
	 * @access public
	 * @var string
	 */
	public $DivisionID;
	/**
	 * @access public
	 * @var Tgc_TaxOffice_Order
	 */
	public $order;
}}

if (!class_exists("Tgc_TaxOffice_CalculateRequestResponse", false)) {
/**
 * Tgc_TaxOffice_CalculateRequestResponse
 */
class Tgc_TaxOffice_CalculateRequestResponse {
	/**
	 * @access public
	 * @var Tgc_TaxOffice_TaxResponse
	 */
	public $CalculateRequestResult;
}}

if (!class_exists("Tgc_TaxOffice_CancelRequest", false)) {
/**
 * Tgc_TaxOffice_CancelRequest
 */
class Tgc_TaxOffice_CancelRequest {
	/**
	 * @access public
	 * @var string
	 */
	public $EntityID;
	/**
	 * @access public
	 * @var string
	 */
	public $DivisionID;
	/**
	 * @access public
	 * @var Tgc_TaxOffice_unsignedLong
	 */
	public $OriginalTransactionID;
}}

if (!class_exists("Tgc_TaxOffice_CancelRequestResponse", false)) {
/**
 * Tgc_TaxOffice_CancelRequestResponse
 */
class Tgc_TaxOffice_CancelRequestResponse {
	/**
	 * @access public
	 * @var Tgc_TaxOffice_TransactionDetail
	 */
	public $CancelRequestResult;
}}

if (!class_exists("Tgc_TaxOffice_FinalizeRequest", false)) {
/**
 * Tgc_TaxOffice_FinalizeRequest
 */
class Tgc_TaxOffice_FinalizeRequest {
	/**
	 * @access public
	 * @var string
	 */
	public $EntityID;
	/**
	 * @access public
	 * @var string
	 */
	public $DivisionID;
	/**
	 * @access public
	 * @var Tgc_TaxOffice_unsignedLong
	 */
	public $OriginalTransactionID;
}}

if (!class_exists("Tgc_TaxOffice_FinalizeRequestResponse", false)) {
/**
 * Tgc_TaxOffice_FinalizeRequestResponse
 */
class Tgc_TaxOffice_FinalizeRequestResponse {
	/**
	 * @access public
	 * @var Tgc_TaxOffice_TransactionDetail
	 */
	public $FinalizeRequestResult;
}}

if (!class_exists("Tgc_TaxOffice_GeoblockRequest", false)) {
/**
 * Tgc_TaxOffice_GeoblockRequest
 */
class Tgc_TaxOffice_GeoblockRequest {
	/**
	 * @access public
	 * @var dateTime
	 */
	public $InvoiceDate;
	/**
	 * @access public
	 * @var Tgc_TaxOffice_Address
	 */
	public $address;
}}

if (!class_exists("Tgc_TaxOffice_GeoblockRequestResponse", false)) {
/**
 * Tgc_TaxOffice_GeoblockRequestResponse
 */
class Tgc_TaxOffice_GeoblockRequestResponse {
	/**
	 * @access public
	 * @var Tgc_TaxOffice_GeoblockInfo
	 */
	public $GeoblockRequestResult;
}}

if (!class_exists("Tgc_TaxOffice_UnattributedReturnRequest", false)) {
/**
 * Tgc_TaxOffice_UnattributedReturnRequest
 */
class Tgc_TaxOffice_UnattributedReturnRequest {
	/**
	 * @access public
	 * @var string
	 */
	public $EntityID;
	/**
	 * @access public
	 * @var string
	 */
	public $DivisionID;
	/**
	 * @access public
	 * @var Tgc_TaxOffice_Order
	 */
	public $order;
}}

if (!class_exists("Tgc_TaxOffice_UnattributedReturnRequestResponse", false)) {
/**
 * Tgc_TaxOffice_UnattributedReturnRequestResponse
 */
class Tgc_TaxOffice_UnattributedReturnRequestResponse {
	/**
	 * @access public
	 * @var Tgc_TaxOffice_TaxResponse
	 */
	public $UnattributedReturnRequestResult;
}}

if (!class_exists("Tgc_TaxOffice_AttributedFullReturnRequest", false)) {
/**
 * Tgc_TaxOffice_AttributedFullReturnRequest
 */
class Tgc_TaxOffice_AttributedFullReturnRequest {
	/**
	 * @access public
	 * @var string
	 */
	public $EntityID;
	/**
	 * @access public
	 * @var string
	 */
	public $DivisionID;
	/**
	 * @access public
	 * @var Tgc_TaxOffice_unsignedLong
	 */
	public $OriginalTransactionID;
	/**
	 * @access public
	 * @var string
	 */
	public $SourceSystem;
	/**
	 * @access public
	 * @var string
	 */
	public $InvoiceID;
	/**
	 * @access public
	 * @var dateTime
	 */
	public $InvoiceDate;
	/**
	 * @access public
	 * @var string
	 */
	public $TransactionDescription;
}}

if (!class_exists("Tgc_TaxOffice_AttributedFullReturnRequestResponse", false)) {
/**
 * Tgc_TaxOffice_AttributedFullReturnRequestResponse
 */
class Tgc_TaxOffice_AttributedFullReturnRequestResponse {
	/**
	 * @access public
	 * @var Tgc_TaxOffice_TaxResponse
	 */
	public $AttributedFullReturnRequestResult;
}}

if (!class_exists("Tgc_TaxOffice_PartialReturnRequest", false)) {
/**
 * Tgc_TaxOffice_PartialReturnRequest
 */
class Tgc_TaxOffice_PartialReturnRequest {
	/**
	 * @access public
	 * @var string
	 */
	public $EntityID;
	/**
	 * @access public
	 * @var string
	 */
	public $DivisionID;
	/**
	 * @access public
	 * @var Tgc_TaxOffice_PartialReturnOrder
	 */
	public $order;
}}

if (!class_exists("Tgc_TaxOffice_PartialReturnRequestResponse", false)) {
/**
 * Tgc_TaxOffice_PartialReturnRequestResponse
 */
class Tgc_TaxOffice_PartialReturnRequestResponse {
	/**
	 * @access public
	 * @var Tgc_TaxOffice_TaxResponse
	 */
	public $PartialReturnRequestResult;
}}

if (!class_exists("Tgc_TaxOffice_TaxAdjustmentRequest", false)) {
/**
 * Tgc_TaxOffice_TaxAdjustmentRequest
 */
class Tgc_TaxOffice_TaxAdjustmentRequest {
	/**
	 * @access public
	 * @var string
	 */
	public $EntityID;
	/**
	 * @access public
	 * @var string
	 */
	public $DivisionId;
	/**
	 * @access public
	 * @var Tgc_TaxOffice_TaxAdjustmentOrder
	 */
	public $TaxAdjustmentOrder;
}}

if (!class_exists("Tgc_TaxOffice_TaxAdjustmentRequestResponse", false)) {
/**
 * Tgc_TaxOffice_TaxAdjustmentRequestResponse
 */
class Tgc_TaxOffice_TaxAdjustmentRequestResponse {
	/**
	 * @access public
	 * @var Tgc_TaxOffice_TaxResponse
	 */
	public $TaxAdjustmentRequestResult;
}}

if (!class_exists("Tgc_TaxOffice_SystemDateTimeRequest", false)) {
/**
 * Tgc_TaxOffice_SystemDateTimeRequest
 */
class Tgc_TaxOffice_SystemDateTimeRequest {
}}

if (!class_exists("Tgc_TaxOffice_SystemDateTimeRequestResponse", false)) {
/**
 * Tgc_TaxOffice_SystemDateTimeRequestResponse
 */
class Tgc_TaxOffice_SystemDateTimeRequestResponse {
	/**
	 * @access public
	 * @var dateTime
	 */
	public $SystemDateTimeRequestResult;
}}

if (!class_exists("Tgc_TaxOffice_GetDataValues", false)) {
/**
 * Tgc_TaxOffice_GetDataValues
 */
class Tgc_TaxOffice_GetDataValues {
	/**
	 * @access public
	 * @var string
	 */
	public $DataType;
	/**
	 * @access public
	 * @var string
	 */
	public $Parameter1;
	/**
	 * @access public
	 * @var string
	 */
	public $Parameter2;
}}

if (!class_exists("Tgc_TaxOffice_GetDataValuesResponse", false)) {
/**
 * Tgc_TaxOffice_GetDataValuesResponse
 */
class Tgc_TaxOffice_GetDataValuesResponse {
	/**
	 * @access public
	 * @var Tgc_TaxOffice_DataValues
	 */
	public $GetDataValuesResult;
}}

if (!class_exists("Tgc_TaxOffice_CreateSKU", false)) {
/**
 * Tgc_TaxOffice_CreateSKU
 */
class Tgc_TaxOffice_CreateSKU {
	/**
	 * @access public
	 * @var Tgc_TaxOffice_ArrayOfSKUDetail
	 */
	public $SKUData;
}}

if (!class_exists("Tgc_TaxOffice_CreateSKUResponse", false)) {
/**
 * Tgc_TaxOffice_CreateSKUResponse
 */
class Tgc_TaxOffice_CreateSKUResponse {
	/**
	 * @access public
	 * @var Tgc_TaxOffice_Message
	 */
	public $CreateSKUResult;
}}

if (!class_exists("Tgc_TaxOffice_GeoblockRequestAll", false)) {
/**
 * Tgc_TaxOffice_GeoblockRequestAll
 */
class Tgc_TaxOffice_GeoblockRequestAll {
	/**
	 * @access public
	 * @var dateTime
	 */
	public $InvoiceDate;
	/**
	 * @access public
	 * @var Tgc_TaxOffice_Address
	 */
	public $address;
}}

if (!class_exists("Tgc_TaxOffice_GeoblockRequestAllResponse", false)) {
/**
 * Tgc_TaxOffice_GeoblockRequestAllResponse
 */
class Tgc_TaxOffice_GeoblockRequestAllResponse {
	/**
	 * @access public
	 * @var Tgc_TaxOffice_ArrayOfGeoblockInfo
	 */
	public $GeoblockRequestAllResult;
}}

if (!class_exists("Tgc_TaxOffice_CreateCustomerCertificateRequest", false)) {
/**
 * Tgc_TaxOffice_CreateCustomerCertificateRequest
 */
class Tgc_TaxOffice_CreateCustomerCertificateRequest {
	/**
	 * @access public
	 * @var Tgc_TaxOffice_CustomerCertificateRequest
	 */
	public $CustomerCertificateRequest;
}}

if (!class_exists("Tgc_TaxOffice_CreateCustomerCertificateRequestResponse", false)) {
/**
 * Tgc_TaxOffice_CreateCustomerCertificateRequestResponse
 */
class Tgc_TaxOffice_CreateCustomerCertificateRequestResponse {
	/**
	 * @access public
	 * @var Tgc_TaxOffice_Message
	 */
	public $CreateCustomerCertificateRequestResult;
}}

if (!class_exists("Tgc_TaxOffice_ExportCustomerCertificateRequest", false)) {
/**
 * Tgc_TaxOffice_ExportCustomerCertificateRequest
 */
class Tgc_TaxOffice_ExportCustomerCertificateRequest {
	/**
	 * @access public
	 * @var string
	 */
	public $EntityID;
	/**
	 * @access public
	 * @var string
	 */
	public $DivisionID;
}}

if (!class_exists("Tgc_TaxOffice_ExportCustomerCertificateRequestResponse", false)) {
/**
 * Tgc_TaxOffice_ExportCustomerCertificateRequestResponse
 */
class Tgc_TaxOffice_ExportCustomerCertificateRequestResponse {
	/**
	 * @access public
	 * @var Tgc_TaxOffice_CustomerCertificatesExport
	 */
	public $ExportCustomerCertificateRequestResult;
}}

if (!class_exists("Tgc_TaxOffice_ExportCustomerCertificateFilterRequest", false)) {
/**
 * Tgc_TaxOffice_ExportCustomerCertificateFilterRequest
 */
class Tgc_TaxOffice_ExportCustomerCertificateFilterRequest {
	/**
	 * @access public
	 * @var string
	 */
	public $EntityID;
	/**
	 * @access public
	 * @var string
	 */
	public $DivisionID;
	/**
	 * @access public
	 * @var Tgc_TaxOffice_CertificateFilter
	 */
	public $certfilter;
}}

if (!class_exists("Tgc_TaxOffice_ExportCustomerCertificateFilterRequestResponse", false)) {
/**
 * Tgc_TaxOffice_ExportCustomerCertificateFilterRequestResponse
 */
class Tgc_TaxOffice_ExportCustomerCertificateFilterRequestResponse {
	/**
	 * @access public
	 * @var Tgc_TaxOffice_CustomerCertificatesExport
	 */
	public $ExportCustomerCertificateFilterRequestResult;
}}

if (!class_exists("Tgc_TaxOffice_GetTransactionTax", false)) {
/**
 * Tgc_TaxOffice_GetTransactionTax
 */
class Tgc_TaxOffice_GetTransactionTax {
	/**
	 * @access public
	 * @var Tgc_TaxOffice_TransactionTax
	 */
	public $request;
}}

if (!class_exists("Tgc_TaxOffice_GetTransactionTaxResponse", false)) {
/**
 * Tgc_TaxOffice_GetTransactionTaxResponse
 */
class Tgc_TaxOffice_GetTransactionTaxResponse {
	/**
	 * @access public
	 * @var Tgc_TaxOffice_TaxResponse
	 */
	public $GetTransactionTaxResult;
}}

if (!class_exists("Tgc_TaxOffice_GetTaxRates", false)) {
/**
 * Tgc_TaxOffice_GetTaxRates
 */
class Tgc_TaxOffice_GetTaxRates {
	/**
	 * @access public
	 * @var Tgc_TaxOffice_TaxLiabilityRequest
	 */
	public $request;
}}

if (!class_exists("Tgc_TaxOffice_GetTaxRatesResponse", false)) {
/**
 * Tgc_TaxOffice_GetTaxRatesResponse
 */
class Tgc_TaxOffice_GetTaxRatesResponse {
	/**
	 * @access public
	 * @var Tgc_TaxOffice_TaxLiabilityResponse
	 */
	public $GetTaxRatesResult;
}}

if (!class_exists("Tgc_TaxOffice_FaultExceptionFaultReasonData", false)) {
/**
 * Tgc_TaxOffice_FaultExceptionFaultReasonData
 */
class Tgc_TaxOffice_FaultExceptionFaultReasonData {
	/**
	 * @access public
	 * @var string
	 */
	public $text;
	/**
	 * @access public
	 * @var string
	 */
	public $xmlLang;
}}

if (!class_exists("Tgc_TaxOffice_FaultExceptionFaultCodeData", false)) {
/**
 * Tgc_TaxOffice_FaultExceptionFaultCodeData
 */
class Tgc_TaxOffice_FaultExceptionFaultCodeData {
	/**
	 * @access public
	 * @var string
	 */
	public $name;
	/**
	 * @access public
	 * @var string
	 */
	public $ns;
}}

if (!class_exists("Tgc_TaxOffice_char", false)) {
/**
 * Tgc_TaxOffice_char
 */
class Tgc_TaxOffice_char {
}}

if (!class_exists("Tgc_TaxOffice_duration", false)) {
/**
 * Tgc_TaxOffice_duration
 */
class Tgc_TaxOffice_duration {
}}

if (!class_exists("Tgc_TaxOffice_guid", false)) {
/**
 * Tgc_TaxOffice_guid
 */
class Tgc_TaxOffice_guid {
}}

if (!class_exists("Tgc_TaxOffice_ProcurementOrder", false)) {
/**
 * Tgc_TaxOffice_ProcurementOrder
 */
class Tgc_TaxOffice_ProcurementOrder {
	/**
	 * @access public
	 * @var dateTime
	 */
	public $InvoiceDate;
	/**
	 * @access public
	 * @var string
	 */
	public $InvoiceID;
	/**
	 * @access public
	 * @var Tgc_TaxOffice_ArrayOfProcurementLineItem
	 */
	public $ProcurementLineItems;
	/**
	 * @access public
	 * @var string
	 */
	public $ProviderType;
	/**
	 * @access public
	 * @var string
	 */
	public $SourceSystem;
	/**
	 * @access public
	 * @var bool
	 */
	public $TestTransaction;
	/**
	 * @access public
	 * @var string
	 */
	public $TransactionDescription;
	/**
	 * @access public
	 * @var Tgc_TaxOffice_unsignedLong
	 */
	public $TransactionID;
	/**
	 * @access public
	 * @var string
	 */
	public $VendorID;
	/**
	 * @access public
	 * @var bool
	 */
	public $finalize;
	/**
	 * @access public
	 * @var dateTime
	 */
	public $TransactionDate;
}}

if (!class_exists("Tgc_TaxOffice_ProcurementLineItem", false)) {
/**
 * Tgc_TaxOffice_ProcurementLineItem
 */
class Tgc_TaxOffice_ProcurementLineItem {
	/**
	 * @access public
	 * @var double
	 */
	public $Amount;
	/**
	 * @access public
	 * @var double
	 */
	public $AvgUnitPrice;
	/**
	 * @access public
	 * @var string
	 */
	public $DataString;
	/**
	 * @access public
	 * @var string
	 */
	public $DataStringID;
	/**
	 * @access public
	 * @var string
	 */
	public $ExemptionCode;
	/**
	 * @access public
	 * @var string
	 */
	public $ID;
	/**
	 * @access public
	 * @var string
	 */
	public $ProviderType;
	/**
	 * @access public
	 * @var double
	 */
	public $Quantity;
	/**
	 * @access public
	 * @var string
	 */
	public $SKU;
	/**
	 * @access public
	 * @var string
	 */
	public $UserData;
}}

if (!class_exists("Tgc_TaxOffice_TaxResponse", false)) {
/**
 * Tgc_TaxOffice_TaxResponse
 */
class Tgc_TaxOffice_TaxResponse {
	/**
	 * @access public
	 * @var Tgc_TaxOffice_ArrayOfLineItemTax
	 */
	public $LineItemTaxes;
	/**
	 * @access public
	 * @var Tgc_TaxOffice_ArrayOfMessage
	 */
	public $Messages;
	/**
	 * @access public
	 * @var double
	 */
	public $TotalTaxApplied;
	/**
	 * @access public
	 * @var Tgc_TaxOffice_unsignedLong
	 */
	public $TransactionID;
	/**
	 * @access public
	 * @var integer
	 */
	public $TransactionStatus;
}}

if (!class_exists("Tgc_TaxOffice_LineItemTax", false)) {
/**
 * Tgc_TaxOffice_LineItemTax
 */
class Tgc_TaxOffice_LineItemTax {
	/**
	 * @access public
	 * @var string
	 */
	public $CountryCode;
	/**
	 * @access public
	 * @var string
	 */
	public $ID;
	/**
	 * @access public
	 * @var string
	 */
	public $StateOrProvince;
	/**
	 * @access public
	 * @var Tgc_TaxOffice_ArrayOfTaxDetail
	 */
	public $TaxDetails;
	/**
	 * @access public
	 * @var double
	 */
	public $TotalTaxApplied;
}}

if (!class_exists("Tgc_TaxOffice_TaxDetail", false)) {
/**
 * Tgc_TaxOffice_TaxDetail
 */
class Tgc_TaxOffice_TaxDetail {
	/**
	 * @access public
	 * @var string
	 */
	public $AuthorityName;
	/**
	 * @access public
	 * @var string
	 */
	public $AuthorityType;
	/**
	 * @access public
	 * @var string
	 */
	public $BaseType;
	/**
	 * @access public
	 * @var double
	 */
	public $ExemptAmt;
	/**
	 * @access public
	 * @var double
	 */
	public $ExemptQty;
	/**
	 * @access public
	 * @var double
	 */
	public $FeeApplied;
	/**
	 * @access public
	 * @var string
	 */
	public $PassFlag;
	/**
	 * @access public
	 * @var string
	 */
	public $PassType;
	/**
	 * @access public
	 * @var double
	 */
	public $TaxApplied;
	/**
	 * @access public
	 * @var string
	 */
	public $TaxName;
	/**
	 * @access public
	 * @var double
	 */
	public $TaxRate;
	/**
	 * @access public
	 * @var double
	 */
	public $TaxableAmount;
	/**
	 * @access public
	 * @var double
	 */
	public $TaxableQuantity;
}}

if (!class_exists("Tgc_TaxOffice_Message", false)) {
/**
 * Tgc_TaxOffice_Message
 */
class Tgc_TaxOffice_Message {
	/**
	 * @access public
	 * @var integer
	 */
	public $Code;
	/**
	 * @access public
	 * @var string
	 */
	public $Info;
	/**
	 * @access public
	 * @var string
	 */
	public $Reference;
	/**
	 * @access public
	 * @var integer
	 */
	public $Severity;
	/**
	 * @access public
	 * @var integer
	 */
	public $Source;
	/**
	 * @access public
	 * @var integer
	 */
	public $TransactionStatus;
}}

if (!class_exists("Tgc_TaxOffice_Tgc_TaxOffice_Exception", false)) {
/**
 * Tgc_TaxOffice_Tgc_TaxOffice_Exception
 */
class Tgc_TaxOffice_Tgc_TaxOffice_Exception {
}}

if (!class_exists("Tgc_TaxOffice_Order", false)) {
/**
 * Tgc_TaxOffice_Order
 */
class Tgc_TaxOffice_Order {
	/**
	 * @access public
	 * @var string
	 */
	public $CustomerID;
	/**
	 * @access public
	 * @var string
	 */
	public $CustomerType;
	/**
	 * @access public
	 * @var dateTime
	 */
	public $InvoiceDate;
	/**
	 * @access public
	 * @var string
	 */
	public $InvoiceID;
	/**
	 * @access public
	 * @var Tgc_TaxOffice_ArrayOfLineItem
	 */
	public $LineItems;
	/**
	 * @access public
	 * @var Tgc_TaxOffice_NexusInfo
	 */
	public $NexusInfo;
	/**
	 * @access public
	 * @var string
	 */
	public $ProviderType;
	/**
	 * @access public
	 * @var Tgc_TaxOffice_SitusInfo
	 */
	public $SitusInfo;
	/**
	 * @access public
	 * @var string
	 */
	public $SourceSystem;
	/**
	 * @access public
	 * @var bool
	 */
	public $TestTransaction;
	/**
	 * @access public
	 * @var string
	 */
	public $TransactionDescription;
	/**
	 * @access public
	 * @var Tgc_TaxOffice_unsignedLong
	 */
	public $TransactionID;
	/**
	 * @access public
	 * @var string
	 */
	public $TransactionType;
	/**
	 * @access public
	 * @var bool
	 */
	public $finalize;
	/**
	 * @access public
	 * @var dateTime
	 */
	public $TransactionDate;
}}

if (!class_exists("Tgc_TaxOffice_LineItem", false)) {
/**
 * Tgc_TaxOffice_LineItem
 */
class Tgc_TaxOffice_LineItem {
	/**
	 * @access public
	 * @var double
	 */
	public $Amount;
	/**
	 * @access public
	 * @var double
	 */
	public $AvgUnitPrice;
	/**
	 * @access public
	 * @var string
	 */
	public $CustomerType;
	/**
	 * @access public
	 * @var string
	 */
	public $DataString;
	/**
	 * @access public
	 * @var string
	 */
	public $ExemptionCode;
	/**
	 * @access public
	 * @var string
	 */
	public $ID;
	/**
	 * @access public
	 * @var Tgc_TaxOffice_NexusInfo
	 */
	public $NexusInfo;
	/**
	 * @access public
	 * @var Tgc_TaxOffice_ProductInfo
	 */
	public $ProductInfo;
	/**
	 * @access public
	 * @var string
	 */
	public $ProviderType;
	/**
	 * @access public
	 * @var double
	 */
	public $Quantity;
	/**
	 * @access public
	 * @var string
	 */
	public $SKU;
	/**
	 * @access public
	 * @var Tgc_TaxOffice_SitusInfo
	 */
	public $SitusInfo;
	/**
	 * @access public
	 * @var string
	 */
	public $TransactionType;
	/**
	 * @access public
	 * @var string
	 */
	public $UserData;
}}

if (!class_exists("Tgc_TaxOffice_NexusInfo", false)) {
/**
 * Tgc_TaxOffice_NexusInfo
 */
class Tgc_TaxOffice_NexusInfo {
	/**
	 * @access public
	 * @var Tgc_TaxOffice_Address
	 */
	public $OrderApprovalAddress;
	/**
	 * @access public
	 * @var string
	 */
	public $OrderApprovalGeoBlock;
	/**
	 * @access public
	 * @var Tgc_TaxOffice_Address
	 */
	public $OrderPlacementAddress;
	/**
	 * @access public
	 * @var string
	 */
	public $OrderPlacementGeoBlock;
	/**
	 * @access public
	 * @var Tgc_TaxOffice_Address
	 */
	public $ShipFromAddress;
	/**
	 * @access public
	 * @var string
	 */
	public $ShipFromGeoBlock;
	/**
	 * @access public
	 * @var Tgc_TaxOffice_Address
	 */
	public $ShipToAddress;
	/**
	 * @access public
	 * @var string
	 */
	public $ShipToGeoBlock;
	/**
	 * @access public
	 * @var string
	 */
	public $FilingLocationId;
}}

if (!class_exists("Tgc_TaxOffice_Address", false)) {
/**
 * Tgc_TaxOffice_Address
 */
class Tgc_TaxOffice_Address {
	/**
	 * @access public
	 * @var string
	 */
	public $City;
	/**
	 * @access public
	 * @var string
	 */
	public $CountryCode;
	/**
	 * @access public
	 * @var string
	 */
	public $County;
	/**
	 * @access public
	 * @var string
	 */
	public $Line1;
	/**
	 * @access public
	 * @var string
	 */
	public $Line2;
	/**
	 * @access public
	 * @var string
	 */
	public $Plus4;
	/**
	 * @access public
	 * @var string
	 */
	public $PostalCode;
	/**
	 * @access public
	 * @var string
	 */
	public $StateOrProvince;
}}

if (!class_exists("Tgc_TaxOffice_ProductInfo", false)) {
/**
 * Tgc_TaxOffice_ProductInfo
 */
class Tgc_TaxOffice_ProductInfo {
	/**
	 * @access public
	 * @var string
	 */
	public $ProductGroup;
	/**
	 * @access public
	 * @var string
	 */
	public $ProductItem;
}}

if (!class_exists("Tgc_TaxOffice_SitusInfo", false)) {
/**
 * Tgc_TaxOffice_SitusInfo
 */
class Tgc_TaxOffice_SitusInfo {
	/**
	 * @access public
	 * @var string
	 */
	public $CanRejectDelivery;
	/**
	 * @access public
	 * @var string
	 */
	public $DeliveryBy;
	/**
	 * @access public
	 * @var string
	 */
	public $FOB;
	/**
	 * @access public
	 * @var string
	 */
	public $MailOrder;
	/**
	 * @access public
	 * @var string
	 */
	public $ShipFromPOB;
	/**
	 * @access public
	 * @var string
	 */
	public $SolicitedOutside;
}}

if (!class_exists("Tgc_TaxOffice_TransactionDetail", false)) {
/**
 * Tgc_TaxOffice_TransactionDetail
 */
class Tgc_TaxOffice_TransactionDetail {
	/**
	 * @access public
	 * @var Tgc_TaxOffice_ArrayOfMessage
	 */
	public $messagesField;
	/**
	 * @access public
	 * @var integer
	 */
	public $transactionStatus;
}}

if (!class_exists("Tgc_TaxOffice_GeoblockInfo", false)) {
/**
 * Tgc_TaxOffice_GeoblockInfo
 */
class Tgc_TaxOffice_GeoblockInfo {
	/**
	 * @access public
	 * @var integer
	 */
	public $AddressVerificationResult;
	/**
	 * @access public
	 * @var Tgc_TaxOffice_Address
	 */
	public $CorrectedAddress;
	/**
	 * @access public
	 * @var integer
	 */
	public $GeoID;
	/**
	 * @access public
	 * @var string
	 */
	public $Geoblock;
	/**
	 * @access public
	 * @var Tgc_TaxOffice_ArrayOfMessage
	 */
	public $Messages;
}}

if (!class_exists("Tgc_TaxOffice_PartialReturnOrder", false)) {
/**
 * Tgc_TaxOffice_PartialReturnOrder
 */
class Tgc_TaxOffice_PartialReturnOrder {
	/**
	 * @access public
	 * @var dateTime
	 */
	public $InvoiceDate;
	/**
	 * @access public
	 * @var string
	 */
	public $InvoiceID;
	/**
	 * @access public
	 * @var Tgc_TaxOffice_ArrayOfPartialLineItem
	 */
	public $LineItems;
	/**
	 * @access public
	 * @var Tgc_TaxOffice_unsignedLong
	 */
	public $OriginalTransactionID;
	/**
	 * @access public
	 * @var string
	 */
	public $SourceSystem;
	/**
	 * @access public
	 * @var string
	 */
	public $TransactionDescription;
}}

if (!class_exists("Tgc_TaxOffice_PartialLineItem", false)) {
/**
 * Tgc_TaxOffice_PartialLineItem
 */
class Tgc_TaxOffice_PartialLineItem {
	/**
	 * @access public
	 * @var double
	 */
	public $Amount;
	/**
	 * @access public
	 * @var double
	 */
	public $AvgUnitPrice;
	/**
	 * @access public
	 * @var string
	 */
	public $ID;
	/**
	 * @access public
	 * @var double
	 */
	public $Quantity;
}}

if (!class_exists("Tgc_TaxOffice_TaxAdjustmentOrder", false)) {
/**
 * Tgc_TaxOffice_TaxAdjustmentOrder
 */
class Tgc_TaxOffice_TaxAdjustmentOrder {
	/**
	 * @access public
	 * @var string
	 */
	public $CustomerID;
	/**
	 * @access public
	 * @var string
	 */
	public $CustomerType;
	/**
	 * @access public
	 * @var dateTime
	 */
	public $InvoiceDate;
	/**
	 * @access public
	 * @var Tgc_TaxOffice_TaxAdjustmentLineItem
	 */
	public $LineItem;
	/**
	 * @access public
	 * @var string
	 */
	public $ProviderType;
	/**
	 * @access public
	 * @var Tgc_TaxOffice_Address
	 */
	public $ShipToAddress;
	/**
	 * @access public
	 * @var string
	 */
	public $ShipToGeoBlock;
	/**
	 * @access public
	 * @var string
	 */
	public $SourceSystem;
	/**
	 * @access public
	 * @var bool
	 */
	public $TaxOnlyCredit;
	/**
	 * @access public
	 * @var bool
	 */
	public $TestTransaction;
	/**
	 * @access public
	 * @var string
	 */
	public $TransactionDescription;
	/**
	 * @access public
	 * @var Tgc_TaxOffice_unsignedLong
	 */
	public $TransactionID;
	/**
	 * @access public
	 * @var bool
	 */
	public $finalize;
	/**
	 * @access public
	 * @var string
	 */
	public $InvoiceID;
	/**
	 * @access public
	 * @var string
	 */
	public $TransactionType;
	/**
	 * @access public
	 * @var dateTime
	 */
	public $TransactionDate;
	/**
	 * @access public
	 * @var string
	 */
	public $FilingLocationId;
}}

if (!class_exists("Tgc_TaxOffice_TaxAdjustmentLineItem", false)) {
/**
 * Tgc_TaxOffice_TaxAdjustmentLineItem
 */
class Tgc_TaxOffice_TaxAdjustmentLineItem {
	/**
	 * @access public
	 * @var double
	 */
	public $Amount;
	/**
	 * @access public
	 * @var string
	 */
	public $ID;
	/**
	 * @access public
	 * @var Tgc_TaxOffice_ProductInfo
	 */
	public $ProductInfo;
	/**
	 * @access public
	 * @var string
	 */
	public $SKU;
	/**
	 * @access public
	 * @var double
	 */
	public $TaxAmount;
	/**
	 * @access public
	 * @var string
	 */
	public $UserData;
}}

if (!class_exists("Tgc_TaxOffice_DataValues", false)) {
/**
 * Tgc_TaxOffice_DataValues
 */
class Tgc_TaxOffice_DataValues {
	/**
	 * @access public
	 * @var Tgc_TaxOffice_ArrayOfDataDetail
	 */
	public $DataDetails;
	/**
	 * @access public
	 * @var Tgc_TaxOffice_ArrayOfMessage
	 */
	public $Messages;
	/**
	 * @access public
	 * @var integer
	 */
	public $transactionStatus;
}}

if (!class_exists("Tgc_TaxOffice_DataDetail", false)) {
/**
 * Tgc_TaxOffice_DataDetail
 */
class Tgc_TaxOffice_DataDetail {
	/**
	 * @access public
	 * @var string
	 */
	public $Key;
	/**
	 * @access public
	 * @var string
	 */
	public $Value;
}}

if (!class_exists("Tgc_TaxOffice_SKUDetail", false)) {
/**
 * Tgc_TaxOffice_SKUDetail
 */
class Tgc_TaxOffice_SKUDetail {
	/**
	 * @access public
	 * @var string
	 */
	public $Description;
	/**
	 * @access public
	 * @var dateTime
	 */
	public $EffectiveDate;
	/**
	 * @access public
	 * @var string
	 */
	public $EntityID;
	/**
	 * @access public
	 * @var Tgc_TaxOffice_ProductInfo
	 */
	public $Product;
	/**
	 * @access public
	 * @var string
	 */
	public $SKU;
}}

if (!class_exists("Tgc_TaxOffice_CustomerCertificateRequest", false)) {
/**
 * Tgc_TaxOffice_CustomerCertificateRequest
 */
class Tgc_TaxOffice_CustomerCertificateRequest {
	/**
	 * @access public
	 * @var Tgc_TaxOffice_ArrayOfCustomerCertificate
	 */
	public $CustomerCertificates;
	/**
	 * @access public
	 * @var string
	 */
	public $DivisionID;
	/**
	 * @access public
	 * @var string
	 */
	public $EntityID;
	/**
	 * @access public
	 * @var bool
	 */
	public $InsertOnly;
}}

if (!class_exists("Tgc_TaxOffice_CustomerCertificate", false)) {
/**
 * Tgc_TaxOffice_CustomerCertificate
 */
class Tgc_TaxOffice_CustomerCertificate {
	/**
	 * @access public
	 * @var Tgc_TaxOffice_Customer
	 */
	public $Customer;
	/**
	 * @access public
	 * @var Tgc_TaxOffice_ArrayOfCertificate
	 */
	public $Certificates;
}}

if (!class_exists("Tgc_TaxOffice_Customer", false)) {
/**
 * Tgc_TaxOffice_Customer
 */
class Tgc_TaxOffice_Customer {
	/**
	 * @access public
	 * @var string
	 */
	public $CustomerName;
	/**
	 * @access public
	 * @var string
	 */
	public $CustomerID;
	/**
	 * @access public
	 * @var string
	 */
	public $Address1;
	/**
	 * @access public
	 * @var string
	 */
	public $Address2;
	/**
	 * @access public
	 * @var string
	 */
	public $City;
	/**
	 * @access public
	 * @var string
	 */
	public $PostalCode;
	/**
	 * @access public
	 * @var string
	 */
	public $Plus4;
	/**
	 * @access public
	 * @var string
	 */
	public $Country;
	/**
	 * @access public
	 * @var string
	 */
	public $StateOrProvince;
}}

if (!class_exists("Tgc_TaxOffice_Certificate", false)) {
/**
 * Tgc_TaxOffice_Certificate
 */
class Tgc_TaxOffice_Certificate {
	/**
	 * @access public
	 * @var string
	 */
	public $Country;
	/**
	 * @access public
	 * @var string
	 */
	public $StateOrProvince;
	/**
	 * @access public
	 * @var string
	 */
	public $CertificateNumber;
	/**
	 * @access public
	 * @var string
	 */
	public $CertificateDesc;
	/**
	 * @access public
	 * @var bool
	 */
	public $IsBlanketCertificate;
	/**
	 * @access public
	 * @var string
	 */
	public $InvoiceNumber;
	/**
	 * @access public
	 * @var bool
	 */
	public $IsStateCertificate;
	/**
	 * @access public
	 * @var bool
	 */
	public $LocalUsesStateCertificate;
	/**
	 * @access public
	 * @var string
	 */
	public $CertificateStatus;
	/**
	 * @access public
	 * @var bool
	 */
	public $IsCertificateOnFile;
	/**
	 * @access public
	 * @var string
	 */
	public $SSTID;
	/**
	 * @access public
	 * @var string
	 */
	public $SSTTypeOfBusiness;
	/**
	 * @access public
	 * @var string
	 */
	public $SSTTypeOfBusinessDesc;
	/**
	 * @access public
	 * @var string
	 */
	public $ExemptReason;
	/**
	 * @access public
	 * @var string
	 */
	public $ExemptReasonDesc;
	/**
	 * @access public
	 * @var string
	 */
	public $CertificateType;
	/**
	 * @access public
	 * @var string
	 */
	public $DriversLicenceState;
	/**
	 * @access public
	 * @var bool
	 */
	public $IgnoreExpirationDate;
	/**
	 * @access public
	 * @var dateTime
	 */
	public $CustomerEffectiveDate;
	/**
	 * @access public
	 * @var dateTime
	 */
	public $CustomerExpirationDate;
	/**
	 * @access public
	 * @var Tgc_TaxOffice_ArrayOfCEMExemption
	 */
	public $CertExemptions;
	/**
	 * @access public
	 * @var string
	 */
	public $TaxAuthorityName;
	/**
	 * @access public
	 * @var string
	 */
	public $ApplyStateToAllLocal;
}}

if (!class_exists("Tgc_TaxOffice_CEMExemption", false)) {
/**
 * Tgc_TaxOffice_CEMExemption
 */
class Tgc_TaxOffice_CEMExemption {
	/**
	 * @access public
	 * @var Tgc_TaxOffice_ProductInfo
	 */
	public $Productinfo;
	/**
	 * @access public
	 * @var string
	 */
	public $Percent;
	/**
	 * @access public
	 * @var string
	 */
	public $ExempDesc;
}}

if (!class_exists("Tgc_TaxOffice_CertificateFilter", false)) {
/**
 * Tgc_TaxOffice_CertificateFilter
 */
class Tgc_TaxOffice_CertificateFilter {
	/**
	 * @access public
	 * @var string
	 */
	public $Country;
	/**
	 * @access public
	 * @var string
	 */
	public $StateOrProvince;
	/**
	 * @access public
	 * @var string
	 */
	public $CertificateNumber;
	/**
	 * @access public
	 * @var string
	 */
	public $CertificateDesc;
	/**
	 * @access public
	 * @var string
	 */
	public $CustomerName;
	/**
	 * @access public
	 * @var string
	 */
	public $CustomerID;
}}

if (!class_exists("Tgc_TaxOffice_CustomerCertificatesExport", false)) {
/**
 * Tgc_TaxOffice_CustomerCertificatesExport
 */
class Tgc_TaxOffice_CustomerCertificatesExport {
	/**
	 * @access public
	 * @var Tgc_TaxOffice_ArrayOfCustomerCertificate
	 */
	public $CustomerCertificates;
	/**
	 * @access public
	 * @var Tgc_TaxOffice_Message
	 */
	public $Message;
}}

if (!class_exists("Tgc_TaxOffice_TransactionTax", false)) {
/**
 * Tgc_TaxOffice_TransactionTax
 */
class Tgc_TaxOffice_TransactionTax {
	/**
	 * @access public
	 * @var string
	 */
	public $DivisionId;
	/**
	 * @access public
	 * @var string
	 */
	public $EntityId;
	/**
	 * @access public
	 * @var Tgc_TaxOffice_unsignedLong
	 */
	public $TransactionId;
}}

if (!class_exists("Tgc_TaxOffice_TaxLiabilityRequest", false)) {
/**
 * Tgc_TaxOffice_TaxLiabilityRequest
 */
class Tgc_TaxOffice_TaxLiabilityRequest {
	/**
	 * @access public
	 * @var Tgc_TaxOffice_Address
	 */
	public $Address;
	/**
	 * @access public
	 * @var string
	 */
	public $CustomerType;
	/**
	 * @access public
	 * @var string
	 */
	public $DivisionId;
	/**
	 * @access public
	 * @var dateTime
	 */
	public $EffectiveDate;
	/**
	 * @access public
	 * @var string
	 */
	public $EntityId;
	/**
	 * @access public
	 * @var string
	 */
	public $Geocode;
	/**
	 * @access public
	 * @var string
	 */
	public $Group;
	/**
	 * @access public
	 * @var string
	 */
	public $Item;
	/**
	 * @access public
	 * @var string
	 */
	public $ProviderType;
	/**
	 * @access public
	 * @var string
	 */
	public $SKU;
	/**
	 * @access public
	 * @var string
	 */
	public $TransactionType;
}}

if (!class_exists("Tgc_TaxOffice_TaxLiabilityResponse", false)) {
/**
 * Tgc_TaxOffice_TaxLiabilityResponse
 */
class Tgc_TaxOffice_TaxLiabilityResponse {
	/**
	 * @access public
	 * @var double
	 */
	public $EstimatedTotalFee;
	/**
	 * @access public
	 * @var double
	 */
	public $EstimatedTotalTaxRate;
	/**
	 * @access public
	 * @var Tgc_TaxOffice_ArrayOfMessage
	 */
	public $Messages;
	/**
	 * @access public
	 * @var Tgc_TaxOffice_ArrayOfTaxItem
	 */
	public $TaxItems;
}}

if (!class_exists("Tgc_TaxOffice_TaxItem", false)) {
/**
 * Tgc_TaxOffice_TaxItem
 */
class Tgc_TaxOffice_TaxItem {
	/**
	 * @access public
	 * @var string
	 */
	public $AuthorityName;
	/**
	 * @access public
	 * @var string
	 */
	public $AuthorityType;
	/**
	 * @access public
	 * @var double
	 */
	public $Fee;
	/**
	 * @access public
	 * @var double
	 */
	public $ImposedFee;
	/**
	 * @access public
	 * @var double
	 */
	public $ImposedRate;
	/**
	 * @access public
	 * @var bool
	 */
	public $IsTieredTax;
	/**
	 * @access public
	 * @var double
	 */
	public $PercentTaxable;
	/**
	 * @access public
	 * @var double
	 */
	public $Rate;
	/**
	 * @access public
	 * @var string
	 */
	public $TaxName;
}}

if (!class_exists("Tgc_TaxOffice_FaultExceptionBase", false)) {
/**
 * Tgc_TaxOffice_FaultExceptionBase
 */
class Tgc_TaxOffice_FaultExceptionBase {
	/**
	 * @access public
	 * @var integer
	 */
	public $ErrorCode;
	/**
	 * @access public
	 * @var string
	 */
	public $Message;
}}

if (!class_exists("Tgc_TaxOffice_SystemException", false)) {
/**
 * Tgc_TaxOffice_SystemException
 */
class Tgc_TaxOffice_SystemException extends Tgc_TaxOffice_Tgc_TaxOffice_Exception {
}}

if (!class_exists("Tgc_TaxOffice_ProcessingFaultException", false)) {
/**
 * Tgc_TaxOffice_ProcessingFaultException
 */
class Tgc_TaxOffice_ProcessingFaultException extends Tgc_TaxOffice_FaultExceptionBase {
}}

if (!class_exists("Tgc_TaxOffice_ValidationFaultException", false)) {
/**
 * Tgc_TaxOffice_ValidationFaultException
 */
class Tgc_TaxOffice_ValidationFaultException extends Tgc_TaxOffice_FaultExceptionBase {
}}

if (!class_exists("Tgc_TaxOffice_CommunicationException", false)) {
/**
 * Tgc_TaxOffice_CommunicationException
 */
class Tgc_TaxOffice_CommunicationException extends Tgc_TaxOffice_SystemException {
}}

if (!class_exists("Tgc_TaxOffice_FaultException", false)) {
/**
 * Tgc_TaxOffice_FaultException
 */
class Tgc_TaxOffice_FaultException extends Tgc_TaxOffice_CommunicationException {
}}

if (!class_exists("Tgc_TaxOffice_STOService", false)) {
/**
 * STOService
 * @author WSDLInterpreter
 */
class Tgc_TaxOffice_STOService extends SoapClient {
	/**
	 * Default class map for wsdl=>php
	 * @access private
	 * @var array
	 */
	private static $classmap = array(
		"CalculateProcurementRequest" => "Tgc_TaxOffice_CalculateProcurementRequest",
		"CalculateProcurementRequestResponse" => "Tgc_TaxOffice_CalculateProcurementRequestResponse",
		"UnattributedProcurementReturnRequest" => "Tgc_TaxOffice_UnattributedProcurementReturnRequest",
		"UnattributedProcurementReturnRequestResponse" => "Tgc_TaxOffice_UnattributedProcurementReturnRequestResponse",
		"CalculateRequest" => "Tgc_TaxOffice_CalculateRequest",
		"CalculateRequestResponse" => "Tgc_TaxOffice_CalculateRequestResponse",
		"CancelRequest" => "Tgc_TaxOffice_CancelRequest",
		"CancelRequestResponse" => "Tgc_TaxOffice_CancelRequestResponse",
		"FinalizeRequest" => "Tgc_TaxOffice_FinalizeRequest",
		"FinalizeRequestResponse" => "Tgc_TaxOffice_FinalizeRequestResponse",
		"GeoblockRequest" => "Tgc_TaxOffice_GeoblockRequest",
		"GeoblockRequestResponse" => "Tgc_TaxOffice_GeoblockRequestResponse",
		"UnattributedReturnRequest" => "Tgc_TaxOffice_UnattributedReturnRequest",
		"UnattributedReturnRequestResponse" => "Tgc_TaxOffice_UnattributedReturnRequestResponse",
		"AttributedFullReturnRequest" => "Tgc_TaxOffice_AttributedFullReturnRequest",
		"AttributedFullReturnRequestResponse" => "Tgc_TaxOffice_AttributedFullReturnRequestResponse",
		"PartialReturnRequest" => "Tgc_TaxOffice_PartialReturnRequest",
		"PartialReturnRequestResponse" => "Tgc_TaxOffice_PartialReturnRequestResponse",
		"TaxAdjustmentRequest" => "Tgc_TaxOffice_TaxAdjustmentRequest",
		"TaxAdjustmentRequestResponse" => "Tgc_TaxOffice_TaxAdjustmentRequestResponse",
		"SystemDateTimeRequest" => "Tgc_TaxOffice_SystemDateTimeRequest",
		"SystemDateTimeRequestResponse" => "Tgc_TaxOffice_SystemDateTimeRequestResponse",
		"GetDataValues" => "Tgc_TaxOffice_GetDataValues",
		"GetDataValuesResponse" => "Tgc_TaxOffice_GetDataValuesResponse",
		"CreateSKU" => "Tgc_TaxOffice_CreateSKU",
		"CreateSKUResponse" => "Tgc_TaxOffice_CreateSKUResponse",
		"GeoblockRequestAll" => "Tgc_TaxOffice_GeoblockRequestAll",
		"GeoblockRequestAllResponse" => "Tgc_TaxOffice_GeoblockRequestAllResponse",
		"CreateCustomerCertificateRequest" => "Tgc_TaxOffice_CreateCustomerCertificateRequest",
		"CreateCustomerCertificateRequestResponse" => "Tgc_TaxOffice_CreateCustomerCertificateRequestResponse",
		"ExportCustomerCertificateRequest" => "Tgc_TaxOffice_ExportCustomerCertificateRequest",
		"ExportCustomerCertificateRequestResponse" => "Tgc_TaxOffice_ExportCustomerCertificateRequestResponse",
		"ExportCustomerCertificateFilterRequest" => "Tgc_TaxOffice_ExportCustomerCertificateFilterRequest",
		"ExportCustomerCertificateFilterRequestResponse" => "Tgc_TaxOffice_ExportCustomerCertificateFilterRequestResponse",
		"GetTransactionTax" => "Tgc_TaxOffice_GetTransactionTax",
		"GetTransactionTaxResponse" => "Tgc_TaxOffice_GetTransactionTaxResponse",
		"GetTaxRates" => "Tgc_TaxOffice_GetTaxRates",
		"GetTaxRatesResponse" => "Tgc_TaxOffice_GetTaxRatesResponse",
		"FaultException" => "Tgc_TaxOffice_FaultException",
		"CommunicationException" => "Tgc_TaxOffice_CommunicationException",
		"SystemException" => "Tgc_TaxOffice_SystemException",
		"FaultException.FaultReasonData" => "Tgc_TaxOffice_FaultExceptionFaultReasonData",
		"FaultException.FaultCodeData" => "Tgc_TaxOffice_FaultExceptionFaultCodeData",
		"char" => "Tgc_TaxOffice_char",
		"duration" => "Tgc_TaxOffice_duration",
		"guid" => "Tgc_TaxOffice_guid",
		"ProcurementOrder" => "Tgc_TaxOffice_ProcurementOrder",
		"ProcurementLineItem" => "Tgc_TaxOffice_ProcurementLineItem",
		"TaxResponse" => "Tgc_TaxOffice_TaxResponse",
		"LineItemTax" => "Tgc_TaxOffice_LineItemTax",
		"TaxDetail" => "Tgc_TaxOffice_TaxDetail",
		"Message" => "Tgc_TaxOffice_Message",
		"Exception" => "Tgc_TaxOffice_Tgc_TaxOffice_Exception",
		"Order" => "Tgc_TaxOffice_Order",
		"LineItem" => "Tgc_TaxOffice_LineItem",
		"NexusInfo" => "Tgc_TaxOffice_NexusInfo",
		"Address" => "Tgc_TaxOffice_Address",
		"ProductInfo" => "Tgc_TaxOffice_ProductInfo",
		"SitusInfo" => "Tgc_TaxOffice_SitusInfo",
		"TransactionDetail" => "Tgc_TaxOffice_TransactionDetail",
		"GeoblockInfo" => "Tgc_TaxOffice_GeoblockInfo",
		"PartialReturnOrder" => "Tgc_TaxOffice_PartialReturnOrder",
		"PartialLineItem" => "Tgc_TaxOffice_PartialLineItem",
		"TaxAdjustmentOrder" => "Tgc_TaxOffice_TaxAdjustmentOrder",
		"TaxAdjustmentLineItem" => "Tgc_TaxOffice_TaxAdjustmentLineItem",
		"DataValues" => "Tgc_TaxOffice_DataValues",
		"DataDetail" => "Tgc_TaxOffice_DataDetail",
		"SKUDetail" => "Tgc_TaxOffice_SKUDetail",
		"CustomerCertificateRequest" => "Tgc_TaxOffice_CustomerCertificateRequest",
		"CustomerCertificate" => "Tgc_TaxOffice_CustomerCertificate",
		"Customer" => "Tgc_TaxOffice_Customer",
		"Certificate" => "Tgc_TaxOffice_Certificate",
		"CEMExemption" => "Tgc_TaxOffice_CEMExemption",
		"CertificateFilter" => "Tgc_TaxOffice_CertificateFilter",
		"CustomerCertificatesExport" => "Tgc_TaxOffice_CustomerCertificatesExport",
		"TransactionTax" => "Tgc_TaxOffice_TransactionTax",
		"ProcessingFaultException" => "Tgc_TaxOffice_ProcessingFaultException",
		"FaultExceptionBase" => "Tgc_TaxOffice_FaultExceptionBase",
		"ValidationFaultException" => "Tgc_TaxOffice_ValidationFaultException",
		"TaxLiabilityRequest" => "Tgc_TaxOffice_TaxLiabilityRequest",
		"TaxLiabilityResponse" => "Tgc_TaxOffice_TaxLiabilityResponse",
		"TaxItem" => "Tgc_TaxOffice_TaxItem",
	);

	/**
	 * Constructor using wsdl location and options array
	 * @param string $wsdl WSDL location for this service
	 * @param array $options Options for the SoapClient
	 */
	public function __construct($wsdl="", $options=array()) {
		foreach(self::$classmap as $wsdlClassName => $phpClassName) {
		    if(!isset($options['classmap'][$wsdlClassName])) {
		        $options['classmap'][$wsdlClassName] = $phpClassName;
		    }
		}
		parent::__construct($wsdl, $options);
	}

		private function splitTypesString($arr)
		{
		  $tempArray = split('[\)\(]+', $arr);
		  unset($tempArray[count($tempArray)-1]);
		  unset($tempArray[0]);
		  return array_values($tempArray);
		}

	/**
	 * Checks if an argument list matches against a valid argument type list
	 * @param array $arguments The argument list to check
	 * @param array $validParameters A list of valid argument types
	 * @return boolean true if arguments match against validParameters
	 * @throws Exception invalid function signature message
	 */
	public function _checkArguments($arguments, $validParameters)
		{
		  $variables = "";
		  foreach ($arguments as $arg)
		  {
		    $type = gettype($arg);
		    if ($type == "object")
		    {
		      $type = get_class($arg);
		    }
		    $variables .= "(".$type.")";
		  }

		  if (!in_array($variables, $validParameters))
		  {
		    // Check for superclasses
		    $myVarArray = $this->splitTypesString($variables);

		    foreach ($validParameters as $vP)
		    {
		      $myParamArray = $this->splitTypesString($vP);

		      if (count($myVarArray) != count($myParamArray))
		      {
		        continue;
		      }

		      $matches = 0;
		      for ($i=0; $i<count($myParamArray); $i++)
		      {
		        if (class_exists($myVarArray[$i]) && class_exists($myParamArray[$i]))
		        {
		          $reflectionClass1 = new ReflectionClass($myVarArray[$i]);
		          $reflectionClass2 = new ReflectionClass($myParamArray[$i]);

		          if ($reflectionClass1->isSubclassOf($reflectionClass2))
		          {
		            $matches++;
		          }
		        }
		        else
		        {
		          if ($myVarArray[$i] == $myParamArray[$i])
		          {
		            $matches++;
		          }
		        }
		      }

		      if ($matches == count($myParamArray))
		      {
		        return true;
		      }
		    }
		    throw new Exception("Invalid parameter types: ".str_replace(")(", ", ", $variables));
		  }
		  return true;
	}

	/**
	 * Service Call: CalculateProcurementRequest
	 * Parameter options:
	 * (Tgc_TaxOffice_CalculateProcurementRequest) parameters
	 * @param mixed,... See function description for parameter options
	 * @return Tgc_TaxOffice_CalculateProcurementRequestResponse
	 * @throws Exception invalid function signature message
	 */
	public function CalculateProcurementRequest($mixed = null) {
		$validParameters = array(
			"(Tgc_TaxOffice_CalculateProcurementRequest)",
		);
		$args = func_get_args();
		$this->_checkArguments($args, $validParameters);
		return $this->__soapCall("CalculateProcurementRequest", $args);
	}


	/**
	 * Service Call: UnattributedProcurementReturnRequest
	 * Parameter options:
	 * (Tgc_TaxOffice_UnattributedProcurementReturnRequest) parameters
	 * @param mixed,... See function description for parameter options
	 * @return Tgc_TaxOffice_UnattributedProcurementReturnRequestResponse
	 * @throws Exception invalid function signature message
	 */
	public function UnattributedProcurementReturnRequest($mixed = null) {
		$validParameters = array(
			"(Tgc_TaxOffice_UnattributedProcurementReturnRequest)",
		);
		$args = func_get_args();
		$this->_checkArguments($args, $validParameters);
		return $this->__soapCall("UnattributedProcurementReturnRequest", $args);
	}


	/**
	 * Service Call: CalculateRequest
	 * Parameter options:
	 * (Tgc_TaxOffice_CalculateRequest) parameters
	 * @param mixed,... See function description for parameter options
	 * @return Tgc_TaxOffice_CalculateRequestResponse
	 * @throws Exception invalid function signature message
	 */
	public function CalculateRequest($mixed = null) {
		$validParameters = array(
			"(Tgc_TaxOffice_CalculateRequest)",
		);
		$args = func_get_args();
		$this->_checkArguments($args, $validParameters);
		return $this->__soapCall("CalculateRequest", $args);
	}


	/**
	 * Service Call: CancelRequest
	 * Parameter options:
	 * (Tgc_TaxOffice_CancelRequest) parameters
	 * @param mixed,... See function description for parameter options
	 * @return Tgc_TaxOffice_CancelRequestResponse
	 * @throws Exception invalid function signature message
	 */
	public function CancelRequest($mixed = null) {
		$validParameters = array(
			"(Tgc_TaxOffice_CancelRequest)",
		);
		$args = func_get_args();
		$this->_checkArguments($args, $validParameters);
		return $this->__soapCall("CancelRequest", $args);
	}


	/**
	 * Service Call: FinalizeRequest
	 * Parameter options:
	 * (Tgc_TaxOffice_FinalizeRequest) parameters
	 * @param mixed,... See function description for parameter options
	 * @return Tgc_TaxOffice_FinalizeRequestResponse
	 * @throws Exception invalid function signature message
	 */
	public function FinalizeRequest($mixed = null) {
		$validParameters = array(
			"(Tgc_TaxOffice_FinalizeRequest)",
		);
		$args = func_get_args();
		$this->_checkArguments($args, $validParameters);
		return $this->__soapCall("FinalizeRequest", $args);
	}


	/**
	 * Service Call: GeoblockRequest
	 * Parameter options:
	 * (Tgc_TaxOffice_GeoblockRequest) parameters
	 * @param mixed,... See function description for parameter options
	 * @return Tgc_TaxOffice_GeoblockRequestResponse
	 * @throws Exception invalid function signature message
	 */
	public function GeoblockRequest($mixed = null) {
		$validParameters = array(
			"(Tgc_TaxOffice_GeoblockRequest)",
		);
		$args = func_get_args();
		$this->_checkArguments($args, $validParameters);
		return $this->__soapCall("GeoblockRequest", $args);
	}


	/**
	 * Service Call: UnattributedReturnRequest
	 * Parameter options:
	 * (Tgc_TaxOffice_UnattributedReturnRequest) parameters
	 * @param mixed,... See function description for parameter options
	 * @return Tgc_TaxOffice_UnattributedReturnRequestResponse
	 * @throws Exception invalid function signature message
	 */
	public function UnattributedReturnRequest($mixed = null) {
		$validParameters = array(
			"(Tgc_TaxOffice_UnattributedReturnRequest)",
		);
		$args = func_get_args();
		$this->_checkArguments($args, $validParameters);
		return $this->__soapCall("UnattributedReturnRequest", $args);
	}


	/**
	 * Service Call: AttributedFullReturnRequest
	 * Parameter options:
	 * (Tgc_TaxOffice_AttributedFullReturnRequest) parameters
	 * @param mixed,... See function description for parameter options
	 * @return Tgc_TaxOffice_AttributedFullReturnRequestResponse
	 * @throws Exception invalid function signature message
	 */
	public function AttributedFullReturnRequest($mixed = null) {
		$validParameters = array(
			"(Tgc_TaxOffice_AttributedFullReturnRequest)",
		);
		$args = func_get_args();
		$this->_checkArguments($args, $validParameters);
		return $this->__soapCall("AttributedFullReturnRequest", $args);
	}


	/**
	 * Service Call: PartialReturnRequest
	 * Parameter options:
	 * (Tgc_TaxOffice_PartialReturnRequest) parameters
	 * @param mixed,... See function description for parameter options
	 * @return Tgc_TaxOffice_PartialReturnRequestResponse
	 * @throws Exception invalid function signature message
	 */
	public function PartialReturnRequest($mixed = null) {
		$validParameters = array(
			"(Tgc_TaxOffice_PartialReturnRequest)",
		);
		$args = func_get_args();
		$this->_checkArguments($args, $validParameters);
		return $this->__soapCall("PartialReturnRequest", $args);
	}


	/**
	 * Service Call: TaxAdjustmentRequest
	 * Parameter options:
	 * (Tgc_TaxOffice_TaxAdjustmentRequest) parameters
	 * @param mixed,... See function description for parameter options
	 * @return Tgc_TaxOffice_TaxAdjustmentRequestResponse
	 * @throws Exception invalid function signature message
	 */
	public function TaxAdjustmentRequest($mixed = null) {
		$validParameters = array(
			"(Tgc_TaxOffice_TaxAdjustmentRequest)",
		);
		$args = func_get_args();
		$this->_checkArguments($args, $validParameters);
		return $this->__soapCall("TaxAdjustmentRequest", $args);
	}


	/**
	 * Service Call: SystemDateTimeRequest
	 * Parameter options:
	 * (Tgc_TaxOffice_SystemDateTimeRequest) parameters
	 * @param mixed,... See function description for parameter options
	 * @return Tgc_TaxOffice_SystemDateTimeRequestResponse
	 * @throws Exception invalid function signature message
	 */
	public function SystemDateTimeRequest($mixed = null) {
		$validParameters = array(
			"(Tgc_TaxOffice_SystemDateTimeRequest)",
		);
		$args = func_get_args();
		$this->_checkArguments($args, $validParameters);
		return $this->__soapCall("SystemDateTimeRequest", $args);
	}


	/**
	 * Service Call: GetDataValues
	 * Parameter options:
	 * (Tgc_TaxOffice_GetDataValues) parameters
	 * @param mixed,... See function description for parameter options
	 * @return Tgc_TaxOffice_GetDataValuesResponse
	 * @throws Exception invalid function signature message
	 */
	public function GetDataValues($mixed = null) {
		$validParameters = array(
			"(Tgc_TaxOffice_GetDataValues)",
		);
		$args = func_get_args();
		$this->_checkArguments($args, $validParameters);
		return $this->__soapCall("GetDataValues", $args);
	}


	/**
	 * Service Call: CreateSKU
	 * Parameter options:
	 * (Tgc_TaxOffice_CreateSKU) parameters
	 * @param mixed,... See function description for parameter options
	 * @return Tgc_TaxOffice_CreateSKUResponse
	 * @throws Exception invalid function signature message
	 */
	public function CreateSKU($mixed = null) {
		$validParameters = array(
			"(Tgc_TaxOffice_CreateSKU)",
		);
		$args = func_get_args();
		$this->_checkArguments($args, $validParameters);
		return $this->__soapCall("CreateSKU", $args);
	}


	/**
	 * Service Call: GeoblockRequestAll
	 * Parameter options:
	 * (Tgc_TaxOffice_GeoblockRequestAll) parameters
	 * @param mixed,... See function description for parameter options
	 * @return Tgc_TaxOffice_GeoblockRequestAllResponse
	 * @throws Exception invalid function signature message
	 */
	public function GeoblockRequestAll($mixed = null) {
		$validParameters = array(
			"(Tgc_TaxOffice_GeoblockRequestAll)",
		);
		$args = func_get_args();
		$this->_checkArguments($args, $validParameters);
		return $this->__soapCall("GeoblockRequestAll", $args);
	}


	/**
	 * Service Call: CreateCustomerCertificateRequest
	 * Parameter options:
	 * (Tgc_TaxOffice_CreateCustomerCertificateRequest) parameters
	 * @param mixed,... See function description for parameter options
	 * @return Tgc_TaxOffice_CreateCustomerCertificateRequestResponse
	 * @throws Exception invalid function signature message
	 */
	public function CreateCustomerCertificateRequest($mixed = null) {
		$validParameters = array(
			"(Tgc_TaxOffice_CreateCustomerCertificateRequest)",
		);
		$args = func_get_args();
		$this->_checkArguments($args, $validParameters);
		return $this->__soapCall("CreateCustomerCertificateRequest", $args);
	}


	/**
	 * Service Call: ExportCustomerCertificateRequest
	 * Parameter options:
	 * (Tgc_TaxOffice_ExportCustomerCertificateRequest) parameters
	 * @param mixed,... See function description for parameter options
	 * @return Tgc_TaxOffice_ExportCustomerCertificateRequestResponse
	 * @throws Exception invalid function signature message
	 */
	public function ExportCustomerCertificateRequest($mixed = null) {
		$validParameters = array(
			"(Tgc_TaxOffice_ExportCustomerCertificateRequest)",
		);
		$args = func_get_args();
		$this->_checkArguments($args, $validParameters);
		return $this->__soapCall("ExportCustomerCertificateRequest", $args);
	}


	/**
	 * Service Call: ExportCustomerCertificateFilterRequest
	 * Parameter options:
	 * (Tgc_TaxOffice_ExportCustomerCertificateFilterRequest) parameters
	 * @param mixed,... See function description for parameter options
	 * @return Tgc_TaxOffice_ExportCustomerCertificateFilterRequestResponse
	 * @throws Exception invalid function signature message
	 */
	public function ExportCustomerCertificateFilterRequest($mixed = null) {
		$validParameters = array(
			"(Tgc_TaxOffice_ExportCustomerCertificateFilterRequest)",
		);
		$args = func_get_args();
		$this->_checkArguments($args, $validParameters);
		return $this->__soapCall("ExportCustomerCertificateFilterRequest", $args);
	}


	/**
	 * Service Call: GetTransactionTax
	 * Parameter options:
	 * (Tgc_TaxOffice_GetTransactionTax) parameters
	 * @param mixed,... See function description for parameter options
	 * @return Tgc_TaxOffice_GetTransactionTaxResponse
	 * @throws Exception invalid function signature message
	 */
	public function GetTransactionTax($mixed = null) {
		$validParameters = array(
			"(Tgc_TaxOffice_GetTransactionTax)",
		);
		$args = func_get_args();
		$this->_checkArguments($args, $validParameters);
		return $this->__soapCall("GetTransactionTax", $args);
	}


	/**
	 * Service Call: GetTaxRates
	 * Parameter options:
	 * (Tgc_TaxOffice_GetTaxRates) parameters
	 * @param mixed,... See function description for parameter options
	 * @return Tgc_TaxOffice_GetTaxRatesResponse
	 * @throws Exception invalid function signature message
	 */
	public function GetTaxRates($mixed = null) {
		$validParameters = array(
			"(Tgc_TaxOffice_GetTaxRates)",
		);
		$args = func_get_args();
		$this->_checkArguments($args, $validParameters);
		return $this->__soapCall("GetTaxRates", $args);
	}


}}

?>