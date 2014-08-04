<?php
/**
 * Class for 'list()' API operation.
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Tgc_StrongMail
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_StrongMail_Model_Api_Mailer_Operation_List extends Tgc_StrongMail_Model_Api_Mailer_Operation_Abstract
{
    /**
     * Returns Transactional Mailing ID by its name.
     *
     * @param string $mailingName
     * @return int|false
     */
    public function getMailingId($mailingName)
    {
        $filter = new Tgc_StrongMail_MailingFilter();
        $filter->orderBy = array();
        $filter->orderBy[] = "MODIFIED_TIME";

        // Create name condition
        $nameCondition = new Tgc_StrongMail_ScalarStringFilterCondition();
        $nameCondition->operator = "EQUAL";
        $nameCondition->value = $mailingName;
        $filter->nameCondition = $nameCondition;

        // Set the filter to request
        $request = new Tgc_StrongMail_ListRequest();
        $request->filter = $filter;

        // Make call and print results
        $result = $this->getClient()->_list($request);

        if (!empty($result) && isset($result->objectId)) {
            if (is_array($result->objectId)) {
                foreach ($result->objectId as $resultObject) {
                    if (isset($resultObject->id)) {
                        return $resultObject->id;
                    }
                }
            } else {
                return $result->objectId->id;
            }
        }

        return false;
    }
}
