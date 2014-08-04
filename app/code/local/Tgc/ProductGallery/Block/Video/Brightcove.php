<?php
/**
 * ProductGallery
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     ProductGallery
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_ProductGallery_Block_Video_Brightcove extends Mage_Core_Block_Template
{
    const XML_PATH_PLAYER_JS_LIB_URL = 'catalog/product_brightcove_video/player_lib_url';
    const XML_PATH_PLAYER_ID = 'catalog/product_brightcove_video/player_id';
    const XML_PATH_PLAYER_KEY = 'catalog/product_brightcove_video/player_key';

    /**
     * Media block reference
     *
     * @var Mage_Catalog_Block_Product_View_Media
     */
    protected $_mediaBlock;

    /**
     * Product model
     *
     * @var Mage_Catalog_Model_Product
     */
    protected $_product;

    /**
     * Product base image data
     *
     * @var array
     */
    protected $_productBaseImageData;

    /**
     * Product gallery images with video
     *
     * @var array
     */
    protected $_productGalleryImagesWithVideo;

    /**
     * Check if block should be shown
     *
     * @return boolean
     */
    public function canShow()
    {
        if (!$this->getProduct()) {
            return false;
        }

        $productBaseImageData = $this->getProductBaseImageData();
        if (!$this->getGalleryImagesWithVideo()
            && !(isset($productBaseImageData['brightcove_id']) && $productBaseImageData['brightcove_id'])) {
            return false;
        }

        return true;
    }

    /**
     * Retrive player js lib url
     *
     * @return string
     */
    public function getPlayerLibUrl()
    {
        return Mage::getStoreConfig(self::XML_PATH_PLAYER_JS_LIB_URL);
    }

    /**
     * Retrive player id
     *
     * @return string
     */
    public function getPlayerId()
    {
        return Mage::getStoreConfig(self::XML_PATH_PLAYER_ID);
    }

    /**
     * Retrive player key
     *
     * @return string
     */
    public function getPlayerKey()
    {
        return Mage::getStoreConfig(self::XML_PATH_PLAYER_KEY);
    }

    /**
     * Retrieve media block
     *
     * @return Mage_Catalog_Block_Product_View_Media
     */
    public function getMediaBlock()
    {
        if (is_null($this->_mediaBlock)) {
            $this->_mediaBlock = $this->getLayout()->getBlock('product.info.media');
        }
        return $this->_mediaBlock;
    }

    /**
     * Retrieve product
     *
     * @return Mage_Catalog_Model_Product
     */
    public function getProduct()
    {
        if (is_null($this->_product)) {
            $this->_product = Mage::registry('product');
        }
        return $this->_product;
    }

    /**
     * Product setter
     *
     * @param Mage_Catalog_Model_Product $product
     * @return \Tgc_ProductGallery_Block_Video_Brightcove
     */
    public function setProduct(Mage_Catalog_Model_Product $product)
    {
        $this->reset();
        $this->_product = $product;
        $this->_productGalleryImageHelper()->init($product);
        return $this;
    }

    /**
     * Retrive gallery images with video config
     *
     * @return array
     */
    public function getGalleryImagesWithVideo()
    {
        if (is_null($this->_productGalleryImagesWithVideo)) {
            $galleryImagesWithVideo = array();
            if (!$this->getProcessOnlyBaseImage() && $this->getMediaBlock()
                && ($galleryImages = $this->getMediaBlock()->getGalleryImages())) {

                if($this->_productGalleryImageHelper()->shouldDisplayDRTVimage()) {
                    $drtvImageData = $this->_productGalleryImageHelper()->getFormattedDrtvImageData($galleryImages);
                    if($drtvImageData) {
                        unset($drtvImageData['path']);
                    }
                }

                foreach ($galleryImages as $image) {
                    if (isset($image['brightcove_id']) && $image['brightcove_id']) {
                        $imageData = $image->getData();
                        if (isset($imageData['path'])) {
                            unset($imageData['path']);
                        }

                        $galleryImagesWithVideo[] = $imageData;
                    }
                }
            }

            $this->_productGalleryImagesWithVideo = $galleryImagesWithVideo;
        }

        return $this->_productGalleryImagesWithVideo;
    }

    /**
     * Retrive product base image data
     *
     * @return array
     */
    public function getProductBaseImageData()
    {
        if (is_null($this->_productBaseImageData)) {
            $imageData = null;
            if (!($this->getProduct()->hasMediaGallery() && is_array($this->getProduct()->getMediaGallery()))) {
                $this->_loadProductMediaGallery();
            }
            $productImages = $this->getProduct()->getMediaGallery('images');

            //must be drtv ad and image must exist, otherwise base image filename will be returned.
            $productBaseImageFile = $this->_productGalleryImageHelper()->getDrtvImageFilename();

            if ($productImages && $productBaseImageFile && $productBaseImageFile != 'no_selection') {
                foreach ($productImages as $image) {
                    if (isset($image['file']) && $image['file'] == $productBaseImageFile) {
                        $imageData = $image;
                        break;
                    }
                }
            }

            $this->_productBaseImageData = $imageData;
        }

        return $this->_productBaseImageData;
    }

    /**
     * Load media gallery data for product
     *
     * @return \Tgc_ProductGallery_Block_Video_Brightcove
     */
    protected function _loadProductMediaGallery()
    {
        $mediaGalleryAttribute = $this->getProduct()->getResource()->getAttribute('media_gallery');
        if (!$mediaGalleryAttribute) {
            return $this;
        }

        $mediaGalleryBackendModel = $mediaGalleryAttribute->getBackend();
        if (!$mediaGalleryBackendModel) {
            return $this;
        }

        $mediaGalleryBackendModel->afterLoad($this->getProduct());

        return $this;
    }

    /**
     * Reset cached data
     *
     * @return \Tgc_ProductGallery_Block_Video_Brightcove
     */
    public function reset()
    {
        $this->_product = null;
        $this->_productBaseImageData = null;
        $this->_productGalleryImagesWithVideo = null;
        return $this;
    }

    /**
     * Returns the product gallery image helper
     * @return Tgc_ProductGallery_Helper_Image
     */
    protected function _productGalleryImageHelper()
    {
        return Mage::helper('tgc_productgallery/image');
    }

    /**
     * Returns the ad code router helper.
     * @return Tgc_Adcoderouter_Helper_Data
     */
    protected function _adcodeHelper()
    {
        return Mage::helper('adcoderouter');
    }
}
