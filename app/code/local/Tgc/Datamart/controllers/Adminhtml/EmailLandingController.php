<?php
/**
 * DataMart integration
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DataMart
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Datamart_Adminhtml_EmailLandingController extends Mage_Adminhtml_Controller_Action
{
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('tgc_datamart/email_landing')
            ->_addBreadcrumb(
                Mage::helper('tgc_datamart')->__('Email Landing Page Manager'),
                Mage::helper('tgc_datamart')->__('Email Landing Page Manager')
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
        $model = Mage::getModel('tgc_datamart/emailLanding')->load($id);

        if ($model->getId() || $id == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getEmailLandingData(true);
            if (!empty($data)) {
                $model->setData($data);
            }
            Mage::register('emailLanding_data', $model);
            $this->loadLayout();
            $this->_setActiveMenu('tgc_datamart/email_landing');

            $this->_addBreadcrumb(
                Mage::helper('tgc_datamart')->__('Email Landing Page Manager'),
                Mage::helper('tgc_datamart')->__('Email Landing Page Manager')
            );
            $this->_addBreadcrumb(
                Mage::helper('tgc_datamart')->__('Manage'),
                Mage::helper('tgc_datamart')->__('Manage')
            );
            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('tgc_datamart')->__('Landing page does not exist')
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
            $model = Mage::getModel('tgc_datamart/emailLanding');
            $model->setData($data)
                ->setId($this->getRequest()->getParam('id'));
            try {
                $model->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('tgc_datamart')->__('Email landing page was successfully saved')
                );
                Mage::getSingleton('adminhtml/session')->setEmailLandingData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId()));

                    return;
                }
                $this->_redirect('*/*/');

                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('tgc_datamart')->__($e->getMessage())
                );
                Mage::getSingleton('adminhtml/session')->setEmailLandingData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));

                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('tgc_datamart')->__('Unable to find email landing page to save')
        );
        $this->_redirect('*/*/');
    }

    public function deleteAction()
    {
        if( $this->getRequest()->getParam('id') > 0 ) {
            try {
                $model = Mage::getModel('tgc_datamart/emailLanding');
                $model->setId($this->getRequest()->getParam('id'))
                    ->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('tgc_datamart')->__('Email landing page was successfully deleted'));
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('tgc_datamart')->__($e->getMessage())
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        }
        $this->_redirect('*/*/');
    }

    public function massDeleteAction()
    {
        $pageIds = $this->getRequest()->getParam('emailLanding');
        if(!is_array($pageIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('tgc_datamart')->__('Please select item(s)'));
        } else {
            try {
                $idsToDelete = array();
                foreach ($pageIds as $pageId) {
                    $idsToDelete[] = $pageId;
                }
                $resource = Mage::getResourceSingleton('tgc_datamart/emailLanding');
                $resource->deleteRowsByIds($idsToDelete);

                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('tgc_datamart')->__(
                        'Total of %d email landing page(s) were successfully deleted', count($pageIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('tgc_datamart')->__($e->getMessage())
                );
            }
        }
        $this->_redirect('*/*/index');
    }

    public function exportCsvAction()
    {
        $fileName   = 'email_landing_pages.csv';
        $content    = $this->getLayout()->createBlock('tgc_datamart/adminhtml_emailLanding_grid')->getCsv();
        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportXmlAction()
    {
        $fileName   = 'email_landing_pages.xml';
        $content    = $this->getLayout()->createBlock('tgc_datamart/adminhtml_emailLanding_grid')->getXml();
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
