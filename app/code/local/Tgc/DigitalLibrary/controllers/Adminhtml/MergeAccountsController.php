<?php
/**
 * Digital Library
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DigitalLibrary
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_DigitalLibrary_Adminhtml_MergeAccountsController extends Mage_Adminhtml_Controller_Action
{
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('tgc_dl/merge_accounts')
            ->_addBreadcrumb(
                Mage::helper('tgc_dl')->__('Merged Accounts Manager'),
                Mage::helper('tgc_dl')->__('Merged Accounts Manager')
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
        $model = Mage::getModel('tgc_dl/mergeAccounts')->load($id);

        if ($model->getId() || $id == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getMergeAccountsData(true);
            if (!empty($data)) {
                $model->setData($data);
            }
            Mage::register('mergeAccounts_data', $model);
            $this->loadLayout();
            $this->_setActiveMenu('tgc_dl/merge_accounts');

            $this->_addBreadcrumb(
                Mage::helper('tgc_dl')->__('Merged Accounts Manager'),
                Mage::helper('tgc_dl')->__('Merged Accounts Manager')
            );
            $this->_addBreadcrumb(
                Mage::helper('tgc_dl')->__('Manage'),
                Mage::helper('tgc_dl')->__('Manage')
            );
            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('tgc_dl')->__('Merged Account does not exist')
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
            $model = Mage::getModel('tgc_dl/mergeAccounts');
            $model->setData($data)
                ->setId($this->getRequest()->getParam('id'));
            try {
                $this->_validateDaxCustomerId($data['dax_customer_id']);
                $this->_validateDaxCustomerId($data['mergeto_dax_customer_id']);
                if ($data['dax_customer_id'] == $data['mergeto_dax_customer_id']) {
                    throw new InvalidArgumentException(
                        Mage::helper('tgc_dl')->__(
                            'The account with DAX Customer ID: %s cannot be merged to itself.',
                            $data['dax_customer_id']
                        )
                    );
                }
                $this->_checkRowExists($data);
                $model->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('tgc_dl')->__('Merged Account was successfully saved')
                );
                Mage::getSingleton('adminhtml/session')->setMergeAccountsData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId()));

                    return;
                }
                $this->_redirect('*/*/');

                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('tgc_dl')->__($e->getMessage())
                );
                Mage::getSingleton('adminhtml/session')->setMergeAccountsData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));

                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('tgc_dl')->__('Unable to find merged account to save')
        );
        $this->_redirect('*/*/');
    }

    private function _checkRowExists($data)
    {
        $row = Mage::getModel('tgc_dl/mergeAccounts')
            ->getCollection()
            ->addFieldToFilter('dax_customer_id', array('eq' => $data['dax_customer_id']))
            ->addFieldToFilter('mergeto_dax_customer_id', array('eq' => $data['mergeto_dax_customer_id']))
            ->getFirstItem();

        if ($row && $row->getId() && $row->getId() != $this->getRequest()->getParam('id')) {
            throw new InvalidArgumentException(
                Mage::helper('tgc_dl')->__(
                    'A row with these values already exists'
                )
            );
        }
    }

    private function _validateDaxCustomerId($daxCustomerId)
    {
        $customer = Mage::getModel('customer/customer')
            ->getCollection()
            ->addFieldToFilter('dax_customer_id', array('eq' => $daxCustomerId))
            ->getFirstItem();

        if (!$customer || !$customer->getId()) {
            throw new InvalidArgumentException(
                Mage::helper('tgc_dl')->__(
                    'Invalid DAX Customer ID: %s supplied. There is no corresponding customer.',
                    $daxCustomerId
                )
            );
        }
    }

    public function deleteAction()
    {
        if( $this->getRequest()->getParam('id') > 0 ) {
            try {
                $model = Mage::getModel('tgc_dl/mergeAccounts');
                $model->setId($this->getRequest()->getParam('id'))
                    ->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('tgc_dl')->__('Merged Account was successfully deleted'));
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
        $ids = $this->getRequest()->getParam('mergeAccounts');
        if(!is_array($ids)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('tgc_dl')->__('Please select account(s)'));
        } else {
            try {
                $idsToDelete = array();
                foreach ($ids as $id) {
                    $idsToDelete[] = $id;
                }
                $resource = Mage::getResourceSingleton('tgc_dl/mergeAccounts');
                $resource->deleteRowsByIds($idsToDelete);

                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('tgc_dl')->__(
                        'Total of %d Merged Account(s) were successfully deleted', count($ids)
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
        $fileName   = 'merged_accounts.csv';
        $content    = $this->getLayout()->createBlock('tgc_dl/adminhtml_mergeAccounts_grid')->getCsv();
        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportXmlAction()
    {
        $fileName   = 'merged_accounts.xml';
        $content    = $this->getLayout()->createBlock('tgc_dl/adminhtml_mergeAccounts_grid')->getXml();
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
