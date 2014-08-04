<?php
/**
 * Tax model factory
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Tgc_TaxOffice
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_TaxOffice_Model_TaxFactory
{
    /**
     * Creates tgc_taxoffice/tax object from address.
     *
     * @param Mage_Sales_Model_Quote_Address $address
     * @return Tgc_TaxOffice_Model_Tax
     */
    public function create($address)
    {
        $config = Mage::getModel('tgc_taxOffice/config', array(
            'store_id' => $address->getQuote()->getStore()->getId()
        ));

        return Mage::getModel('tgc_taxOffice/tax', array(
            'address' => $address,
            'config' => $config,
            'api_processor' => Mage::getModel('tgc_taxOffice/tax_api_processor', array(
                'config' => $config
            ))
        ));
    }
}
