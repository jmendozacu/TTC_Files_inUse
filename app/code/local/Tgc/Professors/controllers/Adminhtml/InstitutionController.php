<?php

include_once 'Tgc/Professors/Controller/Crud.php';

/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category
 * @package
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Professors_Adminhtml_InstitutionController extends Tgc_Professors_Controller_Crud
{
    protected function _getModelType()
    {
        return 'profs/institution';
    }

    protected function _getDeleteErrorMessage()
    {
        return $this->__('Institution that you are tried to delete does not exist.');
    }

    protected function _getDeleteSuccessMessage()
    {
        return $this->__('Institution has been successfully deleted.');
    }

    protected function _getSaveErrorMessage()
    {
        return $this->__('Error occured on institution saving.');
    }

    protected function _getSaveSuccessMessage()
    {
        return $this->__('Institution has been successfully saved.');
    }
}