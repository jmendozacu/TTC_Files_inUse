<?php
/**
 * Custom Layer for processing NewRelease filter.
 * For Solr processor.
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Tgc_NewRelease
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_NewRelease_Model_Enterprise_Layer extends Enterprise_Search_Model_Catalog_Layer
{
    const NEW_FROM_DATE_ATTRIBUTE = 'news_from_date';
    const NEW_TO_DATE_ATTRIBUTE = 'news_to_date';

    /**
     * Initialize product collection
     *
     * @param Enterprise_Search_Model_Resource_Collection $collection
     * @return Mage_Catalog_Model_Layer
     */
    public function prepareProductCollection($collection)
    {
        parent::prepareProductCollection($collection);

        if (Mage::helper('tgc_newRelease')->getNewReleaseFilterValue()) {
            $engine = Mage::helper('catalogsearch')->getEngine();
            if ($engine) {
                if ($engine instanceof Tgc_Solr_Model_Resource_Engine) {
                    /* @var $engine Tgc_Solr_Model_Resource_Engine */
                    $fieldName = $engine->getSearchEngineFieldName(Tgc_NewRelease_Model_Layer::NEW_FROM_DATE_ATTRIBUTE, 'sort');
                    $solrNow = $engine->getAdapter()->getSolrDate(
                        $this->getCurrentCategory()->getStoreId(),
                        Mage::app()->getLocale()->date()
                    );
                    $collection->addFqFilter(array($fieldName => array('from' => '*', 'to' => $solrNow)));
                    $fieldName = $engine->getSearchEngineFieldName(Tgc_NewRelease_Model_Layer::NEW_TO_DATE_ATTRIBUTE, 'sort');
                    $collection->addFqFilter(array($fieldName => array('or' => array(array('from' => $solrNow, 'to' => '*'), array('null' => true)))));
                }
            }
        }

        return $this;
    }
}