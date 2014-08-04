<?php

require_once Mage::getModuleDir('controllers', 'RocketWeb_Podcast') . DS . 'Adminhtml' . DS . 'PodcastController.php';

class Tgc_Podcast_Adminhtml_PodcastController extends RocketWeb_Podcast_Adminhtml_PodcastController
{

    public function editAction()
    {
        $id = $this->getRequest()->getParam('id');
        $model = Mage::getModel('podcast/podcast')->load($id);

        if ($model->getId() || $id == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data)) {
                $model->setData($data);
            }

            Mage::register('podcast_data', $model);

            $this->loadLayout();
            $this->_setActiveMenu('cms/rocketweb_podcast/adminhtml_podcast');

            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Podcast'), Mage::helper('adminhtml')->__('Podcast'));
            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Podcast'), Mage::helper('adminhtml')->__('Item Podcast'));

            if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
                $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
            }
            $this->_addContent($this->getLayout()->createBlock('podcast/adminhtml_podcast_edit'))
                ->_addLeft($this->getLayout()->createBlock('tgc_podcast/adminhtml_podcast_edit_tabs'));

            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('podcast')->__('Podcast does not exist'));
            $this->_redirect('*/*/');
        }
    }

    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost()) {

            $id = $this->getRequest()->getParam('id');

            if (isset($_FILES['podcast_file']['name']) && $_FILES['podcast_file']['name'] != '') {

                try {
                    if (!$_FILES['podcast_file']['size']) {
                        /* Check max upload size limit (MB) */
                        $max_upload = (int)(ini_get('upload_max_filesize'));
                        $max_post = (int)(ini_get('post_max_size'));
                        $memory_limit = (int)(ini_get('memory_limit'));
                        $upload_mb = min($max_upload, $max_post, $memory_limit);
                        throw new Exception('File size is too big. You can use the audio file with a maximum size of ' . $upload_mb . 'MB');
                    }
                    if ($id) {
                        $existing_file_name = Mage::getModel('podcast/podcast')->load($id)->getFileName();
                        unlink(Mage::helper('podcast')->getPodcastDirectoryPath() . $existing_file_name);
                    }

                    $uploader = new Varien_File_Uploader('podcast_file');
                    $uploader->setAllowedExtensions(Mage::helper('podcast')->getAllowAudioExtensions());
                    $uploader->setAllowRenameFiles(true);
                    $uploader->setFilesDispersion(false);

                    $path = Mage::helper('podcast')->getPodcastDirectoryPath();
                    $uploader->save($path, $_FILES['podcast_file']['name']);
                    $data['file_name'] = $uploader->getUploadedFileName();

                } catch (Exception $e) {
                    Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                    Mage::getSingleton('adminhtml/session')->setFormData($data);
                    $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                    return;
                }

            }

            $path = Mage::helper('podcast')->getPodcastDirectoryPath();
            if (isset($_FILES['episode_image']['name']) && $_FILES['episode_image']['name'] != '') {
                try {
                    /* Starting upload */
                    $uploader = new Varien_File_Uploader('episode_image');

                    // Any extention would work
                    $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png'));
                    $uploader->setAllowRenameFiles(false);
                    // Set the file upload mode
                    // false -> get the file directly in the specified folder
                    // true -> get the file in the product like folders
                    //	(file.jpg will go in something like /media/f/i/file.jpg)
                    $uploader->setFilesDispersion(false);
                    // We set media as the upload dir

                    $uploader->save($path, $_FILES['episode_image']['name']);
                    $data['episode_image'] = RocketWeb_Podcast_Helper_Data::PODCAST_DIRECTORY_PATH . $uploader->getUploadedFileName();
                } catch (Exception $e) {
                    Mage::getSingleton('adminhtml/session')->addError(Mage::helper('podcast')->__('Error: ' . $e->getMessage()));
                    Mage::getSingleton('adminhtml/session')->setFormData($data);
                    $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));

                    return;
                }
            } elseif (isset($data['episode_image']['delete']) AND $data['episode_image']['delete'] == 1) {
                $tImg = Mage::getModel('podcast/podcast')->load($id)->getEpisodeImage();
                unlink($path . DS . $data['episode_image']['value']);
                unlink($path . DS . $tImg);
                $data['episode_image'] = '';
            } else {
                $data['episode_image'] = $data['episode_image']['value'];
            }

            $model = Mage::getModel('podcast/podcast');
            $model->setData($data)->setId($id);

            try {

                $format = Mage::app()->getLocale()->getDateTimeFormat(Mage_Core_Model_Locale::FORMAT_TYPE_MEDIUM);
                $model->setCreatedTime(Mage::getModel('core/date')->gmtDate());
                if (isset($data['created_time']) && $data['created_time']) {
                    $dateFrom = Mage::app()->getLocale()->date($data['created_time'], $format);
                    $model->setCreatedTime(Mage::getModel('core/date')->gmtDate(null, $dateFrom->getTimestamp()));
                    $model->setUpdateTime(Mage::getModel('core/date')->gmtDate());
                }

                $model->save();
                /**
                 * Per Assembla #359, disable RSS feed generation
                 */
                // $this->generateRss();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('podcast')->__('Podcast was successfully saved'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $id));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('podcast')->__('Unable to find podcast to save'));
        $this->_redirect('*/*/');
    }

    public function generateStoreRss($store)
    {
        $collection = Mage::getModel('podcast/podcast')->getCollection()->addStoreFilter($store->getId());
        $collection->getSelect()->order('created_time desc');
        if (!$collection->count()) {
            return;
        }

        try {

            $xml = new DOMDocument('1.0', 'utf-8');
            $xml->formatOutput = true;
            $xml->preserveWhiteSpace = false;

            // rss tag          
            $rssElement = $xml->createElement('rss');
            $xml->appendChild($rssElement);
            $rssElement->setAttribute('version', '2.0');
            $rssElement->setAttribute('xmlns:itunes', 'http://www.itunes.com/dtds/podcast-1.0.dtd');
            $rssElement->setAttribute('xmlns:atom', 'http://www.w3.org/2005/Atom');

            // channel tag and basic info tags
            $channelElement = $xml->createElement('channel');
            $rssElement->appendChild($channelElement);
            $channelElement->appendChild($xml->createElement('title', Mage::helper('podcast')->clearString(Mage::getStoreConfig('rocketweb_podcast/settings/page_title', $store->getId()))));
            $channelElement->appendChild($xml->createElement('link', Mage::app()->getStore($store->getId())->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_LINK, false) . Mage::helper('podcast')->getRoute() . '/rss'));

            $channelElement->appendChild($xml->createElement('language', 'en-us'));
            $channelElement->appendChild($xml->createElement('description', Mage::helper('podcast')->clearString(Mage::getStoreConfig('rocketweb_podcast/settings/page_description', $store->getId()))));
            $channelElement->appendChild($xml->createElement('copyright', Mage::helper('podcast')->clearString(Mage::getStoreConfig('rocketweb_podcast/settings/copyright', $store->getId()))));
            $channelElement->appendChild($xml->createElement('itunes:summary', Mage::helper('podcast')->clearString(Mage::getStoreConfig('rocketweb_podcast/settings/summary', $store->getId()))));
            $channelElement->appendChild($xml->createElement('itunes:subtitle', Mage::helper('podcast')->clearString(Mage::getStoreConfig('rocketweb_podcast/settings/subtitle', $store->getId()))));
            $lastBuildDate = $collection->count() ? new Zend_Date($collection->getFirstItem()->getCreatedTime()) : new Zend_Date();
            $channelElement->appendChild($xml->createElement('lastBuildDate', $lastBuildDate->toString(Zend_Date::RSS)));

            $author_name = Mage::helper('podcast')->clearString(Mage::getStoreConfig('rocketweb_podcast/settings/author_name', $store->getId()));
            $author_email = Mage::helper('podcast')->clearString(Mage::getStoreConfig('rocketweb_podcast/settings/author_email', $store->getId()));

            if ($author_name != '' || $author_email != '') {
                $owner = $channelElement->appendChild($xml->createElement('itunes:owner'));
                if ($author_name != '') {
                    $owner->appendChild($xml->createElement('itunes:name', $author_name));
                }
                if ($author_email != '') {
                    $owner->appendChild($xml->createElement('itunes:email', $author_email));
                }
            }

            $channelElement->appendChild($xml->createElement('itunes:author', $author_name));

            $image_path = Mage::getStoreConfig('rocketweb_podcast/image/image', $store->getId());
            if ($image_path) {
                $itunesImage = $channelElement->appendChild($xml->createElement('itunes:image'));
                $itunesImage->setAttribute('href', Mage::helper('podcast')->getPodcastDirectoryChannelUrl($store->getId()) . $image_path);
            }

            $category = $channelElement->appendChild($xml->createElement('itunes:category'));
            $category->setAttribute('text', Mage::getStoreConfig('rocketweb_podcast/settings/category', $store->getId()));

            /* Custom Feed nodes */
            $category2 = $channelElement->appendChild($xml->createElement('itunes:category'));
            $category2->setAttribute('text', Mage::getStoreConfig('rocketweb_podcast/settings/category', $store->getId()));
            $category2_child = $category2->appendChild($xml->createElement('itunes:category'));
            $category2_child->setAttribute('text', 'Higher Education');
            $category3 = $channelElement->appendChild($xml->createElement('itunes:category'));
            $category3->setAttribute('text', 'Technology');
            $category3_child = $category3->appendChild($xml->createElement('itunes:category'));
            $category3_child->setAttribute('text', 'Podcasting');
            $channelElement->appendChild($xml->createElement('itunes:keywords', 'The Teaching Company, interview, academic, university, virtual, professor, learn, course, lecture, education, guide, how to'));
            $channelCategory = $channelElement->appendChild($xml->createElement('category', 'Education/Higher Education'));
            $channelCategory->setAttribute('domain', 'http://www.thegreatcourses.com');
            $channelElement->appendChild($xml->createElement('itunes:block', 'no'));
            $channelImage = $channelElement->appendChild($xml->createElement('image'));
            $channelImage->appendChild($xml->createElement('url', Mage::helper('podcast')->getPodcastDirectoryChannelUrl($store->getId()) . $image_path));
            $channelImage->appendChild($xml->createElement('title', Mage::helper('podcast')->clearString(Mage::getStoreConfig('rocketweb_podcast/settings/page_title', $store->getId()))));
            $channelImage->appendChild($xml->createElement('link', Mage::app()->getStore($store->getId())->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_LINK, false) . Mage::helper('podcast')->getRoute() . '/rss'));
            $channelImage->appendChild($xml->createElement('width', Mage::getStoreConfig('rocketweb_podcast/image/image_width', $store->getId())));
            $channelImage->appendChild($xml->createElement('height', Mage::getStoreConfig('rocketweb_podcast/image/image_height', $store->getId())));

            /* End Custom Feed nodes */

            $explicites = Mage::getSingleton('podcast/explicit')->toOptionArray();
            $channelElement->appendChild($xml->createElement('itunes:explicit',
                strtolower($explicites[(int)Mage::getStoreConfig('rocketweb_podcast/settings/explicit', $store->getId())])));

            $atomLink = $channelElement->appendChild($xml->createElement('atom:link'));
            $atomLink->setAttribute('href', Mage::app()->getStore($store->getId())->getBaseUrl('link', false) . Mage::helper('podcast')->getRoute() . '/rss');
            $atomLink->setAttribute('rel', 'self');
            $atomLink->setAttribute('type', 'application/rss+xml');

            if ($collection->count()) {
                foreach ($collection as $key => $row) {
                    $item = $xml->createElement('item');
                    $item->appendChild($xml->createElement('title', Mage::helper('podcast')->clearString($row->getTitle())));
                    $item->appendChild($xml->createElement('itunes:author', Mage::helper('podcast')->clearString($row->getAuthorName())));

                    $htmlSummary = $row->getShortContent() . ' ' . $row->getLongContent();
                    $item->appendChild($xml->createElement('itunes:summary', Mage::helper('podcast')->clearString($htmlSummary)));
                    $enclosureElement = $item->appendChild($xml->createElement('enclosure'));

                    $file_uri = Mage::helper('podcast')->getPodcastDirectoryUrl($store->getId()) . $row->getFileName();
                    $file_path = Mage::helper('podcast')->getPodcastDirectoryPath() . $row->getFileName();

                    if (function_exists('mime_content_type')) {
                        $contentType = mime_content_type($file_path);
                    } else {
                        $contentType = Mage::helper('downloadable/file')->getFileType($file_path);
                    }

                    $enclosureElement->setAttribute('url', $file_uri);
                    $enclosureElement->setAttribute('length', filesize($file_path));
                    $enclosureElement->setAttribute('type', $contentType);

                    $item->appendChild($xml->createElement('guid', Mage::helper('podcast')->getPodcastDirectoryUrl($store->getId()) . $row->getFileName()));
                    $pubDate = $row->getCreatedTime() ? new Zend_Date($row->getCreatedTime()) : new Zend_Date();
                    $item->appendChild($xml->createElement('pubDate', $pubDate->toString(Zend_Date::RSS)));
                    $item->appendChild($xml->createElement('itunes:keywords', Mage::helper('podcast')->clearString($row->getMetaKeywords())));

                    /* Custom Item nodes */

                    $item->appendChild($xml->createElement('description', Mage::helper('podcast')->clearString($row->getLongContent())));
                    $item->appendChild($xml->createElement('link', $file_uri));
                    $item->appendChild($xml->createElement('itunes:subtitle', Mage::helper('podcast')->clearString($row->getTitle())));
                    $item->appendChild($xml->createElement('itunes:duration', Mage::helper('podcast')->clearString($row->getEpisodeDuration())));
                    $item->appendChild($xml->createElement('itunes:explicit', 'no'));
                    $item->appendChild($xml->createElement('itunes:block', 'no'));

                    /* End Custom Item nodes */

                    $channelElement->appendChild($item);
                }
            }

            $xml->save(Mage::helper('podcast')->getPodcastDirectoryPath() . "rss_{$store->getId()}.xml");
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function productsAction()
    {
        $this->_initPodcastProducts();
        $this->loadLayout();
        $this->getLayout()->getBlock('podcast.edit.tab.products')
            ->setPodcastProductsRelated($this->getRequest()->getPost('products_related', null));
        $this->renderLayout();
    }

    protected function _initPodcastProducts()
    {
        $podcast = Mage::getModel('podcast/podcast');
        $podcastId = (int)$this->getRequest()->getParam('id');
        if ($podcastId) {
            $podcast->load($podcastId);
        }
        Mage::register('current_podcast_products', $podcast);

        return $podcast;
    }

    /**
     * Get related products grid
     */
    public function productsGridAction()
    {
        $this->_initPodcastProducts();
        //Push Existing Values in Array
        $productsarray = array();
        $podcastId = (int)$this->getRequest()->getParam('id');
        foreach (Mage::registry('current_podcast_products')->getPodcastRelatedProducts($podcastId) as $products) {
            $productsarray = $products["product_id"];
        }
        if (!empty($_POST['products_related']))
            array_push($_POST["products_related"], $productsarray);
        Mage::registry('current_podcast_products')->setPodcastProductsRelated($productsarray);

        $this->loadLayout();
        $this->getLayout()->getBlock('podcast.edit.tab.products')
            ->setPodcastProductsRelated($this->getRequest()->getPost('products_related', null));
        $this->renderLayout();
    }


    public function gridAction()
    {
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('tgc_podcast/adminhtml_podcast_edit_tab_products')->toHtml()
        );
    }

    /**
     * Get specified tab grid
     */
    public function gridOnlyAction()
    {
        echo 'Function ===> GridOnlyAction';
        $this->_initProduct();
        $this->loadLayout();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('adminhtml/podcast_edit_tab_products')
                ->toHtml()
        );
    }
}
