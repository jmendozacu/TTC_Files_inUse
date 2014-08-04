<?php
/**
 * Boutique
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Boutique
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Boutique_Model_Source_Boutiques
{
    public function toOptionArray()
    {
        $options = Mage::getModel('tgc_boutique/boutique')
            ->getCollection()
            ->setOrder('name', 'asc')
            ->toOptionArray();

        return $options;
    }

    public function toArray()
    {
        $options = Mage::getModel('tgc_boutique/boutique')
            ->getCollection()
            ->setOrder('name', 'asc')
            ->toItemArray();

        return $options;
    }

    public function toItemOptionArray()
    {
        $allBoutiques = array(
            'value' => 0,
            'label' => Mage::helper('tgc_boutique')->__('All Boutiques'),
        );

        $options = $this->toOptionArray();
        array_unshift($options, $allBoutiques);

        return $options;
    }

    public function toItemArray()
    {
        $options = $this->toArray();
        $options[0] = Mage::helper('tgc_boutique')->__('All Boutiques');
        ksort($options);

        return $options;
    }
}
