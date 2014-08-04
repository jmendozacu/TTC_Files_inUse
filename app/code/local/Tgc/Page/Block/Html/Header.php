<?php
/**
 * Page
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Page
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Page_Block_Html_Header extends Mage_Page_Block_Html_Header
{
    const CACHE_TAG = 'HEADERBLOCK';

    public function _construct()
    {
        parent::_construct();

        $this->addData(array('cache_lifetime' => null));
        $this->addCacheTag(array(
            self::CACHE_TAG,
        ));
    }

    public function getCacheKeyInfo()
    {
        $info = array(
            'customer'    => Mage::getSingleton('customer/session')->getCustomer()->getId(),
            'logged_in'   => Mage::getSingleton('customer/session')->isLoggedIn() ? 1 : 0,
            'is_prospect' => Mage::getSingleton('customer/session')->getCustomer()->getIsProspect(),
        );

        return array_merge($info, parent::getCacheKeyInfo());
    }
}
