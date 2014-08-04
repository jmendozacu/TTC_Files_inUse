<?php
/**
 * DataMart integration
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DataMart
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Datamart_Adminhtml_EmailLanding_DesignController extends Mage_Adminhtml_Controller_Action
{
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('tgc_datamart/email_landing_design')
            ->_addBreadcrumb(
            Mage::helper('tgc_datamart')->__('Email Landing Page Design Manager'),
            Mage::helper('tgc_datamart')->__('Email Landing Page Design Manager')
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
        $id = $this->getRequest()->getParam('id');
        $model = Mage::getModel('tgc_datamart/emailLanding_design')->load($id);

        if ($model->getId() || $id == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getEmailLandingDesignData(true);
            if (!empty($data)) {
                $model->setData($data);
            }
            Mage::register('emailLanding_design_data', $model);
            $this->loadLayout();
            $this->_setActiveMenu('tgc_datamart/email_landing_design');

            $this->_addBreadcrumb(
                Mage::helper('tgc_datamart')->__('Email Landing Page Design Manager'),
                Mage::helper('tgc_datamart')->__('Email Landing Page Design Manager')
            );
            $this->_addBreadcrumb(
                Mage::helper('tgc_datamart')->__('Manage Design'),
                Mage::helper('tgc_datamart')->__('Manage Design')
            );
            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('tgc_datamart')->__('Landing page design does not exist')
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
            $model = Mage::getModel('tgc_datamart/emailLanding_design');
            try {
                //$this->_validateAdcode($data['adcode']);
                $model->setData($data)
                    ->setId($this->getRequest()->getParam('id'));
                $model->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('tgc_datamart')->__('Email landing page design was successfully saved')
                );
                Mage::getSingleton('adminhtml/session')->setEmailLandingDesignData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId()));

                    return;
                }
                $this->_redirect('*/*/');

                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setEmailLandingDesignData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));

                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('tgc_datamart')->__('Unable to find email landing page design to save')
        );
        $this->_redirect('*/*/');
    }

    public function deleteAction()
    {
        if ($this->getRequest()->getParam('id') > 0) {
            try {
                $model = Mage::getModel('tgc_datamart/emailLanding_design');
                $model->setId($this->getRequest()->getParam('id'))
                    ->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('tgc_datamart')->__('Email landing page design was successfully deleted'));
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        }
        $this->_redirect('*/*/');
    }

    public function massDeleteAction()
    {
        $pageIds = $this->getRequest()->getParam('emailLanding_design');
        if (!is_array($pageIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('tgc_datamart')->__('Please select item(s)'));
        } else {
            try {
                $idsToDelete = array();
                foreach ($pageIds as $pageId) {
                    $idsToDelete[] = $pageId;
                }
                $resource = Mage::getResourceSingleton('tgc_datamart/emailLanding_design');
                $resource->deleteRowsByIds($idsToDelete);

                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('tgc_datamart')->__(
                        'Total of %d email landing page design(s) were successfully deleted', count($pageIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    public function exportCsvAction()
    {
        $fileName = 'email_landing_page_designs.csv';
        $content = $this->getLayout()->createBlock('tgc_datamart/adminhtml_emailLanding_design_grid')->getCsv();
        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportXmlAction()
    {
        $fileName = 'email_landing_page_designs.xml';
        $content = $this->getLayout()->createBlock('tgc_datamart/adminhtml_emailLanding_design_grid')->getXml();
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

    /**
     * @param $adCode
     * @return bool
     */
    protected function _validateAdcode($adCode)
    {
        if (!empty($adCode)) {
            if (!is_numeric($adCode)) {
                Mage::throwException($this->__('Invalid Adcode.'));
            }
            $adCode = Mage::getModel('tgc_price/adCode')->load($adCode);
            if (!$adCode->getCode()) {
                Mage::throwException($this->__('Invalid Adcode.'));
            }
        }
        return true;
    }

    /**
     * @param string $idFieldName
     * @return Tgc_Datamart_Adminhtml_EmailLanding_DesignController
     */
    protected function _initEmailLanding($idFieldName = 'id')
    {
        $emailLandingId = (int)$this->getRequest()->getParam($idFieldName);
        $emailLanding = Mage::getModel('tgc_datamart/emailLanding');

        if ($emailLandingId) {
            $emailLanding->load($emailLandingId);
        }

        Mage::register('emailLanding_design_data', $emailLanding);
        return $this;
    }
}
