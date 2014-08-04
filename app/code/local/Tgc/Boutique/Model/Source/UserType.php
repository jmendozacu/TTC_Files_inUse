<?php
/**
 * Boutique
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Boutique
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Boutique_Model_Source_UserType
{
    const ALL_USERS      = 2;
    const ALL_USERS_TEXT = 'All Users';
    const GUEST          = 0;
    const GUEST_TEXT     = 'Guest and Prospect Users';
    const LOGGED         = 1;
    const LOGGED_TEXT    = 'Authenticated Users';

    protected $_userTypes = array();

    public function __construct()
    {
        $this->_userTypes = array(
            array(
                'value' => self::ALL_USERS,
                'label' => self::ALL_USERS_TEXT,
            ),
            array(
                'value' => self::GUEST,
                'label' => self::GUEST_TEXT,
            ),
            array(
                'value' => self::LOGGED,
                'label' => self::LOGGED_TEXT,
            ),
        );
    }

    public function toOptionArray()
    {
        $optionArray = array();

        foreach ($this->_userTypes as $type) {
            $optionArray[$type['value']] = $type['label'];
        }

        return $optionArray;
    }
}
