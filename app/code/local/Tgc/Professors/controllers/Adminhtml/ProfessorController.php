<?php

include_once 'Tgc/Professors/Controller/Crud.php';

/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category
 * @package
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Professors_Adminhtml_ProfessorController extends Tgc_Professors_Controller_Crud
{
    protected function _getModelType()
    {
        return 'profs/professor';
    }

    protected function _getDeleteErrorMessage()
    {
        return $this->__('Professor that you are tried to delete does not exist.');
    }

    protected function _getDeleteSuccessMessage()
    {
        return $this->__('Professor has been successfully deleted.');
    }

    protected function _getSaveErrorMessage()
    {
        return $this->__('Error occured on professor saving.');
    }

    protected function _getSaveSuccessMessage()
    {
        return $this->__('Professor has been successfully saved.');
    }

    protected function _filter(array $values)
    {
        if (is_array($values[Tgc_Professors_Block_Adminhtml_Professor_Edit_Form::FIELD_INSTITUTION])) {
            $values[Tgc_Professors_Block_Adminhtml_Professor_Edit_Form::FIELD_INSTITUTION]
                = array_filter($values[Tgc_Professors_Block_Adminhtml_Professor_Edit_Form::FIELD_INSTITUTION]);
        }
        if (is_array($values[Tgc_Professors_Block_Adminhtml_Professor_Edit_Form::FIELD_ALMA_MATERS])) {
            $values[Tgc_Professors_Block_Adminhtml_Professor_Edit_Form::FIELD_ALMA_MATERS]
                = array_filter($values[Tgc_Professors_Block_Adminhtml_Professor_Edit_Form::FIELD_ALMA_MATERS]);
        }

        return $values;
    }

    protected function _normalize(array $values)
    {
        if (empty($values[Tgc_Professors_Block_Adminhtml_Professor_Edit_Form::FIELD_CATEGORY])) {
            $values[Tgc_Professors_Block_Adminhtml_Professor_Edit_Form::FIELD_CATEGORY] = null;
        }
        if (empty($values[Tgc_Professors_Block_Adminhtml_Professor_Edit_Form::FIELD_ALMA_MATERS])) {
            $values[Tgc_Professors_Block_Adminhtml_Professor_Edit_Form::FIELD_ALMA_MATERS] = array();
        }
        if (empty($values[Tgc_Professors_Block_Adminhtml_Professor_Edit_Form::FIELD_INSTITUTION])) {
            $values[Tgc_Professors_Block_Adminhtml_Professor_Edit_Form::FIELD_INSTITUTION] = array();
        }

        try {
            if (isset($values[Tgc_Professors_Block_Adminhtml_Professor_Edit_Form::FIELD_PHOTO]['delete'])) {
                $values[Tgc_Professors_Block_Adminhtml_Professor_Edit_Form::FIELD_PHOTO] = null;
            } else {
                unset($values[Tgc_Professors_Block_Adminhtml_Professor_Edit_Form::FIELD_PHOTO]);
                $values[Tgc_Professors_Block_Adminhtml_Professor_Edit_Form::FIELD_PHOTO]
                    = $this->_uploadImage(Tgc_Professors_Block_Adminhtml_Professor_Edit_Form::FIELD_PHOTO);
            }
        } catch (Exception $e) {
            // File was not choosen; that's ok.
        }

        return $values;
    }

    private function _uploadImage($field)
    {
        $uploader = new Varien_File_Uploader($field);
        $uploader->setAllowedExtensions(array('jpg', 'png', 'jpeg', 'gif'))
            ->setAllowCreateFolders(true)
            ->setAllowRenameFiles(true)
            ->setFilesDispersion(true)
            ->save(Mage::getBaseDir('media') . DS . Tgc_Professors_Helper_Image::MEDIA_PATH);

        return $uploader->getUploadedFileName();

    }
}