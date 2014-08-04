<?php
/**
 * Customer model
 * 
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Guidance
 * @package     Setup
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Price_Model_Customer_Group extends Mage_Customer_Model_Group
{
    private $_adCodes = null;
    
    /**
     * Returns array of ad codes assotiated with the model
     * 
     * @return array Codes
     */
    public function getAdCodes()
    {
        if (!$this->_adCodes) {
            $this->_adCodes = $this->_loadAdCodes();
        }
    
        return $this->_adCodes;
    }
    
    /**
     * Loads ad codes assotiated with model
     * 
     * @return array Codes
     */
    protected function _loadAdCodes()
    {
        if ($this->isObjectNew()) {
            return array();
        }
        
        $codes = Mage::getResourceModel('tgc_price/adCode_collection')
            ->addFilterByGroup($this)
            ->addOrder('code', Varien_Data_Collection::SORT_ORDER_ASC)
            ->toArray(array('code'));
    
        return array_map(function ($i) {return $i['code'];}, $codes['items']);
    }
}
