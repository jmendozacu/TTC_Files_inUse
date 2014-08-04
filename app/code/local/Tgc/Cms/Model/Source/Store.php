<?php
/**
 * Cms additions
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Cms
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Cms_Model_Source_Store
{
    const ALL_STORES      = 0;
    const ALL_STORES_TEXT = 'All Stores';

    protected $_stores = array();

    public function __construct()
    {
        $this->_stores = array(
            array(
                'value' => self::ALL_STORES,
                'label' => self::ALL_STORES_TEXT,
            )
        );

        $stores = Mage::getModel('core/store')->getCollection();
        foreach ($stores as $store) {
            if ($store->getId() != 0) {
                $this->_stores[] = array(
                    'value' => $store->getId(),
                    'label' => $store->getWebsite()->getName() . ' - ' . $store->getName(),
                );
            }
        }
    }

    public function toOptionArray()
    {
        $optionArray = array();

        foreach ($this->_stores as $store) {
            $optionArray[$store['value']] = $store['label'];
        }

        return $optionArray;
    }
}