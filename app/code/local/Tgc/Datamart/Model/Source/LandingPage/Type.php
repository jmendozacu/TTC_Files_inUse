<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Datamart
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Datamart_Model_Source_LandingPage_Type
{
    const TYPE_EMAIL    = 'Email';
    const TYPE_BUFFET   = 'Buffet';
    const TYPE_RADIO    = 'Radio';
    const EMAIL_VALUE   = 0;
    const BUFFET_VALUE  = 1;
    const RADIO_VALUE   = 2;

    protected $_types = array();

    public function __construct()
    {
        $this->_types = array(
            array(
                'value' => self::EMAIL_VALUE,
                'label' => Mage::helper('tgc_datamart')->__(self::TYPE_EMAIL)
            ),
            array(
                'value' => self::BUFFET_VALUE,
                'label' => Mage::helper('tgc_datamart')->__(self::TYPE_BUFFET)
            ),
            array(
                'value' => self::RADIO_VALUE,
                'label' => Mage::helper('tgc_datamart')->__(self::TYPE_RADIO)
            )
        );
    }

    public function toOptionArray()
    {
        $optionArray = array();

        foreach ($this->_types as $type) {
            $optionArray[$type['value']] = $type['label'];
        }

        return $optionArray;
    }
}
