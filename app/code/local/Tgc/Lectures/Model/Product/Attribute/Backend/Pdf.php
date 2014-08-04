<?php

class Tgc_Lectures_Model_Product_Attribute_Backend_Pdf extends Mage_Eav_Model_Entity_Attribute_Backend_Abstract
{
    protected $_imageArrayKeys = array('name','type','tmp_name','error','size');

    public function afterSave($object)
    {
        if (empty($_FILES) || empty($_FILES['product'])) {
            return;
        }

        $value = $object->getData($this->getAttribute()->getName());

        /**************************************************************************************/
        //Creates an array element of $_FILES variable that stores image values in a format Magento will accept.
        $image = array();
        $attributeName = $this->getAttribute()->getName();
        foreach($this->_imageArrayKeys as $imageArrayKey) {
            if (isset($_FILES['product'][$imageArrayKey][$attributeName])) {
                $image[$imageArrayKey] = $_FILES['product'][$imageArrayKey][$attributeName];
            }
        }

        $_FILES[$attributeName] = $image;
        /**************************************************************************************/

        if (is_array($value) && !empty($value['delete'])) {
            $object->setData($this->getAttribute()->getName(), '');
            $this->getAttribute()->getEntity()
                ->saveAttribute($object, $this->getAttribute()->getName());
            return;
        }

        $path = Mage::getBaseDir('media') . DS . 'catalog' . DS . 'guidebook' . DS;

        try {
            $uploader = new Mage_Core_Model_File_Uploader($this->getAttribute()->getName());
            $uploader->setAllowedExtensions(array('pdf'));
            $uploader->setAllowRenameFiles(true);
            $result = $uploader->save($path);

            $object->setData($this->getAttribute()->getName(), $result['file']);
            $this->getAttribute()->getEntity()->saveAttribute($object, $this->getAttribute()->getName());
        } catch (Exception $e) {
            if ($e->getCode() != Mage_Core_Model_File_Uploader::TMP_NAME_EMPTY) {
                Mage::logException($e);
            }
            /** @TODO add session message for exception so user understands when filetype is invalid */
            return;
        }
    }
}