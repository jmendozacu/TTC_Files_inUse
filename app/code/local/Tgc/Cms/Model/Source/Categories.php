<?php
/**
 * User: mhidalgo
 * Date: 14/03/14
 * Time: 11:21
 */

class Tgc_Cms_Model_Source_Categories
{
    protected $_categories = array();

    public function __construct()
    {
        $categories = Mage::getModel('catalog/category')
            ->getCollection()
            ->addAttributeToSelect('*')
            ->addAttributeToFilter('level', array('gt' => 1))
            ->setStore(Mage::app()->getStore());

        foreach ($categories as $category) {
            if ($category->getId() != 0) {
                if ($category->getLevel() >= 3) {
                    $parent = Mage::getModel('catalog/category')->load($category->getParentId());
                    $label = $parent->getName() . ' > ' . $category->getName();
                } else {
                    $label = $category->getName();
                }
                $this->_categories[] = array(
                    'value' => $category->getId(),
                    'label' => $label,
                );
            }
        }
    }

    public function toOptionArray()
    {
        $optionArray = array();

        foreach ($this->_categories as $category) {
            $optionArray[$category['value']] = $category['label'];
        }

        asort($optionArray);

        return $optionArray;
    }
}