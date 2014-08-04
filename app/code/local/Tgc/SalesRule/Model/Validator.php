<?php
/**
 * Override coupon validator
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     SalesRule
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_SalesRule_Model_Validator extends Mage_SalesRule_Model_Validator
{
    const GROUP_ID_WILDCARD = '*';

    /**
     * Init validator
     * Init process load collection of rules for specific website,
     * customer group and coupon code
     *
     * @param   int $websiteId
     * @param   int $customerGroupId
     * @param   string $couponCode
     * @return  Mage_SalesRule_Model_Validator
     */
    public function init($websiteId, $customerGroupId, $couponCode)
    {
        $this->setWebsiteId($websiteId)
            ->setCustomerGroupId($customerGroupId)
            ->setCouponCode($couponCode);

        $resource = Mage::getResourceModel('salesrule/rule_collection');
        $allowedCoupons = Mage::helper('tgc_dax')->isGroupAllowedCoupons($customerGroupId);
        Mage::getSingleton('customer/session')->setAllowedCoupons($allowedCoupons);
        $isImported = Mage::helper('tgc_dax')->isImportedCoupon($couponCode);
        if ($isImported && $allowedCoupons) {
            $customerGroupId = self::GROUP_ID_WILDCARD;
            $this->setCustomerGroupId(self::GROUP_ID_WILDCARD);
        }

        $key = $websiteId . '_' . $customerGroupId . '_' . $couponCode;
        if (!isset($this->_rules[$key])) {
            if (!$allowedCoupons) {
                $this->_rules[$key] = $resource
                    ->setValidationFilter(-1, -1, null)
                    ->load();
            } else {
                $this->_rules[$key] = $resource
                    ->setValidationFilter($websiteId, $customerGroupId, $couponCode)
                    ->load();
            }
        }
        return $this;
    }

    protected function _getItemQty($item, $rule)
    {
        $qty = $item->getTotalQty();

        return $rule->getDiscountQty() > 0 ? min($qty, $rule->getDiscountQty()) : $qty;
    }
}
