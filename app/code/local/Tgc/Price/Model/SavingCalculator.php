<?php
/**
 * SAving calculator's factory
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category
 * @package
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Price_Model_SavingCalculator
{
    private $_calculators = array();

    public function __construct()
    {
        $this->_calculators = $this->_loadCalculators();
    }

    /**
     * Returns calculator for product
     *
     * @param Mage_Catalog_Model_Product $product
     * @return Tgc_Price_Model_SavingCalculator_Interface
     */
    public function factory(Mage_Catalog_Model_Product $product)
    {
        foreach ($this->_calculators as $calc) {
            /* @var $calc Tgc_Price_Model_SavingCalculator_Interface */
            if ($calc->canCalculate($product)) {
                $new = clone $calc;
                $new->setProduct($product);
                return $new;
            }
        }

        throw new InvalidArgumentException('Unsupported product type.');
    }

    protected function _loadCalculators()
    {
        $modelNames = array('tgc_price/savingCalculator_set', 'tgc_price/savingCalculator_course');
        $calculators = array();

        foreach ($modelNames as $name) {
            $model = Mage::getModel($name);
            if (!$model instanceof Tgc_Price_Model_SavingCalculator_Interface) {
                throw new DomainException('Calculator should implement approriate interface.');
            }
            $calculators[] = $model;
        }

        return $calculators;
    }



    private function _isCourseProduct(Mage_Catalog_Model_Product $product)
    {
        $attrSetId = Mage::getSingleton('eav/config')->getAttributeSetId(Mage_Catalog_Model_Product::ENTITY, 'Courses');

        return $product->getTypeId() == Mage_Catalog_Model_Product_Type_Configurable::TYPE_CODE
            && $product->getAttributeSetId() == $attrSetId;
    }
}