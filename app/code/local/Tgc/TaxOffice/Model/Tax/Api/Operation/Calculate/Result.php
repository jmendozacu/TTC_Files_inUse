<?php
/**
 * "CalculateRequest" API method's result object.
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Tgc_TaxOffice
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_TaxOffice_Model_Tax_Api_Operation_Calculate_Result
{
    /**
     * Raw response object.
     *
     * @var Tgc_TaxOffice_CalculateRequestResponse
     */
    private $_rawResult;

    /**
     * Tax Items list
     *
     * @var array of Tgc_TaxOffice_Model_Tax_Api_Operation_Calculate_TaxLineItem
     */
    protected $_taxItems;

    /**
     * Shipping tax LineItem
     *
     * @var Tgc_TaxOffice_Model_Tax_Api_Operation_Calculate_TaxLineItem
     */
    protected $_shippingLineItem;



    /**
     * Constructor
     *
     * @param $args 'result' argument is required - it is the reult from SOAP (Tgc_TaxOffice_CalculateRequestResponse).
     * @throws InvalidArgumentException
     */
    public function __construct($args)
    {
        if (!isset($args['result'])) {
            throw new InvalidArgumentException("'result' argument is required");
        }
        $this->_rawResult = $args['result'];
    }

    /**
     * Returns raw result
     *
     * @return Tgc_TaxOffice_CalculateRequestResponse
     */
    public function getRawResult()
    {
        return $this->_rawResult;
    }

    /**
     * Calculates Tax Line Items from the result.
     *
     * @return array of Tgc_TaxOffice_Model_Tax_Api_Operation_Calculate_TaxLineItem
     */
    protected function _getLineItemsFromResult()
    {
        if (!isset($this->getRawResult()->CalculateRequestResult->LineItemTaxes) ||
            !isset($this->getRawResult()->CalculateRequestResult->LineItemTaxes->LineItemTax)
        ) {
            return array();
        }

        $lineItems = $this->getRawResult()->CalculateRequestResult->LineItemTaxes->LineItemTax;
        if (!is_array($lineItems)) {
            $lineItems = array($lineItems);
        }

        return $lineItems;
    }

    /**
     * Returns only Tax Line Items for ordered items.
     *
     * @return array of Tgc_TaxOffice_Model_Tax_Api_Operation_Calculate_TaxLineItem
     */
    public function getTaxLineItems()
    {
        if (!isset($this->_taxItems)) {
            $taxLineItems = array();

            $lineItems = $this->_getLineItemsFromResult();
            foreach ($lineItems as $item) {
                if ($item->ID != Tgc_TaxOffice_Model_Tax_Api_Operation_Calculate::SHIPPING_LINE_ITEM_SKU) {
                    $taxLineItems[] = Mage::getModel('tgc_taxOffice/tax_api_operation_calculate_taxLineItem', array('item' => $item));
                }
            }

            $this->_taxItems = $taxLineItems;
        }

        return $this->_taxItems;
    }

    /**
     * Returns shipping amount
     *
     * @return float
     */
    public function getShippingAmount()
    {
        $lineItem = $this->getShippingLineItem();

        return $lineItem ? $lineItem->getTaxAmount() : 0;
    }

    /**
     * Returns shipping amount
     *
     * @return Tgc_TaxOffice_Model_Tax_Api_Operation_Calculate_TaxLineItem
     */
    public function getShippingLineItem()
    {
        if (!isset($this->_shippingLineItem)) {
            $lineItems = $this->_getLineItemsFromResult();
            foreach ($lineItems as $item) {
                if ($item->ID == Tgc_TaxOffice_Model_Tax_Api_Operation_Calculate::SHIPPING_LINE_ITEM_SKU) {
                    $shippingItem = Mage::getModel('tgc_taxOffice/tax_api_operation_calculate_taxLineItem', array('item' => $item));
                    $this->_shippingLineItem = $shippingItem;
                    break;
                }
            }
        }

        return $this->_shippingLineItem;
    }

    /**
     * Returns total tax amount
     *
     * @return float
     */
    public function getTotalTaxAmount()
    {
        return isset($this->getRawResult()->CalculateRequestResult->TotalTaxApplied) ?
            $this->getRawResult()->CalculateRequestResult->TotalTaxApplied : null;
    }
}
