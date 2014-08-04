<?php
/**
 * Cms additions
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Cms
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_Cms_Helper_Wysiwyg_Images extends Mage_Cms_Helper_Wysiwyg_Images
{
    /**
     * Purpose of rewriting this function is, for any path containing word wysiwyg, delete everything that is before it.
     * Prepare Image insertion declaration for Wysiwyg or textarea(as_is mode)
     *
     * @param string $filename Filename transferred via Ajax
     * @param bool $renderAsTag Leave image HTML as is or transform it to controller directive
     * @return string
     */
    public function getImageHtmlDeclaration($filename, $renderAsTag = false)
    {
        $fileurl = $this->getCurrentUrl() . $filename;
        $mediaPath = str_replace(Mage::getBaseUrl('media'), '', $fileurl);
        $mediaPath = $this->_cleanImageWysiwygUrlPath($mediaPath); //prevents any uncessary file path information from being included.
        $directive = sprintf('{{media url="%s"}}', $mediaPath);
        if ($renderAsTag) {
            $html = sprintf('<img src="%s" alt="" />', $this->isUsingStaticUrlsAllowed() ? $fileurl : $directive);
        } else {
            if ($this->isUsingStaticUrlsAllowed()) {
                $html = $fileurl; // $mediaPath;
            } else {
                $directive = Mage::helper('core')->urlEncode($directive);
                $html = Mage::helper('adminhtml')->getUrl('*/cms_wysiwyg/directive', array('___directive' => $directive));
            }
        }
        return $html;
    }

    protected function _cleanImageWysiwygUrlPath($path)
    {
        return $this->_cleanCmsPath($path, 'wysiwyg');
    }

    protected function _cleanCmsPath($path, $type)
    {
        $positionWysiwyg = strpos($path, $type);
        if($positionWysiwyg) {
            $path = substr($path, $positionWysiwyg);
        }

        return $path;
    }

    public function convertIdToPath($id)
    {
        $path = $this->idDecode($id);
        if (!strstr($path, realpath($this->getStorageRoot()))) {
            $path = realpath($this->getStorageRoot()) . $path;
        }
        return $path;
    }
}