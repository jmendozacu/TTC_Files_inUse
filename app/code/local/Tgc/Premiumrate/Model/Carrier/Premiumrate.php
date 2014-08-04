<?php
/**
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Premiumrate
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Premiumrate_Model_Carrier_Premiumrate extends Webshopapps_Premiumrate_Model_Carrier_Premiumrate
{
    /**
     * set total price for NonDigital items
     * @param $request
     * @param $discounted
     * @param $taxed
     */
    protected function setTotalPriceForNonDigital($request, $discounted, $taxed)
    {
        $totalPrice = 0;
        $totalWeight = 0;
        $totalQty = 0;
        $temp = '';
        $includeVirtual = false;
        $useParent = true;
        $cartFreeShipping = false;
        $useBase = false;

        $items = $request->getAllItems();

        if (is_array($items)) {
            $mediaAttributeIds = Mage::helper('tgc_catalog')->getDigitalMediaAttributeId();

            foreach ($items as $item) {
                $price = 0;
                $weight = 0;
                $qty = 0;

                $mediaId = Mage::getModel('catalog/product')->getCollection()
                    ->addAttributeToSelect('media_format')
                    ->addAttributeToFilter('sku', $item->getProduct()->getSku())
                    ->getFirstItem()
                    ->getMediaFormat();

                if (in_array($mediaId, $mediaAttributeIds)) {
                    continue;
                }

                if ($item->getProduct()->isVirtual()) {
                    if (!Mage::helper('wsacommon/shipping')->getVirtualItemTotals($item, $weight, $qty, $price, $useParent,
                        $request->getIgnoreFreeItems(), $temp, $discounted, $cartFreeShipping, $useBase, $taxed, $includeVirtual)
                    ) {
                        continue;
                    }
                } else {
                    if (!Mage::helper('wsacommon/shipping')->getItemTotals($item, $weight, $qty, $price, $useParent,
                        $request->getIgnoreFreeItems(), $temp, $discounted, $cartFreeShipping, $useBase, $taxed)
                    ) {
                        continue;
                    }
                }

                $totalPrice += $price;
                $totalQty += $qty;
                $totalWeight += $weight;
            }

            if (Mage::helper('wsalogger')->isDebug('Webshopapps_Premiumrate')) {
                Mage::helper('wsalogger/log')->postDebug('premiumrate', 'Original Package Weight', $request->getPackageWeight());
                Mage::helper('wsalogger/log')->postDebug('premiumrate', 'Original Package Value', $request->getPackageValue());
                Mage::helper('wsalogger/log')->postDebug('premiumrate', 'Original Package Qty', $request->getPackageQty());
                Mage::helper('wsalogger/log')->postDebug('premiumrate', 'New Package Weight', $totalWeight);
                Mage::helper('wsalogger/log')->postDebug('premiumrate', 'New Package Value', $totalPrice);
                Mage::helper('wsalogger/log')->postDebug('premiumrate', 'New Package Qty', $totalQty);
            }
            if (Mage::helper('core')->isModuleEnabled('Webshopapps_Dropship') && Mage::getStoreConfig('carriers/dropship/active') && Mage::getStoreConfig('carriers/dropship/use_cart_price')) {
                $request->setPackageValue($request->getCartValue());
            } else {
                $request->setPackageValue($totalPrice);
            }
            $request->setPackageWeight($totalWeight);
            $request->setPackageQty($totalQty);
        }
    }

    /**
     * overwrite method to use setTotalPriceForNonDigital instead of private method setTotalPrice
     * @return false|Mage_Core_Model_Abstract
     */
    protected function _getQuotes()
    {
        if ($this->getConfigFlag('custom_sorting')) {
            $result = Mage::getModel('premiumrate_shipping/rate_result');
        } else {
            $result = Mage::getModel('shipping/rate_result');
        }
        $request = $this->_rawRequest;


        $this->setTotalPriceForNonDigital($request, $this->getConfigFlag('use_discount'), $this->getConfigFlag('use_tax_incl'));

        $rateArray = $this->getRate($request);

        //set these back to their original values so we don't interfere with other shipping carriers calculations
        $request->setPackageWeight($this->oldWeight);
        $request->setPackageQty($this->oldQty);
        $request->setPackageValue($this->oldPrice);

        $version = Mage::helper('wsacommon')->getVersion();
        //this is a fix for 1.4.1.1 and earlier versions where the free ship logic used for UPS doesnt work
        if (($version == 1.6 || $version == 1.7 || $version == 1.8)) {

            if ($request->getFreeShipping() === true || $request->getPackageQty() == $this->getFreeBoxes()) {
                $method = Mage::getModel('shipping/rate_result_method');
                $method->setCarrier('premiumrate');
                $method->setCarrierTitle($this->getConfigData('title'));
                $method->setMethod(strtolower('premiumrate_' . $this->getConfigData('free_method_text')));
                $method->setPrice('0.00');
                $method->setMethodTitle($this->getConfigData('free_method_text'));
                $result->append($method);
                return $result;
            }
        }

        if (empty($rateArray)) {
            if ($this->getConfigData('specificerrmsg') != '') {
                $error = Mage::getModel('shipping/rate_result_error');
                $error->setCarrier('premiumrate');
                $error->setCarrierTitle($this->getConfigData('title'));
                $error->setErrorMessage($this->getConfigData('specificerrmsg'));
                $result->append($error);
            }
            return $result;
        }

        if ($this->getConfigFlag('lowest_price_free')) {
            $minValue = 99999;
            $lowRate = 0;

            foreach ($rateArray as $key => $rate) {
                if (!empty($rate) && $rate['price'] >= 0) {
                    if ($rate['price'] < $minValue) {
                        $minValue = $rate['price'];
                        $lowRate = $key;
                    }
                }
            }
            $rateArray[$lowRate]['price'] = 0.00;
            if ($this->getConfigData('free_method_text') != "") {
                $rateArray[$lowRate]['delivery_type'] = $this->getConfigData('free_method_text');
            }
        }

        foreach ($rateArray as $rate) {
            if (!empty($rate) && $rate['price'] >= 0) {
                $method = Mage::getModel('shipping/rate_result_method');

                $method->setCarrier('premiumrate');
                $method->setCarrierTitle($this->getConfigData('title'));

                $modifiedName = preg_replace('/&|;| /', "_", $rate['method_name']);
                $method->setMethod($modifiedName);

                $method->setMethodTitle(Mage::helper('shipping')->__($rate['delivery_type']));

                $shippingPrice = $this->getFinalPriceWithHandlingFee($rate['price']);
                $method->setCost($rate['cost']);
                $method->setDeliveryType($rate['delivery_type']);

                $method->setPrice($shippingPrice);

                $result->append($method);
            }
        }
        return $result;
    }

}
