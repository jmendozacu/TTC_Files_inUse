<?php
/**
 * Overridden observer of Tax module.
 *
 * Overridden, because we need to save taxable amount into the sales_order_tax_item table.
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Tgc_TaxOffice
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_TaxOffice_Model_TaxObserver extends Mage_Tax_Model_Observer
{
    /**
     * Save order tax information
     *
     * @param Varien_Event_Observer $observer
     */
    public function salesEventOrderAfterSave(Varien_Event_Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();

        if (!$order->getConvertingFromQuote() || $order->getAppliedTaxIsSaved()) {
            return;
        }

        $getTaxesForItems   = $order->getQuote()->getTaxesForItems();
        $taxes              = $order->getAppliedTaxes();

        $ratesIdQuoteItemId = array();
        if (!is_array($getTaxesForItems)) {
            $getTaxesForItems = array();
        }
        foreach ($getTaxesForItems as $quoteItemId => $taxesArray) {
            foreach ($taxesArray as $rates) {
                if (count($rates['rates']) == 1) {
                    $ratesIdQuoteItemId[$rates['id']][] = array(
                        'id'        => $quoteItemId,
                        'percent'   => $rates['percent'],
                        'code'      => $rates['rates'][0]['code'],
                        //CUSTOM CODE
                        //Save taxable amount.
                        'taxable_amount' => isset($rates['rates'][0]['taxable_amount']) ?
                            $rates['rates'][0]['taxable_amount'] : null
                        //CUSTOM CODE END
                    );
                } else {
                    $percentDelta   = $rates['percent'];
                    $percentSum     = 0;
                    foreach ($rates['rates'] as $rate) {
                        $ratesIdQuoteItemId[$rates['id']][] = array(
                            'id'        => $quoteItemId,
                            'percent'   => $rate['percent'],
                            'code'      => $rate['code'],
                            //CUSTOM CODE
                            //Save taxable amount.
                            'taxable_amount' => isset($rate['taxable_amount']) ? $rate['taxable_amount'] : null
                            //CUSTOM CODE END
                        );
                        $percentSum += $rate['percent'];
                    }

                    if ($percentDelta != $percentSum) {
                        $delta = $percentDelta - $percentSum;
                        foreach ($ratesIdQuoteItemId[$rates['id']] as &$rateTax) {
                            if ($rateTax['id'] == $quoteItemId) {
                                $rateTax['percent'] = (($rateTax['percent'] / $percentSum) * $delta)
                                        + $rateTax['percent'];
                            }
                        }
                    }
                }
            }
        }

        foreach ($taxes as $id => $row) {
            foreach ($row['rates'] as $tax) {
                if (is_null($row['percent'])) {
                    $baseRealAmount = $row['base_amount'];
                } else {
                    if ($row['percent'] == 0 || $tax['percent'] == 0) {
                        continue;
                    }
                    $baseRealAmount = $row['base_amount'] / $row['percent'] * $tax['percent'];
                }
                $hidden = (isset($row['hidden']) ? $row['hidden'] : 0);
                $data = array(
                    'order_id'          => $order->getId(),
                    'code'              => $tax['code'],
                    'title'             => $tax['title'],
                    'hidden'            => $hidden,
                    'percent'           => $tax['percent'],
                    'priority'          => $tax['priority'],
                    'position'          => $tax['position'],
                    'amount'            => $row['amount'],
                    'base_amount'       => $row['base_amount'],
                    'process'           => $row['process'],
                    'base_real_amount'  => $baseRealAmount,
                );

                $result = Mage::getModel('tax/sales_order_tax')->setData($data)->save();

                if (isset($ratesIdQuoteItemId[$id])) {
                    foreach ($ratesIdQuoteItemId[$id] as $quoteItemId) {
                        if ($quoteItemId['code'] == $tax['code']) {
                            /* @var $order Mage_Sales_Model_Order */
                            /* @var $item Mage_Sales_Model_Order_Item */
                            $item = $order->getItemByQuoteItemId($quoteItemId['id']);
                            if ($item) {
                                $data = array(
                                    'item_id'       => $item->getId(),
                                    'tax_id'        => $result->getTaxId(),
                                    'tax_percent'   => $quoteItemId['percent'],
                                    //CUSTOM CODE
                                    //Save taxable amount.
                                    'taxable_amount'=> isset($quoteItemId['taxable_amount']) ?
                                        $quoteItemId['taxable_amount'] : $item->getRowTotal()
                                    //CUSTOM CODE END
                                );
                                Mage::getModel('tax/sales_order_tax_item')->setData($data)->save();
                            }
                        }
                    }
                }
            }
        }

        $order->setAppliedTaxIsSaved(true);
    }
}