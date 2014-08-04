<?php
/**
 *
 */
class Tgc_Bazaarvoice_Model_System_Config_Source_ProductIdentifier
{
    const VALUE_SKU       = 'sku';
    const VALUE_COURSE_ID = 'course_id';
    const LABEL_SKU       = 'SKU';
    const LABEL_COURSE_ID = 'Course ID';

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' => self::VALUE_SKU,       'label' => Mage::helper('tgc_bv')->__(self::LABEL_SKU)),
            array('value' => self::VALUE_COURSE_ID, 'label' => Mage::helper('tgc_bv')->__(self::LABEL_COURSE_ID)),
        );
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        return array(
            self::VALUE_SKU       => Mage::helper('tgc_bv')->__(self::LABEL_SKU),
            self::VALUE_COURSE_ID => Mage::helper('tgc_bv')->__(self::LABEL_COURSE_ID),
        );
    }

}
