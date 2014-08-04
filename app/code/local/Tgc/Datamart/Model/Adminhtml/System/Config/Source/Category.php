<?php
/**
 * DataMart integration
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DataMart
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Datamart_Model_Adminhtml_System_Config_Source_Category
{
    protected $_options;

    public function toOptionArray()
    {
        if (!$this->_options) {
            $this->_options = $this->_toOptionIdArray();
        }

        return $this->_options;
    }

    /**
     * Returns categories available
     *
     * @return array
     */
    private function _toOptionIdArray()
    {
        $res = array();
        $categoryIds = Mage::getResourceModel('tgc_datamart/emailLanding_collection')
            ->getColumnValues('category');
        $categoryIds = array_unique($categoryIds);

        $usedIds = Mage::getResourceModel('tgc_datamart/emailLanding_design_collection')
            ->getColumnValues('category');
        $usedIds = array_unique($usedIds);

        $availableIds = array_diff($categoryIds, $usedIds);

        foreach ($availableIds as $id) {
            $data['value'] = $id;
            $data['label'] = $id;
            $res[] = $data;
        }

        asort($res);

        return $res;
    }
}
