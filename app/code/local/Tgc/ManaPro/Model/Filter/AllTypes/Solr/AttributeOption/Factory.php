<?php
/**
 * Description
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Tgc_ManaPro
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_ManaPro_Model_Filter_AllTypes_Solr_AttributeOption_Factory
{
    protected $_optionProcessors = array(
        'onsale' => 'tgc_manapro/filter_allTypes_solr_attributeOption_onSale',
        'collection' => 'tgc_manapro/filter_allTypes_solr_attributeOption_collection'
    );

    protected $_defaultProcessor = 'tgc_manapro/filter_allTypes_solr_attributeOption_default';

    private $_cachedProcessors = array();

    /**
     * @param Mage_Catalog_Model_Resource_Eav_Attribute $attribute
     * @param int $optionId
     * @return Tgc_ManaPro_Model_Filter_AllTypes_Solr_AttributeOption_Interface
     */
    public function getOptionProcessor($attribute, $optionId)
    {
        if (!isset($this->_cachedProcessors[$attribute->getAttributeCode()][$optionId])) {
            $processorFound = false;
            foreach ($this->_optionProcessors as $processorModel) {
                /* @var $processor Tgc_ManaPro_Model_Filter_AllTypes_Solr_AttributeOption_Interface */
                $processor = Mage::getModel($processorModel,
                    array('attribute' => $attribute, 'option_id' => $optionId)
                );
                if ($processor->canProcess()) {
                    $this->_cachedProcessors[$attribute->getAttributeCode()][$optionId] = $processor;
                    $processorFound = true;
                    break;
                }
            }
            if (!$processorFound) {
                $this->_cachedProcessors[$attribute->getAttributeCode()][$optionId] =
                    Mage::getModel($this->_defaultProcessor, array('attribute' => $attribute, 'option_id' => $optionId));
            }
        }
        return $this->_cachedProcessors[$attribute->getAttributeCode()][$optionId];
    }
}