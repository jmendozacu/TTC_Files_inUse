<?php
/**
 * User: mhidalgo
 * Date: 11/03/14
 * Time: 14:49
 */
class Tgc_Zmag_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function getWebsiteOptions() {
        $websites = Mage::app()->getWebsites();

        $websitesOptions = array();
        foreach ($websites as $website) {
            $websitesOptions[$website->getId()] = $website->getName();
        }

        return $websitesOptions;
    }

    public function getZmagCollection($websiteId = null, $status = Tgc_Zmag_Model_Zmag::STATUS_ENABLED) {
        if (is_null($websiteId)) {
            $websiteId = Mage::app()->getWebsite()->getId();
        } else {
            if (!array_key_exists($websiteId,$this->getWebsiteOptions())) {
                $websiteId = Mage::app()->getWebsite()->getId();
            }
        }

        if ($status != Tgc_Zmag_Model_Zmag::STATUS_ENABLED || $status != Tgc_Zmag_Model_Zmag::STATUS_DISABLED) {
            $status = Tgc_Zmag_Model_Zmag::STATUS_ENABLED;
        }

        $zmag = Mage::getModel('tgc_zmag/zmag')->getCollection()
            ->addFieldToFilter('website_id',$websiteId)
            ->addFieldToFilter('status',$status)
            ->setOrder('zmag_id');

        return $zmag;
    }

    public function getZmagIcon() {
        $zmagCollection = $this->getZmagCollection();

        $zmagCollection->getSelect()
            ->reset(Zend_Db_Select::COLUMNS)
            ->columns('icon as icon');

        return $zmagCollection->getFirstItem()->getIcon();
    }
}