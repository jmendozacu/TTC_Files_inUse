<?php
/**
 * Block file model
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Guidance
 * @package     CmsSetup
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Guidance_CmsSetup_Model_File_Block extends Guidance_CmsSetup_Model_File
{
    /**
     * Returns identifier
     *
     * @return string
     */
    public function getId()
    {
        return $this->_getHeader('id');
    }

    /**
     * Returns title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->_getHeader('title');
    }

    /**
     * Returns stores codes
     *
     * @return array<string>
     */
    public function getStores()
    {
        return array_filter(array_map('trim', explode(',', $this->_getHeader('stores'))));
    }

    /**
     * Return true if enabled
     *
     * @return boolean
     */
    public function isEnabled()
    {
        return strtolower($this->_getHeader('status')) == 'enabled';
    }
}