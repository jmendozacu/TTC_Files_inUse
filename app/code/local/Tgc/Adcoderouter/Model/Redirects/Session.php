<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Guidance
 * @package     Adcoderouter
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_Adcoderouter_Model_Redirects_Session extends Mage_Core_Model_Abstract
{
    protected $_redirectErrors;

    public function addRedirectError($errorMessage)
    {
        $this->_redirectErrors[] = $errorMessage;
    }

    public function getRedirectErrors()
    {
        return $this->_redirectErrors;
    }
}