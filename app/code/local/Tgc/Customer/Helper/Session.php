<?php
/**
 * Customer Active Session helper
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Customer
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Customer_Helper_Session extends Mage_Core_Helper_Data
{
    function encrypt($clear)
    {
        $encrypted = trim(
            base64_encode(
                mcrypt_encrypt(
                    MCRYPT_RIJNDAEL_256,
                    Tgc_Customer_Model_ActiveSession::ACTIVE_SESSION_SALT,
                    $clear,
                    MCRYPT_MODE_ECB,
                    mcrypt_create_iv(
                        mcrypt_get_iv_size(
                            MCRYPT_RIJNDAEL_256,
                            MCRYPT_MODE_ECB
                        ),
                        MCRYPT_RAND
                    )
                )
            )
        );

        $search = array('+', '/', '=');
        $replace = array('-', '_', '!');
        $urlSafe = str_replace($search, $replace, $encrypted);

        return $urlSafe;
    }

    function decrypt($urlSafe)
    {
        $replace = array('+', '/', '=');
        $search = array('-', '_', '!');
        $encrypted = str_replace($search, $replace, $urlSafe);

        $clear = trim(
            mcrypt_decrypt(
                MCRYPT_RIJNDAEL_256,
                Tgc_Customer_Model_ActiveSession::ACTIVE_SESSION_SALT,
                base64_decode($encrypted),
                MCRYPT_MODE_ECB,
                mcrypt_create_iv(
                    mcrypt_get_iv_size(
                        MCRYPT_RIJNDAEL_256,
                        MCRYPT_MODE_ECB
                    ),
                    MCRYPT_RAND
                )
            )
        );

        return $clear;
    }
}
