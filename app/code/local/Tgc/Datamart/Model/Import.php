<?php
/**
 * DataMart integration
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DataMart
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Datamart_Model_Import extends Mage_ImportExport_Model_Import
{
    const CHECKSUM_IDENTIFIER = 'Expected Records';

    private $_isUsingInterface;

    private $_reformatEntityFiles = array('customer');

    /**
     * Get checksum value from import file if exists
     *
     * @return int checksum | bool false
     */
    public function getChecksumValue()
    {
        $adapter = $this->_getEntityAdapter();

        if (!$this->_isUsingInterface($adapter)) {
            return false;
        }

        return $adapter->getChecksumValue() >= 0 ?
            $adapter->getChecksumValue() :
            false;
    }

    public function isChecksumRequired()
    {
        $isChecksumRequired = false;
        $adapter = $this->_getEntityAdapter();
        if ($this->_isUsingInterface($adapter)) {
            $isChecksumRequired = true;
        }

        return $isChecksumRequired;
    }

    private function _isUsingInterface($adapter)
    {
        if (isset($this->_isUsingInterface)) {
            return $this->_isUsingInterface;
        }

        $class = new ReflectionClass($adapter);
        $this->_isUsingInterface = $class->implementsInterface('Tgc_Datamart_Model_Import_Entity_Interface');

        return $this->_isUsingInterface;
    }

    /**
     * Override to remove the checksum row from count if it exists
     *
     * @return int
     */
    public function getProcessedRowsCount()
    {
        $rowCount = $this->_getEntityAdapter()->getProcessedRowsCount();

        return $this->getChecksumValue() >= 0 ?
            $rowCount - 1 :
            $rowCount;
    }

    /**
     * Validate if checksum is correct
     *
     * @return bool valid or not
     */
    public function validateChecksum()
    {
        $checksum = $this->getChecksumValue();
        $rowCount = $this->getProcessedRowsCount();

        //no checksum in import file?
        if (!$checksum && !$this->isChecksumRequired()) { //if user leaves out checksum, it is not value, therefore, it will not return true if this is omitted.
            return true;
        }
        if ($this->_getEntityAdapter()->getErrorsCount() >= $this->_getEntityAdapter()->getErrorsLimit()) {
            return true;
        }

        return $checksum == $rowCount;
    }

    /**
     * Move uploaded file and create source adapter instance.
     *
     * @throws Mage_Core_Exception
     * @return string Source file path
     */
    public function uploadSource()
    {
        $entity    = $this->getEntity();
        $uploader  = Mage::getModel('core/file_uploader', self::FIELD_NAME_SOURCE_FILE);
        $uploader->skipDbProcessing(true);
        $result    = $uploader->save(self::getWorkingDir());
        $extension = pathinfo($result['file'], PATHINFO_EXTENSION);

        $uploadedFile = $result['path'] . $result['file'];
        if (!$extension) {
            unlink($uploadedFile);
            Mage::throwException(Mage::helper('importexport')->__('Uploaded file has no extension'));
        }

        /* Process custom file reformatting */
        if(in_array($entity, $this->_reformatEntityFiles))
        {
            // Hand off file path to our reformatting helper
            $reformattedFile = Mage::helper('tgc_datamart/'.$entity)->processFileReformat($uploadedFile);
            $uploadedFile = $reformattedFile;
        }

        $sourceFile = self::getWorkingDir() . $entity;

        $sourceFile .= '.' . $extension;

        if(strtolower($uploadedFile) != strtolower($sourceFile)) {
            if (file_exists($sourceFile)) {
                unlink($sourceFile);
            }

            if (!@rename($uploadedFile, $sourceFile)) {
                Mage::throwException(Mage::helper('importexport')->__('Source file moving failed'));
            }
        }
        // trying to create source adapter for file and catch possible exception to be convinced in its adequacy
        try {
            $this->_getSourceAdapter($sourceFile);
        } catch (Exception $e) {
            unlink($sourceFile);
            Mage::throwException($e->getMessage());
        }
        return $sourceFile;
    }

    /**
     * DB data source model getter.
     * Change from Resource Singleton to Resource to support multiple profiles running simultaneously.
     *
     * @static
     * @return Mage_ImportExport_Model_Mysql4_Import_Data
     */
    public static function getDataSourceModel()
    {
        return Mage::getResourceModel('importexport/import_data');
    }

    /**
     * Import source file structure to DB.
     *
     * @param array $data - entity and behavior values from Controller request.  Originally this info was being pulled from importexport_importdata table
     * which did not support running multiple profiles at the same time.
     *
     * @return bool
     */
    public function importSource($data = array())
    {
        if(!isset($data['entity']) || !isset($data['behavior']))
        {
            Mage::throwException(Mage::helper('importexport')->__('Entity and/or Behavior is unknown'));
        }
        $this->setData(array(
            'entity'   => $data['entity'],
            'behavior' => $data['behavior']
        ));
        $this->addLogComment(Mage::helper('importexport')->__('Begin import of "%s" with "%s" behavior', $this->getEntity(), $this->getBehavior()));
        $result = $this->_getEntityAdapter()->importData();
        $this->addLogComment(array(
            Mage::helper('importexport')->__('Checked rows: %d, checked entities: %d, invalid rows: %d, total errors: %d', $this->getProcessedRowsCount(), $this->getProcessedEntitiesCount(), $this->getInvalidRowsCount(), $this->getErrorsCount()),
            Mage::helper('importexport')->__('Import has been done successfuly.')
        ));
        return $result;
    }

    private function _helperDax()
    {
        return Mage::helper('tgc_dax');
    }
}
