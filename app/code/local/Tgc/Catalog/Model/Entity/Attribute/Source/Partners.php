<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Catalog
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_Catalog_Model_Entity_Attribute_Source_Partners extends Mage_Eav_Model_Entity_Attribute_Source_Table
{
    public function getAllOptions()
    {
        $options = array();

        $options[] = array(
            'value' => '',
            'label' => 'Select'
        );

        /** @var $collection Tgc_Cms_Model_Resource_Partners_Collection */
        $collection = Mage::getModel('tgc_cms/partners')->getCollection();

        foreach ($collection as $partner) {
            $options[] = array(
                'value' => $partner->getId(),
                'label' => $partner->getAltText()
            );
        }

        return $options;
    }
}
