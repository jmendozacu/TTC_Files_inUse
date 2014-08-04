<?php
/**
 * Catalog helper
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Catalog
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Catalog_Helper_Data extends Enterprise_Catalog_Helper_Data
{
    protected $_tgcCatalogConnection;

    private $_productUrlPrefix = array();

    private $_setId;

    private $_courseSetId;

    /**
     * Truncate string to the last included word
     *
     * @param $text
     * @param $charactersAmount
     * @param string $suffix
     * @return string
     */
    public function __construct(array $args = array())
    {
        parent::__construct($args);

        $this->_tgcCatalogConnection = Mage::getSingleton('core/resource')->getConnection('write');
    }

    public function truncateTextByWords($text, $charactersAmount, $suffix = '')
    {
        $text = strip_tags($text);
        if (strlen($text) <= $charactersAmount) {
            return $text;
        }
        $truncatedText = substr($text . ' ', 0, $charactersAmount);
        return preg_replace('/\s+?(\S+)?$/', '', $truncatedText) . $suffix;
    }

    public function truncateTextBasedOnNumberWords($text, $numberWordsToDisplay, $beforeAfter = 'before', $hasSuffix = true, $suffix = '....')
    {
        $text = str_replace('  ', ' ', $text); //eliminating double spaces.

        $arrayWordsInText = str_word_count($text, 1);
        $numberWordsInText = str_word_count($text);
        $newText = false;

        if (Zend_Validate::is($numberWordsToDisplay, 'Digits')) {
            if ($numberWordsToDisplay < $numberWordsInText) {
                $stringPositionOfNthWord = $this->getPositionNthWord($arrayWordsInText, $numberWordsToDisplay, $text);

                if ($beforeAfter == 'before') {
                    $newText = substr($text, 0, $stringPositionOfNthWord) . ".";
                } elseif ($beforeAfter == 'after') {
                    $newText = substr($text, $stringPositionOfNthWord);
                }

                if ($newText && $beforeAfter == 'after') {
                    $newText = trim($newText);
                    $newText = trim($newText, '.');
                }

                if ($newText && $hasSuffix) {
                    $newText .= $suffix;
                }

                if ($newText) {
                    $newText = preg_replace('/\s+?(\S+)?$/', '', $newText);
                }
            } else {
                if ($beforeAfter == 'before') {
                    $newText = $text;
                } elseif ($beforeAfter == 'after') {
                    //a value is not set for $newText if after, because string does not reach max number of words! Therefore, nothing comes after.
                }
            }
        }

        return $newText;
    }

    public function isNeedToAdvancedTruncate($text, $length = 100)
    {
        if (strlen(preg_replace('/<.*?>/', '', $text)) <= $length) {
            return false;
        }

        return true;
    }

    public function advancedTruncate($text, $length = 100, $ending = '...', $exact = false, $considerHtml = true)
    {
        if ($considerHtml) {

            // if the plain text is shorter than the maximum length, return the whole text
            if (strlen(preg_replace('/<.*?>/', '', $text)) <= $length) {
                return $text;
            }
            // splits all html-tags to scanable lines
            preg_match_all('/(<.+?>)?([^<>]*)/s', $text, $lines, PREG_SET_ORDER);

            $total_length = strlen($ending);
            $open_tags = array();
            $truncate = '';
            foreach ($lines as $line_matchings) {
                // if there is any html-tag in this line, handle it and add it (uncounted) to the output
                if (!empty($line_matchings[1])) {
                    // if it's an "empty element" with or without xhtml-conform closing slash
                    if (preg_match('/^<(\s*.+?\/\s*|\s*(img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param)(\s.+?)?)>$/is', $line_matchings[1])) {
                        // do nothing
                        // if tag is a closing tag
                    } else if (preg_match('/^<\s*\/([^\s]+?)\s*>$/s', $line_matchings[1], $tag_matchings)) {
                        // delete tag from $open_tags list
                        $pos = array_search($tag_matchings[1], $open_tags);
                        if ($pos !== false) {
                            unset($open_tags[$pos]);
                        }
                        // if tag is an opening tag
                    } else if (preg_match('/^<\s*([^\s>!]+).*?>$/s', $line_matchings[1], $tag_matchings)) {
                        // add tag to the beginning of $open_tags list
                        array_unshift($open_tags, strtolower($tag_matchings[1]));
                    }
                    // add html-tag to $truncate'd text
                    $truncate .= $line_matchings[1];
                }
                // calculate the length of the plain text part of the line; handle entities as one character
                $content_length = strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', ' ', $line_matchings[2]));
                if ($total_length+$content_length> $length) {
                    // the number of characters which are left
                    $left = $total_length + $content_length;// $length - $total_length;
                    $entities_length = 0;
                    // search for html entities
                    if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', $line_matchings[2], $entities, PREG_OFFSET_CAPTURE)) {
                        // calculate the real length of all entities in the legal range
                        foreach ($entities[0] as $entity) {
                            if ($entity[1]+1-$entities_length <= $left) {
                                $left--;
                                $entities_length += strlen($entity[0]);
                            } else {
                                // no more characters left
                                break;
                            }
                        }
                    }
                    $truncate .= substr($line_matchings[2], 0, $left+$entities_length);
                    // maximum lenght is reached, so get off the loop
                    break;
                } else {
                    $truncate .= $line_matchings[2];
                    $total_length += $content_length;
                }
                // if the maximum length is reached, get off the loop
                if($total_length>= $length) {
                    break;
                }
            }
        } else {
            if (strlen($text) <= $length) {
                return $text;
            } else {
                $truncate = substr($text, 0, $length - strlen($ending));
            }
        }
        // if the words shouldn't be cut in the middle...
        //  if (!$exact) {
        //      // ...search the last occurance of a space...
        //      $spacepos = strrpos($truncate, ' ');
        //      if (isset($spacepos)) {
        //          // ...and cut the text in this position
        //          $truncate = substr($truncate, 0, $spacepos);
        //      }
        //  }

        // add the defined ending to the text
        $truncate .= $ending;
        if($considerHtml) {
            // close all unclosed html-tags
            foreach ($open_tags as $tag) {
                $truncate .= '</' . $tag . '>';
            }
        }
        return $truncate;
    }

    public function getPositionNthWord($arrayWordsInText, $numberWordsToDisplay, $text)
    {
        $stringPositionOfNthWord = $this->getNthWordByConcatenation($arrayWordsInText, $numberWordsToDisplay, $text);

        if (!$stringPositionOfNthWord) {
            $stringPositionOfNthWord = $this->getNthWordByManualCount($arrayWordsInText, $numberWordsToDisplay, $text);
        }

        return $stringPositionOfNthWord;
    }

    public function getNthWordByConcatenation($arrayWordsInText, $numberWordsToDisplay, $text, $wordCounter = 1, $wordCountMax = 4, $stopProcessingLimit = 20)
    {
        //First we find the word that is at position in string equal to $numberWordsToDsiplay, let's say number is 15.  Once we find 15th position of string
        //the 15th, 16th, 17th, and 18th words in string are concatenated.  This enables us to find the position of the 15th word in the string.
        $numberWordsInText = count($arrayWordsInText);
        $wordCounterOriginal = $wordCounter;
        $demarcater = '';

        while ($wordCounter <= $wordCountMax) {
            if ($numberWordsToDisplay + $wordCounter < $numberWordsInText) {
                $demarcater .= " " . $arrayWordsInText[$numberWordsToDisplay + $wordCounter];
                $demarcater = trim($demarcater);
            }
            $wordCounter++;
        }

        $stringPositionOfNthWord = false;
        if ($demarcater) { //this will not execute if the stopProcessingLimit has been reached.
            $stringPositionOfNthWord = strpos($text, $demarcater);
        }

        if (!$stringPositionOfNthWord) {
            if ($wordCountMax < $stopProcessingLimit) {
                $incrementer = 3;
                return $this->getNthWordByConcatenation($arrayWordsInText, $numberWordsToDisplay, $text, $wordCounterOriginal + $incrementer, $wordCountMax + $incrementer);
            }
        }

        return $stringPositionOfNthWord;
    }

    public function getNthWordByManualCount($arrayWordsInText, $numberWordsToDisplay, $text)
    {
        $totalIterations = 1;
        $stringPositionOfNthWord = 0;
        $stringPositionOfNthWord += strlen($arrayWordsInText[0]);
        While ($totalIterations < $numberWordsToDisplay) {
            $nextWord = next($arrayWordsInText);
            $stringPositionOfNthWord += strlen($nextWord) + 1;
            $totalIterations++;
        }
        $stringPositionOfNthWord--; //we don't want an extra space on the end.
        return $stringPositionOfNthWord;
    }


    public function getProfessors(Mage_Catalog_Model_Product $product)
    {
        return (array)Mage::getResourceSingleton('profs/professor')
            ->getProfessorsForProduct($product);
    }

    public function getProductIdFromCourseId($courseId)
    {
        return $this->getEntityIdsByAttributeValue('course_id', $courseId);
    }

    public function getProductUrlFromCourseId($courseId, $withBaseUrl = true)
    {
        /* @var $product Mage_Catalog_Model_Product */
        $product = Mage::getModel('catalog/product')->getCollection()
            ->addFieldToFilter('entity_id', $this->getProductIdFromCourseId($courseId))
            ->addAttributeToSelect('url_key')
            ->getFirstItem();
        // Get the URL to Product without Category
        $url = $product->getUrlModel()->getUrl($product, array('_ignore_category' => true));
        if (!$withBaseUrl) {
            $url = substr($url, strlen($product->getStore()->getBaseUrl()));
        }
        return $url;
    }

    public function getProductNameFromCourseId($courseId)
    {
        /* @var $product Mage_Catalog_Model_Product */
        $product = Mage::getModel('catalog/product')->getCollection()
            ->addFieldToFilter('entity_id', $this->getProductIdFromCourseId($courseId))
            ->addAttributeToSelect('name')
            ->getFirstItem();
        // Get the Course Name
        $courseName = $product->getName();

        return $courseName;
    }

    public function getSetUrl($set)
    {
        return $set->getUrlModel()->getUrl($set);
    }

    public function getEntityIdsByAttributeValue($attrCode, $attrValue, $entity = Mage_Catalog_Model_Product::ENTITY)
    {
        $result = null;
        $eavAttributeConfig = Mage::getModel('eav/config');
        $attribute = $eavAttributeConfig->getAttribute($entity, $attrCode);

        if ($attribute instanceof Mage_Eav_Model_Entity_Attribute) {
            $table = $attribute->getBackend()->getTable();
            $attributeId = $attribute->getId();

            if ($table && $attributeId) {
                $selectAttributeValue = $this->_tgcCatalogConnection->select()
                    ->from(array('a' => $table), array('entity_id'))
                    ->joinLeft(array('b' => 'catalog_product_entity'), 'a.entity_id = b.entity_id')
                    ->where('a.attribute_id = :attribute_id')
                    ->where('a.value = :value')
                    ->where('a.value IS NOT NULL')
                    ->where("b.type_id = 'configurable'");

                $result = $this->_tgcCatalogConnection->fetchCol($selectAttributeValue, array('attribute_id' => $attributeId, 'value' => $attrValue));
                if (count($result) == 1) {
                    $result = $result[0];
                } else {
                    $result = null; //there should never be more than 1 course with the same id!
                }
            }
        }

        return $result;
    }

    public function retrieveModeFromCustomModeSwitcher($currentMode, $defaultMode)
    {
        $lastPagerRequested = Mage::getSingleton('catalog/session')->getLastPagerUserRequested();
        $currentFullActionName = Mage::app()->getFrontController()->getAction()->getFullActionName();

        $mode = $currentMode;

        if ($lastPagerRequested) {
            if ($lastPagerRequested != $currentFullActionName) {
                //this if condition resets mode value to be the default, when you switch from one page to another, you must reset.
                $mode = $this->retrievePagesDefaultMode($currentFullActionName, $defaultMode);
                Mage::getSingleton('catalog/session')->setLastPagerUserRequested($currentFullActionName);
            }
        } else {
            Mage::getSingleton('catalog/session')->setLastPagerUserRequested($currentFullActionName);
            Mage::getSingleton('catalog/session')->unsDisplayMode();
            $mode = $this->retrievePagesDefaultMode($currentFullActionName, $defaultMode);
        }

        return $mode;
    }

    public function retrievePagesDefaultMode($currentFullActionName, $defaultMode)
    {
        if ($currentFullActionName == 'profs_professor_view') {
            $mode = 'list';
        } else {
            $mode = $defaultMode;
        }

        return $mode;
    }

    /**
     * get attribute option id for digital attribute
     * @return array
     */
    public function getDigitalMediaAttributeId()
    {
        $attribute = Mage::getModel('eav/config')->getAttribute('catalog_product', 'media_format');
        $attributeSource = $attribute->getSource();

        $mediaAttributeIds = array();
        $mediaTypes = array("Audio Download", "Video Download", "Soundtrack Download", "Digital Transcript", "Transcript Book");
        foreach ($mediaTypes as $type) {
            $mediaAttributeIds[] = $attributeSource->getOptionId($type);
        }
        return $mediaAttributeIds;
    }

    public function getSetAttributeSetId()
    {
        if (!$this->_setId) {
            $this->_setId = $this->_getAttributeSetIdByName('Sets');
        }
        return $this->_setId;
    }


    public function getCourseAttributeSetId()
    {
        if (!$this->_courseSetId) {
            $this->_courseSetId = $this->_getAttributeSetIdByName('Courses');
        }
        return $this->_courseSetId;
    }

    private function _getAttributeSetIdByName($name)
    {
        return Mage::getModel('eav/entity_attribute_set')
            ->load($name, 'attribute_set_name')
            ->getId();
    }

    public function isSetProduct(Mage_Catalog_Model_Product $product)
    {
         return $product->getAttributeSetId() == $this->getSetAttributeSetId();
    }

    public function isCourseProduct(Mage_Catalog_Model_Product $product)
    {
         return $product->getAttributeSetId() == $this->getCourseAttributeSetId();
    }
}
