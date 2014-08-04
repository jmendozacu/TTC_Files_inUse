<?php
/**
 * Description
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Tgc_ManaPro
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_ManaPro_Model_Filter_AllTypes_Solr_AttributeOption_OnSale
    extends Tgc_ManaPro_Model_Filter_AllTypes_Solr_AttributeOption_Abstract
    implements Tgc_ManaPro_Model_Filter_AllTypes_Solr_AttributeOption_Interface
{
    private static $_onSaleOptionId;

    const OPTION_TEXT = "On Sale";

    /**
     * Checks, can this processor process current optionId of current attribute
     *
     * @return bool
     */
    public function canProcess()
    {
        return $this->getOptionId() == $this->getOnSaleOptionId();
    }

    /**
     * @return int
     */
    public function getOnSaleOptionId()
    {
        if (!isset(self::$_onSaleOptionId)) {
            self::$_onSaleOptionId = $this->_getOptionIdByText(self::OPTION_TEXT);
        }
        return self::$_onSaleOptionId;
    }

    /**
     * Returns filterable value for Solr request
     *
     * @return string|int
     */
    public function getFilterableValue()
    {
        return Mage::helper('tgc_solr')->getOnSaleOptionValue();
    }

    /**
     * Should this option added to AND operator of Solr or not.
     *
     * @return bool
     */
    public function isForAndOperand()
    {
        return true;
    }
}
