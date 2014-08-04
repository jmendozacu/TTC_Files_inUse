<?php
/**
 * DataMart integration
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DataMart
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Datamart_Adminhtml_AnonymousUpsellController extends Mage_Adminhtml_Controller_Action
{
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('tgc_datamart/anonymous_upsell')
            ->_addBreadcrumb(
                Mage::helper('tgc_datamart')->__('Anonymous Upsell Manager'),
                Mage::helper('tgc_datamart')->__('Anonymous upsell Manager')
            );

        return $this;
    }

    public function getResource()
    {
        return Mage::getResourceModel('tgc_datamart/anonymousUpsell');
    }

    public function indexAction()
    {
        $this->_initAction()
            ->renderLayout();
    }

    public function editAction()
    {
        $id    = $this->getRequest()->getParam('id');
        $model = Mage::getModel('tgc_datamart/anonymousUpsell')->load($id);

        if ($model->getId() || $id == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getAnonymousUpsellFormData(true);
            if (!empty($data)) {
                $model->setData($data);
            }
            Mage::register('anonymousUpsell_data', $model);
            $this->loadLayout();
            $this->_setActiveMenu('tgc_datamart/anonymous_upsell');

            $this->_addBreadcrumb(
                Mage::helper('tgc_datamart')->__('Anonymous Upsell Manager'),
                Mage::helper('tgc_datamart')->__('Anonymous Upsell Manager')
            );
            $this->_addBreadcrumb(
                Mage::helper('tgc_datamart')->__('Manage'),
                Mage::helper('tgc_datamart')->__('Manage')
            );
            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('tgc_datamart')->__('Anonymous upsell does not exist')
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
            $model = Mage::getModel('tgc_datamart/anonymousUpsell');
            $model->setData($data)
                ->setId($this->getRequest()->getParam('id'));
            try {
                $resource = $this->getResource();
                $existingId = $resource->getIdBySubjectAndCourse($data['subject_id'], $data['course_id']);
                if ($existingId && $existingId != $model->getId()) {
                    throw new InvalidArgumentException(
                        Mage::helper('tgc_datamart')->__('An upsell with Subject ID: %s and Course ID: %s already exists', $data['subject_id'], $data['course_id'])
                    );
                }
                $model->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('tgc_datamart')->__('Anonymous upsell was successfully saved')
                );
                Mage::getSingleton('adminhtml/session')->setAnonymousUpsellFormData(false);
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
                Mage::getSingleton('adminhtml/session')->setAnonymousUpsellFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));

                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('tgc_datamart')->__('Unable to find anonymous upsell to save')
        );
        $this->_redirect('*/*/');
    }

    public function deleteAction()
    {
        if( $this->getRequest()->getParam('id') > 0 ) {
            try {
                $model = Mage::getModel('tgc_datamart/anonymousUpsell');
                $model->setId($this->getRequest()->getParam('id'))
                    ->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('tgc_datamart')->__('Anonymous upsell was successfully deleted'));
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
        $pageIds = $this->getRequest()->getParam('anonymousUpsell');
        if(!is_array($pageIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('tgc_datamart')->__('Please select item(s)'));
        } else {
            try {
                $idsToDelete = array();
                foreach ($pageIds as $pageId) {
                    $idsToDelete[] = $pageId;
                }
                $resource = Mage::getResourceSingleton('tgc_datamart/anonymousUpsell');
                $resource->deleteRowsByIds($idsToDelete);

                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('tgc_datamart')->__(
                        'Total of %d anonymous upsell(s) were successfully deleted', count($pageIds)
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
        $fileName   = 'anonymous_upsells.csv';
        $content    = $this->getLayout()->createBlock('tgc_datamart/adminhtml_anonymousUpsell_grid')->getCsv();
        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportXmlAction()
    {
        $fileName   = 'anonymous_upsells.xml';
        $content    = $this->getLayout()->createBlock('tgc_datamart/adminhtml_anonymousUpsell_grid')->getXml();
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
