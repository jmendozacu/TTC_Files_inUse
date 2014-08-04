<?php
/**
 * Cms additions
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Cms
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Cms_Adminhtml_QuotesController extends Mage_Adminhtml_Controller_Action
{
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('tgc_cms/quotes')
            ->_addBreadcrumb(
                Mage::helper('tgc_cms')->__('Quotes Manager'),
                Mage::helper('tgc_cms')->__('Quotes item Manager')
            );

        return $this;
    }

    public function getResource()
    {
        return Mage::getResourceModel('tgc_cms/quotes');
    }

    public function indexAction()
    {
        $this->_initAction()
            ->renderLayout();
    }

    public function editAction()
    {
        $id    = $this->getRequest()->getParam('id');
        $model = Mage::getModel('tgc_cms/quotes')->load($id);

        if ($model->getId() || $id == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getQuotesFormData(true);
            if (!empty($data)) {
                $model->setData($data);
            }
            Mage::register('quotes_data', $model);
            $this->loadLayout();
            $this->_setActiveMenu('tgc_cms/quotes');

            $this->_addBreadcrumb(
                Mage::helper('tgc_cms')->__('Quotes Manager'),
                Mage::helper('tgc_cms')->__('Quotes Manager')
            );
            $this->_addBreadcrumb(
                Mage::helper('tgc_cms')->__('Manage'),
                Mage::helper('tgc_cms')->__('Manage')
            );
            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('tgc_cms')->__('Quotes item does not exist')
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
            $model = Mage::getModel('tgc_cms/quotes');
            $model->setData($data)
                ->setId($this->getRequest()->getParam('id'));
            try {
                $model->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('tgc_cms')->__('Quotes item was successfully saved')
                );
                Mage::getSingleton('adminhtml/session')->setQuotesFormData(false);
                Mage::app()->getCacheInstance()->invalidateType(Mage_Core_Block_Abstract::CACHE_GROUP);
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
                Mage::getSingleton('adminhtml/session')->setQuotesFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));

                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('tgc_cms')->__('Unable to find quotes item to save')
        );
        $this->_redirect('*/*/');
    }

    public function deleteAction()
    {
        if( $this->getRequest()->getParam('id') > 0 ) {
            try {
                $model = Mage::getModel('tgc_cms/quotes');
                $model->setId($this->getRequest()->getParam('id'))
                    ->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('tgc_cms')->__('Quotes item was successfully deleted'));
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
        $itemIds = $this->getRequest()->getParam('quotes');
        if(!is_array($itemIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('tgc_cms')->__('Please select item(s)'));
        } else {
            try {
                $idsToDelete = array();
                foreach ($itemIds as $itemId) {
                    $idsToDelete[] = $itemId;
                }
                $resource = Mage::getResourceSingleton('tgc_cms/quotes');
                $resource->deleteRowsByIds($idsToDelete);

                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('tgc_cms')->__(
                        'Total of %d quote item(s) were successfully deleted', count($itemIds)
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
        $ids    = (array)$this->getRequest()->getParam('quotes');
        $status = (int)$this->getRequest()->getParam('status');

        try {
            $resource = Mage::getResourceSingleton('tgc_cms/quotes');
            $resource->massStatusUpdate($ids, $status);

            $this->_getSession()->addSuccess(
                $this->__('Total of %d quote items(s) have been updated.', count($ids))
            );
        }
        catch (Mage_Core_Model_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Exception $e) {
            $this->_getSession()
                ->addException($e, $this->__('An error occurred while updating the quote item(s) status.'));
        }

        $this->_redirect('*/*/');
    }

    public function exportCsvAction()
    {
        $fileName   = 'quotes.csv';
        $content    = $this->getLayout()->createBlock('tgc_cms/adminhtml_quotes_grid')->getCsv();
        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportXmlAction()
    {
        $fileName   = 'quotes.xml';
        $content    = $this->getLayout()->createBlock('tgc_cms/adminhtml_quotes_grid')->getXml();
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
