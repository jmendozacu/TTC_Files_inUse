<?php
/**
 * Description
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Tgc_ManaPro
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_ManaPro_Model_Filter_AllTypes_Solr_AttributeOption_Default
    extends Tgc_ManaPro_Model_Filter_AllTypes_Solr_AttributeOption_Abstract
    implements Tgc_ManaPro_Model_Filter_AllTypes_Solr_AttributeOption_Interface
{
    /**
     * Checks, can this processor process current optionId of current attribute
     *
     * @return bool
     */
    public function canProcess()
    {
        return true;
    }

    /**
     * Returns filterable value for Solr request
     *
     * @return string|int
     */
    public function getFilterableValue()
    {
        return $this->getOptionId();
    }

    /**
     * Should this option added to AND operator of Solr or not.
     *
     * @return bool
     */
    public function isForAndOperand()
    {
        return false;
    }
}