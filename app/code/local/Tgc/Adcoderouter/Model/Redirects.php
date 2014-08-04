<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Guidance
 * @package     Adcoderouter
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_Adcoderouter_Model_Redirects extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('adcoderouter/redirects');
    }

    public function reset() {
        $this->clearData();
        return $this;
    }

    public function clearData()
    {
        foreach ($this->_data as $data){
            if (is_object($data) && method_exists($data, 'reset')){
                $data->reset();
            }
        }

        $this->setData(array());
        $this->setOrigData();
        return $this;
    }
}