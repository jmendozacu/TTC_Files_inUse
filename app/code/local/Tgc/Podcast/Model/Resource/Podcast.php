<?php

class Tgc_Podcast_Model_Resource_Podcast extends RocketWeb_Podcast_Model_Resource_Podcast
{

    protected function _afterLoad(Mage_Core_Model_Abstract $object)
    {
        $products = $this->__listProducts($object);
        if ($products)
        {
            $object->setData('product_ids', $products);
        }

        return parent::_afterLoad($object);
    }

    private function __listProducts(Mage_Core_Model_Abstract $object)
    {
        $select = $this->_getReadAdapter()->select()
            ->from($this->getTable('tgc_podcast/podcast_product'))
            ->where('podcast_id = ?', $object->getId());
        $data = $this->_getReadAdapter()->fetchAll($select);
        if ($data)
        {
            $productsArr = array();
            foreach ($data as $_i)
            {
                $productsArr[] = $_i['product_id'];
            }

            return $productsArr;
        }
    }

    /**
     * Override to add saving of Podcast and Courses
     *
     * @param Mage_Core_Model_Abstract $object
     * @return Mage_Core_Model_Resource_Db_Abstract
     */
    protected function _afterSave(Mage_Core_Model_Abstract $object)
    {
        $links = $object['links']; // echo '<pre>'; print_r($links);exit;
        if (isset($links['related']))
        {
            // delete previous and re-insert
            $conditionProduct = $this->_getWriteAdapter()->quoteInto('podcast_id = ?', $object->getId());
            $this->_getWriteAdapter()->delete($this->getTable('tgc_podcast/podcast_product'), $conditionProduct);

            $productIds = Mage::helper('adminhtml/js')->decodeGridSerializedInput($links['related']); // echo '<pre>';print_r($productIds);exit;

            foreach ($productIds as $_p)
            {
                $objArr = array();
                $objArr['podcast_id'] = $object->getId();
                $objArr['product_id'] = $_p;
                $this->_getWriteAdapter()->insert($this->getTable('tgc_podcast/podcast_product'),$objArr);
            }
        }
        return parent::_afterSave($object);
    }

}
