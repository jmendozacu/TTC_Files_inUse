<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Dax
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Dax_Adminhtml_OrderController extends Mage_Adminhtml_Controller_Action
{
    const DEBUG_ENABLED_PATH = 'dax/order_export/debug_enabled';

    public function preDispatch()
    {
        Mage::getDesign()
            ->setArea($this->_currentArea)
            ->setPackageName((string)Mage::getConfig()->getNode('stores/admin/design/package/name'))
            ->setTheme((string)Mage::getConfig()->getNode('stores/admin/design/theme/default'))
        ;
        foreach (array('layout', 'template', 'skin', 'locale') as $type) {
            if ($value = (string)Mage::getConfig()->getNode("stores/admin/design/theme/{$type}")) {
                Mage::getDesign()->setTheme($type, $value);
            }
        }

        $this->getLayout()->setArea($this->_currentArea);

        Mage::dispatchEvent('adminhtml_controller_action_predispatch_start', array());
        Mage_Core_Controller_Varien_Action::preDispatch();

        if ($this->getRequest()->isDispatched()
            && $this->getRequest()->getActionName() !== 'denied'
            && !$this->_isAllowed()) {
            $this->_forward('denied');
            $this->setFlag('', self::FLAG_NO_DISPATCH, true);
            return $this;
        }

        if (!$this->getFlag('', self::FLAG_IS_URLS_CHECKED)
            && !$this->getRequest()->getParam('forwarded')
            && !$this->_getSession()->getIsUrlNotice(true)
            && !Mage::getConfig()->getNode('global/can_use_base_url')) {
            $this->setFlag('', self::FLAG_IS_URLS_CHECKED, true);
        }

        if (is_null(Mage::getSingleton('adminhtml/session')->getLocale())) {
            Mage::getSingleton('adminhtml/session')->setLocale(Mage::app()->getLocale()->getLocaleCode());
        }

        $testMode = (bool)Mage::getStoreConfig(self::DEBUG_ENABLED_PATH);

        if (!Mage::getIsDeveloperMode() && !$testMode) {
            $this->setFlag('', self::FLAG_NO_DISPATCH, true);
            $this->_forward('denied');
            return $this;
        }

        return $this;
    }

    public function exportAction()
    {
        $this->loadLayout();

        $html = '<p>DAX order export started</p>';
        $html .= Mage::getSingleton('tgc_dax/orderExport')->processExport();
        $block = $this->getLayout()->createBlock('core/text', 'dax.order.export')
            ->setText($html);
        $this->getLayout()->getBlock('content')->append($block);

        $this->renderLayout();
    }
}
