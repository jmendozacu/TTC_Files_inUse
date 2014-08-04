<?php
/**
 * Boutique
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Boutique
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Boutique_Model_Source_Pages
{
    public function toOptionArray()
    {
        $options = Mage::getModel('tgc_boutique/boutiquePages')
            ->getCollection()
            ->setOrder('page_title', 'asc')
            ->toOptionArray();

        return $options;
    }

    public function toArray()
    {
        $options = Mage::getModel('tgc_boutique/boutiquePages')
            ->getCollection()
            ->setOrder('page_title', 'asc')
            ->toItemArray();

        return $options;
    }

    public function toItemOptionArray()
    {
        $allPages = array(
            'value' => 0,
            'label' => Mage::helper('tgc_boutique')->__('All Pages'),
        );

        $options = $this->toOptionArray();
        array_unshift($options, $allPages);

        return $options;
    }

    public function toItemArray()
    {
        $options = $this->toArray();
        $options[0] = Mage::helper('tgc_boutique')->__('All Pages');
        ksort($options);

        return $options;
    }
}
