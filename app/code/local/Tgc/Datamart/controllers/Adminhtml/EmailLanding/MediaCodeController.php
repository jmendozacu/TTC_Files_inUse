<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DataMart
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_Datamart_Adminhtml_EmailLanding_MediaCodeController extends Mage_Adminhtml_Controller_Action
{
    /**
     * Check ACL
     *
     * @return boolean
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('cms/landing_pages/media_codes');
    }

    /**
     * Init actions
     *
     * @return Tgc_Datamart_Adminhtml_EmailLanding_MediaCodeController
     */
    protected function _initAction()
    {
        // load layout, set active menu and breadcrumbs
        $this->loadLayout()
            ->_setActiveMenu('cms/landing_page/media_codes')
            ->_addBreadcrumb(Mage::helper('cms')->__('CMS'), Mage::helper('cms')->__('CMS'))
            ->_addBreadcrumb($this->__('Manage Landing Pages'), $this->__('Manage Landing Pages'))
            ->_addBreadcrumb($this->__('Media Codes'), $this->__('Media Codes'))
        ;
        return $this;
    }

    /**
     * Index action
     */
    public function indexAction()
    {
        $this->_title(Mage::helper('cms')->__('CMS'))->_title($this->__('Landing Page Media Codes'));

        $this->_initAction();
        $this->renderLayout();
    }

    /**
     * Create new media code action
     */
    public function newAction()
    {
        // the same form is used to create and edit
        $this->_forward('edit');
    }

    /**
     * Edit media code action
     */
    public function editAction()
    {
        $this->_title(Mage::helper('cms')->__('CMS'))->_title($this->__('Landing Page Media Codes'));

        // 1. Get ID and create model
        $id = $this->getRequest()->getParam('entity_id');
        $model = Mage::getModel('tgc_datamart/emailLanding_mediacode');

        // 2. Initial checking
        if ($id) {
            $model->load($id);
            if (! $model->getId()) {
                Mage::getSingleton('adminhtml/session')->addError($this->__('This media code no longer exists.'));
                $this->_redirect('*/*/');
                return;
            }
        }

        $this->_title($model->getId() ? $model->getTitle() : $this->__('New Media Code'));

        // 3. Set entered data if was error when we do save
        $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }

        // 4. Register model to use later in blocks
        Mage::register('tgc_datamart_landing_media_code', $model);

        // 5. Build edit form
        $this->_initAction()
            ->_addBreadcrumb($id ? $this->__('Edit Media Code') : $this->__('New Media Code'), $id ? $this->__('Edit Media Code') : $this->__('New Media Code'))
            ->renderLayout();
    }

    /**
     * Save action
     */
    public function saveAction()
    {
        // check if data sent
        if ($data = $this->getRequest()->getPost()) {
            $id = $this->getRequest()->getParam('entity_id');
            $model = Mage::getModel('tgc_datamart/emailLanding_mediacode')->load($id);
            if (!$model->getId() && $id) {
                Mage::getSingleton('adminhtml/session')->addError($this->__('This Media Code no longer exists.'));
                $this->_redirect('*/*/');
                return;
            }

            // try to save it
            try {
                if (!isset($data['media_code']) || !$data['media_code']) {
                    Mage::throwException($this->__('Media Code is required'));
                }
                if (!isset($data['ad_code']) || !$data['ad_code']) {
                    Mage::throwException($this->__('Media Code is required'));
                }

                $model->addData($data);
                // save the data
                $model->save();
                // display success message
                Mage::getSingleton('adminhtml/session')->addSuccess($this->__('The Media Code has been saved.'));
                // clear previously saved data from session
                Mage::getSingleton('adminhtml/session')->setFormData(false);

                // check if 'Save and Continue'
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('entity_id' => $model->getId()));
                    return;
                }
                // go to grid
                $this->_redirect('*/*/');
                return;

            } catch (Exception $e) {
                // display error message
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                // save data in session
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                // redirect to edit form
                $this->_redirect('*/*/edit', array('entity_id' => $id));
                return;
            }
        }
        $this->_redirect('*/*/');
    }

    /**
     * Delete action
     */
    public function deleteAction()
    {
        // check if we know what should be deleted
        if ($id = $this->getRequest()->getParam('entity_id')) {
            try {
                // init model and delete
                $model = Mage::getModel('tgc_datamart/emailLanding_mediacode');
                $model->load($id);
                $model->delete();
                // display success message
                Mage::getSingleton('adminhtml/session')->addSuccess($this->__('The Media Code has been deleted.'));
                // go to grid
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                // display error message
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                // go back to edit form
                $this->_redirect('*/*/edit', array('entity_id' => $id));
                return;
            }
        }
        // display error message
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('cms')->__('Unable to find a Media Code to delete.'));
        // go to grid
        $this->_redirect('*/*/');
    }
}
