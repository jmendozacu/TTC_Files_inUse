<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DataMart
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_Datamart_Model_EmailLanding_Mediacode extends Mage_Core_Model_Abstract
{
    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'tgc_datamart_landing_media_code';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'media_code';

    protected function _construct()
    {
        $this->_init('tgc_datamart/emailLanding_mediacode');
    }

    /**
     * Media Code Aliases getter
     *
     * @return array
     */
    public function getMediaCodeAliases()
    {
        if (!is_array($this->getData('media_code_aliases'))) {
            $aliases = explode(',', $this->getData('media_code_aliases'));
            foreach ($aliases as $key => &$alias) {
                $alias = trim($alias);
                if (!$alias) {
                    unset($aliases[$key]);
                }
            }
            foreach ($aliases as $key => &$alias) {
                $duplicateKey = array_search($alias, $aliases);
                if ($duplicateKey != $key) {
                    unset($aliases[$key]);
                }
            }
            $this->setData('media_code_aliases', $aliases);
        }

        return $this->getData('media_code_aliases');
    }

    /**
     * Check media code for uniqueness
     *
     * @throws Mage_Core_Exception
     * @return Mage_Core_Model_Abstract
     */
    protected function _beforeSave()
    {
        $collection = $this->getCollection()
            ->joinAliases()
            ->addFieldToFilter(
                array('media_code' => 'media_code', 'alias' => 'alias'),
                array('media_code' => $this->getMediaCode(), 'alias' => $this->getMediaCode()))
            ->addFieldToFilter($this->getIdFieldName(), array('neq' => $this->getId()));

        if ($collection->getSize()) {
            Mage::throwException(
                Mage::helper('tgc_datamart')->__('Media Code should be unique')
            );
        }

        if ($this->getMediaCodeAliases()) {
            $collection = $this->getCollection()
                ->joinAliases()
                ->addFieldToFilter(
                    array('media_code' => 'media_code', 'alias' => 'alias'),
                    array(
                        'media_code' => array('in' => $this->getMediaCodeAliases()),
                        'alias' => array('in' => $this->getMediaCodeAliases())
                ))
                ->addFieldToFilter($this->getIdFieldName(), array('neq' => $this->getId()));

            if ($collection->getSize()) {
                Mage::throwException(
                    Mage::helper('tgc_datamart')->__('Media Code Aliases should be unique')
                );
            }
        }

        return parent::_beforeSave();
    }

    /**
     * Load media code data by media code
     *
     * @param integer $mediaCode
     * @return \Tgc_Datamart_Model_EmailLanding_Mediacode
     */
    public function loadByMediaCode($mediaCode)
    {
        $this->_getResource()->loadByMediaCode($this, $mediaCode);
        $this->_afterLoad();
        $this->setOrigData();
        $this->_hasDataChanges = false;
        return $this;
    }
}
