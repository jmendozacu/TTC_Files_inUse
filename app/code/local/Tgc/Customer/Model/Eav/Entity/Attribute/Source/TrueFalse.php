<?php

class Tgc_Customer_Model_Eav_Entity_Attribute_Source_TrueFalse extends Mage_Eav_Model_Entity_Attribute_Source_Boolean
{
    /**
     * Retrieve all options array
     *
     * @return array
     */
    public function getAllOptions()
    {
        if (is_null($this->_options)) {
            $this->_options = array(
                array(
                    'label' => Mage::helper('eav')->__('True'),
                    'value' => self::VALUE_YES
                ),
                array(
                    'label' => Mage::helper('eav')->__('False'),
                    'value' => self::VALUE_NO
                ),
            );
        }
        return $this->_options;
    }

    /**
     * Get a text for index option value
     *
     * @param  string|int $value
     * @return string|bool
     */
    public function getIndexOptionText($value)
    {
        switch ($value) {
            case self::VALUE_YES:
                return 'True';
            case self::VALUE_NO:
                return 'False';
            default:
                return 'False';
        }

        return parent::getIndexOptionText($value);
    }
}