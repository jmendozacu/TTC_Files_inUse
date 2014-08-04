<?php
/**
 * Description
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Tgc_ManaPro
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_ManaPro_Model_Filter_AllTypes_Solr_AttributeOption_Collection
    extends Tgc_ManaPro_Model_Filter_AllTypes_Solr_AttributeOption_Default
{
    private static $_collectionOptionId;

    const OPTION_TEXT = "Collections";

    /**
     * Checks, can this processor process current optionId of current attribute
     *
     * @return bool
     */
    public function canProcess()
    {
        if (!isset(self::$_collectionOptionId)) {
            self::$_collectionOptionId = $this->_getOptionIdByText(self::OPTION_TEXT);
        }
        return $this->getOptionId() == self::$_collectionOptionId;
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
