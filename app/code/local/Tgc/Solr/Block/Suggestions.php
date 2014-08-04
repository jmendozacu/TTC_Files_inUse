<?php

/**
 * "Did you mean? ... " search recommendation block.
 * Rewritten, because we need to have different URL for search.
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Tgc_Solr
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Solr_Block_Suggestions extends Enterprise_Search_Block_Suggestions
{
    /**
     * Retrieve search suggestions
     *
     * @return array
     */
    public function getSuggestions()
    {
        $helper = Mage::helper('enterprise_search');

        $searchSuggestionsEnabled = (bool)$helper->getSolrConfigData('server_suggestion_enabled');
        if (!($helper->isThirdPartSearchEngine() && $helper->isActiveEngine()) || !$searchSuggestionsEnabled) {
            return array();
        }

        $suggestionsModel = Mage::getSingleton('enterprise_search/suggestions');
        $suggestions = $suggestionsModel->getSearchSuggestions();

        foreach ($suggestions as $key => $suggestion) {
            $suggestions[$key]['link'] = $this->getUrl('search/result/') . '?q=' . urlencode($suggestion['word']);
        }

        return $suggestions;
    }
}
