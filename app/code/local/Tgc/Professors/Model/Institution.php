<?php
/**
 * Professor model
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category
 * @package
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 *
 * @method string getName()
 * @method string setName() setName(string $name)
 */
class Tgc_Professors_Model_Institution extends Mage_Core_Model_Abstract
{
    protected $_eventPrefix = 'institution';
    protected $_eventObject = 'institution';

    protected function _construct()
    {
        $this->_init('profs/institution');
    }
}