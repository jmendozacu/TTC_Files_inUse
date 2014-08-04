<?php
/**
 * Magento
 * NOTICE OF LICENSE
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 * DISCLAIMER
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category   Mage
 * @package    Mage_Catalog
 * @copyright  Copyright (c) 2008 Irubin Consulting Inc. DBA Varien (http://www.varien.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


/**
 * Catalog super product configurable part block
 *
 * @category    Mage
 * @package     Mage_Catalog
 * @author      Magento Core Team <core@magentocommerce.com>
 */

class Ayasoftware_SimpleProductPricing_Catalog_Block_Product_View_Type_Configurable extends Mage_Catalog_Block_Product_View_Type_Configurable
{

    public function getJsonConfig()
    {
        $showOutOfStock        = false;
        $specialPricedProducts = array();
        $config                = Zend_Json::decode(parent::getJsonConfig());
        if (Mage::getStoreConfig('spp/setting/show')) {
            $productsCollection = $this->canShowOutOfStockProducts();
            $showOutOfStock     = true;
        } else {
            $productsCollection = $this->getAllowProducts();
        }
        //Create the extra price and tier price data/html we need.
        foreach ($productsCollection as $product) {
            $productId   = $product->getId();
            $stockItem   = Mage::getModel('cataloginventory/stock_item')->loadByProduct($productId);
            $currentItem = Mage::getModel('catalog/product')->load($productId);
            if ($stockItem->getQty() <= 0) {
                $stockInfo[$productId] = array(
                    "stockLabel"  => $this->__('Out of stock'),
                    "stockQty"    => intval($stockItem->getQty()),
                    "is_in_stock" => false,
                );
            } else {
                $stockInfo[$productId] = array(
                    "stockLabel"  => $this->__('In stock'),
                    "stockQty"    => intval($stockItem->getQty()),
                    "is_in_stock" => true,
                );
            }
            $finalPrice = $product->getFinalPrice();

            if ($product->getCustomerGroupId()) {
                $finalPrice = $product->getGroupPrice();
            }

            if ($product->getTierPrice()) {
                $tprices = array();
                foreach ($tierprices = $product->getTierPrice() as $tierprice) {
                    $tprices[] = $tierprice['price'];
                }
                $tierpricing = min($tprices);
            } else {
                $tierpricing = '';
            }

            $childProducts[$productId] = array(
                "price"       => $this->_registerJsPrice($this->_convertPrice($product->getPrice())),
                "finalPrice"  => $this->_registerJsPrice($this->_convertPrice($finalPrice)),
                "tierpricing" => $this->_registerJsPrice($this->_convertPrice($tierpricing))
            );

            if (Mage::getStoreConfig('spp/setting/shortdescription')) {
                $shortDescriptions[$productId] = array(
                    "shortDescription" => $this->helper('catalog/output')->productAttribute($product, nl2br($product->getShortDescription()), 'short_description')
                );
            }
            if (Mage::getStoreConfig('spp/setting/description')) {
                $Descriptions[$productId] = array(
                    "Description" => $this->helper('catalog/output')->productAttribute($product, nl2br($product->getDescription()), 'description')
                );
            }
            if (Mage::getStoreConfig('spp/setting/productname')) {
                $ProductNames[$productId] = array(
                    "ProductName" => $product->getName()
                );
            }
        }
        if (Mage::getStoreConfig('spp/setting/customstockdisplay')) {
            $config['customStockDisplay'] = true;
        } else {
            $config['customStockDisplay'] = false;
        }
        $config['showOutOfStock']           = $showOutOfStock;
        $config['stockInfo']                = $stockInfo;
        $config['childProducts']            = $childProducts;
        $config['productName']              = $this->getProduct()->getName();
        $config['description']              = $this->getProduct()->getDescription();
        $config['shortDescription']         = $this->getProduct()->getShortDescription();
        $config['showPriceRangesInOptions'] = true;
        $config['rangeToLabel']             = $this->__('-');
        if (Mage::getStoreConfig('spp/setting/hideprices')) {
            $config['hideprices'] = true;
        } else {
            $config['hideprices'] = false;
        }

        if (Mage::getStoreConfig('spp/setting/productname')) {
            $config['ProductNames']      = $ProductNames;
            $config['updateProductName'] = true;
        } else {
            $config['updateProductName'] = false;
        }
        if (Mage::getStoreConfig('spp/setting/description')) {
            $config['Descriptions']      = $Descriptions;
            $config['updateDescription'] = true;
        } else {
            $config['updateDescription'] = false;
        }
        if (Mage::getStoreConfig('spp/setting/shortdescription')) {
            $config['shortDescriptions']      = $shortDescriptions;
            $config['updateShortDescription'] = true;
        } else {
            $config['updateShortDescription'] = false;
        }
        if (Mage::getStoreConfig('spp/setting/showfromprice')) {
            $config['showfromprice'] = true;
        } else {
            $config['showfromprice'] = false;
        }
        $config['priceFromLabel'] = '';
        $config['ajaxBaseUrl']    = Mage::getUrl('spp/ajax/');
        return Zend_Json::encode($config);
    }


    /**
     * Get Allowed Products
     *
     * @return array
     */
    public function canShowOutOfStockProducts()
    {

        $category_name = Mage::getSingleton('catalog/layer')
            ->getCurrentCategory()
            ->getName();
        if (!$this->hasAllowProducts()) {
            $products    = array();
            $allProducts = $this->getProduct()->getTypeInstance(true)
                ->getUsedProducts(null, $this->getProduct());
            foreach ($allProducts as $product) {
                $products[] = $product;
            }
            $this->setAllowProducts($products);
        }
        return $this->getData('allow_products');
    }


    public function getAllowProducts()
    {

        $category_name = Mage::getSingleton('catalog/layer')
            ->getCurrentCategory()
            ->getName();
        if (!$this->hasAllowProducts()) {
            $products          = array();
            $skipSaleableCheck = Mage::helper('catalog/product')->getSkipSaleableCheck();
            $allProducts       = $this->getProduct()->getTypeInstance(true)
                ->getUsedProducts(null, $this->getProduct());
            foreach ($allProducts as $product) {

                if ($product->isSaleable() || $skipSaleableCheck) {
                    $products[] = $product;
                }
            }
            $this->setAllowProducts($products);
        }
        return $this->getData('allow_products');
    }

    public function isMediaFormat($attribute)
    {
        return $attribute->hasProductAttribute()
                && $attribute->getProductAttribute()->getAttributeCode() == 'media_format';
    }
}