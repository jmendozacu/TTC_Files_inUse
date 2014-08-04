<?php
/**
 * DataMart integration
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DataMart
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_Datamart_Adminhtml_EmailLanding_BannerController extends Mage_Adminhtml_Controller_Action
{
    /**
     * Check ACL
     *
     * @return boolean
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('cms/landing_pages/banners');
    }

    /**
     * Init actions
     *
     * @return Tgc_Datamart_Adminhtml_EmailLanding_BannersController
     */
    protected function _initAction()
    {
        // load layout, set active menu and breadcrumbs
        $this->loadLayout()
            ->_setActiveMenu('cms/landing_page/banners')
            ->_addBreadcrumb(Mage::helper('cms')->__('CMS'), Mage::helper('cms')->__('CMS'))
            ->_addBreadcrumb($this->__('Manage Landing Pages'), $this->__('Manage Landing Pages'))
            ->_addBreadcrumb($this->__('Banners'), $this->__('Banners'))
        ;
        return $this;
    }

    /**
     * Index action
     */
    public function indexAction()
    {
        $this->_title(Mage::helper('cms')->__('CMS'))->_title($this->__('Landing Page Banners'));

        $this->_initAction();
        $this->renderLayout();
    }

    /**
     * Create new banner action
     */
    public function newAction()
    {
        // the same form is used to create and edit
        $this->_forward('edit');
    }

    /**
     * Edit banner action
     */
    public function editAction()
    {
        $this->_title(Mage::helper('cms')->__('CMS'))->_title($this->__('Landing Page Banners'));

        // 1. Get ID and create model
        $id = $this->getRequest()->getParam('banner_id');
        $model = Mage::getModel('tgc_datamart/emailLanding_banner');

        // 2. Initial checking
        if ($id) {
            $model->load($id);
            if (! $model->getId()) {
                Mage::getSingleton('adminhtml/session')->addError($this->__('This banner no longer exists.'));
                $this->_redirect('*/*/');
                return;
            }
        }

        $this->_title($model->getId() ? $model->getTitle() : $this->__('New Banner'));

        // 3. Set entered data if was error when we do save
        $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }

        // 4. Register model to use later in blocks
        Mage::register('tgc_datamart_landing_banner', $model);

        // 5. Build edit form
        $this->_initAction()
            ->_addBreadcrumb($id ? $this->__('Edit Banner') : $this->__('New Banner'), $id ? $this->__('Edit Banner') : $this->__('New Banner'))
            ->renderLayout();
    }

    /**
     * Save action
     */
    public function saveAction()
    {
        // check if data sent
        if ($data = $this->getRequest()->getPost()) {
            if (isset($data['mobile_image']) && is_array($data['mobile_image'])) {
                $data['mobile_image'] = isset($data['mobile_image']['value']) ? $data['mobile_image']['value'] : '';
            }

            if (isset($data['desktop_image']) && is_array($data['desktop_image'])) {
                $data['desktop_image'] = isset($data['desktop_image']['value']) ? $data['desktop_image']['value'] : '';
            }

            $id = $this->getRequest()->getParam('banner_id');
            $model = Mage::getModel('tgc_datamart/emailLanding_banner')->load($id);
            if (!$model->getId() && $id) {
                Mage::getSingleton('adminhtml/session')->addError($this->__('This banner no longer exists.'));
                $this->_redirect('*/*/');
                return;
            }

            // try to save it
            try {
                $bannerForm = Mage::getModel('tgc_datamart/emailLanding_banner_form', $model);
                $errors = $bannerForm->validateData($data);
                if (!empty($errors)) {
                    foreach ($errors as $error) {
                        Mage::getSingleton('adminhtml/session')->addError($error);
                    }
                    Mage::getSingleton('adminhtml/session')->setFormData($data);
                    $this->_redirect('*/*/edit', array('banner_id' => $id));
                    return;
                }

                $bannerForm->compactData($data);

                // save the data
                $model->save();
                // display success message
                Mage::getSingleton('adminhtml/session')->addSuccess($this->__('The banner has been saved.'));
                // clear previously saved data from session
                Mage::getSingleton('adminhtml/session')->setFormData(false);

                // check if 'Save and Continue'
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('banner_id' => $model->getId()));
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
                $this->_redirect('*/*/edit', array('banner_id' => $id));
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
        if ($id = $this->getRequest()->getParam('banner_id')) {
            try {
                // init model and delete
                $model = Mage::getModel('tgc_datamart/emailLanding_banner');
                $model->load($id);
                $model->delete();
                // display success message
                Mage::getSingleton('adminhtml/session')->addSuccess($this->__('The Banner has been deleted.'));
                // go to grid
                $this->_redirect('*/*/');
                return;

            } catch (Exception $e) {
                // display error message
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                // go back to edit form
                $this->_redirect('*/*/edit', array('banner_id' => $id));
                return;
            }
        }
        // display error message
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('cms')->__('Unable to find a Banner to delete.'));
        // go to grid
        $this->_redirect('*/*/');
    }
}
