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
 * Wishlist sidebar block
 *
 * @category    Tgc
 * @package     Tgc_Checkout
 * @author      Guidance <clohm@guidance.com>
 */
class Tgc_Checkout_Block_Cart_Sidebar extends Mage_Checkout_Block_Cart_Sidebar
{
    /**
     * Get shopping cart items qty based on configuration (summary qty or items qty)
     *
     * Override to dispatch cart migration event.
     *
     * @return int | float
     */
    public function getSummaryCount()
    {
        // Trigger our cart migration routine to re-populate cart with items from old website.
        Mage::dispatchEvent('checkout_quote_migrate', array('quote'=>$this->getQuote()));

        if ($this->getData('summary_qty')) {
            return $this->getData('summary_qty');
        }
        return Mage::getSingleton('checkout/cart')->getSummaryQty();
    }

}
