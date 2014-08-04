<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category
 * @package
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Professors_Model_Professor_Attribute_Source extends Mage_Eav_Model_Entity_Attribute_Source_Abstract
{
    private $_allOptions;

    public function getAllOptions($withEmpty = true)
    {
        if (!$this->_allOptions) {
            $this->_allOptions = $this->_loadOptions();
        }

        return $withEmpty
            ? array_unshift($this->_allOptions, array('label' => null, 'value' => null))
            : $this->_allOptions;
    }

    public function getOptionText($value)
    {
        $isMultiple = false;
        if (strpos($value, ',')) {
            $isMultiple = true;
            $value = explode(',', $value);
        }

        $options = $this->getAllOptions(false);

        if ($isMultiple) {
            $values = array();
            foreach ($options as $item) {
                if (in_array($item['value'], $value)) {
                    $values[] = $item['label'];
                }
            }
            return $values;
        }

        $p = Mage::getModel('profs/professor')->load($value);

        return $p->isObjectNew() ? null : "{$p->getLastName()}, {$p->getFirstName()} {$p->getTitle()}";
    }

    private function _loadOptions()
    {
        return Mage::getResourceModel('profs/professor_collection')
            ->setOrder('last_name', Varien_Data_Collection_Db::SORT_ORDER_ASC)
            ->toOptionArray();
    }
}