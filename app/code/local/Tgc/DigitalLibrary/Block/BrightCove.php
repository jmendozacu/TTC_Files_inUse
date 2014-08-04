<?php
/**
 * BrightCove widget
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DigitalLibrary
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_DigitalLibrary_Block_BrightCove extends Mage_Core_Block_Template
implements Mage_Widget_Block_Interface
{
    const JS_LOCATION      = 'http://admin.brightcove.com/js/BrightcoveExperiences.js';
    const SMART_PLAYER_JS  = 'http://admin.brightcove.com/js/api/SmartPlayerAPI.js';
    const FLASH_URL        = 'http://c.brightcove.com/services/viewer/federated_f9';
    const DEFAULT_BG_COLOR = '#ffffff';
    const DEFAULT_WIDTH    = '100%';
    const DEFAULT_HEIGHT   = '100%';

    private $_expId;

    /**
     * Initialize block's cache and template settings
     */
    protected function _construct()
    {
        parent::_construct();

        if (empty($this->_template)) {
            $this->setTemplate('digital-library/brightcove/widget/player.phtml');
        }
    }

    /**
     * Returns a valid hex value for the bg color
     */
    public function getBgColor()
    {
        $bgColor = $this->getData('bg_color');

        if (empty($bgColor) || !preg_match('/^#[a-f0-9]{6}$/i', $bgColor)) {
            $bgColor = self::DEFAULT_BG_COLOR;
        }

        return $bgColor;
    }

    public function getPlayerWidth()
    {
        $width = $this->getData('player_width');

        if (empty($width) || !is_numeric($width)) {
            $width = self::DEFAULT_WIDTH;
        }

        return $width;
    }

    public function getPlayerHeight()
    {
        $height = $this->getData('player_height');

        if (empty($height) || !is_numeric($height)) {
            $height = self::DEFAULT_HEIGHT;
        }

        return $height;
    }

    public function getDynamicStreaming()
    {
        return (string)$this->getData('dynamic_streaming');
    }
}
