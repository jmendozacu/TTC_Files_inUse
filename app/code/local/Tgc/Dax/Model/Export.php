<?php
/**
 * Dax integration
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Dax
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Dax_Model_Export extends Enterprise_ImportExport_Model_Export
{
    private $_nonEavEntities = array(
        'subscription_updates',
    );

    public function getNonEavEntities()
    {
        return $this->_nonEavEntities;
    }

    /**
     * Export data.
     *
     * @throws Mage_Core_Exception
     * @return string
     */
    public function export()
    {
        if (isset($this->_data[self::FILTER_ELEMENT_GROUP]) || in_array($this->getEntity(), $this->_nonEavEntities)) {
            $this->addLogComment(Mage::helper('importexport')->__('Begin export of %s', $this->getEntity()));
            $result = $this->_getEntityAdapter()
                ->setWriter($this->_getWriter())
                ->export();
            $countRows = substr_count(trim($result), "\n");
            if (!$countRows) {
                // Add "expected records, 0" for empty file. Place in first two columns of CSV.
                $cols = explode(',', $result);
                $this->_getEntityAdapter()->getWriter()->writeRow(array($cols[0] => 'Expected Records', $cols[1] => 0));
                $result = $this->_getEntityAdapter()->getWriter()->getContents();
            }
            if ($result) {
                $this->addLogComment(array(
                    Mage::helper('importexport')->__('Exported %s rows to file.', $countRows),
                    Mage::helper('importexport')->__('Export has been done.')
                ));
            }
            return $result;
        } else {
            Mage::throwException(
                Mage::helper('importexport')->__('No filter data provided')
            );
        }
    }
}
