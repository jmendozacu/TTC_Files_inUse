<?php
/**
 * Digital Library
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DigitalLibrary
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_DigitalLibrary_Model_CrossPlatformResume extends Mage_Core_Model_Abstract
{
    const CACHE_TAG         = 'cross_platform_resume';

    protected $_eventPrefix = 'cross_platform_resume';
    protected $_eventObject = 'crossPlatformResume';

    protected function _construct()
    {
        $this->_init('tgc_dl/crossPlatformResume');
    }
}
