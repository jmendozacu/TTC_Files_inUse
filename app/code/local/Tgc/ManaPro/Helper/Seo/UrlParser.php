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
class Tgc_ManaPro_Helper_Seo_UrlParser extends Mana_Seo_Helper_UrlParser  {

    /**
     * @param Mana_Seo_Model_ParsedUrl $token
     * @param string $multipleValueSeparator
     * @param string $priceSeparator
     * @param string $minusSymbol
     * @return int[][] | bool
     */
    protected function _scanNumbers($token, $multipleValueSeparator, $priceSeparator, $minusSymbol) {
        $cInvalid = Mana_Seo_Model_ParsedUrl::CORRECT_INVALID_PRICE_FILTER_VALUE;

        /* @var $mbstring Mana_Core_Helper_Mbstring */
        $mbstring = Mage::helper('mana_core/mbstring');

        $text = $token->getTextToBeParsed();

        $pos = 0;
        $result = array();

        $multipleValueSeparatorLength = $mbstring->strlen($multipleValueSeparator);
        $priceSeparatorLength = $mbstring->strlen($priceSeparator);
        $minusLength = $mbstring->strlen($minusSymbol);

        while ($pos !== false) {
            $pair = array();

            // reading from minus sign
            if (($nextPos = $mbstring->strpos($text, $minusSymbol, $pos)) === $pos) {
                $minusText = $minusSymbol;
                $pos += $minusLength;
            }
            else {
                $minusText = '';
            }

            // reading from value
            if (($nextPos = $mbstring->strpos($text, $priceSeparator, $pos)) !== false) {
                $pair['from'] = $minusText.$mbstring->substr($text, $pos, $nextPos - $pos);
                $pos = $nextPos + $priceSeparatorLength;
                if ($pair['from'] === '' || !is_numeric($pair['from'])) {
                    $this->_correct($token, $cInvalid, __LINE__, $text);
                    return false;
                }
            }
            else {
                $this->_correct($token, $cInvalid, __LINE__, $text);
                return false;
            }

            /* CUSTOM CODE: scan such lines, like "450--400-450" correctly
            // reading to minus sign
            if (($nextPos = $mbstring->strpos($text, $minusSymbol, $pos)) === $pos) {
                $minusText = $minusSymbol;
                $pos += $minusLength;
            }
            else {
                $minusText = '';
            }
            */

            // reading from value
            if (($nextPos = $mbstring->strpos($text, $multipleValueSeparator, $pos)) !== false) {
                $pair['to'] = $minusText . $mbstring->substr($text, $pos, $nextPos - $pos);
                $pos = $nextPos + $multipleValueSeparatorLength;
            }
            else {
                $pair['to'] = $minusText . $mbstring->substr($text, $pos);
                $pos = false;
            }
            if ($pair['to'] !== '' && !is_numeric($pair['to'])) {
                $this->_correct($token, $cInvalid, __LINE__, $text);

                return false;
            }

            $result[] = $pair;
        }

        return count($result) ? $result : false;
    }

    /**
     * @param Mana_Seo_Model_ParsedUrl $token
     * @param int $from
     * @param int $to
     * @return bool
     */
    protected function _setPriceFilter($token, $from, $to) {
        $cSwapRangeBounds = Mana_Seo_Model_ParsedUrl::CORRECT_SWAP_RANGE_BOUNDS;

        /* @var $core Mana_Core_Helper_Data */
        $core = Mage::helper('mana_core');

        $isSlider = $core->isManadevLayeredNavigationInstalled() &&
            in_array($token->getParameterUrl()->getFilterDisplay(), array('slider', 'range', 'min_max_slider'));
        if ($this->_schema->getUseRangeBounds() || $isSlider) {
            $from = 0 + $from;
            $to = 0 + $to;
            if ($to == 0) {
                Mage::unregister('manadev_max_ranges');
                Mage::register('manadev_max_ranges', array(
                    'from' => $from,
                    'to' => ''
                ));
            } else {
                if ($from > $to) {
                    $this->_notice($token, $cSwapRangeBounds, __LINE__, "$from,$to");
                    $t = $from;
                    $from = $to;
                    $to = $t;
                }
            }
            if ($isSlider) {
                $token->addQueryParameter($token->getAttributeCode(), "$from,$to");
            }
            else {
                if ($from == $to) {
                    return false;
                }
                if ($to == 0) {
                    Mage::unregister('manadev_max_ranges');
                    Mage::register('manadev_max_ranges', array(
                        'from' => $from,
                        'to' => ''
                    ));
                    $range = Mage::helper('tgc_manapro')->getPriceFilterRangeAndLastAmount();
                    if ($range) {
                        $range = $range['range_step'];
                    }
                    $index = 0;
                } else {
                    $range = $to - $from;
                    $rawIndex = $to / $range;
                    $index = round($rawIndex);
                    if (abs($index - $rawIndex) >= 0.001) {
                        return false;
                    }
                }

                $token->addQueryParameter($token->getAttributeCode(), "$index,$range");
            }
        }
        else {
            $token->addQueryParameter($token->getAttributeCode(), "$from,$to");
        }

        return true;
    }
}
