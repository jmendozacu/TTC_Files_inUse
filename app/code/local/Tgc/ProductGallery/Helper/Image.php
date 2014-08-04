<?php
/**
 * ProductGallery
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     ProductGallery
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_ProductGallery_Helper_Image extends Mage_Core_Helper_Abstract
{

    protected $_attributeNameDrtvUses = null;

    protected $_drtvImageFilename = null;

    protected $_shouldDisplayDrtvImage = null;

    protected $_product = null;

    /**
     * Returns the main product image.  If the product is a drtv, the drtv image is used, otherwise regular image is used.
     *
     * @param $_product
     * @param $imgWidth
     * @param $imgHeight
     * @return mixed
     */
    public function getProductImage($_product,$imgWidth, $imgHeight,$imageToDisplay = 'image', $imageFile = null)
    {
        if($this->_adcodeHelper()->isDrtvAd()) {
            $imageToDisplay = $this->getDrtvImageType(); //if drtv trailer image exists, it uses that, if not, falls back to base image.
        }

        return $this->getLayout()->helper('catalog/image')->init($_product, $imageToDisplay, $imageFile)->resize($imgWidth, $imgHeight);
    }

    /**
     * Get image URL of the given product
     *
     * @param Mage_Catalog_Model_Product	$product		Product
     * @param int							$w				Image width
     * @param int							$h				Image height
     * @param string						$imgVersion		Image version: image, small_image, thumbnail
     * @param mixed							$file			Specific file
     * @return string
     */
    public function getProductImgVideoUrl($product, $w, $h, $imgVersion='image', $file=NULL)
    {
        $url = '';
        if ($h <= 0)
        {
            $url = Mage::helper('catalog/image')
                ->init($product, $imgVersion, $file)
                ->constrainOnly(true)
                ->keepAspectRatio(true)
                ->keepFrame(false)
                //->setQuality(90)
                ->resize($w);
        }
        else
        {
            $url = $this->getProductImage($product,$w, $h,$imgVersion, $file);
        }
        return $url;
    }

    /**
     * If the drtv image does not exist, then base image is used as a fallback.
     * @return null
     */
    public function getDrtvImageType()
    {
        if(is_null($this->_attributeNameDrtvUses)) {
            $this->_attributeNameDrtvUses = false;
            if($product = $this->getProduct()) {
                if($product->getId()) {
                    if($product->getDrtvTrailer() && $product->getDrtvTrailer() != 'no_selection' && $this->shouldDisplayDRTVimage()) {
                        $this->_attributeNameDrtvUses = 'drtv_trailer';
                    } else {
                        $this->_attributeNameDrtvUses = 'image';
                    }
                }
            }
        }

        return $this->_attributeNameDrtvUses;
    }

    /**
     * Returns the filename of the image being used for drtv trailer. (if the fallback image is being used. it returns that image.)
     * @return bool|null
     */
    public function getDrtvImageFilename()
    {
        if(is_null($this->_drtvImageFilename)) {
            $this->_drtvImageFilename = false;
            $drtvImageType = $this->getDrtvImageType();
            $product = $this->getProduct();

            if($product && $drtvImageType) {
                if($product->getId()) {
                    if($drtvImageType) {
                        $this->_drtvImageFilename = $product->getData($drtvImageType);
                    }
                }
            }
        }

        return $this->_drtvImageFilename;
    }

    /**
     * Determines if the DRTV image should be displayed.
     * @return bool
     */
    public function shouldDisplayDRTVimage()
    {
        if(is_null($this->_shouldDisplayDrtvImage)) {
            $this->_shouldDisplayDrtvImage = false;
            if($product = $this->getProduct()) {
                if($currentProductAttributeSetId = $product->getAttributeSetId()) {
                    $catalogConfig = Mage::getModel('catalog/config');
                    $courseAttributeSetId = $catalogConfig->getAttributeSetId(Mage_Catalog_Model_Product::ENTITY,'Courses'); //this is attribute_set_id for Courses
                    if($currentProductAttributeSetId == $courseAttributeSetId) { //if the current product is a course this evaluates to true.
                        if($this->_adcodeHelper()->isDrtvAdType()) {
                            $this->_shouldDisplayDrtvImage = true;
                        }
                    }
                }
            }
        }

        return $this->_shouldDisplayDrtvImage;
    }

    /**
     * Returns an instance of the object containing the drtv data.
     * @param string $galleryImages
     * @return bool|Varien_Object
     */
    public function getDrtvImage($galleryImages = '')
    {
        $drtvImage = false;
        if($galleryImages instanceof Varien_Data_Collection) {
            $newGalleryImagesCollection = clone $galleryImages;
            //Line below, drtv image filename is returned as long as it exists, if it does not exist, base image filename is pulled.
            $drtvTrailer = $this->getDrtvImageFilename();
            $drtvImage = $newGalleryImagesCollection->getItemByColumnValue('file', $drtvTrailer);
        }

        return $drtvImage;
    }

    public function getRealDrtvImage($galleryImages = '')
    {
        $drtvImage = false;
        if($galleryImages instanceof Varien_Data_Collection) {
            $newGalleryImagesCollection = clone $galleryImages;
            //Line below, drtv image filename is returned as long as it exists, if it does not exist, base image filename is pulled.
            $drtvTrailer = $this->getProduct()->getDrtvTrailer();
            $drtvImage = $newGalleryImagesCollection->getItemByColumnValue('file', $drtvTrailer);
        }

        return $drtvImage;
    }

    /**
     * Returns all image data for the drtv image in an array format
     * @param string $galleryImages
     * @return bool|mixed
     */
    public function getFormattedDrtvImageData($galleryImages = '')
    {
        $drtvImageData = false;

        $drtvImage = $this->getDrtvImage($galleryImages);
        if($drtvImage instanceof Varien_Object) {
            if($this->getBrightcoveIdOfDRTV($galleryImages)) {
                $drtvImage->setPath(null);
                $drtvImageData = $drtvImage->getData();
            }
        }

        return $drtvImageData;
    }

    /**
     * Returns brightcove id of the DRTV
     * return int
     */
    public function getBrightcoveIdOfDRTV($galleryImages = '')
    {
        $brightcoveId = false;
        if($galleryImages instanceof Varien_Data_Collection) {
            $newGalleryImagesCollection = clone $galleryImages;
            //Line below, drtv image filename is returned as long as it exists, if it does not exist, base image filename is pulled.
            $drtvTrailer = $this->getProduct()->getDrtvTrailer();
            $drtvImage = $newGalleryImagesCollection->getItemByColumnValue('file', $drtvTrailer);

            if(is_object($drtvImage)) {
                $brightcoveId = $drtvImage->getBrightcoveId();
            }
        }

        return $brightcoveId;
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
     * Init helper for certain product
     *
     * @param type $product
     * @return \Tgc_ProductGallery_Helper_Image
     */
    public function init($product)
    {
        $this->_product = $product;
        $this->_attributeNameDrtvUses = null;
        $this->_drtvImageFilename = null;
        $this->_shouldDisplayDrtvImage = null;
        return $this;
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
