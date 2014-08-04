<?php
/**
 * Cms additions
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Cms
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Cms_Adminhtml_PartnersController extends Mage_Adminhtml_Controller_Action
{
    const MEDIA_PATH = 'cms/partners';

    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('tgc_cms/partners')
            ->_addBreadcrumb(
                Mage::helper('tgc_cms')->__('Partners Manager'),
                Mage::helper('tgc_cms')->__('Partners item Manager')
            );

        return $this;
    }

    public function getResource()
    {
        return Mage::getResourceModel('tgc_cms/partners');
    }

    public function indexAction()
    {
        $this->_initAction()
            ->renderLayout();
    }

    public function editAction()
    {
        $id = $this->getRequest()->getParam('id');
        $model = Mage::getModel('tgc_cms/partners')->load($id);

        if ($model->getId() || $id == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getPartnersFormData(true);
            if (!empty($data)) {
                $model->setData($data);
            }
            Mage::register('partners_data', $model);
            $this->loadLayout();
            $this->_setActiveMenu('tgc_cms/partners');

            $this->_addBreadcrumb(
                Mage::helper('tgc_cms')->__('Partners Manager'),
                Mage::helper('tgc_cms')->__('Partners Manager')
            );
            $this->_addBreadcrumb(
                Mage::helper('tgc_cms')->__('Manage'),
                Mage::helper('tgc_cms')->__('Manage')
            );
            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('tgc_cms')->__('Partners item does not exist')
            );
            $this->_redirect('*/*/');
        }
    }

    public function newAction()
    {
        $this->_forward('edit');
    }

    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost()) {
            if (isset($_FILES['image']['name']) && (file_exists($_FILES['image']['tmp_name']))) {
                try {
                    $uploader = new Varien_File_Uploader('image');
                    $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png')); // or pdf or anything
                    $uploader->setAllowRenameFiles(false);
                    $uploader->setFilesDispersion(false);
                    $path = Mage::getBaseDir('media') . DS . self::MEDIA_PATH . DS;
                    $uploader->save($path, $_FILES['image']['name']);
                    $data['image'] = self::MEDIA_PATH . '/' . $_FILES['image']['name'];
                } catch (Exception $e) {
                    Mage::logException($e);
                }
            } else {
                if (isset($data['image']['delete']) && $data['image']['delete'] == 1) {
                    $data['image'] = '';
                } else {
                    unset($data['image']);
                }
            }
            $model = Mage::getModel('tgc_cms/partners');
            $model->setData($data)
                ->setId($this->getRequest()->getParam('id'));
            try {
                $model->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('tgc_cms')->__('Partners item was successfully saved')
                );
                Mage::getSingleton('adminhtml/session')->setPartnersFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId()));

                    return;
                }
                $this->_redirect('*/*/');

                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('tgc_cms')->__($e->getMessage())
                );
                Mage::getSingleton('adminhtml/session')->setPartnersFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));

                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('tgc_cms')->__('Unable to find partners item to save')
        );
        $this->_redirect('*/*/');
    }

    public function deleteAction()
    {
        if ($this->getRequest()->getParam('id') > 0) {
            try {
                $model = Mage::getModel('tgc_cms/partners');
                $model->setId($this->getRequest()->getParam('id'))
                    ->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('tgc_cms')->__('Partners item was successfully deleted'));
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('tgc_cms')->__($e->getMessage())
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        }
        $this->_redirect('*/*/');
    }

    public function massDeleteAction()
    {
        $itemIds = $this->getRequest()->getParam('partners');
        if (!is_array($itemIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('tgc_cms')->__('Please select item(s)'));
        } else {
            try {
                $idsToDelete = array();
                foreach ($itemIds as $itemId) {
                    $idsToDelete[] = $itemId;
                }
                $resource = Mage::getResourceSingleton('tgc_cms/partners');
                $resource->deleteRowsByIds($idsToDelete);

                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('tgc_cms')->__(
                        'Total of %d partner item(s) were successfully deleted', count($itemIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('tgc_cms')->__($e->getMessage())
                );
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * Update item(s) status action
     *
     */
    public function massStatusAction()
    {
        $ids = (array)$this->getRequest()->getParam('partners');
        $status = (int)$this->getRequest()->getParam('status');

        try {
            $resource = Mage::getResourceSingleton('tgc_cms/partners');
            $resource->massStatusUpdate($ids, $status);

            $this->_getSession()->addSuccess(
                $this->__('Total of %d items(s) have been updated.', count($ids))
            );
        } catch (Mage_Core_Model_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Exception $e) {
            $this->_getSession()
                ->addException($e, $this->__('An error occurred while updating the item(s) status.'));
        }

        $this->_redirect('*/*/');
    }

    public function exportCsvAction()
    {
        $fileName = 'partners.csv';
        $content = $this->getLayout()->createBlock('tgc_cms/adminhtml_partners_grid')->getCsv();
        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportXmlAction()
    {
        $fileName = 'partners.xml';
        $content = $this->getLayout()->createBlock('tgc_cms/adminhtml_partners_grid')->getXml();
        $this->_sendUploadResponse($fileName, $content);
    }

    protected function _sendUploadResponse($fileName, $content, $contentType = 'application/octet-stream')
    {
        $response = $this->getResponse();
        $response->setHeader('HTTP/1.1 200 OK', '');
        $response->setHeader('Pragma', 'public', true);
        $response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
        $response->setHeader('Content-Disposition', 'attachment; filename=' . $fileName);
        $response->setHeader('Last-Modified', date('r'));
        $response->setHeader('Accept-Ranges', 'bytes');
        $response->setHeader('Content-Length', strlen($content));
        $response->setHeader('Content-type', $contentType);
        $response->setBody($content);
        $response->sendResponse();
        die();
    }
}
