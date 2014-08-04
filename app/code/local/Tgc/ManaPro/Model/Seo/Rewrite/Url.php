<?php
/**
 * @category    Mana
 * @package     Mana_Seo
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */
/**
 * @author Mana Team
 *
 */
class Tgc_ManaPro_Model_Seo_Rewrite_Url extends Mana_Seo_Rewrite_Url {

    /**
     * @param Mana_Seo_Model_Url $parameterUrl
     * @param string $value
     * @return string
     */
    protected function _generatePriceParameter($parameterUrl, $value) {
        /* @var $core Mana_Core_Helper_Data */
        $core = Mage::helper('mana_core');

        $isSlider = $core->isManadevLayeredNavigationInstalled() &&
            in_array($parameterUrl->getFilterDisplay(), array('slider', 'range', 'min_max_slider'));

        $path = '';
        if ($value == '__0__') {
            return $parameterUrl->getFinalUrlKey() . $this->_schema->getFirstValueSeparator() . $value;
        }
        elseif ($value != '__0__,__1__') {
            $values = array();
            foreach (explode('_', $value) as $singleValue) {
                $values[] = explode(',', $singleValue);
            }
            uasort($values, array($this, '_comparePriceValues'));
        }
        else {
            $values = array(explode(',', $value));
        }
        foreach ($values as $singleValue) {
            list($from, $to) = $singleValue;
            if ($path) {
                $path .= $this->_schema->getMultipleValueSeparator();
            }
            if ($isSlider) {
                $path .= $from . $this->_schema->getPriceSeparator() . $to;
            }
            else {
                $index = $from;
                $range = $to;
                if ($this->_schema->getUseRangeBounds()) {
                    if ($index == 0) {
                        $range = Mage::helper('tgc_manapro')->getPriceFilterRangeAndLastAmount();
                        if ($range) {
                            $from = round($range['range_step']*($range['show_more_item_count']-1), 2);
                        }
                        $to = '';
                    } else {
                        $from = ($index - 1) * $range;
                        $to = $from + $range;
                    }
                    $path .= $from . $this->_schema->getPriceSeparator() . $to;
                }
                else {
                    $path .= $index . $this->_schema->getPriceSeparator() . $range;
                }
            }
        }
        $path = $this->_encode($parameterUrl->getFinalUrlKey()) . $this->_schema->getFirstValueSeparator() . $path;

        return $path;
    }
}