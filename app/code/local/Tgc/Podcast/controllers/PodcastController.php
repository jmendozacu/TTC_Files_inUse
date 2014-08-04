<?php
require_once 'RocketWeb/Podcast/controllers/PodcastController.php';
class Tgc_Podcast_PodcastController extends RocketWeb_Podcast_PodcastController
{
    public function viewAction()
    {
        $podcast_url = $this->getRequest()->getParam('identifier',0);
        $podcast_id = Mage::helper('podcast')->decodeUrl($podcast_url);
        if (!$podcast_id && Mage::getModel('podcast/podcast')->load($podcast_id)){
            if (!Mage::getModel('podcast/podcast')->load($podcast_url, 'url_key')->getId()) {
                $this->_forward('NoRoute');
                return false;
            }
        }
        $this->loadLayout();
        $this->getLayout()->getBlock('root')->setTemplate(Mage::getStoreConfig('rocketweb_podcast/settings/layout'));

        if ($head = $this->getLayout()->getBlock('head')) {
            $head->setTitle(Mage::getStoreConfig('rocketweb_podcast/settings/page_title'));
            $head->setDescription(Mage::getStoreConfig('rocketweb_podcast/settings/page_description'));
        }

        $this->renderLayout();
    }
}
