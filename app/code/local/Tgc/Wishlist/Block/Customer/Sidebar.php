<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Wishlist
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Wishlist_Block_Customer_Sidebar extends Mage_Wishlist_Block_Customer_Sidebar {

    /**
     * Override Core method to return html even if wishlist is empty
     * Have to call on grandParent class method _toHTML in order to avoid the getItemCount() conditional
     *
     * Prepare before to html
     *
     * @return string
     */
    protected function _toHtml()
    {
        //return grandParent class name dynamically through Reflection to keep it update proof
        $reflection = Mage::helper('Guidance_Reflection');
        $grandParentClass = $reflection->getGrandParentClassName(get_class());

        return $grandParentClass::_toHtml();
    }
}

?>