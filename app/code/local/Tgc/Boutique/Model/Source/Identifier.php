<?php
/**
 * Boutique
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Boutique
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Boutique_Model_Source_Identifier
{
    public function toOptionArray()
    {
        $emptyOption = array(
            array(
                'value' => null,
                'label' => '-- Please Select Page Content --',
            )
        );

        $options = Mage::getModel('cms/block')
            ->getCollection()
            ->setOrder('title', 'asc')
            ->toOptionArray();

        return array_merge($emptyOption, $options);
    }
}
