<?php
/**
 * @category    Podcast
 * @package     Tgc
 * @copyright   Copyright (c) 2014 Guidance
 * @author      Chris Lohman <clohm@guidance.com>
 */

class Tgc_Podcast_Helper_Data extends RocketWeb_Podcast_Helper_Data
{
    const PAGE_DESCRIPTION = 'rocketweb_podcast/settings/page_description';
    const PAGE_TITLE = 'rocketweb_podcast/settings/page_title';
    const PAGE_SUBTITLE = 'rocketweb_podcast/settings/subtitle';
    const PAGE_BG_IMAGE = 'rocketweb_podcast/settings/tgc_bg_image';
    const PAGE_LOGO_IMAGE = 'rocketweb_podcast/settings/tgc_logo_image';

    public function getPageDescription()
    {
        return Mage::getStoreConfig(self::PAGE_DESCRIPTION);
    }

    public function getPageTitle()
    {
        return Mage::getStoreConfig(self::PAGE_TITLE);
    }

    public function getPageSubtitle()
    {
        return Mage::getStoreConfig(self::PAGE_SUBTITLE);
    }

    public function getPageBackgroundImage()
    {
        $bg_image_path = Mage::getStoreConfig(self::PAGE_BG_IMAGE);

        return $this->getPodcastDirectoryChannelUrl() . $bg_image_path;
    }

    public function getPageLogoImage()
    {
        $bg_image_path = Mage::getStoreConfig(self::PAGE_LOGO_IMAGE);

        return $this->getPodcastDirectoryChannelUrl() . $bg_image_path;
    }

    public function removePlayerWidget($longContent){
        $processor = Mage::helper('cms')->getPageTemplateProcessor();

        $cmsHtml = $processor->filter($longContent);

        $dom = new DOMDocument;
        @$dom->loadHTML($cmsHtml);
        $xPath = new DOMXPath($dom);
        $nodes = $xPath->query('//*[@id="brightCove-widget"]');

        foreach($nodes as $node){
            $node->parentNode->removeChild($node);
        }

        return $cmsHtml = $dom->saveHTML();
    }

    public function getPodcastUrl($podcast) {
        $url = Mage::getBaseUrl() . Mage::helper('podcast')->getRoute() . '/';
        if ($podcast->getUrlKey()) {
            $url .= $podcast->getUrlKey();
        } else {
            $url .= $this->encodeUrl($podcast->getTitle(), $podcast->getPodcastId());
        }
        return $url;
    }
}
