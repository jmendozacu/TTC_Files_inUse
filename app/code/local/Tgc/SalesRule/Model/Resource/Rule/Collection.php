<?php
/**
 * Override resource model for coupon code import
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     SalesRule
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_SalesRule_Model_Resource_Rule_Collection extends Mage_SalesRule_Model_Resource_Rule_Collection
{
    /**
     * Filter collection by specified website, customer group, coupon code, date.
     * Filter collection to use only active rules.
     * Involved sorting by sort_order column.
     *
     * @param int $websiteId
     * @param int $customerGroupId
     * @param string $couponCode
     * @param string|null $now
     * @use $this->addWebsiteGroupDateFilter()
     *
     * @return Mage_SalesRule_Model_Resource_Rule_Collection
     */
    public function setValidationFilter($websiteId, $customerGroupId, $couponCode = '', $now = null)
    {
        if (!$this->getFlag('validation_filter')) {

            /* We need to overwrite joinLeft if coupon is applied */
            $this->getSelect()->reset();
            Mage_Rule_Model_Resource_Rule_Collection_Abstract::_initSelect();

            if ($customerGroupId == Tgc_SalesRule_Model_Validator::GROUP_ID_WILDCARD) {
                $this->addWebsiteDateFilter($websiteId, $now);
            } else {
                $this->addWebsiteGroupDateFilter($websiteId, $customerGroupId, $now);
            }
            $select = $this->getSelect();

            if (strlen($couponCode)) {
                $select->joinLeft(
                    array('rule_coupons' => $this->getTable('salesrule/coupon')),
                    'main_table.rule_id = rule_coupons.rule_id ',
                    array('code')
                );
                $select->where('(main_table.coupon_type = ? ', Mage_SalesRule_Model_Rule::COUPON_TYPE_NO_COUPON)
                    ->orWhere('(main_table.coupon_type = ? AND rule_coupons.type = 0',
                    Mage_SalesRule_Model_Rule::COUPON_TYPE_AUTO)
                    ->orWhere('main_table.coupon_type = ? AND main_table.use_auto_generation = 1 ' .
                    'AND rule_coupons.type = 1', Mage_SalesRule_Model_Rule::COUPON_TYPE_SPECIFIC)
                    ->orWhere('main_table.coupon_type = ? AND main_table.use_auto_generation = 0 ' .
                    'AND rule_coupons.type = 0)', Mage_SalesRule_Model_Rule::COUPON_TYPE_SPECIFIC)
                    ->where('rule_coupons.code = ?)', $couponCode);
            } else {
                $this->addFieldToFilter('main_table.coupon_type', Mage_SalesRule_Model_Rule::COUPON_TYPE_NO_COUPON);
            }
            $this->setOrder('sort_order', self::SORT_ORDER_ASC);
            $this->setFlag('validation_filter', true);
        }

        return $this;
    }

    /**
     * Filter collection by website(s) and date.
     * Filter collection to only active rules.
     * Sorting is not involved
     *
     * @param int $websiteId
     * @param string|null $now
     * @use $this->addWebsiteFilter()
     *
     * @return Mage_SalesRule_Model_Mysql4_Rule_Collection
     */
    public function addWebsiteDateFilter($websiteId, $now = null)
    {
        if (!$this->getFlag('website_group_date_filter')) {
            if (is_null($now)) {
                $now = Mage::getModel('core/date')->date('Y-m-d');
            }

            $this->addWebsiteFilter($websiteId);

            $this->getSelect()
                ->where('from_date is null or from_date <= ?', $now)
                ->where('to_date is null or to_date >= ?', $now);

            $this->addIsActiveFilter();

            $this->setFlag('website_group_date_filter', true);
        }

        return $this;
    }
}
