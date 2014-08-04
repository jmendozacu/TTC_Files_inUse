<?php
/**
 * Tgc Catalog
 *
 * @author      Guidance Magento SuperTeam <magento@guidance.com>
 * @category    Tgc
 * @package     Catalog
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_Catalog_Block_Infortis_Cloudzoom_Product_View_Media extends Infortis_CloudZoom_Block_Product_View_Media
{

    protected $_galleryImagesSortedAndFiltered = null;

    /**
     * Retrieve list of gallery images
     *
     * @return array|Varien_Data_Collection
     */
    public function getGalleryImages()
    {
        if ($this->_isGalleryDisabled) {
            return array();
        }

        if(is_null($this->_galleryImagesSortedAndFiltered)) {
            $galleryImages = $this->getProduct()->getMediaGalleryImages();
            $galleryImagesSorted = new Varien_Data_Collection();

            //If the page is not a drtv page, getDrtvImage actually selects the base image instead.
            $drtvImage = $this->_productGalleryImageHelper()->getDrtvImage($galleryImages);

            $realDrtvImage = $this->_productGalleryImageHelper()->getRealDrtvImage($galleryImages); //this is never the fallback. It is always DRTV image.

            if(is_object($drtvImage) && $drtvImage->getId()) {
                $galleryImagesSorted->addItem($drtvImage);
            }

            if($this->_adcodeHelper()->isDrtvAd()) {
                if($galleryImages->count() > 0) {
                    //Ensuring that drtv image exists.


                    //foreach loop adds drtv testimonials to the collection first.
                    foreach($galleryImages as $galleryImage) {
                        if($galleryImage->getDrtvTestimonial() == 1 && !$this->_isGalleryImageDrtvTrailer($drtvImage, $galleryImage, $realDrtvImage)) {
                            $galleryImagesSorted->addItem($galleryImage);
                        }
                    }

                    //All other items in original collection are added after drtv testimonials are added.
                    foreach($galleryImages as $galleryImage) {
                        if($galleryImage->getDrtvTestimonial() != 1 && !$this->_isGalleryImageDrtvTrailer($drtvImage, $galleryImage, $realDrtvImage)) {
                            $galleryImagesSorted->addItem($galleryImage);
                        }
                    }
                }
            } else {
                //If not a DRTV ad page, then, this prevents drtv testimonials from appearing in thumbnails.
                foreach($galleryImages as $galleryImage) {
                    if($galleryImage->getDrtvTestimonial() != 1 && !$this->_isGalleryImageDrtvTrailer($drtvImage, $galleryImage, $realDrtvImage)) {
                        $galleryImagesSorted->addItem($galleryImage);
                    }
                }
            }

            $this->_galleryImagesSortedAndFiltered = $galleryImagesSorted;
        }

        return $this->_galleryImagesSortedAndFiltered;
    }

    protected function _isGalleryImageDrtvTrailer($drtvTrailerImage, $galleryImage, $realDrtvImage = '')
    {
        $isGalleryImageDrtvTrailer = false;

        if($drtvTrailerImage instanceof Varien_Object && $galleryImage instanceof Varien_Object) {
            if($drtvTrailerImage->getId() && $galleryImage->getId()) {
                //Following if condition does not add drtv image to the collection if current image being processed is drtv or if current image does not exist.
                if($drtvTrailerImage->getId() == $galleryImage->getId()
                    || ($realDrtvImage instanceof Varien_Object && $realDrtvImage->getId() == $galleryImage->getId())) { //prevents base image from being displayed twice.
                    $isGalleryImageDrtvTrailer = true;
                }
            }
        }

        return $isGalleryImageDrtvTrailer;
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