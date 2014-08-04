<?php
/**
 * Bazaarvoice
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Bazaarvoice
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Bazaarvoice_Helper_Data extends Bazaarvoice_Connector_Helper_Data
{
    const OPTION_1_STAR = '1-stars-and-up';
    const OPTION_2_STAR = '2-stars-and-up';
    const OPTION_3_STAR = '3-stars-and-up';
    const OPTION_4_STAR = '4-stars-and-up';
    const OPTION_5_STAR = '5-stars-and-up';
    const STAGING_URL = 'theteachingcompany.ugc.bazaarvoice.com/bvstaging/static/3456qa-en_us';
    const QA_LABEL = 'Questions & Answers';
    const REVIEW_LABEL = 'Reviews';
    const XML_CONFIG_ID = 'bazaarvoice/tgc_config/product_identifier';

    public function isBvEnabled()
    {
        $moduleEnabled = (bool)Mage::getStoreConfig('bazaarvoice/general/enable_bv');
        $reviewsEnabled = (bool)Mage::getStoreConfig('bazaarvoice/rr/enable_rr');

        return ($moduleEnabled && $reviewsEnabled);
    }

    public function isQaEnabled()
    {
        $moduleEnabled = (bool)Mage::getStoreConfig('bazaarvoice/general/enable_bv');
        $qaEnabled = (bool)Mage::getStoreConfig('bazaarvoice/qa/enable_qa');

        return ($moduleEnabled && $qaEnabled);
    }

    public function getBvApiHostUrl($isStatic, $store = null)
    {
        // Build protocol based on current page
        $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != '') ? 'https' : 'http';
        // Build hostname based on environment setting
        $environment = Mage::getStoreConfig('bazaarvoice/general/environment', $store);
        if ($environment == 'staging') {
            return $protocol . '://' . self::STAGING_URL;
        } else {
            $apiHostname = 'display.ugc.bazaarvoice.com';
        }
        // Build static dir name based on param
        if ($isStatic) {
            $static = 'static/';
        } else {
            $static = '';
        }
        // Lookup other config settings
        $clientName = Mage::getStoreConfig('bazaarvoice/general/client_name', $store);
        $deploymnetZoneName = Mage::getStoreConfig('bazaarvoice/general/deployment_zone', $store);
        // Get locale code from BV config,
        // Note that this doesn't use Magento's locale, this will allow clients to override this and map it as they see fit
        $localeCode = Mage::getStoreConfig('bazaarvoice/general/locale', $store);
        // Build url string
        $url = $protocol . '://' . $apiHostname . '/' . $static . $clientName . '/' . urlencode($deploymnetZoneName) . '/' . $localeCode;
        // Return final url
        return $url;
    }

    public function isAuthenticated()
    {
        $session = Mage::getSingleton('customer/session');
        if (!$session->isLoggedIn()) {
            return false;
        }

        $customer = $session->getCustomer();
        $isProspect = $customer->getIsProspect();
        if ($isProspect) {
            return false;
        }

        return true;
    }

    public function getReviewLabel()
    {
        return self::REVIEW_LABEL;
    }

    public function getQaLabel()
    {
        return self::QA_LABEL;
    }

    public function getProductIdentifier()
    {
        return Mage::getStoreConfig(self::XML_CONFIG_ID);
    }

    public function getProductId($product)
    {
        $identifier = $this->getProductIdentifier();
        $productId = $product->getData($identifier);
        if (empty($productId)) {
            $productId = $product->getSku();
        }

        return $this->replaceIllegalCharacters($productId);

    }

    public function getProductFromProductExternalId($productExternalId)
    {
        $rawId = $this->reconstructRawId($productExternalId);
        $model = Mage::getModel('catalog/product');
        $identifier = $this->getProductIdentifier();

        $productCollection = $model->getCollection()->addAttributeToSelect('*')
            ->addAttributeToFilter($identifier, $rawId)
            ->load();


        foreach ($productCollection as $product) {
            // return the first one
            return $product;
        }

        return Mage::getModel('catalog/product');
    }

    public function getSmartSEOContent($bvProduct, $bvSubjectArr, $pageFormat)
    {
        //let's not waste our time here
        return false;
    }

    public function getRatingForProduct(Mage_Catalog_Model_Product $product)
    {
        $rating = Mage::getBlockSingleton('tgc_bv/reviews')->getProductRating($product);

        return $rating;
    }

    public function getInlineRatingForProduct($product)
    {
        $html = '';
        $rating = $product->getInlineRating();
        if ($rating) {
            $rating = round($rating, 1, PHP_ROUND_HALF_UP);
            @list($i, $d) = explode('.', $rating);
        } else {
            $i = $d = 0;
        }
        $html .= '<div class="ratings"><div class="rating-box"><div class="rating" style="width: ' . (intval($i).intval($d))*2 . '%"></div></div></div>';
        return $html;
    }
}
