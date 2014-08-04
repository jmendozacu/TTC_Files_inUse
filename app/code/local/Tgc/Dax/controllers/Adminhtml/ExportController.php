<?php
require_once Mage::getModuleDir('controllers', 'Mage_ImportExport') . DS . 'Adminhtml' . DS . 'ExportController.php';
/**
 * Dax integration
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Dax
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Dax_Adminhtml_ExportController extends Mage_ImportExport_Adminhtml_ExportController
{
    /**
     * Load data with filter applying and create file for download.
     *
     * @return Mage_ImportExport_Adminhtml_ExportController
     */
    public function exportAction()
    {
        /** @var $model Mage_ImportExport_Model_Export */
        $model  = Mage::getModel('importexport/export');
        $entity = $this->getRequest()->getParam('entity');
        $export = Mage::getModel('tgc_dax/export');
        $nonEav = in_array($entity, $export->getNonEavEntities());

        if ($this->getRequest()->getPost(Mage_ImportExport_Model_Export::FILTER_ELEMENT_GROUP) || $nonEav) {

            try {
                $model->setData($this->getRequest()->getParams());

                if (!$model->getData($export::FILTER_ELEMENT_GROUP) && $nonEav) {
                    $model->setData($export::FILTER_ELEMENT_GROUP, true);
                }

                return $this->_prepareDownloadResponse(
                    $model->getFileName(),
                    $model->export(),
                    $model->getContentType()
                );
            } catch (Mage_Core_Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::logException($e);
                $this->_getSession()->addError($this->__('No valid data sent'));
            }
        } else {
            $this->_getSession()->addError($this->__('No valid data sent'));
        }
        return $this->_redirect('*/*/index');
    }
}
