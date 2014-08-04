<?php
/**
 * Dax default price entity for importexport
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Dax
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Dax_Model_Import_Entity_Price_Default extends Tgc_Dax_Model_Import_Entity_Price_Attribute
{
    /**
     * Returns special price attribute code because default pricing is mapped to special price
     *
     * @see Tgc_Dax_Model_Import_Entity_Price_Attribute::_getPriceAttributeCode()
     */
    protected function _getPriceAttributeCode()
    {
        return 'special_price';
    }

    /**
     * Returns entity code
     *
     * @see Mage_ImportExport_Model_Import_Entity_Abstract::getEntityTypeCode()
     */
    public function getEntityTypeCode()
    {
        return 'default_price';
    }
}
