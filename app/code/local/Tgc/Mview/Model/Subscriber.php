<?php

/**
 * Changelog Subscriber domain entity
 *
 * @category    Enterprise
 * @package     Enterprise_Mview
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Tgc_Mview_Model_Subscriber extends Enterprise_Mview_Model_Subscriber
{
    protected function _afterSave()
    {
        parent::_afterSave();

        //save custom triggers if exist
        if ($triggers = $this->getCustomTriggers()) {
            foreach ($triggers as $trigger) {
                /* @var $trigger Tgc_Mview_Model_CustomTrigger */
                $trigger->setSubscriberId($this->getId());
                $trigger->save();
            }
        }
        return $this;
    }
}
