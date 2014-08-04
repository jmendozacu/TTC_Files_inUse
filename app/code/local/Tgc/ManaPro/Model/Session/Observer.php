<?php

/**
 * Mana observer override.
 * Overridden for saving filters into session for All categories and All attributes.
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Tgc_ManaPro
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 *
 */
class Tgc_ManaPro_Model_Session_Observer extends ManaPro_FilterAdmin_Model_Session_Observer
{
	/* BASED ON SNIPPET: Models/Event handler */
	/**
	 * Remembers category filters or applies remembered filters (handles event "controller_action_predispatch_catalog_category_view")
	 * @param Varien_Event_Observer $observer
	 */
	public function rememberCategoryFilters($observer)
    {
		/* @var $action Mage_Catalog_CategoryController */
        $action = $observer->getEvent()->getControllerAction();

		if (Mage::getStoreConfigFlag('mana_filters/session/save_applied_filters')) {
            //CUSTOM CODE
            //Save selected filter values into session globally
			//if ($categoryId = (int) $action->getRequest()->getParam('id', false)) {
			    $this->_rememberOrRestoreFilter($action, 'm_category_filters');
			//}
            //CUSTOM CODE END
		}
	}

    protected function _rememberOrRestoreFilter($action, $localKey)
    {
        extract($this->_getAppliedFilters($action));
        /* @var $locals array */
        /* @var $globals array */
        /* @var $specials array */
        /* @var $do int */
//        Mage::log('------------', Zend_Log::DEBUG, 'filter_session.log');
//        Mage::log('locals: ' . json_encode($locals), Zend_Log::DEBUG, 'filter_session.log');
//        Mage::log('globals: ' . json_encode($globals), Zend_Log::DEBUG, 'filter_session.log');
//        Mage::log('specials: ' . json_encode($specials), Zend_Log::DEBUG, 'filter_session.log');
        /* @var $session Mage_Core_Model_Session */
        $session = Mage::getSingleton('core/session');
        //if (!count($specials) && !count($locals) && !count($globals)) {
        if (empty($specials['m-layered'])) {
            // restore
            $query = array();
            if ($session->hasData('m_global_filters')) {
                $query = array_merge($query, $session->getData('m_global_filters'));
                foreach ($globals as $key => $value) {
                    if (!isset($query[$key])) {
                        $query[$key] = null;
                    }
                }
            }
            if ($session->hasData($localKey)) {
                $query = array_merge($query, $session->getData($localKey));
                foreach ($locals as $key => $value) {
                    if (!isset($query[$key])) {
                        $query[$key] = null;
                    }
                }
            }
            $params = array('_current' => true, '_use_rewrite' => true);
            $url = Mage::getUrl('*/*/*', array_merge($params, array('_query' => $query)));
            if ($url != Mage::getUrl('*/*/*', $params)) {
                // redirect to URL with applied filters
                if (!empty($query)) {
                    $query['m-layered'] = 1;
                }
                $url = Mage::getUrl('*/*/*', array_merge($params, array('_query' => $query)));
                $action->getResponse()->setRedirect($url);
                $action->getRequest()->setDispatched(true);
                $action->setFlag('', Mage_Core_Controller_Varien_Action::FLAG_NO_DISPATCH, true);
            }
        }
        else {
            // remember/remove
            $session->setData('m_global_filters', $globals);
            $session->setData($localKey, $locals);
        }
    }

	protected function _getAppliedFilters($action)
    {
	    $locals = array();
	    $globals = array();
	    $specials = array();

        foreach (array_keys($action->getRequest()->getQuery()) as $param) {
            if (in_array($param, $this->_getSpecialParameters())) {
                $specials[$param] = $action->getRequest()->getParam($param);
            }
            elseif (in_array($param, $this->_getGlobalParameters())) {
                $globals[$param] = $action->getRequest()->getParam($param);
            }
            elseif (in_array($param, $this->_getFilterNames())) {
                //CUSTOM CODE
                //Add selected filters into session
                $globals[$param] = $action->getRequest()->getParam($param);
                //CUSTOM CODE END
            }
        }

        if (count($specials) > 0) {
            if (count($locals) + count($globals) > 0) {
                $do = self::REMEMBER;
            }
            else {
                $do = self::REMOVE;
            }
        }
        else {
            $do = self::RESTORE;
        }

        return compact('locals', 'globals', 'specials', 'do');
    }
}