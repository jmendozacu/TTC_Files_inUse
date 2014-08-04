<?php
/**
 * User: mhidalgo
 * Date: 11/03/14
 * Time: 13:32
 */
class Tgc_Zmag_Adminhtml_ZmagController extends Mage_Adminhtml_Controller_Action
{
    function indexAction() {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function gridAction()
    {
        $this->loadLayout();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('tgc_zmag/adminhtml_grid')->toHtml()
        );
    }

    function newAction() {
        $this->_title($this->__('New Zmag'));
        $this->loadLayout();
        $this->_addContent($this->getLayout()->createBlock('tgc_zmag/adminhtml_form_edit'))
            ->_addLeft($this->getLayout()->createBlock('tgc_zmag/adminhtml_form_edit_tabs'));
        $this->renderLayout();
    }

    function editAction() {
        $zmagId  = (int) $this->getRequest()->getParam('id');

        $this->_title($this->__('Edit Zmag'));

        $model = Mage::getModel('tgc_zmag/zmag');
        if ($zmagId) {
            $model->load($zmagId);
            if ($model->getId()) {
                $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
                if ($data) {
                    $model->setData($data)->setId($zmagId);
                }
            } else {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('tgc_zmag')->__('Zmag does not exist'));
                $this->_redirect('*/*/');
                return;
            }
        }
        Mage::register('zmag_data', $model);

        $this->loadLayout();
        $this->_addContent($this->getLayout()->createBlock('tgc_zmag/adminhtml_form_edit'))
            ->_addLeft($this->getLayout()->createBlock('tgc_zmag/adminhtml_form_edit_tabs'));
        $this->renderLayout();
    }

    function saveAction() {
        if ($data = $this->getRequest()->getPost()) {
            $model = Mage::getModel('tgc_zmag/zmag');

            if ($id = $this->getRequest()->getParam('id')) {
                $model->load($id);
            }
            // Save icon
            if (isset($_FILES['icon']['name']) && $_FILES['icon']['name'] != '') {
                try {
                    // If model have icon so delete this
                    if ($icon = $model->getIcon()) {
                        $urlParts = parse_url($icon);
                        $imageFile = Mage::getBaseDir().$urlParts['path'];
                        unlink($imageFile);
                    }

                    /* Starting upload */
                    $uploader = new Varien_File_Uploader('icon');

                    // Any extention would work
                    $uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
                    $uploader->setAllowRenameFiles(false);

                    // Set the file upload mode
                    // false -> get the file directly in the specified folder
                    // true -> get the file in the product like folders
                    //  (file.jpg will go in something like /media/f/i/file.jpg)
                    $uploader->setFilesDispersion(false);

                    // We set media as the upload dir
                    $dir = Mage::getBaseDir('media').DS."zmag/icons/".$data['publication_id'];
                    $path = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA)."zmag/icons/".$data['publication_id'] ;
                    $uploader->save($dir, $_FILES['icon']['name'] );

                } catch (Exception $e) {
                    Mage::logException($e);
                }

                if (isset($path)) {
                    $data['icon'] = $path.DS.$_FILES['icon']['name'];
                }
            }
            else {
                if (isset($data['icon']['delete']) && $data['icon']['delete'] == 1) {
                    $urlParts = parse_url($data['icon']['value']);
                    $imageFile = Mage::getBaseDir().$urlParts['path'];
                    unlink($imageFile);
                    $data['icon'] = '';
                }
                else {
                    unset($data['icon']);
                }
            }
            if (isset($data['icon'])) $model->setIcon($data['icon']);

            try {
                $model->setData($data);
                if ($id) {
                    $model->setId($id);
                }
                $model->save();

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('tgc_zmag')->__('Zmag was successfully saved.'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            }
            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }

        $this->_redirect('*/*/');
        return;
    }

    function deleteAction() {
        if ( $this->getRequest()->getParam('id') > 0 ) {
            try {
                $model = Mage::getModel('tgc_zmag/zmag');

                $model->load($this->getRequest()->getParam('id'))
                    ->delete();

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Zmag was successfully deleted'));
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        $this->_redirect('*/*/');
        return;
    }

    public function exportCsvAction()
    {
        $fileName   = 'Zmag.csv';
        $content    = $this->getLayout()->createBlock('tgc_zmag/adminhtml_grid')
            ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportXmlAction()
    {
        $fileName   = 'Zmag.xml';
        $content    = $this->getLayout()->createBlock('tgc_zmag/adminhtml_grid')
            ->getXml();

        $this->_sendUploadResponse($fileName, $content);
    }
}