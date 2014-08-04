<?php
/**
 * Overridden, because we need to change min password length
 *
 * @author      Guidance Magento SuperTeam <magento@guidance.com>
 * @category    Tgc
 * @package     Tgc_StrongMail
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_StrongMail_Model_Customer_Attribute_Backend_Password extends Mage_Customer_Model_Customer_Attribute_Backend_Password
{
    public function beforeSave($object)
    {
        $minLength = Tgc_StrongMail_Model_Customer::MIN_PASSWORD_LENGTH;
        $password = trim($object->getPassword());
        $len = Mage::helper('core/string')->strlen($password);

        if ($len) {
            if ($len < $minLength) {
                Mage::throwException(Mage::helper('customer')->__('The password must have at least ' . $minLength . ' characters. Leading or trailing spaces will be ignored.'));
            }
            $object->setPasswordHash($object->hashPassword($password));
        }
    }
}
