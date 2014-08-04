<?php

class Tgc_Adcoderouter_Model_Field_Source_Adtype extends Varien_Object
{
    const SPACE_AD_ID = 1;
    const DRTV_AD_ID = 2;

    protected $_options = array(
        0                   => 'None',
        self::SPACE_AD_ID   => 'Space Ad',
        self::DRTV_AD_ID    => 'DRTV Ad',
    );

    public function getAllOptions()
    {
        return $this->_options;
    }

    public function getOptionArray()
    {
        $options = array();

        $options[] = array(
            'value' => 1,
            'label' => 'None'
        );

        $options[] = array(
            'value' => 2,
            'label' => 'Space Ad'
        );

        $options[] = array(
            'value' => 3,
            'label' => 'DRTV'
        );

        return $options;
    }
}
