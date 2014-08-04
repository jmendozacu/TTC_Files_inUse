<?php
/**
 * Mana Helper override.
 * Overridden for saving filters into session on "/courses" page.
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Tgc_ManaPro
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_ManaPro_Helper_Data extends Mana_Filters_Helper_Data
{
    protected $_priceRangeData;

    public function markLayeredNavigationUrl($url, $routePath, $routeParams)
    {
	    $request = Mage::app()->getRequest();
	    $path = $request->getModuleName().'/'.$request->getControllerName(). '/'.$request->getActionName();

        if ($path == 'catalog/category/view'
            //CUSTOM CODE
            //We need to add the parameter for our "/courses" URL too,
            //because we use ManaDev layered navigation there too.
            || $path == 'courses/index/index'
            //CUSTOM CODE END
        ) {
            if (Mage::getStoreConfigFlag('mana_filters/session/save_applied_filters')) {
                $url .= (strpos($url, '?') === false) ? '?m-layered=1' : '&m-layered=1';
            }
        }
        elseif ($path == 'catalogsearch/result/index') {
            if (Mage::getStoreConfigFlag('mana_filters/session/save_applied_search_filters')) {
                $url .= (strpos($url, '?') === false) ? '?m-layered=1' : '&m-layered=1';
            }
        }
        else {
            if (Mage::getStoreConfigFlag('mana_filters/session/save_applied_cms_filters')) {
                $url .= (strpos($url, '?') === false) ? '?m-layered=1' : '&m-layered=1';
            }
        }
		return $url;
	}

    public function getPriceFilterRangeAndLastAmount()
    {
        if (!isset($this->_priceRangeData)) {
            $this->_priceRangeData = Mage::getResourceModel('tgc_manapro/price')
                ->getPriceFilterRangeAndLastAmount();
        }
        return $this->_priceRangeData;
    }
}
