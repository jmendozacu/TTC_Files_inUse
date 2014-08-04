<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Professors
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Professors_Model_Source_Category
{
    public function toOptionArray($addEmpty = true)
    {
        $collection = Mage::getResourceModel('catalog/category_collection')
            ->addAttributeToSelect('name')
            ->addFieldToFilter('path', array('neq' => '1'))
            ->addOrderField('path')
            ->load();

        $options = array();

        if ($addEmpty) {
            $options[] = array('label' => null, 'value' => null);
        }

        foreach ($collection as $category) {
            $options[] = array(
                'label' => str_repeat('....', $category->getLevel() - 1) . '  ' . $category->getName(),
                'value' => $category->getId()
            );
        }

        return $options;
    }
}