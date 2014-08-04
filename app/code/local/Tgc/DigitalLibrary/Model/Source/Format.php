<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DigitalLibrary
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_DigitalLibrary_Model_Source_Format
{
    const AUDIO = 0;
    const VIDEO = 1;

    protected $_formats = array();

    public function __construct()
    {
        $this->_formats = array(
            array(
                'value' => self::AUDIO,
                'label' => Mage::helper('tgc_dl')->__('Audio')
            ),
            array(
                'value' => self::VIDEO,
                'label' => Mage::helper('tgc_dl')->__('Video')
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