<?php
class Tgc_Adcoderouter_Block_Adminhtml_Widget_Grid_Column_Renderer_Customdatetimestart extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Datetime
{
    const DATE_EMPTY = '0000-00-00';
    const NO_DATE_TEXT = 'No Start Date';

    /**
     * @param Varien_Object $row
     * @return bool|string
     */
    public function render(Varien_Object $row)
    {
        $data = $this->_getValue($row);
        if($data == self::DATE_EMPTY) {
            return self::NO_DATE_TEXT;
        } elseif ($data) {
            //This prevents a timezone conversion from being performed!!  This value does not depend on timezone for admin user.
            $format = $this->_getFormat();
            $date = new Zend_Date(Mage::app()->getLocale()->getLocale());
            $date->set($data, $format);
            $date->setTimezone(Mage_Core_Model_Locale::DEFAULT_TIMEZONE);
            return $date->toString($format);
        }
        return $this->getColumn()->getDefault();
    }
}