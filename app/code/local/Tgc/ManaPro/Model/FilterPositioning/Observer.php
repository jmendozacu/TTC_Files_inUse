<?php
/**
 * ManaPro_FilterPositioning observer
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     ManaPro
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_ManaPro_Model_FilterPositioning_Observer extends ManaPro_FilterPositioning_Model_Observer
{
    public function renderCategoryCmsBlock($observer) {
        /* @var $block Mage_Catalog_Block_Category_View */
        $block = $observer->getEvent()->getBlock();

        if ($block instanceof Mage_Catalog_Block_Category_View &&
            $block->getCurrentCategory() &&
            ($block->isContentMode() || $block->isMixedMode()) &&
            ($aboveBlock = $block->getChild('above_products')) &&
            !$aboveBlock->getData('m_already_rendered'))
        {
            /* @var $cmsBlock Mage_Cms_Block_Block */
            $cmsBlock = $block->getLayout()->createBlock('cms/block');
            $cmsBlock->setData('block_id', $block->getCurrentCategory()->getDataUsingMethod('landing_page'));
            $html = $aboveBlock->toHtml(). $cmsBlock->toHtml();
            $aboveBlock->setData('m_already_rendered', true);
            $block->setData('cms_block_html', $html);
        }
    }
}
