<?php
/**
 * Search recommendation block.
 * Rewritten, because we need to have different URL for search.
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Tgc_Solr
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Solr_Block_Recommendations extends Enterprise_Search_Block_Recommendations
{
    /**
     * Retrieve search recommendations.
     * Rewritten, because "search/result" URLs of recommendations should be returned instead of "catalogsearch/result"
     *
     * @return array
     */
    public function getRecommendations()
    {
        $searchRecommendationsEnabled = (boolean)Mage::helper('enterprise_search')
            ->getSearchConfigData('search_recommendations_enabled');

        if (!$searchRecommendationsEnabled) {
            return array();
        }

        $recommendationsModel = Mage::getModel('enterprise_search/recommendations');
        $recommendations = $recommendationsModel->getSearchRecommendations();

        if (!count($recommendations)) {
            return array();
        }
        $result = array();

        /** @var $coreHelper Mage_Core_Helper_Data */
        $coreHelper = Mage::helper('core');
        foreach ($recommendations as $recommendation) {
            $result[] = array(
                'word'        => $coreHelper->escapeHtml($recommendation['query_text']),
                'num_results' => $recommendation['num_results'],
                'link'        => $this->getUrl("search/result/") . "?q=" . urlencode($recommendation['query_text'])
            );
        }
        return $result;
    }
}
