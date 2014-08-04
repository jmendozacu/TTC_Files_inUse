<?php

class Tgc_Lectures_Model_Eav_Entity_Attribute_Source_Freelectstatus extends Mage_Core_Model_Abstract
{

    protected $_options = array(
        array(
            'value'     => 1,
            'label'     => 'subscribe',
        ),
        array(
            'value'     => 2,
            'label'     => 'un-confirmed',
        ),
        array(
            'value'     => 3,
            'label'     => 'unsubscribed',
        ),
    );

    public function getAllOptions()
    {
        return $this->_options;
    }
}