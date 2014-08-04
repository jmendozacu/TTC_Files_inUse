<?php
/**
 * Digital Library
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DigitalLibrary
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_DigitalLibrary_Block_Index_List extends Tgc_DigitalLibrary_Block_List_Abstract
{

    /**
     * Retrieve loaded category collection
     *
     * @return Mage_Eav_Model_Entity_Collection_Abstract
     */
    protected function _getProductCollection()
    {
        if (is_null($this->_productCollection)) {
            $access = Mage::getResourceModel('tgc_dl/accessRights');
            $customer = $this->_getCustomer();

            $this->_productCollection = $access->getCoursesCollectionForCustomer($customer);
        }

        return $this->_productCollection;
    }

    public function getProductCount()
    {
        $collection = $this->_getProductCollection();

        return count($collection->getAllIds());
    }

    /**
     * @param $product Mage_Catalog_Model_Product
     * @return mixed
     */
    public function getReviewUrl($product) {
        $params = array();
        $params['review'] = true;
        $url = $product->getUrlModel()->getUrl($product, $params);

        if (!strpos($url,"review/1")) {
            $url .= "?review=1";
        }

        return $url;
    }
}
