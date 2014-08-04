<?php
/**
 * Model's helper.
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Tgc_NewRelease
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_NewRelease_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * Returns NewReleases flag from the current request
     *
     * @return bool|int
     */
    public function getNewReleaseFilterValue()
    {
        $request = Mage::app()->getRequest();
        if ($request) {
            return $request->getParam('new_release', false);
        }
        return false;
    }
}
