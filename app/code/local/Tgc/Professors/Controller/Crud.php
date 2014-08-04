<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category
 * @package
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
abstract class Tgc_Professors_Controller_Crud extends Mage_Adminhtml_Controller_Action
{
    abstract protected function _getModelType();

    abstract protected function _getDeleteSuccessMessage();

    abstract protected function _getDeleteErrorMessage();

    abstract protected function _getSaveSuccessMessage();

    abstract protected function _getSaveErrorMessage();

    protected function _normalize(array $values)
    {
        return $values;
    }

    protected function _filter(array $values)
    {
        return $values;
    }

    private function _loadModel()
    {
        $model = Mage::getModel($this->_getModelType());

        $id = $this->getRequest()->getParam('id');
        if ($id) {
            $model->load($id);
        }

        return $model;
    }

    public function indexAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function newAction()
    {
        $this->_forward('edit');
    }

    public function editAction()
    {
        $this->loadLayout();
        $form = $this->_getFormBlock();
        if ($form instanceof Tgc_Professors_Block_Adminhtml_ModelFormInterface) {
            $form->setValuesFromModel($this->_loadModel());
        }
        $this->renderLayout();
    }

    protected function _getFormBlock()
    {
        return $this->getLayout()
            ->getBlock('container')
            ->getChild('form');
    }

    public function deleteAction()
    {
        $model = $this->_loadModel();

        if (!$model->isObjectNew()) {
            $model->delete();
            $this->_getSession()->addSuccess($this->_getDeleteSuccessMessage());
        } else {
            $this->_getSession()->addError($this->_getDeleteErrorMessage());
        }

        $this->_redirectIndex();
    }

    public function saveAction()
    {
        try {
            if ($this->getRequest()->isPost()) {
                $this->_loadModel()
                    ->addData($this->_normalize($this->_filter($this->getRequest()->getParams())))
                    ->save();
            }
            $this->_getSession()->addSuccess($this->_getSaveSuccessMessage());
        } catch (Exception $e) {
            Mage::logException($e);
            $this->_getSession()->addError($this->_getSaveErrorMessage());
        }

        $this->_redirectIndex();
    }

    private function _redirectIndex()
    {
        $this->_redirect('*/*/index');
    }
}