<?php
require_once (Mage::getModuleDir('controllers', 'Mage_Adminhtml') . DS . 'System' . DS . 'Convert' . DS . 'GuiController.php');
/**
 * Bazaarvoice
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Bazaarvoice
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Bazaarvoice_Adminhtml_System_Convert_GuiController extends Mage_Adminhtml_System_Convert_GuiController
{
    /**
     * Profiles list action
     */
    public function indexAction()
    {
        $this->_title($this->__('System'))
            ->_title($this->__('Import and Export'))
            ->_title($this->__('Profiles'));

        if ($this->getRequest()->getQuery('ajax')) {
            $this->_forward('grid');
            return;
        }
        $this->loadLayout();

        /**
         * Set active menu item
         */
        $this->_setActiveMenu('system/convert');

        /**
         * Append profiles block to content
         */
        $this->_addContent(
            $this->getLayout()->createBlock('tgc_bv/adminhtml_system_convert_gui', 'convert_profile')
        );

        /**
         * Add breadcrumb item
         */
        $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Import/Export'), Mage::helper('adminhtml')->__('Import/Export'));
        $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Profiles'), Mage::helper('adminhtml')->__('Profiles'));

        $this->renderLayout();
    }

    public function gridAction()
    {
        $this->getResponse()->setBody($this->getLayout()->createBlock('tgc_bv/adminhtml_system_convert_gui_grid')->toHtml());
    }

    /**
     * Profile edit action
     */
    public function editAction()
    {
        $this->_initProfile();
        $this->loadLayout();

        $profile = Mage::registry('current_convert_profile');

        // set entered data if was error when we do save
        $data = Mage::getSingleton('adminhtml/session')->getConvertProfileData(true);

        if (!empty($data)) {
            $profile->addData($data);
        }

        $this->_title($profile->getId() ? $profile->getName() : $this->__('New Profile'));

        $this->_setActiveMenu('system/convert');


        $this->_addContent(
            $this->getLayout()->createBlock('adminhtml/system_convert_gui_edit')
        );

        /**
         * Append edit tabs to left block
         */
        $this->_addLeft($this->getLayout()->createBlock('tgc_bv/adminhtml_system_convert_gui_edit_tabs'));

        $this->renderLayout();
    }

    /**
     * Delete profile action
     */
    public function deleteAction()
    {
        $this->_initProfile();
        $profile = Mage::registry('current_convert_profile');
        if ($profile->getId()) {
            try {
                if ($profile->getEntityType() == Tgc_Bazaarvoice_Model_Convert_Adapter_Review::ENTITY) {
                    $message = Mage::helper('tgc_bv')->__(
                        'This profile is required by the Tgc_Bazaarvoice module.'
                    );
                    throw new LogicException($message);
                }
                $profile->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__('The profile has been deleted.'));
            } catch (Exception $e){
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*');
    }

    /**
     * Save profile action
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost()) {
            if (!$this->_initProfile('profile_id')) {
                return;
            }
            $profile = Mage::registry('current_convert_profile');

            // Prepare profile saving data
            if (isset($data)) {
                $profile->addData($data);
            }

            try {
                $profile->save();

                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__('The profile has been saved.'));
            } catch (Exception $e){
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setConvertProfileData($data);
                $this->getResponse()->setRedirect($this->getUrl('*/*/edit', array('id' => $profile->getId())));
                return;
            }
            if ($this->getRequest()->getParam('continue')) {
                $this->_redirect('*/*/edit', array('id' => $profile->getId()));
            } else {
                $this->_redirect('*/*');
            }
        } else {
            Mage::getSingleton('adminhtml/session')->addError(
                $this->__('Invalid POST data (please check post_max_size and upload_max_filesize settings in your php.ini file).')
            );
            $this->_redirect('*/*');
        }
    }
}
