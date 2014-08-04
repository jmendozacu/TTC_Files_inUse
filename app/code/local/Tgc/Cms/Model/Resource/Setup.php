<?php
/**
 * Cms additions
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Cms
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Cms_Model_Resource_Setup extends Guidance_Setup_Model_Resource_Setup
{
    /**
     * Init widget instance object and set it to registry
     *
     * @param $package
     * @param $theme
     * @param $type
     * @return Mage_Widget_Model_Widget_Instance|boolean
     */
    protected function _initWidgetInstance($package, $theme, $type)
    {
        /** @var $widgetInstance Mage_Widget_Model_Widget_Instance */
        $widgetInstance = Mage::getModel('widget/widget_instance');

        $instanceId = null;
        $type       = null;
        $package    = null;
        $theme      = null;

        $packageTheme = $package . '/' . $theme == '/' ? null : $package . '/' . $theme;
        $widgetInstance->setType($type)
            ->setPackageTheme($packageTheme);

        Mage::unregister('current_widget_instance');
        Mage::register('current_widget_instance', $widgetInstance);

        return $widgetInstance;
    }
}
