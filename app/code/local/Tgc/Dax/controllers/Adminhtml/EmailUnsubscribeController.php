<?php
/**
 * Dax integration
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Dax
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Dax_Adminhtml_EmailUnsubscribeController extends Mage_Adminhtml_Controller_Action
{
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('tgc_dax/email_unsubscribe')
            ->_addBreadcrumb(
                Mage::helper('tgc_dax')->__('Email Unsubscribe Manager'),
                Mage::helper('tgc_dax')->__('Email Unsubscribe Manager')
            );

        return $this;
    }

    public function indexAction()
    {
        $this->_initAction()
            ->renderLayout();
    }

    public function editAction()
    {
        $id    = $this->getRequest()->getParam('id');
        $model = Mage::getModel('tgc_dax/emailUnsubscribe')->load($id);

        if ($model->getId() || $id == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getEmailUnsubscribeData(true);
            if (!empty($data)) {
                $model->setData($data);
            }
            Mage::register('emailUnsubscribe_data', $model);
            $this->loadLayout();
            $this->_setActiveMenu('tgc_dax/email_unsubscribe');

            $this->_addBreadcrumb(
                Mage::helper('tgc_dax')->__('Email Unsubscribe Manager'),
                Mage::helper('tgc_dax')->__('Email Unsubscribe Manager')
            );
            $this->_addBreadcrumb(
                Mage::helper('tgc_dax')->__('Manage'),
                Mage::helper('tgc_dax')->__('Manage')
            );
            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('tgc_dax')->__('Unsubscribe request does not exist')
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
            $model = Mage::getModel('tgc_dax/emailUnsubscribe');
            $model->setData($data)
                ->setId($this->getRequest()->getParam('id'));
            try {
                $model->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('tgc_dax')->__('Email unsubscribe was successfully saved')
                );
                Mage::getSingleton('adminhtml/session')->setEmailUnsubscribeData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId()));

                    return;
                }
                $this->_redirect('*/*/');

                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('tgc_dax')->__($e->getMessage())
                );
                Mage::getSingleton('adminhtml/session')->setEmailUnsubscribeData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));

                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('tgc_dax')->__('Unable to find email unsubscribe to save')
        );
        $this->_redirect('*/*/');
    }

    public function deleteAction()
    {
        if( $this->getRequest()->getParam('id') > 0 ) {
            try {
                $model = Mage::getModel('tgc_dax/emailUnsubscribe');
                $model->setId($this->getRequest()->getParam('id'))
                    ->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('tgc_dax')->__('Email unsubscribe was successfully deleted'));
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('tgc_dax')->__($e->getMessage())
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        }
        $this->_redirect('*/*/');
    }

    public function massDeleteAction()
    {
        $pageIds = $this->getRequest()->getParam('emailUnsubscribe');
        if(!is_array($pageIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('tgc_dax')->__('Please select item(s)'));
        } else {
            try {
                $idsToDelete = array();
                foreach ($pageIds as $pageId) {
                    $idsToDelete[] = $pageId;
                }
                $resource = Mage::getResourceSingleton('tgc_dax/emailUnsubscribe');
                $resource->deleteRowsByIds($idsToDelete);

                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('tgc_dax')->__(
                        'Total of %d email unsubscribe(s) were successfully deleted', count($pageIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('tgc_dax')->__($e->getMessage())
                );
            }
        }
        $this->_redirect('*/*/index');
    }

    public function exportCsvAction()
    {
        $fileName   = 'email_unsubscribes.csv';
        $content    = $this->getLayout()->createBlock('tgc_dax/adminhtml_emailUnsubscribe_grid')->getCsv();
        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportXmlAction()
    {
        $fileName   = 'email_unsubscribes.xml';
        $content    = $this->getLayout()->createBlock('tgc_dax/adminhtml_emailUnsubscribe_grid')->getXml();
        $this->_sendUploadResponse($fileName, $content);
    }

    protected function _sendUploadResponse($fileName, $content, $contentType='application/octet-stream')
    {
        $response = $this->getResponse();
        $response->setHeader('HTTP/1.1 200 OK','');
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
