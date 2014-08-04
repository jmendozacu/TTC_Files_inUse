<?php
/**
 * Boutique
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Boutique
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Boutique_Adminhtml_BoutiquePagesController extends Mage_Adminhtml_Controller_Action
{
    const UNIQUE_KEY_ERROR = '23000';

    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('tgc_boutique/boutique_pages')
            ->_addBreadcrumb(
                Mage::helper('tgc_boutique')->__('Boutique Page Manager'),
                Mage::helper('tgc_boutique')->__('Boutique Page item Manager')
            );

        return $this;
    }

    public function getResource()
    {
        return Mage::getResourceModel('tgc_boutique/boutiquePages');
    }

    public function indexAction()
    {
        $this->_initAction()
            ->renderLayout();
    }

    public function editAction()
    {
        $id    = $this->getRequest()->getParam('id');
        $model = Mage::getModel('tgc_boutique/boutiquePages')->load($id);

        if ($model->getId() || $id == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getBoutiquePagesFormData(true);
            if (!empty($data)) {
                $model->setData($data);
            }
            Mage::register('boutiquePages_data', $model);
            $this->loadLayout();
            $this->_setActiveMenu('tgc_boutique/boutique_pages');

            $this->_addBreadcrumb(
                Mage::helper('tgc_boutique')->__('Boutique Page Manager'),
                Mage::helper('tgc_boutique')->__('Boutique Page Manager')
            );
            $this->_addBreadcrumb(
                Mage::helper('tgc_boutique')->__('Manage'),
                Mage::helper('tgc_boutique')->__('Manage')
            );
            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('tgc_boutique')->__('Boutique Page does not exist')
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
            $model = Mage::getModel('tgc_boutique/boutiquePages');
            $model->setData($data)
                ->setId($this->getRequest()->getParam('id'));
            try {
                $model->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('tgc_boutique')->__('Boutique Page was successfully saved')
                );
                Mage::getSingleton('adminhtml/session')->setBoutiquePagesFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId()));

                    return;
                }
                $this->_redirect('*/*/');

                return;
            } catch (Exception $e) {
                if ($e->getCode() == self::UNIQUE_KEY_ERROR) {
                    Mage::getSingleton('adminhtml/session')->addError(
                        Mage::helper('tgc_boutique')->__('A Boutique Page with url-key %s already exists.', $data['url-key'])
                    );
                } else {
                    Mage::getSingleton('adminhtml/session')->addError(
                        Mage::helper('tgc_boutique')->__($e->getMessage())
                    );
                }
                Mage::getSingleton('adminhtml/session')->setBoutiquePagesFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));

                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('tgc_boutique')->__('Unable to find Boutique Page to save')
        );
        $this->_redirect('*/*/');
    }

    public function deleteAction()
    {
        if( $this->getRequest()->getParam('id') > 0 ) {
            try {
                $model = Mage::getModel('tgc_boutique/boutiquePages');
                $model->setId($this->getRequest()->getParam('id'))
                    ->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('tgc_boutique')->__('Boutique Page was successfully deleted'));
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('tgc_boutique')->__($e->getMessage())
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        }
        $this->_redirect('*/*/');
    }

    public function massDeleteAction()
    {
        $itemIds = $this->getRequest()->getParam('boutiquePages');
        if(!is_array($itemIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('tgc_boutique')->__('Please select pages(s)'));
        } else {
            try {
                $idsToDelete = array();
                foreach ($itemIds as $itemId) {
                    $idsToDelete[] = $itemId;
                }
                $resource = Mage::getResourceSingleton('tgc_boutique/boutiquePages');
                $resource->deleteRowsByIds($idsToDelete);

                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('tgc_boutique')->__(
                        'Total of %d page(s) were successfully deleted', count($itemIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('tgc_boutique')->__($e->getMessage())
                );
            }
        }
        $this->_redirect('*/*/index');
    }

    public function exportCsvAction()
    {
        $fileName   = 'boutique_pages.csv';
        $content    = $this->getLayout()->createBlock('tgc_boutique/adminhtml_boutiquePages_grid')->getCsv();
        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportXmlAction()
    {
        $fileName   = 'boutique_pages.xml';
        $content    = $this->getLayout()->createBlock('tgc_boutique/adminhtml_boutiquePages_grid')->getXml();
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
