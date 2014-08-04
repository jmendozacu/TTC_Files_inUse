<?php
/**
 * Digital Library
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DigitalLibrary
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_DigitalLibrary_Adminhtml_CrossPlatformResumeController extends Mage_Adminhtml_Controller_Action
{
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('tgc_dl/cross_platform_resume')
            ->_addBreadcrumb(
                Mage::helper('tgc_dl')->__('Resume Data Manager'),
                Mage::helper('tgc_dl')->__('Resume Data Manager')
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
        $model = Mage::getModel('tgc_dl/crossPlatformResume')->load($id);

        if ($model->getId() || $id == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getCrossPlatformResumeData(true);
            if (!empty($data)) {
                $model->setData($data);
            }
            Mage::register('crossPlatformResume_data', $model);
            $this->loadLayout();
            $this->_setActiveMenu('tgc_dl/cross_platform_resume');

            $this->_addBreadcrumb(
                Mage::helper('tgc_dl')->__('Resume Data Manager'),
                Mage::helper('tgc_dl')->__('Resume Data Manager')
            );
            $this->_addBreadcrumb(
                Mage::helper('tgc_dl')->__('Manage'),
                Mage::helper('tgc_dl')->__('Manage')
            );
            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('tgc_dl')->__('Resume data does not exist')
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
            $model = Mage::getModel('tgc_dl/crossPlatformResume');
            $model->setData($data)
                ->setId($this->getRequest()->getParam('id'));
            try {
                $lecture = Mage::getModel('lectures/lectures')->load($data['lecture_id']);
                if (!$lecture->getId()) {
                    throw new InvalidArgumentException(
                        Mage::helper('tgc_dl')->__('Lecture ID %s is not a valid lecture ID', $data['lecture_id'])
                    );
                }
                $customerId = Mage::getResourceModel('tgc_dl/crossPlatformResume')->getCustomerIdByWebUserId($data['web_user_id']);
                if (!$customerId) {
                    throw new InvalidArgumentException(
                        Mage::helper('tgc_dl')->__('Web User ID %s does not belong to any existing customer', $data['web_user_id'])
                    );
                }
                $model->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('tgc_dl')->__('Resume data was successfully saved')
                );
                Mage::getSingleton('adminhtml/session')->setCrossPlatformResumeData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId()));

                    return;
                }
                $this->_redirect('*/*/');

                return;
            } catch (Exception $e) {
                if ($e->getCode() == 23000) {
                    Mage::getSingleton('adminhtml/session')->addError(
                        Mage::helper('tgc_dl')->__('There is already an entry with this Web User ID, Lecture ID and Format')
                    );
                } else {
                    Mage::getSingleton('adminhtml/session')->addError(
                        Mage::helper('tgc_dl')->__($e->getMessage())
                    );
                }
                Mage::getSingleton('adminhtml/session')->setCrossPlatformResumeData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));

                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('tgc_dl')->__('Unable to find resume data to save')
        );
        $this->_redirect('*/*/');
    }

    public function deleteAction()
    {
        if( $this->getRequest()->getParam('id') > 0 ) {
            try {
                $model = Mage::getModel('tgc_dl/crossPlatformResume');
                $model->setId($this->getRequest()->getParam('id'))
                    ->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('tgc_dl')->__('Resume data was successfully deleted'));
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
        $ids = $this->getRequest()->getParam('crossPlatformResume');
        if(!is_array($ids)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('tgc_dl')->__('Please select item(s)'));
        } else {
            try {
                $idsToDelete = array();
                foreach ($ids as $id) {
                    $idsToDelete[] = $id;
                }
                $resource = Mage::getResourceSingleton('tgc_dl/crossPlatformResume');
                $resource->deleteRowsByIds($idsToDelete);

                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('tgc_dl')->__(
                        'Total of %d item(s) were successfully deleted', count($ids)
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
        $fileName   = 'resume_data.csv';
        $content    = $this->getLayout()->createBlock('tgc_dl/adminhtml_crossPlatformResume_grid')->getCsv();
        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportXmlAction()
    {
        $fileName   = 'resume_data.xml';
        $content    = $this->getLayout()->createBlock('tgc_dl/adminhtml_crossPlatformResume_grid')->getXml();
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
