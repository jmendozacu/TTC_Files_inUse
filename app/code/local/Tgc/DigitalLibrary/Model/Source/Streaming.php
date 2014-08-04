<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DigitalLibrary
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_DigitalLibrary_Model_Source_Streaming extends Mage_Eav_Model_Entity_Attribute_Source_Abstract
{
    const AUDIO_ONLY = 0;
    const VIDEO_ONLY = 1;
    const BOTH       = 2;
    const NONE       = 3;

    public function __construct()
    {
        $this->_options = array(
            array(
                'value' => self::AUDIO_ONLY,
                'label' => Mage::helper('tgc_dl')->__('Audio Only')
            ),
            array(
                'value' => self::VIDEO_ONLY,
                'label' => Mage::helper('tgc_dl')->__('Video Only')
            ),
            array(
                'value' => self::BOTH,
                'label' => Mage::helper('tgc_dl')->__('Both Audio and Video')
            ),
            array(
                'value' => self::NONE,
                'label' => Mage::helper('tgc_dl')->__('None')
            ),
        );
    }

    public function toOptionArray()
    {
        $optionArray = array();

        foreach ($this->_options as $option) {
            $optionArray[$option['value']] = $option['label'];
        }

        return $optionArray;
    }

    public function getAllOptions()
    {
        return $this->_options;
    }
}
