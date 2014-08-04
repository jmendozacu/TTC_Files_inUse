<?php

/**
 * Custom Layer for processing NewRelease filter.
 * For default CatalogSearch.
 *
 * @category    Tgc
 * @package     Tgc_NewRelease
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Tgc_NewRelease_Model_Layer extends Mage_Catalog_Model_Layer
{
    const NEW_FROM_DATE_ATTRIBUTE = 'news_from_date';
    const NEW_TO_DATE_ATTRIBUTE = 'news_to_date';

    /**
     * Initialize product collection
     *
     * @param Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Collection $collection
     * @return Mage_Catalog_Model_Layer
     */
    public function prepareProductCollection($collection)
    {
        parent::prepareProductCollection($collection);

        if (Mage::helper('tgc_newRelease')->getNewReleaseFilterValue()) {
            $collection->addAttributeToFilter(self::NEW_FROM_DATE_ATTRIBUTE, array('lteq' => now(), 'notnull' => 1))
                ->addAttributeToFilter(array(
                    array('attribute' => self::NEW_TO_DATE_ATTRIBUTE, 'gteq' => now()),
                    array('attribute' => self::NEW_TO_DATE_ATTRIBUTE, 'null' => 1)
                ), null, 'left');
        }
        return $this;
    }
}
