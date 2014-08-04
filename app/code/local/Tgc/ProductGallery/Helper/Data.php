<?php
/**
 * ProductGallery
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     ProductGallery
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_ProductGallery_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * Places the drtv_trailer at the beginning of the array.  As a result, the first image that will be displayed on the adminhtml media
     * gallery page will be the drtv trailer.
     * @param $block
     * @return array|null
     */
    public function getImageTypes($block)
    {
        $imageTypesSorted = null;
        if($block instanceof Mage_Adminhtml_Block_Catalog_Product_Helper_Form_Gallery_Content) {
            $imageTypes = $block->getImageTypes();

            $imageTypesNodrtv = $imageTypes;
            if(isset($imageTypes['drtv_trailer'])) {
                $drtvTrailer = array('drtv_trailer' => $imageTypesNodrtv['drtv_trailer']);
                unset($imageTypesNodrtv['drtv_trailer']);
                $imageTypesSorted = array_merge($drtvTrailer,$imageTypesNodrtv);
            } else {
                $imageTypesSorted = $imageTypes;
            }
        }

        return $imageTypesSorted;
    }
}
