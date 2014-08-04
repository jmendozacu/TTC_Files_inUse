<?php
/**
 * TaxLineItem model
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Tgc_TaxOffice
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_TaxOffice_Model_Tax_Api_Operation_Calculate_TaxLineItem
{
    const CANADA_PST_TAX_CODE = 'PST';
    const CANADA_GST_TAX_CODE = 'GST';

    /**
     * Original line item tax object from the response
     *
     * @var Tgc_TaxOffice_LineItemTax
     */
    private $_taxItemObject;

    /**
     * Constructor
     *
     * @param $args 'item' argument is required - original line item tax object from the response
     * @throws InvalidArgumentException
     */
    public function __construct($args)
    {
        if (!isset($args['item'])) {
            throw new InvalidArgumentException("'item' argument is required");
        }
        $this->_taxItemObject = $args['item'];
    }

    /**
     * Quote Item ID from the response
     *
     * @return string
     */
    public function getId()
    {
        return $this->_taxItemObject->ID;
    }

    /**
     * Returns total tax amount of the item
     *
     * @return float
     */
    public function getTaxAmount()
    {
        return $this->_taxItemObject->TotalTaxApplied;
    }

    /**
     * Returns taxDetail object by code
     *
     * @param string $code
     * @return Tgc_TaxOffice_TaxDetail|null
     */
    protected function _getTaxDetailByCode($code)
    {
        $taxDetails = $this->_taxItemObject->TaxDetails->TaxDetail;
        if (!is_array($taxDetails)) {
            $taxDetails = array($taxDetails);
        }
        foreach ($taxDetails as $taxDetail) {
            /* @var $taxDetail Tgc_TaxOffice_TaxDetail */
            if ($this->_getTaxCode($taxDetail) == $code) {
                return $taxDetail;
            }
        }
        return null;
    }

    /**
     * Returns total tax amount of the item
     *
     * @param $code
     * @return float
     */
    public function getTaxAmountByTaxCode($code)
    {
        $taxDetail = $this->_getTaxDetailByCode($code);
        if ($taxDetail) {
            return $taxDetail->TaxApplied;
        }
        return 0;
    }

    /**
     * Returns tax percent
     *
     * @return float
     */
    public function getTaxPercent()
    {
        $taxDetails = $this->_taxItemObject->TaxDetails->TaxDetail;
        if (!is_array($taxDetails)) {
            $taxDetails = array($taxDetails);
        }

        $taxPercent = 0;
        foreach ($taxDetails as $taxDetail) {
            if (!empty($taxDetail) && isset($taxDetail->TaxRate)) {
                $taxPercent += $taxDetail->TaxRate;
            }
        }

        return round($taxPercent*100, 2);
    }

    /**
     * Returns all taxes for the item in format for Tax Collector.
     *
     * @return array
     */
    public function getAllRates()
    {
        $taxDetails = $this->_taxItemObject->TaxDetails->TaxDetail;
        if (!is_array($taxDetails)) {
            $taxDetails = array($taxDetails);
        }

        $result = array();
        foreach ($taxDetails as $taxDetail) {
            /* @var $taxDetail Tgc_TaxOffice_TaxDetail */
            $taxPercent =  round($taxDetail->TaxRate*100, 2);
            $taxCode = $this->_getTaxCode($taxDetail);
            $result[] = array(
                'id' => $taxCode,
                'percent' => $taxPercent,
                'rates' => array(array(
                    'code' => $taxCode,
                    'percent' => $taxPercent,
                    'position' => 1,
                    'priority' => 1,
                    'title' => $taxDetail->TaxName,
                    'taxable_amount' => $taxDetail->TaxableAmount
                ))
            );
        }

        return $result;
    }

    /**
     * Calculates TaxCode for Magento from the response.
     *
     * @param Tgc_TaxOffice_TaxDetail $taxDetail
     * @return string
     */
    protected function _getTaxCode($taxDetail)
    {
        if ($this->_taxItemObject->CountryCode == 'CA') {
            if (strpos($taxDetail->TaxName, self::CANADA_GST_TAX_CODE) !== false) {
                return self::CANADA_GST_TAX_CODE;
            } elseif (strpos($taxDetail->TaxName, self::CANADA_PST_TAX_CODE) !== false) {
                return self::CANADA_PST_TAX_CODE;
            }
        }
        return $taxDetail->TaxName;
    }
}