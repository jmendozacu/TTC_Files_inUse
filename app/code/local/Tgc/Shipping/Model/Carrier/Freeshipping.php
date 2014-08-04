<?php
/**
 * Shipping
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Shipping
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Shipping_Model_Carrier_Freeshipping
    extends Mage_Shipping_Model_Carrier_Freeshipping
{
    protected function _updateFreeMethodQuote($request)
    {
        $freeShipping = false;
        $items = $request->getAllItems();
        $c = count($items);
        for ($i = 0; $i < $c; $i++) {
            if ($items[$i]->getProduct() instanceof Mage_Catalog_Model_Product) {
                if ($items[$i]->getFreeShipping() || $items[$i]->getProduct()->getTypeId() == 'giftcard') {
                    $freeShipping = true;
                } else {
                    return;
                }
            }
        }
        if ($freeShipping) {
            $request->setFreeShipping(true);
        }
    }
}
