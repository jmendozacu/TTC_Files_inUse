<?php
/**
 * Customer address helper
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Customer
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_Customer_Helper_Address extends Mage_Customer_Helper_Address
{
    /**
     * Prepare address attributes for output
     *
     * @param type $address
     * @return array
     */
    public function prepareAttributesForOutput($address, $escapeHtml = true)
    {
        $attributes = $this->getAttributes();

        $data = array();
        foreach ($attributes as $attribute) {
            /* @var $attribute Mage_Customer_Model_Attribute */
            if (!$attribute->getIsVisible()) {
                continue;
            }
            if ($attribute->getAttributeCode() == 'country_id') {
                $data['country'] = $address->getCountryModel()->getName();
            } else if ($attribute->getAttributeCode() == 'region') {
                $data['region'] = Mage::helper('directory')->__($address->getRegion());
            } else {
                $dataModel = Mage_Customer_Model_Attribute_Data::factory($attribute, $address);
                $value     = $dataModel->outputValue(Mage_Customer_Model_Attribute_Data::OUTPUT_FORMAT_TEXT);
                if ($attribute->getFrontendInput() == 'multiline') {
                    $values    = $dataModel->outputValue(Mage_Customer_Model_Attribute_Data::OUTPUT_FORMAT_ARRAY);
                    // explode lines
                    foreach ($values as $k => $v) {
                        $key = sprintf('%s%d', $attribute->getAttributeCode(), $k + 1);
                        $data[$key] = $escapeHtml ? $this->escapeHtml($v) : $v;
                    }
                }
                $data[$attribute->getAttributeCode()] = $escapeHtml ? $this->escapeHtml($value) : $value;
            }
        }

        return $data;
    }
}
