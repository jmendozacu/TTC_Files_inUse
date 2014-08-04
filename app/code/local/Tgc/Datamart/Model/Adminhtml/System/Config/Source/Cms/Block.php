<?php
/**
 * DataMart integration
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DataMart
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Datamart_Model_Adminhtml_System_Config_Source_Cms_Block
{
    protected $_options;

    public function toOptionArray()
    {
        if (!$this->_options) {
            $this->_options = $this->_toOptionIdArray(Mage::getResourceModel('cms/block_collection')->load());
        }

        return $this->_options;
    }

    /**
     * Returns pairs identifier - title for unique identifiers
     * and pairs identifier|page_id - title for non-unique after first
     *
     * @param $collection Mage_Cms_Model_Resource_Block_Collection
     * @return array
     */
    private function _toOptionIdArray($collection)
    {
        $res = array();
        $selectBlock = array('value' => null, 'label' => '--Please Select A CMS Block--');
        $res[] = $selectBlock;
        $existingIdentifiers = array();
        foreach ($collection as $item) {
            $identifier = $item->getData('identifier');

            $data['value'] = $identifier;
            $data['label'] = $item->getData('title');

            if (in_array($identifier, $existingIdentifiers)) {
                $data['value'] .= '|' . $item->getData('page_id');
            } else {
                $existingIdentifiers[] = $identifier;
            }

            $res[] = $data;
        }
        asort($res);

        return $res;
    }
}
