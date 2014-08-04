<?php
/**
 * Ad codes' collection
 * 
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Guidance
 * @package     Setup
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Price_Model_Resource_AdCode_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    protected function _construct()
    {
        $this->_init('tgc_price/adCode');
    }
    
    /**
     * Adds filter by customer group
     * 
     * @param Mage_Customer_Model_Group|int $group Group model or group ID
     * @throws InvalidArgumentException On incorrect group
     * @return Tgc_Price_Model_Resource_AdCode_Collection Self
     */
    public function addFilterByGroup($group)
    {
        $groupId = ($group instanceof Mage_Customer_Model_Group) ? $group->getId() : $group;
        if (!is_scalar($groupId)) {
            throw new InvalidArgumentException('Group should be either group model or group ID.');
        }
        
        return $this->addFieldToFilter('customer_group_id', $groupId);
    }
}
