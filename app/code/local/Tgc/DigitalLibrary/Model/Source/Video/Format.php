<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DigitalLibrary
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_DigitalLibrary_Model_Source_Video_Format
{
    protected $_formats = array();

    public function __construct()
    {
        $this->_formats = array(
            array(
                'value' => 0,
                'label' => Mage::helper('tgc_dl')->__('m4v')
            ),
            array(
                'value' => 1,
                'label' => Mage::helper('tgc_dl')->__('wmv')
            ),
        );
    }

    public function toOptionArray()
    {
        $optionArray = array();

        foreach ($this->_formats as $format) {
            $optionArray[$format['value']] = $format['label'];
        }

        return $optionArray;
    }
}
