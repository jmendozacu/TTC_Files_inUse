<?php
/**
 * Backend model to check remote file path
 *
 * @author Guidance Magento Team <magento@guidance.com>
 * @category Tgc
 * @package Dax
 * @copyright Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Dax_Model_Source_System_Config_Backend_RemotePath extends Mage_Core_Model_Config_Data
{
    public function save()
    {
        $path = (string)$this->getValue();
        if (empty($path)) {
            return parent::save();
        }

        $newPath = rtrim($path, '/');
        if ($path != $newPath) {
            $this->setValue($newPath);
        }

        return parent::save();
    }
}
