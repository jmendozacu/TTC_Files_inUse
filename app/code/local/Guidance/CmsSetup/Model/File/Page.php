<?php
/**
 * Page file model
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Guidance
 * @package     CmsSetup
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Guidance_CmsSetup_Model_File_Page extends Guidance_CmsSetup_Model_File
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
     * Returns store codes
     *
     * @return array<string>
     */
    public function getStores()
    {
        return array_filter(array_map('trim', explode(',', $this->_getHeader('stores'))));
    }

    /**
     * Returns true if page is published
     *
     * @return boolean
     */
    public function isPublished()
    {
        return strtolower($this->_getHeader('status')) == 'published';
    }

    /**
     * Returns content heading
     *
     * @return string
     */
    public function getContentHeading()
    {
        return $this->_getHeader('content heading');
    }

    /**
     * Returns layout of page
     *
     * @return string
     */
    public function getLayout()
    {
        return $this->_getHeader('layout');
    }

    /**
     * Returns layout update XML of page
     *
     * @return string
     */
    public function getLayoutUpdate()
    {
        return $this->_getHeader('layout update');
    }

    /**
     * Returns title of page
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->_getHeader('title');
    }
}