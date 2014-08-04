<?php
/**
 * Solr search
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Solr
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Solr_Block_Autocomplete extends Mage_CatalogSearch_Block_Autocomplete
{
    protected function _toHtml()
    {
        if (!Mage::helper('tgc_solr')->isSolr() || !$this->_beforeToHtml()) {
            return parent::_toHtml();
        }

        $suggestData = $this->getSuggestData();
        if (empty($suggestData)) {
            return '';
        }

        $html = '<ul>';
        $queryText = $this->helper('catalogsearch')->getQueryText();
        $queryUrl = Mage::helper('catalogsearch')
            ->getResultUrl($queryText);
        $html .= '<li><a class="autocomplete-result" href="' . htmlspecialchars($queryUrl) . '">' . $this->__('See all results...') . '</a></li>';

        foreach ($suggestData as $index => $itemArray) {
            if (empty($itemArray)) {
                continue;
            }

            $html .= '<li class="autocomplete-title">' . ucfirst($index) . '</li>';

            foreach ($itemArray as $item) {
                $html .= '<li><a class="autocomplete-result" href="' . htmlspecialchars($item['url']) . '">';

                if ($index == 'courses') {
                    $html .= htmlspecialchars($item['name']);
                } else {
                    $html .= $item['highlight'];
                }

                $html .= '</a></li>';
            }
        }

        $html.= '</ul>';

        return $html;
    }

    public function getSuggestData()
    {
        if (!Mage::helper('tgc_solr')->isSolr()
            || !Mage::helper('tgc_solr')->isMinQueryLength()
            || !Mage::getStoreConfig('catalog/search/solr_server_suggestion_enabled')
        ) {
            return array();
        }

        $suggestData = array(
            'courses'      => $this->_getSuggestCourses(),
            'professors'   => $this->_getSuggestProfessors(),
            'universities' => $this->_getSuggestUniversities(),
        );

        return array_filter($suggestData);
    }

    private function _getSuggestProfessors()
    {
        $query = Mage::helper('tgc_solr')->getQuery();
        /*
        $query = array(
            'professor_name_en' => $query,
            'professor_name_ngrams' => $query
        );
        */
        $store  = Mage::app()->getStore();
        $limit = Mage::getStoreConfig('catalog/search/solr_server_suggestion_count');
        //build solr query
        $params = array(
            'limit'       => $limit,
            'store_id'    => 0,
            'locale_code' => $store->getConfig(Mage_Core_Model_Locale::XML_PATH_DEFAULT_LOCALE),
            'ignore_handler' => true,
            'solr_params' => array(
                //highlighting
                'hl'               => 'on',
                //highlight fragment size
                'hl.fragsize'      => 50,
                //fields to highlight, in order
                'hl.fl'            => implode(',', array(
                    'professor_name_en',
                    'professor_name_ngrams'
                )),
                //get suggestions
                'spellcheck'       => 'true',
                'spellcheck.count' => $limit,
                'qt' => 'professor_autocomplete'
            ),
            //fields to search
            'fields'               => array(
                'professor_name_en',
            ),
            //this means 'score'
            'sort_by' => array(
                array('relevance' => 'desc'),
            ),
        );

        $engine = Mage::getResourceSingleton('tgc_solr/engine');

        $highlight = $engine->getAutoCompleteRequest($query, $params);
        $iterator = 0;
        $limit = Mage::getStoreConfig('catalog/search/solr_server_suggestion_count');
        $professorIds = array();
        foreach ($highlight as $key => &$value) {
            if ($iterator >= $limit) {
                break;
            }
            if (isset($value->professor_name_en)) {
                $professorIds[$key] = $value;
                $iterator++;
            } else {
                $professorIds[$key] = false;
            }
        }

        $professors = Mage::getModel('profs/professor')
            ->getCollection()
            ->addFieldToFilter('professor_id', array('in' => array_keys($professorIds)));

        $result = array();
        foreach ($professors as $professor) {
            $highlightText = null;
            if (!empty($professorIds[$professor->getId()])) {
                foreach ($professorIds[$professor->getId()] as $hl) {
                    if ($hl) {
                        $highlightText = $hl[0];
                        break;
                    }
                }
            }

            $result[$professor->getId()] = array(
                'url'        => Mage::helper('profs')->getProfessorUrl($professor),
                'highlight'  => !empty($highlightText) ? $highlightText :
                    $professor->getTitle() . ' ' .
                    $professor->getFirstName() . ' '.
                    $professor->getLastName() . ' '.
                    $professor->getQual()
                ,
            );
        }

        //sort results by initial order of professorIds.
        $sortedResult = array();
        foreach ($professorIds as $id => $val) {
            $sortedResult[] = $result[$id];
        }

        return $sortedResult;
    }

    private function _getSuggestUniversities()
    {
        $query = Mage::helper('tgc_solr')->getQuery();
        /*
        $query = array(
            'institution_en' => $query,
            'institute_name_ngrams' => $query
        );*/
        $store  = Mage::app()->getStore();
        $limit = Mage::getStoreConfig('catalog/search/solr_server_suggestion_count') * 5; //will get cut during render
        //build solr query
        $params = array(
            'limit'       => $limit,
            'store_id'    => -1,
            'locale_code' => $store->getConfig(Mage_Core_Model_Locale::XML_PATH_DEFAULT_LOCALE),
            'ignore_handler' => true,
            'solr_params' => array(
                //highlighting
                'hl'               => 'on',
                //highlight fragment size
                'hl.fragsize'      => 50,
                //fields to highlight, in order
                'hl.fl'            => implode(',', array(
                    'institution_en',
                    'institute_name_ngrams',
                    'id'
                )),
                //get suggestions
                'spellcheck'       => 'true',
                'spellcheck.count' => $limit,
                'qt' => 'institution_autocomplete'
            ),
            //store filter added later
            'filters'     => array(
                'visibility'       => array(
                    Mage_Catalog_Model_Product_Visibility::VISIBILITY_IN_SEARCH,
                    Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH,
                ),
            ),
            //fields to search
            'fields'               => array(
                'institution_en',
                'institute_name_ngrams',
                'id'
            ),
            //this means 'score'
            'sort_by' => array(
                array('relevance' => 'desc'),
            ),
        );

        $engine = Mage::getResourceSingleton('tgc_solr/engine');

        $highlight = $engine->getAutoCompleteRequest($query, $params);

        $iterator = 0;
        $limit = Mage::getStoreConfig('catalog/search/solr_server_suggestion_count');
        $result = array();
        $foundInstitutes = array();
        foreach ($highlight as $k => &$value) {
            if ($iterator >= $limit) {
                break;
            }
            if (isset($value->institution_en) && !empty($value->institution_en)) {
                $highlightText = trim($value->institution_en[0]);
                $realName = trim(strip_tags($highlightText));
            } else {
                $id = explode('-', $k);
                $id = $id[0];
                $institutionModel = Mage::getModel('profs/institution')->load($id);
                $highlightText = $institutionModel->getName();
                $realName = trim($highlightText);
            }
            if (in_array($realName, $foundInstitutes)) {
                continue;
            }
            $instituteUrl = Mage::helper('catalogsearch')
                ->getResultUrl($realName);
            $result[] = array(
                'url'        => $instituteUrl,
                'highlight'  => $highlightText
            );
            $foundInstitutes[] = $realName;
            $iterator++;
        }

        return $result;
    }

    private function _getSuggestCourses()
    {
        $query = Mage::helper('tgc_solr')->getQuery();
        $limit = Mage::getStoreConfig('catalog/search/solr_server_suggestion_count');
        /*
        $query = array(
            'attr_course_id_en' => $query,
            'attr_name_en' => $query,
            'course_ngrams'         => $query,
            'name_ngrams'           => $query
        );*/
        $store  = Mage::app()->getStore();
        //build solr query
        $params = array(
            'limit'       => $limit,
            'store_id'    => $store->getId(),
            'locale_code' => $store->getConfig(Mage_Core_Model_Locale::XML_PATH_DEFAULT_LOCALE),
            'ignore_handler' => true,
            'solr_params' => array(
                //highlighting
                'hl'               => 'on',
                //highlight fragment size
                'hl.fragsize'      => 50,
                //fields to highlight, in order
                'hl.fl'            => implode(',', array(
                    'attr_course_id_en',
                    'attr_name_en'
                )),
                //get suggestions
                'spellcheck' => 'true',
                'spellcheck.count' => $limit,
                'qt' => 'course_autocomplete'
            ),
            //store filter added later
            'filters'     => array(
                'visibility'       => array(
                    Mage_Catalog_Model_Product_Visibility::VISIBILITY_IN_SEARCH,
                    Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH,
                ),
                'in_stock' => true
            ),
            //fields to search
            'fields'               => array(
                'attr_course_id_en',
                'attr_name_en'
            ),
            //this means 'score'
            'sort_by' => array(
                array('attribute_set_id' => 'asc'),
                array('relevance' => 'desc')
             ),
        );

        $engine = Mage::getResourceSingleton('tgc_solr/engine');

        $highlight = $engine->getAutoCompleteRequest($query, $params);

        if (!$highlight) {
            return array();
        }

        $iterator = 0;
        $limit = Mage::getStoreConfig('catalog/search/solr_server_suggestion_count');
        $productIds = array();
        foreach ($highlight as $key => &$value) {
            if (!count($value)) {
                continue;
            }
            if ($iterator >= $limit) {
                break;
            }
            $keyParts = explode('|', $key);
            $productIds[$keyParts[0]] = $value;
            $iterator++;
        }

        if (!$productIds) {
            return array();
        }

        $result = array();
        $collection = Mage::getModel('catalog/product')
            ->getCollection()
            ->setStore($store)
            ->addAttributeToFilter('entity_id', array('in' => array_keys($productIds)))
            ->addAttributeToSelect('name')
            ->addUrlRewrite();
        foreach ($collection as $product) {
            $highlightText = null;
            foreach ($productIds[$product->getId()] as $hl) {
                $highlightText = $hl[0];
                break;
            }
            $result[$product->getId()] = array(
                'name'       => $product->getName(),
                'url'        => $product->getProductUrl(),
                'highlight'  => isset($highlightText) ? $highlightText : null,
            );
        }

        //sort results by initial order of productIds.
        $sortedResult = array();
        foreach ($productIds as $id => $val) {
            $sortedResult[] = $result[$id];
        }

        return $sortedResult;
    }
}
