<?php

class Tgc_Adcoderouter_Block_Adminhtml_Widget_Form_Element_Renderer_Customdate extends Varien_Data_Form_Element_Date
{
    const BLANK_DATE_VALUE = '11/30/00-1 12:00 AM';

    /**
     * @param null $format
     * @return string
     */
    public function getValue($format = null)
    {
        if (empty($this->_value)) {
            return '';
        }
        if (null === $format) {
            $format = $this->getFormat();
        }

        $defaultFormat = "M/d/yyyy h:mm a";
        $defaultValue = $this->_value->toString($defaultFormat);

        if($defaultValue == self::BLANK_DATE_VALUE) {
            $value = '';
        } else {
            $value = $this->_value->toString($format);
        }

        return $value;
    }
}