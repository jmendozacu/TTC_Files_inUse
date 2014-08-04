<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     SalesRule
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_SalesRule_Model_Rule_Condition_Product extends Mage_SalesRule_Model_Rule_Condition_Product
{
    /**
     * Add special attributes
     *
     * @param array $attributes
     */
    protected function _addSpecialAttributes(array &$attributes)
    {
        parent::_addSpecialAttributes($attributes);
        $attributes['quote_item_qty'] = Mage::helper('salesrule')->__('Quantity in cart');
        $attributes['quote_item_price'] = Mage::helper('salesrule')->__('Price in cart');
        $attributes['quote_item_row_total'] = Mage::helper('salesrule')->__('Row total in cart');
        $attributes['on_sale_flag'] = Mage::helper('salesrule')->__('On Sale');
    }

    public function validate(Varien_Object $object)
    {
        $product = $object->getProduct();
        if (!($product instanceof Mage_Catalog_Model_Product)) {
            $product = Mage::getModel('catalog/product')->load($object->getProductId());
        }

        $product->setOnSaleFlag($product->getOnSaleFlag() ? '1' : '0');
        $object->setProduct($product);

        return parent::validate($object);
    }

    public function getInputType()
    {
        if ($this->getAttribute()==='on_sale_flag') {
            return 'select';
        }
        if ($this->getAttribute()==='attribute_set_id') {
            return 'select';
        }
        if (!is_object($this->getAttributeObject())) {
            return 'string';
        }
        if ($this->getAttributeObject()->getAttributeCode() == 'category_ids') {
            return 'category';
        }
        switch ($this->getAttributeObject()->getFrontendInput()) {
            case 'select':
                return 'select';

            case 'multiselect':
                return 'multiselect';

            case 'date':
                return 'date';

            case 'boolean':
                return 'boolean';

            default:
                return 'string';
        }
    }

    public function getValueElementType()
    {
        if ($this->getAttribute()==='on_sale_flag') {
            return 'select';
        }
        if ($this->getAttribute()==='attribute_set_id') {
            return 'select';
        }
        if (!is_object($this->getAttributeObject())) {
            return 'text';
        }
        switch ($this->getAttributeObject()->getFrontendInput()) {
            case 'select':
            case 'boolean':
                return 'select';

            case 'multiselect':
                return 'multiselect';

            case 'date':
                return 'date';

            default:
                return 'text';
        }
    }

    public function getValueSelectOptions()
    {
        if ($this->getAttribute() == 'on_sale_flag') {
            return array(
                array('value' => 0, 'label' => 'No'),
                array('value' => 1, 'label' => 'Yes'),
            );
        }

        return parent::getValueSelectOptions();
    }
}
