<?php
/**
 * Flat rate shipping collection
 * 
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Shipping
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Shipping_Model_Resource_FlatRate_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    protected function _construct()
    {
        $this->_init('tgc_shipping/flatRate');
    }
    
    /**
     * Adds filter by customer group
     * 
     * @param Mage_Customer_Model_Group|int $group Group model or group ID
     * @throws InvalidArgumentException On incorrect group
     * @return Tgc_Shipping_Model_Resource_FlatRate_Collection
     */
    public function addFilterByGroup($group)
    {
        $groupId = ($group instanceof Mage_Customer_Model_Group) ? $group->getId() : $group;
        if (!is_scalar($groupId)) {
            throw new InvalidArgumentException('Group should be either group model or group ID.');
        }
        
        return $this->addFieldToFilter('customer_group_id', $groupId);
    }

    /**
     * Adds filter by website
     *
     * @param Mage_Core_Model_Website|int $website model or website ID
     * @throws InvalidArgumentException On incorrect website
     * @return Tgc_Shipping_Model_Resource_FlatRate_Collection
     */
    public function addFilterByWebsite($website)
    {
        $websiteId = ($website instanceof Mage_Core_Model_Website) ? $website->getId() : $website;
        if (!is_scalar($websiteId)) {
            throw new InvalidArgumentException('Website should be either website model or website ID.');
        }

        return $this->addFieldToFilter('website_id', $websiteId);
    }
}
