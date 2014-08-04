<?php
/**
 * Bazaarvoice
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Bazaarvoice
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Bazaarvoice_Model_Convert_Parser_Review extends Mage_Dataflow_Model_Convert_Parser_Xml_Excel
{
    /**
     * Simple Xml object
     *
     * @var SimpleXMLElement
     */
    protected $_xmlElement;

    /**
     * Field list
     *
     * @var array
     */
    protected $_parseFieldNames;

    public function parse()
    {
        $adapterName   = $this->getVar('adapter', null);
        $adapterMethod = $this->getVar('method', 'saveRow');

        if (!$adapterName || !$adapterMethod) {
            $message = Mage::helper('dataflow')->__('Please declare "adapter" and "method" nodes first.');
            $this->addException($message, Mage_Dataflow_Model_Convert_Exception::FATAL);
            return $this;
        }

        $batchModel = $this->getBatchModel();
        $batchIoAdapter = $this->getBatchModel()->getIoAdapter();

        if (Mage::app()->getRequest()->getParam('files')) {
            $file = Mage::app()->getConfig()->getTempVarDir().'/import/'
                . urldecode(Mage::app()->getRequest()->getParam('files'));
            $this->_copy($file);
        }
        $countRows = 0;
        $file = $batchIoAdapter->getFile(true);
        $reader = new XMLReader();

        $profile = $this->getAction()->getContainer()->getProfile();
        $guiData = $profile->getDataflowProfile();
        $extension = pathinfo($guiData['gui_data']['file']['filename'], PATHINFO_EXTENSION);

        if ($extension == 'gz') {
            $fileGunzipped = $file . '.xml';
            $gzInterface = new Mage_Archive_Gz();
            $gzInterface->unpack($file, $fileGunzipped);
            $message = Mage::helper('dataflow')->__('File downloaded and unpacked');
            $this->addException($message);
            $reader->open($fileGunzipped);
        } else {
            $reader->open($file);
        }

        $doc = new DOMDocument;
        while ($reader->read() && $reader->name !== 'Product');

        while ($reader->name === 'Product') {
            $item = simplexml_import_dom($doc->importNode($reader->expand(), true));
            $countRows++;
            $data = array($item->asXml());
            $this->getBatchImportModel()
                ->setId(null)
                ->setBatchId($this->getBatchModel()->getId())
                ->setBatchData($data)
                ->setStatus(1)
                ->save();
            $reader->next('Product');
        }

        $this->addException(Mage::helper('dataflow')->__('Found %d reviews.', $countRows));
        $this->addException(Mage::helper('dataflow')->__('Starting %s :: %s', $adapterName, $adapterMethod));

        $batchModel->setParams($this->getVars())
            ->setAdapter($adapterName)
            ->save();

        return $this;
    }
}
