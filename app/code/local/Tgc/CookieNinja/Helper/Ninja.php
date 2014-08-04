<?php
/**
 * Cookie Ninja
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     CookieNinja
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_CookieNinja_Helper_Ninja extends Mage_Core_Helper_Data
{
    public function isNinja()
    {
        return true;
    }

    public function getDefaultAdcode()
    {
        $website = Mage::app()->getWebsite();
        $session = Mage::getSingleton('customer/session');
        $prospect = true;
        if ($session->isLoggedIn() && !$session->getCustomer()->getIsProspect()) {
            $prospect = false;
        }

        switch ($website->getCode()) {
            case 'base':
                if ($prospect) {
                    $adcode = Tgc_CookieNinja_Model_Ninja::DEFAULT_US_ADCODE;
                } else {
                    $adcode = Tgc_CookieNinja_Model_Ninja::DEFAULT_US_AUTH_ADCODE;
                }
                break;
            case 'uk':
                if ($prospect) {
                    $adcode = Tgc_CookieNinja_Model_Ninja::DEFAULT_UK_ADCODE;
                } else {
                    $adcode = Tgc_CookieNinja_Model_Ninja::DEFAULT_UK_AUTH_ADCODE;
                }
                break;
            case 'au':
                if ($prospect) {
                    $adcode = Tgc_CookieNinja_Model_Ninja::DEFAULT_AU_ADCODE;
                } else {
                    $adcode = Tgc_CookieNinja_Model_Ninja::DEFAULT_AU_AUTH_ADCODE;
                }
                break;
            default:
                $adcode = '';
        }

        return $adcode;
    }

    public function shouldDisplayPriorityCode($code)
    {
        $defaultCodes = array(
            Tgc_CookieNinja_Model_Ninja::DEFAULT_US_ADCODE,
            Tgc_CookieNinja_Model_Ninja::DEFAULT_US_AUTH_ADCODE,
            Tgc_CookieNinja_Model_Ninja::DEFAULT_UK_ADCODE,
            Tgc_CookieNinja_Model_Ninja::DEFAULT_UK_AUTH_ADCODE,
            Tgc_CookieNinja_Model_Ninja::DEFAULT_AU_ADCODE,
            Tgc_CookieNinja_Model_Ninja::DEFAULT_AU_AUTH_ADCODE,
        );

        return in_array($code, $defaultCodes) ? false : true;
    }

    public function isAdCodeDefault($code)
    {
        $defaultCodes = array(
            Tgc_CookieNinja_Model_Ninja::DEFAULT_US_ADCODE,
            Tgc_CookieNinja_Model_Ninja::DEFAULT_US_AUTH_ADCODE,
            Tgc_CookieNinja_Model_Ninja::DEFAULT_UK_ADCODE,
            Tgc_CookieNinja_Model_Ninja::DEFAULT_UK_AUTH_ADCODE,
            Tgc_CookieNinja_Model_Ninja::DEFAULT_AU_ADCODE,
            Tgc_CookieNinja_Model_Ninja::DEFAULT_AU_AUTH_ADCODE,
        );

        return in_array($code, $defaultCodes) ? true : false;
    }

    public function validateAdcode($code)
    {
        $adCode = Mage::getModel('tgc_price/adCode')->load($code);
        if (is_null($adCode->getCustomerGroupId())) {
            return false;
        }
        $groupId = $adCode->getCustomerGroupId();
        $groups = Mage::getModel('customer/group')
            ->getCollection()
            ->addFieldToFilter('customer_group_id', array('eq' => $groupId))
            ->addFieldToFilter('start_date',
            array(
                array('to' => Mage::getModel('core/date')->gmtDate()),
                array('start_date', 'null' => ''))
        )
            ->addFieldToFilter('stop_date',
            array(
                array('gteq' => Mage::getModel('core/date')->gmtDate()),
                array('stop_date', 'null' => ''))
        )
            ->addFieldtoFilter('website_id', array('in' => array(0, Mage::app()->getWebsite()->getId())));

        return count($groups) > 0 ? true : false;
    }
}
