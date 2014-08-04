<?php
/**
 * DataMart integration
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DataMart
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Datamart_CoursesController extends Mage_Core_Controller_Front_Action
{
    const CATEGORY_PARAM = 'sa';
    const CATEGORY_PARAM_BUFFET = 'lid';
    const ADCODE_PARAM   = 'ai';
    const MEDIA_CODE_PARAM = 'mc';
    const MESSAGE_AD_CODE_INACTIVE = 'Your request could not be completed, because the ad code is not active.';
    const MESSAGE_AD_CODE_HAS_NOPAGE = 'Your request could not be completed, because no page is associated with this ad code.';
    protected $_adCodeRedirectFailRoute = 'tgc_boutique';

    public function specialofferAction()
    {
        $adCode = $this->getRequest()->getParam(self::ADCODE_PARAM);
        if (!is_null($adCode)) {
            if(!$this->_helperAdcoderouter()->isAdCodeValid($adCode)) {
                $this->_helperAdcoderouter()->getRedirectsSession()->addRedirectError(Tgc_Adcoderouter_Model_Router::INVALID_AD_CODE);
                return $this->_redirect($this->_adCodeRedirectFailRoute);
            }

            $adCode = Mage::getModel('tgc_price/adCode')->load($adCode);
            if ($adCode->getId()) {
                if ((string)$adCode->getActiveFlag() == '0') {
                    $this->_helperAdcoderouter()->getRedirectsSession()->addRedirectError(self::MESSAGE_AD_CODE_INACTIVE);
                    return $this->_redirect($this->_adCodeRedirectFailRoute);
                }
                Mage::register('ad_code', $adCode->getId());
                $this->_setAdcode($adCode);
            }
        }

        $landingPageCategory = $this->getRequest()->getParam(self::CATEGORY_PARAM);
        if (!$landingPageCategory
            || !$this->_isCategoryParamValid($landingPageCategory, Tgc_Datamart_Model_Source_LandingPage_Type::EMAIL_VALUE)) {
            $this->_helperAdcoderouter()->getRedirectsSession()->addRedirectError(self::MESSAGE_AD_CODE_HAS_NOPAGE);
            return $this->_redirect($this->_adCodeRedirectFailRoute);
        }
        $landingPageDesign = $this->_getPageDesign(
            $landingPageCategory,
            Tgc_Datamart_Model_Source_LandingPage_Type::EMAIL_VALUE
        );
        Mage::register('landing_page_category', $landingPageCategory);
        Mage::register('landing_page_design', $landingPageDesign);

        $rootCategory = Mage::getModel('catalog/category')->load((int)Mage::app()->getStore()->getRootCategoryId());
        $rootCategory->setDefaultSortBy('position');
        Mage::register('current_category', $rootCategory);

        $update = $this->getLayout()->getUpdate();
        $update->addHandle('default');
        $update->addHandle('catalog_category_layered');

        $this->addActionLayoutHandles();
        $this->loadLayoutUpdates();

        $this->generateLayoutXml()->generateLayoutBlocks();
        $this->renderLayout();
    }

    public function courseBuffetOfferAction()
    {
        $adCode = $this->getRequest()->getParam(self::ADCODE_PARAM);
        if (!is_null($adCode)) {
            $adCode = Mage::getModel('tgc_price/adCode')->load($adCode);
            if ($adCode->getId()) {
                if ((string)$adCode->getActiveFlag() == '0') {
                    return $this->_redirect('tgc_boutique');
                }
                Mage::register('ad_code', $adCode->getId());
                $this->_setAdcode($adCode);
            }
        }

        $landingPageCategory = $this->getRequest()->getParam(self::CATEGORY_PARAM_BUFFET);
        if (!$landingPageCategory
            || !$this->_isCategoryParamValid($landingPageCategory, Tgc_Datamart_Model_Source_LandingPage_Type::BUFFET_VALUE)) {
            return $this->norouteAction();
        }
        $landingPageDesign = $this->_getPageDesign(
            $landingPageCategory,
            Tgc_Datamart_Model_Source_LandingPage_Type::BUFFET_VALUE
        );
        Mage::register('landing_page_category', $landingPageCategory);
        Mage::register('landing_page_design', $landingPageDesign);

        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * Radio Landing Page action
     */
    public function radioOfferAction()
    {
        $mediaCodeParam = trim($this->getRequest()->getParam(self::MEDIA_CODE_PARAM));
        $mediaCode = Mage::getModel('tgc_datamart/emailLanding_mediacode');
        if ($mediaCodeParam) {
            $mediaCode->loadByMediaCode($mediaCodeParam);
            if (!$mediaCode->getId()
                && ($defaultMediaCode = Mage::helper('tgc_datamart/config')->getRadioLandingDefaultMediaCode())
                && $defaultMediaCode != $mediaCodeParam) {
                return $this->_redirect('*/*/*', array(self::MEDIA_CODE_PARAM => $defaultMediaCode));
            }
        }

        if (!$mediaCode->getId() ||
            !$this->_isCategoryParamValid($mediaCode->getMediaCode(), Tgc_Datamart_Model_Source_LandingPage_Type::RADIO_VALUE)) {
            return $this->norouteAction();
        }

        $this->_setAdcode($mediaCode->getAdCode());

        Mage::register('media_code_model', $mediaCode);
        Mage::register('landing_page_category', $mediaCode->getMediaCode());
        $landingPageDesign = $this->_getPageDesign(
            $mediaCode->getMediaCode(),
            Tgc_Datamart_Model_Source_LandingPage_Type::RADIO_VALUE
        );
        Mage::register('landing_page_design', $landingPageDesign);

        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * Apply adCode to customer session
     *
     * @param string $adCode
     */
    private function _setAdcode($adCode)
    {
        /** Tgc_Price_AdCode_Processor */
        Mage::helper('tgc_price')->getAdCodeProcessor()
            ->changePrices($adCode);
    }

    /**
     * Get the page design for a given category
     *
     * @param string $category
     * @param string $pageType
     * @return Tgc_Datamart_Model_EmailLanding_Design
     */
    private function _getPageDesign($category, $pageType)
    {
        $pages = Mage::getModel('tgc_datamart/emailLanding_design')
            ->getCollection()
            ->addFieldToFilter('category', array('eq' => $category))
            ->addFieldToFilter('landing_page_type', array('eq' => $pageType));
        if (count($pages) > 0) {
            return $pages->getFirstItem();
        }

        return Mage::getModel('tgc_datamart/emailLanding_design');
    }

    /**
     * Check that category has assigned courses
     *
     * @param integer $pageType
     * @return boolean
     */
    protected function _isCategoryParamValid($category, $pageType)
    {
        return (bool) Mage::getModel('tgc_datamart/emailLanding')->getCollection()
            ->addFieldToFilter('category', $category)
            ->addFieldToFilter('date_expires', array('gt' => Mage::getModel('core/date')->gmtDate()))
            ->addFieldToFilter('landing_page_type', $pageType)
            ->getSize();
    }

    /**
     * Proceed to checkout buffet page action
     */
    public function proceedToCheckoutAction()
    {
        $cart = Mage::getSingleton('checkout/cart');
        $formsData = $this->getRequest()->getParam('add_to_cart_form_data');
        $errorOccured = false;
        if ($formsData && is_array($formsData)) {
            $session = Mage::getSingleton('checkout/session');
            foreach ($formsData as $formData) {
                try {
                    $params = array();
                    parse_str($formData, $params);
                    if (!isset($params['product'])) {
                        continue;
                    }

                    if (isset($params['qty'])) {
                        $filter = new Zend_Filter_LocalizedToNormalized(
                            array('locale' => Mage::app()->getLocale()->getLocaleCode())
                        );
                        $params['qty'] = $filter->filter($params['qty']);
                    }

                    $product = Mage::getModel('catalog/product')
                        ->setStoreId(Mage::app()->getStore()->getId())
                        ->load((int) $params['product']);
                    if (!$product->getId()) {
                        continue;
                    }

                    $cart->addProduct($product, $params);
                } catch (Mage_Core_Exception $e) {
                    if ($session->getUseNotice(true)) {
                        $session->addNotice(Mage::helper('core')->escapeHtml($e->getMessage()));
                    } else {
                        $messages = array_unique(explode("\n", $e->getMessage()));
                        foreach ($messages as $message) {
                            $session->addError(Mage::helper('core')->escapeHtml($message));
                        }
                    }
                    $errorOccured = true;
                } catch (Exception $e) {
                    $session->addException($e, $this->__('Cannot add the item to shopping cart.'));
                    Mage::logException($e);
                    $errorOccured = true;
                }
            }

            $cart->save();
            $session->setCartWasUpdated(true);
        }

        if (!$errorOccured && Mage::helper('checkout')->canOnepageCheckout()) {
            $this->_redirect('checkout/onepage');
        } else {
            $this->_redirect('checkout/cart');
        }
    }

    protected function _helperAdcoderouter()
    {
        return Mage::helper('adcoderouter');
    }
}
