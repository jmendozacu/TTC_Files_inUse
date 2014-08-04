<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Wishlist
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_Wishlist_Block_Customer_Wishlist_Item_Edit_Options extends Mage_Wishlist_Block_Abstract
{
    /**
     * Renderers with render type key
     * block    => the block name
     * template => the template file
     * renderer => the block object
     *
     * @var array
     */
    protected $_itemOptionsRenders = array();

    public function isEnabled()
    {
        return true;
    }

    /**
     * Add options renderer for item product type
     *
     * @param   string $type
     * @param   string $block
     * @param   string $template
     * @return  Mage_Checkout_Block_Cart_Abstract
     */
    public function addItemOptionsRender($type, $block, $template)
    {
        $this->_itemOptionsRenders[$type] = array(
            'block' => $block,
            'template' => $template,
            'renderer' => null
        );

        return $this;
    }

    /**
     * Retrieve item options renderer block
     *
     * @param string $type
     * @return Mage_Core_Block_Abstract
     */
    public function getItemOptionsRenderer($type)
    {
        if (!isset($this->_itemOptionsRenders[$type])) {
            return false;
        }

        if (is_null($this->_itemOptionsRenders[$type]['renderer'])) {
            $this->_itemOptionsRenders[$type]['renderer'] = $this->getLayout()
                ->createBlock($this->_itemOptionsRenders[$type]['block'])
                ->setTemplate($this->_itemOptionsRenders[$type]['template'])
                ->setRenderedBlock($this);
        }
        return $this->_itemOptionsRenders[$type]['renderer'];
    }

    /**
     * Return product type for wishlist item
     *
     * @param Varien_Object $item
     * @return string
     */
    protected function _getItemType(Varien_Object $item)
    {
        return $item->getProduct()->getTypeId();
    }

    /**
     * Get item row html
     *
     * @param   Varien_Object $item
     * @return  string
     */
    public function getItemOptionsHtml(Varien_Object $item)
    {
        $type = $this->_getItemType($item);

        $block = $this->getItemOptionsRenderer($type);
        if (!$block) {
            return '';
        }

        $block->setItem($item);
        return $block->toHtml();
    }

    public function getItemAddToCartUrl($item)
    {
        return Mage::helper('tgc_wishlist')->getAddToCartUrl($item);
    }
}
