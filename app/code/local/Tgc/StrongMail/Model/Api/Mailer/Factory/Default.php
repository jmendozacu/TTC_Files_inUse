<?php
/**
 * Mailer factory. Builds API mailer class for the application.
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Tgc_StrongMail
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_StrongMail_Model_Api_Mailer_Factory_Default implements Tgc_StrongMail_Model_Api_Mailer_Factory_Interface
{
    /**
     * Creates API Mailer object
     *
     * @param int $storeId
     * @return Tgc_StrongMail_Model_Api_Mailer
     */
    public function create($storeId)
    {
        return Mage::getModel(
            'tgc_strongMail/api_mailer',
            array(
                'processor' => Mage::getModel(
                    'tgc_strongMail/api_mailer_processor',
                    array(
                        'config' => Mage::getModel(
                            'tgc_strongMail/api_mailer_config',
                            array(
                                'store_id' => $storeId
                            )
                        )
                    )
                )
            )
        );
    }
}