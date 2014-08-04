<?php
/**
 * Q and A my account
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Bazaarvoice
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Bazaarvoice_Block_Qa extends Tgc_Bazaarvoice_Block_Abstract
{
    const QUESTIONS_TYPE = 'questions';
    const ANSWERS_TYPE   = 'answers';
    const IMAGE_WIDTH    = 50;
    const IMAGE_HEIGHT   = 75;

    private $_questions;
    private $_answers;

    public function getCurrentDirection()
    {
        $dir = $this->_getData('_current_qa_direction');
        if ($dir) {
            return $dir;
        }

        $directions = array('asc', 'desc');
        $dir = strtolower($this->getRequest()->getParam($this->getDirectionVarName()));
        if ($dir && in_array($dir, $directions)) {
            if ($dir == $this->_direction) {
                Mage::getSingleton('customer/session')->unsQaDirection();
            } else {
                $this->_memorizeParam('qa_direction', $dir);
            }
        } else {
            $dir = Mage::getSingleton('customer/session')->getQaDirection();
        }
        // validate direction
        if (!$dir || !in_array($dir, $directions)) {
            $dir = $this->_direction;
        }
        $this->setData('_current_qa_direction', $dir);

        return $dir;
    }

    private function _getQuestions()
    {
        if (isset($this->_questions)) {
            return $this->_questions;
        }

        $url = $this->_getQuestionsUrl();
        $this->_questions = $this->_makeRequest($url);

        return $this->_questions;
    }

    private function _getQuestionById($id)
    {
        $url = $this->_getQuestionByIdUrl($id);
        $question = $this->_makeRequest($url);

        if (isset($question[0])) {
            return $question[0];
        }

        return array();
    }

    private function _getQuestionByIdUrl($id)
    {
        $url = $this->_apiUrl . self::QUESTIONS_TYPE . '.' . self::API_FORMAT;
        $query = array(
            'PassKey'    => $this->_getApiKey(),
            'ApiVersion' => self::API_VERSION,
            'Filter'     => 'Id:' . $id,
            'Include'    => 'Authors,Answers',
            'Limit'      => '99',
        );
        $queryString = http_build_query($query);

        return $url . '?' . $queryString;
    }

    private function _getAnswers()
    {
        if (isset($this->_answers)) {
            return $this->_answers;
        }

        $url = $this->_getAnswersUrl();
        $this->_answers = $this->_makeRequest($url);

        return $this->_answers;
    }

    private function _getAnswersByIds($ids)
    {
        $url = $this->_getAnswersByIdsUrl($ids);
        $answers = $this->_makeRequest($url);
        $result = array();
        foreach ($answers as $answer) {
            $key = strtotime($answer['SubmissionTime']);
            $result[$key] = array(
                'answer'      => $answer['AnswerText'],
                'user_photo'  => isset($answer['Photos'][0]) ? $answer['Photos'][0] : null,
                'answer_date' => $this->_convertDate($answer['SubmissionTime']),
                'user_name'   => $answer['UserNickname']
            );
        }

        krsort($result);

        return $result;
    }

    private function _getAnswersByIdsUrl($ids)
    {
        $url = $this->_apiUrl . self::ANSWERS_TYPE . '.' . self::API_FORMAT;
        $query = array(
            'PassKey'    => $this->_getApiKey(),
            'ApiVersion' => self::API_VERSION,
            'Filter'     => 'Id:' . join(',', $ids),
            'Include'    => 'Authors,Products,Questions',
            'Limit'      => '99',
        );
        $queryString = http_build_query($query);

        return $url . '?' . $queryString;
    }

    private function _getProductIdByQuestionId($questionId)
    {
        $question = $this->_getQuestionById($questionId);

        return $question['ProductId'];
    }


    private function _getQuestionsUrl()
    {
        $url = $this->_apiUrl . self::QUESTIONS_TYPE . '.' . self::API_FORMAT;
        $customer = Mage::getSingleton('customer/session')->getCustomer();
        $query = array(
            'PassKey'    => $this->_getApiKey(),
            'ApiVersion' => self::API_VERSION,
            'Filter'     => 'AuthorId:' . $customer->getWebUserId(),
            'Include'    => 'Products,Authors,Answers',
            'Limit'      => '99',
        );
        $queryString = http_build_query($query);

        return $url . '?' . $queryString;
    }

    private function _getAnswersUrl()
    {
        $url = $this->_apiUrl . self::ANSWERS_TYPE . '.' . self::API_FORMAT;
        $customer = Mage::getSingleton('customer/session')->getCustomer();
        $query = array(
            'PassKey'    => $this->_getApiKey(),
            'ApiVersion' => self::API_VERSION,
            'Filter'     => 'AuthorId:' . $customer->getWebUserId(),
            'Include'    => 'Products,Categories,Questions,Authors',
            'Limit'      => '99',
        );
        $queryString = http_build_query($query);

        return $url . '?' . $queryString;
    }

    public function getSummaryData()
    {
        $author = $this->_getAuthor();
        $summary = array(
            'questions'         => isset($author['QAStatistics']['TotalQuestionCount'])    ? $author['QAStatistics']['TotalQuestionCount']             : 0,
            'answers'           => isset($author['QAStatistics']['TotalAnswerCount'])      ? $author['QAStatistics']['TotalAnswerCount']               : 0,
            'helpful_votes'     => isset($author['QAStatistics']['HelpfulVoteCount'])      ? $author['QAStatistics']['HelpfulVoteCount']               : 0,
            'featured_question' => isset($author['QAStatistics']['FeaturedQuestionCount']) ? $author['QAStatistics']['FeaturedQuestionCount']          : 0,
            'featured_answers'  => isset($author['QAStatistics']['FeaturedAnswerCount'])   ? $author['QAStatistics']['FeaturedAnswerCount']            : 0,
            'best_answers'      => isset($author['QAStatistics']['BestAnswerCount'])       ? $author['QAStatistics']['BestAnswerCount']                : 0,
            'community_rank'    => isset($author['ContributorRank'])                       ? $this->_formatContributorRank($author['ContributorRank']) : 'None',
            'last_qa'           => $this->_getLastQaTime($author['QAStatistics']),
            'active_since'      => $this->_getLastQaTime($author['QAStatistics']),
        );

        return $summary;
    }

    private function _getLastQaTime($stats)
    {
        if (!is_null($stats['LastQuestionTime']) && $stats['LastQuestionTime'] > $stats['LastAnswerTime']) {
            return $this->_convertDate($stats['LastQuestionTime']);
        } else {
            return $this->_convertDate($stats['LastAnswerTime']);
        }

        return 'Never';
    }

    public function getItems()
    {
        $items = $this->_prepareItemsData();
        $dir   = $this->getCurrentDirection();

        switch ($dir) {
            case 'asc':
                krsort($items);
                break;
            case 'desc':
            default:
                ksort($items);
        }

        return $items;
    }

    private function _prepareItemsData()
    {
        $questions = $this->_prepareQuestions();
        $answers   = $this->_prepareAnswers();

        $data = array_merge($questions, $answers);

        return $data;
    }

    /**
     * These are questions asked by you and answered by somebody else
     */
    private function _prepareQuestions()
    {
        $questions = $this->_getQuestions();
        $data      = array();

        foreach ($questions as $question) {
            $key        = strtotime($question['SubmissionTime']);
            $product    = $this->_getProductFromIdentifier($question['ProductId']);
            $data[$key] = array(
                'type'                => self::QUESTIONS_TYPE,
                'date'                => $this->_convertDate($question['SubmissionTime']),
                'product_image_url'   => Mage::helper('catalog/image')->init($product, 'image')->resize(self::IMAGE_WIDTH, self::IMAGE_HEIGHT)->__toString(),
                'product_name'        => $product->getName(),
                'product_description' => $product->getDescription(),
                'see_all_qa_url'      => $this->_getSeeAllQaUrl($question['AuthorId']),
                'ask_question_url'    => $product->getProductUrl(),
                'user_nickname'       => $question['UserNickname'],
                'question_summary'    => $question['QuestionSummary'],
                'question_details'    => $question['QuestionDetails'],
                'answers'             => empty($question['AnswerIds']) ? array() : $this->_getAnswersByIds($question['AnswerIds']),
            );
        }

        return $data;
    }

    /**
     * These are your answers to other peoples questions
     */
    private function _prepareAnswers()
    {
        $answers = $this->_getAnswers();
        $author  = $this->_getAuthor();
        $data    = array();

        foreach ($answers as $answer) {
            $key        = strtotime($answer['SubmissionTime']);
            $productId  = $this->_getProductIdByQuestionId($answer['QuestionId']);
            $product    = $this->_getProductFromIdentifier($productId);
            $question   = $this->_getQuestionById($answer['QuestionId']);
            $allAnswers = empty($question['AnswerIds']) ? array() : $this->_getAnswersByIds($question['AnswerIds']);
            $data[$key] = array(
                'type'                => self::ANSWERS_TYPE,
                'date'                => $this->_convertDate($question['SubmissionTime']),
                'product_image_url'   => Mage::helper('catalog/image')->init($product, 'image')->resize(self::IMAGE_WIDTH, self::IMAGE_HEIGHT)->__toString(),
                'product_name'        => $product->getName(),
                'product_description' => $product->getDescription(),
                'see_all_qa_url'      => $this->_getSeeAllQaUrl($answer['AuthorId']),
                'ask_question_url'    => $product->getProductUrl(),
                'question_summary'    => $question['QuestionSummary'],
                'question_details'    => $question['QuestionDetails'],
                'all_answers'         => $allAnswers,
                'user_answer'         => $answer['AnswerText'],
                'user_rank'           => $this->_formatContributorRank($author['ContributorRank']),
                'helpful_votes'       => $answer['TotalPositiveFeedbackCount'],
                'not_helpful_votes'   => $answer['TotalNegativeFeedbackCount'],
                'answer_date'         => $this->_convertAnswerDate($answer['SubmissionTime']),
            );
        }

        return $data;
    }

    private function _getSeeAllQaUrl($authorId)
    {
        $environment = Mage::getStoreConfig('bazaarvoice/general/environment');

        $link = 'http://ugc.teachco.com';
        if ($environment == 'staging') {
            $link .= '/bvstaging';
        }
        $link .= '/profiles/3456qa-en_us/';
        $link .= $authorId;
        $link .= '/profile.htm';

        return $link;
    }

    protected function _convertAnswerDate($date)
    {
        $maxDaysForDisplay = 30;
        $timestamp = strtotime($this->_convertDate($date));
        $day = 60 * 60 * 24;
        $today = time();

        if ($timestamp < ($today - ($day * $maxDaysForDisplay))) {
            return date('F j, Y', strtotime($date));
        }

        if ($timestamp > ($today - $day)) {
            return Mage::helper('tgc_bv')->__('1 day ago');
        }

        $n = 2;
        while ($n < $maxDaysForDisplay) {
            if ($timestamp > ($today - ($day * $n))) {
                return Mage::helper('tgc_bv')->__('% days ago', $n);
            }
            $n++;
        }

        return date('F j, Y', strtotime($date));
    }
}
