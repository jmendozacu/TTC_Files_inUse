<?php
/**
 * User: mhidalgo
 * Date: 21/04/14
 * Time: 16:38
 */
class Tgc_Customer_Model_Address extends Mage_Customer_Model_Address
{
    /**
     * Validate address attribute values
     *
     * @return array | bool
     */
    public function validate()
    {
        $this->_resetErrors();

        $this->implodeStreetAddress();

        if (!Zend_Validate::is($this->getFirstname(), 'NotEmpty')) {
            $this->addError(Mage::helper('customer')->__('Please enter the first name.'));
        }

        if (!Zend_Validate::is($this->getLastname(), 'NotEmpty')) {
            $this->addError(Mage::helper('customer')->__('Please enter the last name.'));
        }

        if (!Zend_Validate::is($this->getStreet(1), 'NotEmpty')) {
            $this->addError(Mage::helper('customer')->__('Please enter the street.'));
        }

        if (!Zend_Validate::is($this->getCity(), 'NotEmpty')) {
            $this->addError(Mage::helper('customer')->__('Please enter the city.'));
        }

        /*if (!Zend_Validate::is($this->getTelephone(), 'NotEmpty')) {
            $this->addError(Mage::helper('customer')->__('Please enter the telephone number.'));
        }*/

        $_havingOptionalZip = Mage::helper('directory')->getCountriesWithOptionalZip();
        if (!in_array($this->getCountryId(), $_havingOptionalZip)
            && !Zend_Validate::is($this->getPostcode(), 'NotEmpty')
        ) {
            $this->addError(Mage::helper('customer')->__('Please enter the zip/postal code.'));
        }

        if (!Zend_Validate::is($this->getCountryId(), 'NotEmpty')) {
            $this->addError(Mage::helper('customer')->__('Please enter the country.'));
        }

        if ($this->getCountryModel()->getRegionCollection()->getSize()
            && !Zend_Validate::is($this->getRegionId(), 'NotEmpty')
            && Mage::helper('directory')->isRegionRequired($this->getCountryId())
        ) {
            $this->addError(Mage::helper('customer')->__('Please enter the state/province.'));
        }

        Mage::dispatchEvent('customer_address_validation_after', array('address' => $this));

        $errors = $this->_getErrors();

        $this->_resetErrors();

        if (empty($errors) || $this->getShouldIgnoreValidation()) {
            return true;
        }
        return $errors;
    }
}