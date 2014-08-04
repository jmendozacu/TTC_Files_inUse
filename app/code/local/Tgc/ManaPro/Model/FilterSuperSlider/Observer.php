<?php
/**
 * @category    Mana
 * @package     ManaPro_FilterSuperSlider
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */
/**
 * This class observes certain (defined in etc/config.xml) events in the whole system and provides public methods -
 * handlers for these events.
 * @author Mana Team
 *
 */
class Tgc_ManaPro_Model_FilterSuperSlider_Observer extends ManaPro_FilterSuperSlider_Model_Observer
{
    /**
     * Applies specific formatting to price range (handles event "m_render_price_range")
     * @param Varien_Event_Observer $observer
     */
    public function renderPriceRange($observer)
    {
        /* @var $range array */ $range = $observer->getEvent()->getRange();
        /* @var $model Mana_Filters_Model_Filter_Decimal */ $model = $observer->getEvent()->getModel();
        /* @var $result Varien_Object */ $result = $observer->getEvent()->getResult();

        /* @var $helper ManaPro_FilterSuperSlider_Helper_Data */
        $helper = Mage::helper(strtolower('ManaPro_FilterSuperSlider'));
        //CUSTOM CODE
        // the client doesn't want to format first number in range like 0.00 - 99.99 or 100.00 - 199.99
        //Should be: 0 - 99.99 or 100 - 199.99
        $oldDecimalDigits = $model->getFilterOptions()->getSliderDecimalDigits();
        $model->getFilterOptions()->setSliderDecimalDigits(0);
        $fromPrice = $helper->formatNumber($range['from'], $model->getFilterOptions());
        $model->getFilterOptions()->setSliderDecimalDigits($oldDecimalDigits);
        //CUSTOM CODE END
        $toPrice = $helper->formatNumber($range['to'], $model->getFilterOptions());
        $result->setLabel(Mage::helper('catalog')->__('%s - %s', $fromPrice, $toPrice));
    }
}