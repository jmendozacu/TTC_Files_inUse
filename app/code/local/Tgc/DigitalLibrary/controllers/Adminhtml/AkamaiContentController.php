<?php
/**
 * Digital Library
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DigitalLibrary
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_DigitalLibrary_Adminhtml_AkamaiContentController extends Mage_Adminhtml_Controller_Action
{
    const DB_ERROR = '23000';

    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('tgc_dl/akamai_content')
            ->_addBreadcrumb(
                Mage::helper('tgc_dl')->__('Akamai Content Manager'),
                Mage::helper('tgc_dl')->__('Akamai Content Manager')
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
        $model = Mage::getModel('tgc_dl/akamaiContent')->load($id);

        if ($model->getId() || $id == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getAkamaiContentData(true);
            if (!empty($data)) {
                $model->setData($data);
            }
            Mage::register('akamaiContent_data', $model);
            $this->loadLayout();
            $this->_setActiveMenu('tgc_dl/akamai_content');

            $this->_addBreadcrumb(
                Mage::helper('tgc_dl')->__('Akamai Content Manager'),
                Mage::helper('tgc_dl')->__('Akamai Content Manager')
            );
            $this->_addBreadcrumb(
                Mage::helper('tgc_dl')->__('Manage'),
                Mage::helper('tgc_dl')->__('Manage')
            );
            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('tgc_dl')->__('Akamai content does not exist')
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
            $model = Mage::getModel('tgc_dl/akamaiContent');
            $model->setData($data)
                ->setId($this->getRequest()->getParam('id'));
            try {
                $model->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('tgc_dl')->__('Akamai Content was successfully saved')
                );
                Mage::getSingleton('adminhtml/session')->setAkamaiContentData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId()));

                    return;
                }
                $this->_redirect('*/*/');

                return;
            } catch (Exception $e) {
                if ($e->getCode() == self::DB_ERROR) {
                    if (!$this->_courseExists($data['course_id'])) {
                        Mage::getSingleton('adminhtml/session')->addError(
                            Mage::helper('tgc_dl')->__('A product with this Course ID does not exist')
                        );
                    } else {
                        Mage::getSingleton('adminhtml/session')->addError(
                            Mage::helper('tgc_dl')->__('A row with this Course ID already exists')
                        );
                    }
                } else {
                    Mage::getSingleton('adminhtml/session')->addError(
                        Mage::helper('tgc_dl')->__($e->getMessage())
                    );
                }
                Mage::getSingleton('adminhtml/session')->setAkamaiContentData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));

                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('tgc_dl')->__('Unable to find Akamai Content to save')
        );
        $this->_redirect('*/*/');
    }

    private function _courseExists($courseId)
    {
        $row = Mage::getModel('tgc_dl/akamaiContent')
            ->getCollection()
            ->addFieldToFilter('course_id', array('eq' => $courseId))
            ->getFirstItem();

        if ($row && $row->getId() && $row->getId() != $this->getRequest()->getParam('id')) {
            return true;
        }

        return false;
    }

    public function deleteAction()
    {
        if ($this->getRequest()->getParam('id') > 0) {
            try {
                $model = Mage::getModel('tgc_dl/akamaiContent');
                $model->setId($this->getRequest()->getParam('id'))
                    ->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('tgc_dl')->__('Akamai Content was successfully deleted'));
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('tgc_dl')->__($e->getMessage())
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        }
        $this->_redirect('*/*/');
    }

    public function massDeleteAction()
    {
        $ids = $this->getRequest()->getParam('akamaiContent');
        if (!is_array($ids)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('tgc_dl')->__('Please select content'));
        } else {
            try {
                $idsToDelete = array();
                foreach ($ids as $id) {
                    $idsToDelete[] = $id;
                }
                $resource = Mage::getResourceSingleton('tgc_dl/akamaiContent');
                $resource->deleteRowsByIds($idsToDelete);

                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('tgc_dl')->__(
                        'Total of %d Akamai Content items were successfully deleted', count($ids)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('tgc_dl')->__($e->getMessage())
                );
            }
        }
        $this->_redirect('*/*/index');
    }

    public function exportCsvAction()
    {
        $fileName = 'akamai_content.csv';
        $content  = $this->getLayout()->createBlock('tgc_dl/adminhtml_akamaiContent_grid')->getCsv();
        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportXmlAction()
    {
        $fileName   = 'akamai_content.xml';
        $content    = $this->getLayout()->createBlock('tgc_dl/adminhtml_akamaiContent_grid')->getXml();
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
