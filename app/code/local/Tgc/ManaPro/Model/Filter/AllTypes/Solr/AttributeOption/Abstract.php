<?php
/**
 * Description
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Tgc_ManaPro
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_ManaPro_Model_Filter_AllTypes_Solr_AttributeOption_Abstract
{
    /**
     * @var Mage_Catalog_Model_Resource_Eav_Attribute
     */
    private $_attribute;

    /**
     * @var int
     */
    private $_optionId;

    public function __construct($args = array())
    {
        if (empty($args['attribute'])) {
            throw new InvalidArgumentException('"attribute" argument is required');
        }

        $this->_attribute = $args['attribute'];
        $this->_optionId = (isset($args['option_id']) ? $args['option_id'] : null);
    }

    /**
     * @return Mage_Catalog_Model_Resource_Eav_Attribute
     */
    public function getAttribute()
    {
        return $this->_attribute;
    }

    /**
     * @return int
     */
    public function getOptionId()
    {
        return $this->_optionId;
    }

    protected function _getOptionIdByText($optionText)
    {
        $attribute = $this->getAttribute();
        return $attribute->getSource()->getOptionId($optionText);
    }
}