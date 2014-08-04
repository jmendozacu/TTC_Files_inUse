<?php
/**
 * TaxOffice Quote Total collector.
 * Is executed after all tax collectors and before GrandTotal collector.
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Tgc_TaxOffice
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_TaxOffice_Model_Tax_Total_TaxOfficeCollector extends Mage_Tax_Model_Sales_Total_Quote_Subtotal
{
    /**
     * Constructor
     *
     * @param array $args
     */
    public function __construct($args = array())
    {
        $this->_helper = Mage::helper('tax');
        $this->_calculator = Mage::getSingleton('tax/calculation');
        $this->_config = Mage::getSingleton('tax/config');
    }

    /**
     * Collect tax totals for quote address
     *
     * @param   Mage_Sales_Model_Quote_Address $address
     * @return  Tgc_TaxOffice_Model_Tax_Total_TaxOfficeCollector
     */
    public function collect(Mage_Sales_Model_Quote_Address $address)
    {
        $configModel = Mage::getModel('tgc_taxOffice/config');

        if (!$configModel->isEnabled() ||
            !$configModel->isAllowedForCountry($address->getCountryId()) ||
            //the feature has not been implemented for "prices include tax" setting in Magento Admin
            $this->_config->priceIncludesTax($this->_store)
        ) {
            return $this;
        }

        try {
            $taxProcessor = Mage::getSingleton('tgc_taxOffice/taxFactory')
                ->create($address);
            $this->_store = $address->getQuote()->getStore();

            $this->_setAddress($address);

            $taxProcessor->calculateAllTaxes();

            $address->setTaxAmount(0);
            $address->setBaseTaxAmount(0);

            $items = $address->getAllItems();

            foreach ($items as $item) {
                if ($item->getParentItem()) {
                    continue;
                }
                if ($item->getHasChildren() && $item->isChildrenCalculated()) {
                    foreach ($item->getChildren() as $child) {
                        $this->_setTaxesForItem($child);
                    }
                    $this->_recalculateParent($item);
                } else {
                    $this->_setTaxesForItem($item);
                }
            }

             //the feature has not been implemented for "shipping includes taxes" setting in Magento Admin
            if (!$this->_config->shippingPriceIncludesTax($this->_store)) {
                $this->_processShipping($address);
            }

            $this->_processAddress($address);
        } catch (Exception $e) {
            Mage::logException($e);
            return $this;
        }

        return $this;
    }

    /**
     * Sets tax amounts from the result into address item
     *
     * @param Mage_Sales_Model_Quote_Item_Abstract $item
     */
    protected function _setTaxesForItem(Mage_Sales_Model_Quote_Item_Abstract $item)
    {
        /* @var Tgc_TaxOffice_Model_Tax_Api_Operation_Calculate_TaxLineItem $taxes */
        /* @var Tgc_TaxOffice_Model_Tax_Api_Operation_Calculate_TaxLineItem $baseTaxes */
        $taxes = $item->getSalesTaxOfficeTaxes();
        $baseTaxes = $item->getBaseSalesTaxOfficeTaxes();
        if (!is_null($taxes) && !is_null($baseTaxes)) {
            $rowTax = $taxes->getTaxAmount();
            $baseRowTax = $baseTaxes->getTaxAmount();

            $taxSubtotal = $this->_calculator->round($item->getRowTotal() + $rowTax);
            $baseTaxSubtotal = $this->_calculator->round($item->getBaseRowTotal() + $baseRowTax);
            $taxPrice = $item->getPrice() + $this->_calculator->round($rowTax / $item->getQty());
            $baseTaxPrice = $item->getBasePrice() + $this->_calculator->round($baseRowTax / $item->getQty());

            $item->setRowTax($rowTax);
            $item->setBaseRowTax($baseRowTax);
            $item->setPriceInclTax($taxPrice);
            $item->setBasePriceInclTax($baseTaxPrice);
            $item->setRowTotalInclTax($taxSubtotal);
            $item->setBaseRowTotalInclTax($baseTaxSubtotal);
            $item->setTaxAmount($rowTax);
            $item->setBaseTaxAmount($baseRowTax);
            $item->setTaxPercent($taxes->getTaxPercent());

            $appliedRates = $taxes->getAllRates();
            $item->setTaxRates($appliedRates);
            $taxForItems = $item->getQuote()->getTaxesForItems();
            if (!is_array($taxForItems)) {
                $taxForItems = array();
            }
            $taxForItems[$item->getId()] = $appliedRates;
            $item->getQuote()->setTaxesForItems($taxForItems);
            foreach ($appliedRates as $rate) {
                $this->_saveAppliedTaxes(
                    $this->_getAddress(),
                    array($rate),
                    $taxes->getTaxAmountByTaxCode($rate['id']),
                    $baseTaxes->getTaxAmountByTaxCode($rate['id'])
                );
            }
        }
    }

    /**
     * Collect applied tax rates information on address level
     *
     * @param   Mage_Sales_Model_Quote_Address $address
     * @param   array $applied
     * @param   float $taxAmount
     * @param   float $baseTaxAmount
     */
    protected function _saveAppliedTaxes(Mage_Sales_Model_Quote_Address $address, $applied, $taxAmount, $baseTaxAmount)
    {
        $previouslyAppliedTaxes = $address->getAppliedTaxes();
        $process = count($previouslyAppliedTaxes);

        foreach ($applied as $row) {
            if (!isset($previouslyAppliedTaxes[$row['id']])) {
                $row['process'] = $process;
                $row['amount'] = 0;
                $row['base_amount'] = 0;
                $previouslyAppliedTaxes[$row['id']] = $row;
            }

            $previouslyAppliedTaxes[$row['id']]['amount'] += $taxAmount;
            $previouslyAppliedTaxes[$row['id']]['base_amount'] += $baseTaxAmount;
        }
        $address->setAppliedTaxes($previouslyAppliedTaxes);
    }

    /**
     * Sets shipping tax amount into address from the result
     *
     * @param Mage_Sales_Model_Quote_Address $address
     */
    protected function _processShipping(Mage_Sales_Model_Quote_Address $address)
    {
        $shippingTaxes = $address->getSalesTaxOfficeShippingTaxes();
        $baseShippingTaxes = $address->getSalesTaxOfficeBaseShippingTaxes();
        if (!is_null($shippingTaxes) && !is_null($baseShippingTaxes)) {
            $address->setShippingInclTax($address->getShippingAmount() + $shippingTaxes);
            $address->setBaseShippingInclTax($address->getBaseShippingAmount() + $baseShippingTaxes);
            /* @var $taxBaseLineItem Tgc_TaxOffice_Model_Tax_Api_Operation_Calculate_TaxLineItem */
            $taxBaseLineItem = $address->getSalesTaxOfficeBaseShippingLineItem();
            /* @var $taxLineItem Tgc_TaxOffice_Model_Tax_Api_Operation_Calculate_TaxLineItem */
            $taxLineItem = $address->getSalesTaxOfficeShippingLineItem();
            if ($taxLineItem) {
                foreach ($taxLineItem->getAllRates() as $rate) {
                    $this->_saveAppliedTaxes(
                        $address,
                        array($rate),
                        $taxLineItem->getTaxAmountByTaxCode($rate['id']),
                        $taxBaseLineItem->getTaxAmountByTaxCode($rate['id'])
                    );
                }
            }
        }
    }

    /**
     * Sets all tax values from the result into address.
     *
     * @param Mage_Sales_Model_Quote_Address $address
     */
    protected function _processAddress(Mage_Sales_Model_Quote_Address $address)
    {
        $totalTaxes = $address->getSalesTaxOfficeTaxes();
        $baseTotalTaxes = $address->getBaseSalesTaxOfficeTaxes();
        $shippingTaxes = $address->getSalesTaxOfficeShippingTaxes();
        $baseShippingTaxes = $address->getSalesTaxOfficeBaseShippingTaxes();
        if (!is_null($totalTaxes) && !is_null($baseTotalTaxes)) {
            $address->setTaxAmount($totalTaxes);
            $address->setBaseTaxAmount($baseTotalTaxes);
            $address->setSubtotalInclTax($address->getSubtotal() + $totalTaxes - $shippingTaxes);
            $address->setBaseSubtotalInclTax($address->getBaseSubtotal() + $baseTotalTaxes - $baseShippingTaxes);

            $address->setTotalAmount('tax', $totalTaxes);
            $address->setBaseTotalAmount('tax', $baseTotalTaxes);
        }
    }
}
