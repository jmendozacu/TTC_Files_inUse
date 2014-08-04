<?php


class Tgc_Dax_Model_Import_Entity_Checksum_Base extends Mage_ImportExport_Model_Import_Entity_Abstract
    implements Tgc_Datamart_Model_Import_Entity_Interface
{
    protected $_importChecksumValue;

    protected function _importData()
    {

    }

    public function validateRow(array $rowData, $rowNum)
    {

    }

    public function getEntityTypeCode()
    {

    }

    /**
     * Validate data rows and save bunches to DB.
     *
     * @return Mage_ImportExport_Model_Import_Entity_Abstract
     */
    protected function _saveValidatedBunches()
    {
        $source          = $this->_getSource();
        $productDataSize = 0;
        $bunchRows       = array();
        $startNewBunch   = false;
        $nextRowBackup   = array();
        $maxDataSize = Mage::getResourceHelper('importexport')->getMaxDataSize();
        $bunchSize = Mage::helper('importexport')->getBunchSize();

        $source->rewind();
        $this->_dataSourceModel->cleanBunches();

        while ($source->valid() || $bunchRows) {
            if ($startNewBunch || !$source->valid()) {
                $this->_dataSourceModel->saveBunch($this->getEntityTypeCode(), $this->getBehavior(), $bunchRows);

                $bunchRows       = $nextRowBackup;
                $productDataSize = strlen(serialize($bunchRows));
                $startNewBunch   = false;
                $nextRowBackup   = array();
            }
            if ($source->valid()) {
                if ($this->_errorsCount >= $this->_errorsLimit) { // errors limit check
                    return;
                }
                $rowData = $source->current();

                $this->_processedRowsCount++;

                if(0 === $source->key()) { //only executes for checksum row
                    $this->_daxDataHelper()->processChecksum($rowData, $this); //this validates and sets the checksum value.
                } elseif ($this->validateRow($rowData, $source->key())) { // add row to bunch for save
                    $rowData = $this->_prepareRowForDb($rowData);
                    $rowSize = strlen(Mage::helper('core')->jsonEncode($rowData));

                    $isBunchSizeExceeded = ($bunchSize > 0 && count($bunchRows) >= $bunchSize);

                    if (($productDataSize + $rowSize) >= $maxDataSize || $isBunchSizeExceeded) {
                        $startNewBunch = true;
                        $nextRowBackup = array($source->key() => $rowData);
                    } else {
                        $bunchRows[$source->key()] = $rowData;
                        $productDataSize += $rowSize;
                    }
                }
                $source->next();
            }
        }
        return $this;
    }

    public function getChecksumValue()
    {
        return $this->_importChecksumValue;
    }

    public function setChecksumValue($checksumValue)
    {
        $this->_importChecksumValue = $checksumValue;
    }

    protected function _daxDataHelper()
    {
        return Mage::helper('tgc_dax');
    }
}
