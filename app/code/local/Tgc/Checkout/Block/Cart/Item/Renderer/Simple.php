<?php
/**
 * Magento Enterprise Edition
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Magento Enterprise Edition License
 * that is bundled with this package in the file LICENSE_EE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.magentocommerce.com/license/enterprise-edition
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Checkout
 * @copyright   Copyright (c) 2013 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://www.magentocommerce.com/license/enterprise-edition
 */


/**
 * Renderer for mini cart
 *
 * @category    Tgc
 * @package     Tgc_Checkout
 * @author      Guidance <clohm@guidance.com>
 */
class Tgc_Checkout_Block_Cart_Item_Renderer_Simple extends Ayasoftware_SimpleProductPricing_Checkout_Block_Cart_Item_Renderer
{
    /**
     * For transcript product, it returns the parent's name.
     * @return null|string
     */
    public function getProductName()
    {
        $item = $this->getItem();
        if ($this->getConfigurableProductParentId()) {
            $product = $this->getConfigurableProductParent();
            return $product->getName();
        } elseif($item->getIsTranscriptProduct()) {
            $transcriptsParentProduct = $this->getTranscriptProductParent();
            $transcriptsParentProductName = null;
            if($transcriptsParentProduct instanceof Mage_Catalog_Model_Product) {
                $transcriptsParentProductName = $transcriptsParentProduct->getName();
            }
            return $transcriptsParentProductName;
        } else {
            return Mage_Checkout_Block_Cart_Item_Renderer::getProductName();
        }

    }

    /**
     * Returns thumbnail image of current product.
     * @return Mage_Catalog_Model_Product_Image
     */
    public function getProductThumbnail()
    {
        $isTranscriptProduct = $this->getItem()->getIsTranscriptProduct();

        if (!$this->getConfigurableProductParentId() && !$isTranscriptProduct) {
            return Mage_Checkout_Block_Cart_Item_Renderer::getProductThumbnail();
        }

        #If showing simple product image  - if its a transcript product, the parent products image needs to be displayed!
        if($isTranscriptProduct) {
            $product = $this->getTranscriptProductParent();
        } else {
            $product = $this->getProduct();
        }

        #if product image is not a thumbnail
        if($product->getData('thumbnail') && ($product->getData('thumbnail') != 'no_selection')) {
            return $this->helper('catalog/image')->init($product, 'thumbnail');
        }
        #If simple prod thumbnail image is placeholder, or we're not using simple product image
        #show configurable product image
        $product = $this->getConfigurableProductParent();
        return $this->helper('catalog/image')->init($product, 'thumbnail');
    }

    /**
     * Returns the transcript product associated with the current item.
     * @return mixed
     */
    protected function getTranscriptProductParent()
    {
        $quote = Mage::helper('checkout/cart')->getQuote();
        $parentItemIdOfTranscript = $this->getItem()->getTranscriptParentItemId();
        $transcriptParentProduct = false;

        if($parentItem = $quote->getItemById($parentItemIdOfTranscript)) {
            if($productId = $parentItem->getProduct()->getId()) {
                $transcriptParentProduct = Mage::getModel('catalog/product')
                    ->setStoreId(Mage::app()->getStore()->getId())
                    ->load($productId);
            }
        }

        return $transcriptParentProduct;
    }

}
