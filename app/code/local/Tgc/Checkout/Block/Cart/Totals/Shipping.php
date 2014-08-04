<?php
/**
 * User: mhidalgo
 * Date: 25/02/14
 * Time: 10:46
 */

class Tgc_Checkout_Block_Cart_Totals_Shipping extends Mage_Core_Block_Template
{
    private $_title = "";
    /** @var $_helper Tgc_Checkout_Helper_Data */
    private $_helper = null;

    protected function _construct() {
        $this->_helper = Mage::helper('tgc_checkout');

        parent::_construct();
    }

    public function getShippingRatesByCountryCode() {
        return $this->_helper->getShippingRatesByCountryCode();
    }

    public function getInternationalShippingRates() {
        return $this->_helper->getInternationalShippingRates();
    }

    public function getTitle() {
        if ($this->_title == "") {
            switch (Mage::app()->getWebsite()->getName()) {
                case "US":$this->_title = "United States";
                    break;
                case "UK":$this->_title = "United Kingdom";
                    break;
                case "Australia":$this->_title = "Australia";
                    break;
                default:$this->_title = "United States";
                break;
            }
        }
        return $this->_title;
    }
}