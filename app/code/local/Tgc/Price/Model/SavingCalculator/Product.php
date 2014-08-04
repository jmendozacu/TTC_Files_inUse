<?php
/**
 * Calculator base for product calculators
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category
 * @package
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
abstract class Tgc_Price_Model_SavingCalculator_Product implements Tgc_Price_Model_SavingCalculator_Interface
{
    private $_product;

    /**
     * @var Varien_Db_Adapter_Interface
     */
    private $_connection;

    public function __construct()
    {
        $this->_connection = Mage::getSingleton('core/resource')->getConnection('read');
    }

    /**
     * Sets product
     *
     * @see Tgc_Price_Model_SavingCalculator_Interface::setProduct()
     * @throws InvalidArgumentException Ifproduct is not supported
     */
    public function setProduct(Mage_Catalog_Model_Product $product)
    {
        if (!$this->canCalculate($product)) {
            throw new InvalidArgumentException('Unsupported product.');
        }
        $this->_product = $product;
    }

    /**
     * Returns product defined with setProduct
     *
     * @throws LogicException If undefined
     * @return Mage_Catalog_Model_Product
     */
    protected function _getProduct()
    {
        if (!$this->_product) {
            throw new LogicException('Product is undefined.');
        }

        return $this->_product;
    }

    /**
     * Returns read connection
     *
     * @return Varien_Db_Adapter_Interface
     */
    protected function _getConnection()
    {
        return $this->_connection;
    }
}
