<?php
/**
 * Mailer Factory interface
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Tgc_StrongMail
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
interface Tgc_StrongMail_Model_Api_Mailer_Factory_Interface
{
    /**
     * Creates API Mailer object
     *
     * @param int $storeId
     * @return Tgc_StrongMail_Model_Api_Mailer
     */
    public function create($storeId);
}