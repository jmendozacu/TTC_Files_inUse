<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category
 * @package
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Customer_Model_Customer extends Mage_Customer_Model_Customer
{
    public function getAddressCollection()
    {
        return $this->_addFilterByAllowedCountries(parent::getAddressCollection());
    }

    private function _addFilterByAllowedCountries(Mage_Customer_Model_Resource_Address_Collection $addresses)
    {
        $allowCountries = explode(',', (string)Mage::getStoreConfig('general/country/allow'));
        if (!empty($allowCountries)) {
            $addresses->addFieldToFilter('country_id', array('in' => $allowCountries));
        }

        return $addresses;
    }
}
