<?php
/**
 * Magento model for calculating taxes
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Tgc_TaxOffice
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_TaxOffice_Model_Tax
{
    /**
     * Config
     *
     * @var Tgc_TaxOffice_Model_Config
     */
    private $_config;

    /**
     * API processor instance
     *
     * @var Tgc_TaxOffice_Model_Tax_Api_ProcessorInterface
     */
    private $_apiProcessor;

    /**
     * Quote address
     *
     * @var Mage_Sales_Model_Quote_Address
     */
    private $_address;

    /**
     * Constructor
     *
     * @param array $args 'config' is optional argument, 'address' is required.
     * @throws InvalidArgumentException
     */
    public function __construct($args = array())
    {
        if (!isset($args['address'])) {
            throw new InvalidArgumentException("'address' argument is required");
        }

        $this->_address = $args['address'];

        if (isset($args['config'])) {
            if (!($args['config'] instanceof Tgc_TaxOffice_Model_Config)) {
                throw new InvalidArgumentException("'config' must be an instance of Tgc_TaxOffice_Model_Config");
            }
            $this->_config = $args['config'];
        } else {
            $this->_config = Mage::getModel('tgc_taxOffice/config');
        }

        $this->_config->setStoreId($this->_address->getQuote()->getStoreId());

        if (isset($args['api_processor'])) {
            if (!($args['api_processor'] instanceof Tgc_TaxOffice_Model_Tax_Api_ProcessorInterface)) {
                throw new InvalidArgumentException(
                    "'api_processor' must be an instance of Tgc_TaxOffice_Model_Tax_Api_ProcessorInterface"
                );
            }
            $this->_apiProcessor = $args['api_processor'];
        } else {
            $this->_apiProcessor = Mage::getModel('tgc_taxOffice/tax_api_processor', array('config' => $this->_config));
        }
    }

    /**
     * API Config getter
     *
     * @return Tgc_TaxOffice_Model_Config
     */
    public function getConfig()
    {
        return $this->_config;
    }

    /**
     * @return Tgc_TaxOffice_Model_Tax_Api_ProcessorInterface
     */
    protected function _getApiProcessor()
    {
        return $this->_apiProcessor;
    }

    /**
     * Address setter
     *
     * @param Mage_Sales_Model_Quote_Address $address
     * @return Tgc_TaxOffice_Model_Tax
     */
    public function setAddress($address)
    {
        $this->_address = $address;
        $this->_config->setStoreId($this->_address->getQuote()->getStoreId());
        return $this;
    }

    /**
     * Address getter
     *
     * @return Mage_Sales_Model_Quote_Address
     */
    public function getAddress()
    {
        return $this->_address;
    }

    /**
     * Calculates taxes for the address and sets results to the address items.
     *
     * @param bool $useBasePrice
     * @throws DomainException
     */
    public function calculateTaxes($useBasePrice)
    {
        $address = $this->getAddress();
        if (!$address) {
            throw new DomainException('Cannot calculate taxes: address was not set');
        }

        $lineItems = $this->_prepareLineItems($address);
        $lineItems->setUseBaseAmounts($useBasePrice);
        $shippingAmount = ($useBasePrice ? $address->getBaseShippingAmount() : $address->getShippingAmount());

        $result = $this->_getApiProcessor()
            ->calculate(
                $address,
                $lineItems,
                $shippingAmount
            );
        $this->_setTaxResultIntoItems($result, $lineItems, $useBasePrice);

        if ($this->getAddress()->getQuote()->getStore()->getCurrentCurrencyCode() ==
            $this->getAddress()->getQuote()->getStore()->getBaseCurrencyCode()
        ) {
            $this->_setTaxResultIntoItems($result, $lineItems, !$useBasePrice);
        }
    }

    /**
     * Sets taxes into address items objects from the API result
     *
     * @param Tgc_TaxOffice_Model_Tax_Api_Operation_Calculate_Result $result
     * @param Tgc_TaxOffice_Model_Tax_Api_LineItemCollection $lineItems
     * @param bool $forBasePrice
     */
    protected function _setTaxResultIntoItems($result, $lineItems, $forBasePrice)
    {
        foreach ($result->getTaxLineItems() as $taxItem) {
            /* @var $taxItem Tgc_TaxOffice_Model_Tax_Api_Operation_Calculate_TaxLineItem */
            foreach ($lineItems as $item) {
                /* @var $item Tgc_TaxOffice_Model_Tax_Api_LineItem */
                if ($taxItem->getId() == $item->getItem()->getId()) {
                    if ($forBasePrice) {
                        $item->getItem()->setBaseSalesTaxOfficeTaxes($taxItem);
                    } else {
                        $item->getItem()->setSalesTaxOfficeTaxes($taxItem);
                    }
                    break;
                }
            }
        }

        if ($forBasePrice) {
            $this->getAddress()->setSalesTaxOfficeBaseShippingTaxes($result->getShippingAmount());
            $this->getAddress()->setSalesTaxOfficeBaseShippingLineItem($result->getShippingLineItem());
            $this->getAddress()->setBaseSalesTaxOfficeTaxes($result->getTotalTaxAmount());
        } else {
            $this->getAddress()->setSalesTaxOfficeShippingTaxes($result->getShippingAmount());
            $this->getAddress()->setSalesTaxOfficeShippingLineItem($result->getShippingLineItem());
            $this->getAddress()->setSalesTaxOfficeTaxes($result->getTotalTaxAmount());
        }
    }

    /**
     * Calculates all taxes for address and address items.
     */
    public function calculateAllTaxes()
    {
        $this->calculateTaxes(false);
        if ($this->getAddress()->getQuote()->getStore()->getCurrentCurrencyCode() !=
            $this->getAddress()->getQuote()->getStore()->getBaseCurrencyCode()
        ) {
            $this->calculateTaxes(true);
        }
    }

    /**
     * Prepares line items collection from address items for the API request.
     *
     * @param Mage_Sales_Model_Quote_Address $address
     * @return Tgc_TaxOffice_Model_Tax_Api_LineItemCollection
     */
    protected function _prepareLineItems($address)
    {
        $quoteItems = $address->getAllItems();

        /* @var $lineItems Tgc_TaxOffice_Model_Tax_Api_LineItemCollection */
        $lineItems = Mage::getModel('tgc_taxOffice/tax_api_lineItemCollection', array('config' => $this->getConfig()));
        foreach ($quoteItems as $item) {
            if ($item->getParentItem()) {
                continue;
            }
            if ($item->getHasChildren() && $item->isChildrenCalculated()) {
                foreach ($item->getChildren() as $child) {
                    $lineItems->add(
                        Mage::getModel('tgc_taxOffice/tax_api_lineItem',
                            array('config' => $this->getConfig(), 'item' => $child)
                        )
                    );
                }
            } else {
                $lineItems->add(
                    Mage::getModel('tgc_taxOffice/tax_api_lineItem',
                        array('config' => $this->getConfig(), 'item' => $item)
                    )
                );
            }
        }
        return $lineItems;
    }
}
