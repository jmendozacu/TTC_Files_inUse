<?php
/**
 * Reviews in my account
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Bazaarvoice
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Bazaarvoice_Block_Reviews extends Tgc_Bazaarvoice_Block_Abstract
{
    const REVIEWS_TYPE   = 'reviews';
    const PRODUCTS_TYPE  = 'products';

    private $_reviewData;

    public function getReviews()
    {
        if (isset($this->_reviewData)) {
            return $this->_reviewData;
        }

        $url = $this->_getReviewsUrl();
        $this->_reviewData = $this->_makeRequest($url);

        return $this->_reviewData;
    }

    public function getSummaryData()
    {
        $author = $this->_getAuthor();

        $summary = array(
            'review_count'   => isset($author['ReviewStatistics']['TotalReviewCount']) ? $author['ReviewStatistics']['TotalReviewCount'] : 0,
            'helpful_votes'  => isset($author['ReviewStatistics']['HelpfulVoteCount']) ? $author['ReviewStatistics']['HelpfulVoteCount'] : 0,
            'average_rating' => isset($author['ReviewStatistics']['AverageOverallRating']) ? $author['ReviewStatistics']['AverageOverallRating'] : 0,
            'last_review'    => isset($author['ReviewStatistics']['LastSubmissionTime']) ? $this->_convertDate($author['ReviewStatistics']['LastSubmissionTime']) : 'Never',
            'active_since'   => isset($author['ReviewStatistics']['LastSubmissionTime']) ? $this->_convertDate($author['ReviewStatistics']['LastSubmissionTime']) : 'Never',
        );

        return $summary;
    }

    public function getReviewData(array $review)
    {
        $productId = $review['ProductId'];
        $product   = $this->_getProductFromIdentifier($productId);

        $data = array();
        $data['course_name'] = $product->getName();
        $data['go_to_review_url'] = $product->getProductUrl();
        $data['update_review_url'] = $data['go_to_review_url'];
        $data['overall_rating'] = intval($review['Rating']);
        $data['secondary_ratings'] = array();
        foreach ($review['SecondaryRatingsOrder'] as $key => $order) {
            $data['secondary_ratings'][$key] = array(
                'label' => $review['SecondaryRatings'][$order]['Label'],
                'rating' => $review['SecondaryRatings'][$order]['Value']
            );
        }
        $data['user_nickname'] = $review['UserNickname'];
        $data['user_location'] = $review['UserLocation'];
        $data['review_title'] = $review['Title'];
        $data['review_text'] = $review['ReviewText'];
        $data['review_date'] = $this->_convertDate($review['SubmissionTime']);
        $data['total_feedback'] = $review['TotalFeedbackCount'];
        $data['total_positive_feedback'] = $review['TotalPositiveFeedbackCount'];

        return $data;
    }

    private function _getReviewsUrl()
    {
        $url = $this->_apiUrl . self::REVIEWS_TYPE . '.' . self::API_FORMAT;
        $customer = Mage::getSingleton('customer/session')->getCustomer();
        $query = array(
            'PassKey'    => $this->_getApiKey(),
            'ApiVersion' => self::API_VERSION,
            'Filter'     => 'AuthorId:' . $customer->getWebUserId(),
            'Include'    => 'Products,Categories,Authors,Comments',
            'Limit'      => '99',
            'Sort'       => 'SubmissionTime:' . $this->getCurrentDirection(),
        );
        $queryString = http_build_query($query);

        return $url . '?' . $queryString;
    }

    private function _getProductRatingUrl($product)
    {
        $url = $this->_apiUrl . self::PRODUCTS_TYPE . '.' . self::API_FORMAT;
        $query = array(
            'PassKey'    => $this->_getApiKey(),
            'ApiVersion' => self::API_VERSION,
            'Filter'     => 'Id:' . Mage::helper('tgc_bv')->getProductId($product),
            'Include'    => 'Products,Reviews',
            'Stats'      => 'Reviews',
        );
        $queryString = http_build_query($query);

        return $url . '?' . $queryString;
    }

    public function getProductRating($product)
    {
        $url        = $this->_getProductRatingUrl($product);
        $ratingData = $this->_makeRequest($url);
        if (!isset($ratingData[0])) {
            return 0;
        }

        $rating     = $ratingData[0]['ReviewStatistics']['AverageOverallRating'];
        $rounded    = empty($rating) ? 0 : round($rating, 1);

        return $rounded;
    }
}
