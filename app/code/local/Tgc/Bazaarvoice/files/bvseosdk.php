<?php

/**
 * BV PHP SEO SDK
 *
 * Base code to power either SEO or SEO and display. This SDK
 * is provided as is and Bazaarvoice, Inc. is not responsbile
 * for future mainentence or support.  You are free to modify
 * this SDK as needed to suit your needs.
 *
 * This SDK was built with the following assumptions:
 *      - you are running PHP 5 or greater
 *      - you have the curl library installed
 *      - every request has the user agent header
 *        in it (if using a CDN like Akamai additional configuration
 *        maybe required).
 *
 */

/**
 * Example usage:
 *
 * require(bvsdk.php);
 *
 * $bv = new BV(array(
 *    'deployment_zone_id' => '12325',
 *    'product_id' => 'product1',
 *    'cloud_key' => 'agileville-78B2EF7DE83644CAB5F8C72F2D8C8491',
 *    'staging' => TRUE
 * ));
 *
 */

// ------------------------------------------------------------------------

/**
 * BV Class
 *
 * When you instantiate the BV class, pass it's constructor an array
 * containing the following key value pairs.
 *
 *   Required fields:
 *      deployment_zone_id (string)
 *      product_id (string)
 *      cloud_key (string)
 *
 *   Optional fields
 *      current_page_url (string) (defaults to detecting the current_page automtically)
 *      staging (boolean) (defaults to true, need to put false when go to production)
 *      subject_type (string) (defaults to product, for questions you can pass in categories here if needed)
 *      latency_timeout (int) (in millseconds) (defaults to 1000ms)
 *      bv_product (string) (defaults to reviews which is the only supported product right now)
 *      bot_list (string) (defualts to msnbot|googlebot|teoma|bingbot|yandexbot|yahoo)
 */

class BV {

    /**
     * BV Class Constructor
     *
     * The constructor takes in all the arguments via a single array.
     *
     * @access public
     * @param array
     * @return object
     */
    public function __construct($params = array())
    {
        // check to make sure we have the required paramaters
        if( empty($params) OR ! $params['deployment_zone_id'] OR ! $params['product_id'])
        {
            throw new Exception('BV Class missing required paramters. 
             BV expects an array with the following indexes: deployment_zone_id (string) and product_id 
             (string). ');
        }

        // config array, defaults are defined here
        $this->config = array(
            'staging' => true,
            'subject_type' => 'product',
            'latency_timeout' => 1000,
            'current_page_url' => $this->_getCurrentUrl(),
            'bot_detection' => true,
            'include_display_integration_code' => false,
            'client_name' => $params['deployment_zone_id'],
            'internal_file_path' => true,
            'bot_list' => 'msnbot|google|teoma|bingbot|yandexbot|yahoo',
        );

        // merge passed in params with defualts for config. 
        $this->config = array_merge($this->config, $params);

        // setup the reviews object
        $this->reviews = new Reviews($this->config);

        // setup the questions object
        $this->questions = new Questions($this->config);

    }

    // since this is used to set the default for an optional config option it is
    // included in the BV class. 
    public function _getCurrentUrl(){
        // depending on protocal set the 
        // beginging of url and defualt port
        if(isset($_SERVER["HTTPS"])){
            $url = 'https://';
            $defaultPort = '443';
        }else{
            $url = 'http://';
            $defaultPort = '80';
        }

        $url .= $_SERVER["SERVER_NAME"];

        // if there is a port other than the defaultPort being used it needs to be included
        if ($_SERVER["SERVER_PORT"] != $defaultPort){
            $url .= ":".$_SERVER["SERVER_PORT"];
        }

        $url .= $_SERVER["REQUEST_URI"];

        return $url;
    }
} // end of BV class

// Most shared functionatly is here so when we add support for questions
// and answers it should be minimal changes. Just need to create an answers
// class which inherits from Base.
class Base{

    const API_ENDPOINT   = 'api.bazaarvoice.com/data/';
    const API_VERSION    = '5.4';
    const API_FORMAT     = 'json';
    const XML_API_KEY    = 'bazaarvoice/conversations_api/api_key';
    const PRODUCTS_TYPE  = 'products';
    const REVIEWS_TYPE   = 'reviews';
    const QUESTIONS_TYPE = 'questions';
    const ANSWERS_TYPE   = 'answers';

    private $_apiUrl;
    private $_product;
    private $_reviews;
    private $_questions;
    private $_answers;
    private $_payload;
    private $_seoRendered = false;

    public function __construct($params = array())
    {
        if ( ! $params)
        {
            throw new Exception('BV Base Class missing config array.');
        }

        $this->config = $params;

        // setup bv (internal) defaults
        $this->bv_config['seo-domain']['staging']     = 'seo-stg.bazaarvoice.com';
        $this->bv_config['seo-domain']['production']  = 'seo.bazaarvoice.com';

        $this->_apiUrl = 'http://';
        $environment = Mage::getStoreConfig('bazaarvoice/general/environment');
        if ($environment == 'staging') {
            $this->_apiUrl .= 'stg.';
        }
        $this->_apiUrl .= self::API_ENDPOINT;
    }

    /**
     * Render SEO
     *
     * Method used to do all the work to fetch, parse, and then return
     * the SEO payload. This is set as protected so classes inheriting
     * from the base class can invoke it or replace it if needed.
     *
     * @access protected
     * @return string
     */
    protected function _renderSEO()
    {
        // we will return a payload of a string
        if ($this->_seoRendered) {
            return $this->_payload;
        }

        try {
            // get the page number of SEO content to load
            $page_number = $this->_getPageNumber();

            // build the URL to access the SEO content for
            // this product / page combination
            $seo_url = $this->_buildSeoUrl($page_number);

            // make call to get SEO payload from cloud
            $seo_content = $this->_fetchSeoContent($seo_url);

            // replace tokens for pagination URLs with page_url
            $seo_content = $this->_replaceTokens($seo_content);

            // if debug mode is on we want to include more debug data
            if (isset($_GET['bvreveal']))
            {
                if($_GET['bvreveal'] == 'debug')
                {
                    $printable_config = $this->config;
                    unset($printable_config['cloud_key']);
                    $seo_content .= $this->_buildComment('Config options: '.print_r($printable_config, TRUE));
                }
            }

            $pay_load = $seo_content;
        } catch (Exception $e) {
            $pay_load = $this->_buildComment('Bazaarvoice throws exception');
        }

        return $pay_load;
    }


    // --------------------------------------------------------------------
    /*  Private methods. Internal workings of SDK.                       */
    //--------------------------------------------------------------------

    /**
     * isBot
     *
     * Helper method to determine if current request is a bot or not. Will
     * use the configured regex string which can be overriden with params.
     *
     * @access private
     * @return bool
     */
    private function _isBot()
    {
        // we need to check the user agent string to see if this is a bot,
        // unless the bvreveal parameter is there or we have disabled bot
        // detection through the bot_detection flag
        if(isset($_GET['bvreveal']) || !$this->config['bot_detection'] || Mage::getIsDeveloperMode() || (isset($_GET['user']) && $_GET['user'] == 'guidance')) {
            return true;
        }

        // search the user agent string for an indictation if this is a search bot or not
        return preg_match('/('.$this->config['bot_list'].')/i', $_SERVER['HTTP_USER_AGENT']);
    }

    /**
     * getPageNumber
     *
     * Helper method to pull from the URL the page of SEO we need to view.
     *
     * @access private
     * @return int
     */
    private function _getPageNumber()
    {
        // default to page 1 if a page is not specified in the URL
        $page_number = 1;

        // some implementations wil use bvpage query parameter like ?bvpage=2
        if (isset($_GET['bvpage'])){
            $page_number = (int) $_GET['bvpage'];

            // remove the bvpage parameter from the current URL so we don't keep appending it
            $seo_param = str_replace('/', '\/', $_GET['bvrrp']); // need to escape slashses for regex
            $this->config['current_page_url'] = preg_replace('/[?&]bvrrp='.$seo_param.'/', '', $this->config['current_page_url']);
        }
        // other implementations use the bvrrp, bvqap, or bvsyp parameter ?bvrrp=1234-en_us/reviews/product/2/ASF234.htm
        else if(isset($_GET['bvrrp']) OR isset($_GET['bvqap']) OR isset($_GET['bvsyp']) ){
            if(isset($_GET['bvrrp']))
            {
                $bvparam = $_GET['bvrrp'];
            }
            else if(isset($_GET['bvqap']))
            {
                $bvparam = $_GET['bvqap'];
            }
            else
            {
                $bvparam = $_GET['bvsyp'];
            }

            preg_match('/\/(\d+?)\/[^\/]+$/', $_SERVER['QUERY_STRING'], $page_number);
            $page_number = max(1, (int) $page_number[1]);

            // remove the bvrrp parameter from the current URL so we don't keep appending it
            $seo_param = str_replace('/', '\/', $bvparam); // need to escape slashses for regex
            $this->config['current_page_url'] = preg_replace('/[?&]bvrrp='.$seo_param.'/', '', $this->config['current_page_url']);
        }

        return $page_number;
    }// end of _getPageNumber()

    /**
     * buildSeoUrl
     *
     * Helper method to that builds the URL to the SEO payload
     *
     * @access private
     * @param int (page number)
     * @return string
     */
    private function _buildSeoUrl($page_number){
        // are we pointing at staging or production?
        if($this->config['staging']){
            $hostname = $this->bv_config['seo-domain']['staging'];
        }else{
            $hostname = $this->bv_config['seo-domain']['production'];
        }

        // dictates order of URL
        $url_parts = array(
            'http://'.$hostname,
            $this->config['cloud_key'],
            $this->config['deployment_zone_id'],
            $this->config['bv_product'],
            $this->config['subject_type'],
            $page_number,
            urlencode($this->config['product_id']).'.htm'
        );

        // if our SEO content source is a file path
        // we need to remove the first two sections
        // and prepend the passed in file path
        if($this->config['internal_file_path'])
        {
            unset($url_parts[0]);
            unset($url_parts[1]);

            return $this->config['internal_file_path'].implode("/", $url_parts);
        }

        // implode will convert array to a string with / in between each value in array
        return implode("/", $url_parts);
    }

    private function _fetchSeoContent($resource)
    {
        if($this->config['internal_file_path'])
        {
            return $this->_fetchFileContent($resource);
        }
        else
        {
            return $this->_fetchCloudContent($resource);
        }
    }

    /**
     * fetchFileContent
     *
     * Helper method that will take in a file path and return it's payload while
     * handling the possible errors or exceptions that can happen.
     *
     * @access private
     * @param string (valid file path)
     * @return string (contents of file)
     */
    private function _fetchFileContent($path){
        return $this->_renderPayload();
    }

    protected function _getApiKey()
    {
        return Mage::getStoreConfig(self::XML_API_KEY);
    }

    protected function _makeRequest($url)
    {
        if (empty($url)) {
            return array();
        }

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL            => $url,
            CURLOPT_USERAGENT      => 'Teachco infograbber',
        ));

        $response = curl_exec($curl);
        $decoded = Zend_Json::decode($response);
        $results = (array)$decoded['Results'];

        return $results;
    }

    private function _getProductsApiUrl()
    {
        return $this->_getApiUrl(self::PRODUCTS_TYPE);
    }

    private function _getReviewsApiUrl()
    {
        return $this->_getApiUrl(self::REVIEWS_TYPE);
    }

    private function _getQuestionsApiUrl()
    {
        return $this->_getApiUrl(self::QUESTIONS_TYPE);
    }

    private function _getAnswersApiUrl()
    {
        return $this->_getApiUrl(self::ANSWERS_TYPE);
    }

    private function _getApiUrl($type)
    {
        $product = Mage::registry('current_product');
        if (!$product) {
            return false;
        } else {
            $productId  = Mage::helper('tgc_bv')->getProductId($product);
        }

        switch ($type) {
            case self::PRODUCTS_TYPE:
                $filterName = 'Id';
                break;
            default:
                $filterName = 'ProductId';
        }

        $url = $this->_apiUrl . $type . '.' . self::API_FORMAT;
        $query = array(
            'PassKey'    => $this->_getApiKey(),
            'ApiVersion' => self::API_VERSION,
            'Filter'     => $filterName . ':' . $productId,
            'Include'    => 'Products,Categories,Authors,Reviews,Questions,Answers',
            'Limit'      => '99',
            'Stats'      => 'Reviews,Questions,Answers',
        );
        $queryString = http_build_query($query);

        return $url . '?' . $queryString;
    }

    private function _renderPayload()
    {
        $this->_product   = $this->_makeRequest($this->_getProductsApiUrl());
        $this->_reviews   = $this->_makeRequest($this->_getReviewsApiUrl());
        $this->_questions = $this->_makeRequest($this->_getQuestionsApiUrl());
        $this->_answers   = $this->_makeRequest($this->_getAnswersApiUrl());

        $this->_render();

        return $this->_payload;
    }

    private function _render()
    {
        if ($this->_seoRendered) {
            return;
        }

        $productName = isset($this->_product[0]['Name']) ? $this->_product[0]['Name'] : 'Unknown';

        $this->_payload .= <<<PAYLOAD_ROOT
<!--begin-bvseo-reviews-->
<div itemscope itemtype="http://schema.org/Product">
	<meta itemprop="name" content="{$productName}" />

PAYLOAD_ROOT;

        if (isset($this->_product[0]['Name'])) {

        $this->_payload .= <<<PAYLOAD_AGGREGATE_RATING
    <!--begin-aggregate-rating-->
	<div id="bvseo-aggregateRatingSection" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
	    <span class="bvseo-itemReviewed" itemprop="itemReviewed">{$this->_product[0]['Name']}</span> is rated
            <span class="bvseo-ratingValue" itemprop="ratingValue">{$this->_product[0]['ReviewStatistics']['AverageOverallRating']}</span> out of
            <span class="bvseo-bestRating" itemprop="bestRating">{$this->_product[0]['ReviewStatistics']['OverallRatingRange']}</span> by
	    <span class="bvseo-reviewCount" itemprop="reviewCount">{$this->_product[0]['ReviewStatistics']['TotalReviewCount']}</span>.
	</div>
	<!--end-aggregate-rating-->

PAYLOAD_AGGREGATE_RATING;

        $this->_payload .= <<<PAYLOAD_REVIEW_BEGIN
    <!--begin-reviews-->
	<div id="bvseo-reviewsSection">

PAYLOAD_REVIEW_BEGIN;

        foreach ($this->_reviews as $review)
        {
            $date = $this->_convertDate($review['SubmissionTime']);
            $this->_payload .= <<<PAYLOAD_REVIEW_ITEM
        <div class="bvseo-review" itemprop="review" itemscope itemtype="http://schema.org/Review" data-reviewid="{$review['Id']}">
			<meta itemprop="itemReviewed" content="{$this->_product[0]['Name']}" />
			<span itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating">
				Rated <span itemprop="ratingValue">{$review['Rating']}</span> out of
				<span itemprop="bestRating">{$review['RatingRange']}</span></span> by
				<span itemprop="author">{$review['UserNickname']}</span> from
				<span itemprop="name">{$review['Title']}</span>
				<span itemprop="description">{$review['ReviewText']}
			</span>
		    <div class="bvseo-pubdate">Date published: $date</div>
			<meta itemprop="datePublished" content="$date" />
		</div>

PAYLOAD_REVIEW_ITEM;
        }

        $this->_payload .= <<<PAYLOAD_REVIEW_END
        </div>
	<script type="text/javascript">
		document.getElementById('bvseo-reviewsSection').style.display = 'none';
	</script>
	<!--end-reviews-->

PAYLOAD_REVIEW_END;

        $this->_payload .= <<<PAYLOAD_QUESTION_BEGIN
    <!--begin-questions-->
	<div id="bvseo-questionsSection">

PAYLOAD_QUESTION_BEGIN;

        foreach ($this->_questions as $question)
        {
            $date = $this->_convertDate($question['SubmissionTime']);
            $this->_payload .= <<<PAYLOAD_QUESTION_ITEM
        <div class="bvseo-question" itemprop="question" itemscope itemtype="http://schema.org/Reviews" data-questionid="{$question['Id']}">
			<meta itemprop="itemReviewed" content="{$this->_product[0]['Name']}" />
			<span itemprop="userComments" itemscope itemtype="http://schema.org/UserComments">
				<span itemprop="commentText">{$question['QuestionDetails']}</span></span> by
				<span itemprop="author">{$question['UserNickname']}</span> from
				<span itemprop="contentLocation">{$question['UserLocation']}</span>
				<span itemprop="description">{$question['QuestionSummary']}
			</span>
		    <div class="bvseo-pubdate">Date published: $date</div>
			<meta itemprop="datePublished" content="$date" />
		</div>

PAYLOAD_QUESTION_ITEM;
        }

        $this->_payload .= <<<PAYLOAD_QUESTION_END
        </div>
	<script type="text/javascript">
		document.getElementById('bvseo-questionsSection').style.display = 'none';
	</script>
	<!--end-questions-->

PAYLOAD_QUESTION_END;

        $this->_payload .= <<<PAYLOAD_ANSWER_BEGIN
    <!--begin-answers-->
	<div id="bvseo-answersSection">

PAYLOAD_ANSWER_BEGIN;

        foreach ($this->_answers as $answer)
        {
            $date = $this->_convertDate($answer['SubmissionTime']);
            $this->_payload .= <<<PAYLOAD_ANSWER_ITEM
        <div class="bvseo-answer" itemprop="question" itemscope itemtype="http://schema.org/Reviews" data-questionid="{$answer['Id']}">
			<meta itemprop="itemReviewed" content="{$this->_product[0]['Name']}" />
			<span itemprop="userComments" itemscope itemtype="http://schema.org/UserComments">
				<span itemprop="commentText">{$answer['AnswerText']}</span></span> by
				<span itemprop="author">{$answer['UserNickname']}</span> from
				<span itemprop="contentLocation">{$answer['UserLocation']}</span>
		    <div class="bvseo-pubdate">Date published: $date</div>
			<meta itemprop="datePublished" content="$date" />
		</div>

PAYLOAD_ANSWER_ITEM;
        }

        $this->_payload .= <<<PAYLOAD_ANSWER_END
        </div>
	<script type="text/javascript">
		document.getElementById('bvseo-answersSection').style.display = 'none';
	</script>
	<!--end-answers-->

PAYLOAD_ANSWER_END;

        }

        $today = date('Y-m-d');
        $this->_payload .= <<<PAYLOAD_END_ROOT
</div>
<ul id="BVSEOCPS_GEN" style="display:none;">
	<li id="bvDateModified">{$today}</li>
	<li id="pr">bvseo-cps-pr-8,30</li>
	<li id="tr">bvseo-cps-tr-5</li>
	<li id="sr">bvseo-cps-sr-relevancy</li>
	<li id="pl">bvseo-cps-pl-CONV</li>
	<li id="cn">bvseo-cps-cn-teachco</li>
	<li id="tl">bvseo-cps-tl-en_US</li>
	<li id="cp">bvseo-cps-cp-1</li>
	<li id="en">bvseo-cps-en-PRD</li>
</ul>

<!--end-bvseo-reviews-->

PAYLOAD_END_ROOT;


        $this->_seoRendered = true;
    }

    private function _convertDate($date)
    {
        return date('Y-m-d', strtotime($date));
    }

    /**
     * fetchCloudContent
     *
     * Helper method that will take in a URL and return it's payload while
     * handling the possible errors or exceptions that can happen.
     *
     * @access private
     * @param string (valid url)
     * @return string
     */
    private function _fetchCloudContent($url){

        // is cURL installed yet?
        // if ( ! function_exists('curl_init')){
        //    return '<!-- curl library is not installed -->';
        // }

        // create a new cURL resource handle
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url); // Set URL to download
        curl_setopt($ch, CURLOPT_REFERER, $this->config['current_page_url']); // Set a referer as coming from the current page url
        curl_setopt($ch, CURLOPT_HEADER, 0); // Include header in result? (0 = yes, 1 = no)
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Should cURL return or print out the data? (true = return, false = print)
        curl_setopt($ch, CURLOPT_TIMEOUT, ($this->config['latency_timeout'] / 1000)); // Timeout in seconds

        // make the request to the given URL and then store the response, request info, and error number
        // so we can use them later
        $request = array(
            'response' => curl_exec($ch),
            'info' => curl_getinfo($ch),
            'error_number' => curl_errno($ch),
            'error_message' => curl_error($ch)
        );

        // Close the cURL resource, and free system resources
        curl_close($ch);

        // see if we got any errors with the connection
        if($request['error_number'] != 0){
            $msg = 'Error - '.$request['error_message'];
            $this->_buildComment($msg);
        }

        // see if we got a status code of something other than 200
        if($request['info']['http_code'] != 200){
            $msg = 'HTTP status code of '.$request['info']['http_code'].' was returned';
            return $this->_buildComment($msg);
        }

        // if we are here we got a response so let's return it
        $msg = 'timer '.($request['info']['total_time'] * 1000).'ms';
        return $request['response'].$this->_buildComment($msg);
    }

    /**
     * replaceTokens
     *
     * After we have an SEO payload we need to replace the {INSERT_PAGE_URI}
     * tokens with the current page url so pagination works.
     *
     * @access private
     * @param string (valid url)
     * @return string
     */

    private function _replaceTokens($content){
        // determine if query string exists in current page url
        if (parse_url($this->config['current_page_url'], PHP_URL_QUERY) != ''){
            // append an amperstand, because the URL already has a ? mark
            $page_url_query_prefix = '&';
        } else {
            // append a question mark, since this URL currently has no query
            $page_url_query_prefix = '?';
        }

        $content = str_replace('{INSERT_PAGE_URI}', $this->config['current_page_url'] . $page_url_query_prefix, $content);

        return $content;
    }

    private function _buildComment($msg){
        return "\n".'<!--BVSEO|dp: '.$this->config['deployment_zone_id'].'|sdk: v1.0-p|msg: '.$msg.' -->';
    }

} // end of Base class


class Reviews extends Base{

    function __construct($params = array())
    {
        // call Base Class constructor
        parent::__construct($params);

        // since we are in the reviews class
        // we need to set the bv_product config 
        // to reviews so we get reviews in our
        // SEO request
        $this->config['bv_product'] = 'reviews';

        // for reviews subject type will always 
        // need to be product
        $this->config['subject_type'] = 'product';
    }

    public function renderSeo()
    {
        $pay_load = $this->_renderSeo();

        // if they want to power display integration as well
        // then we need to include the JS integration code
        // regardless of if it's a bot or not
        if($this->config['include_display_integration_code'])
        {
            $pay_load .= '
               <script>
                   $BV.ui("rr", "show_reviews", {
                       productId: "'.$this->config['product_id'].'"
                   });
               </script>
           ';
        }

        return $pay_load;

    }
} // end of Reviews class


class Questions extends Base{

    function __construct($params = array())
    {
        // call Base Class constructor
        parent::__construct($params);

        // since we are in the questions class
        // we need to set the bv_product config 
        // to questions so we get questions in our
        // SEO request
        $this->config['bv_product'] = 'questions';
    }

    public function renderSeo()
    {
        $pay_load = $this->_renderSeo();

        // if they want to power display integration as well
        // then we need to include the JS integration code
        // regardless of if it's a bot or not
        if($this->config['include_display_integration_code'])
        {

            $pay_load .= '
               <script>
                   $BV.ui("qa", "show_questions", {
                       productId: "'.$this->config['product_id'].'"
                   });
               </script>
           ';
        }

        return $pay_load;

    }
} // end of Questions class

// end of bvsdk.php