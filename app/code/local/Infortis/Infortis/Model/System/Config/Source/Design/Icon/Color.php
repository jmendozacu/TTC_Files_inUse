<?php

class Infortis_Infortis_Model_System_Config_Source_Design_Icon_Color
{
    public function toOptionArray()
    {
		return array(
			array('value' => 'b',		'label' => Mage::helper('ultimo')->__('Black')),
            array('value' => 'w',		'label' => Mage::helper('ultimo')->__('White'))
        );
    }
}