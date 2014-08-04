<?php

class Tgc_Adcoderouter_Helper_Data extends Mage_Core_Helper_Abstract
{
    const ERROR_AD_CODE_EXPIRED = 'The page you requested has expired.';

    const ERROR_AD_CODE_NOT_YET_STARTED = 'The page you requested has expired, because the ad code is not<br /> currently active, and will be at a later date.';

    public function retrieveValidAdCode()
    {
        $match= false;
        if($match = $this->retrieveMatchIfExists()) {
            if($match == Tgc_Adcoderouter_Model_Router::DATE_NOT_VALID) {
                $match = false;
            } else {
                $urlRedirectMatch = Mage::getModel('adcoderouter/redirects')->load($match);
                $match = $this->_getRequest()->getParam(
                    $urlRedirectMatch->getAdCodeFromParam(),
                    $urlRedirectMatch->getAdCode()
                );
            }
        }

        return $match;
    }

    public function retrieveMatchIfExists()
    {
        $adcodeRedirects = Mage::getModel('adcoderouter/redirects')->getCollection();
        $searchExpression = trim($this->_getRequest()->getOriginalPathInfo(), '/');
        $adcodeRedirects->addFieldToFilter('search_expression', $searchExpression);
        $adcodeRedirects->addFieldToFilter('store_id', Mage::app()->getStore()->getId());
        $currentDate = date('Y-m-d');

        $numberMatchesBeforeDateFilterApplied = $adcodeRedirects->count();

        $adcodeRedirects->addFieldToFilter('start_date',array(
            'date'      => true,
            'to'        => $currentDate,
        ));
        $adcodeRedirects->addFieldToFilter('end_date',array(
            'date'      => true,
            'from'        => $currentDate,
        ));

        $numberMatches = $adcodeRedirects->clear()->count();

        if($numberMatches > 0) {
            if($numberMatches > 1) {
                Mage::log('An url matched with more than one redirect when this request path was requested: ' . $searchExpression . ' .  There should never be more than one.');
            } else {
                $adcodeRedirect = $adcodeRedirects->getFirstItem();
                if($adCodeRedirectId = $adcodeRedirect->getId()) {
                    if($this->isDateRangeValid($adcodeRedirect->getStartDate(), $adcodeRedirect->getEndDate())) {
                        $this->getRedirectsSession()->setAdCodeRedirectId($adCodeRedirectId);
                        return $adCodeRedirectId;
                    }
                }
            }
        } elseif($numberMatchesBeforeDateFilterApplied > 0) {
            return Tgc_Adcoderouter_Model_Router::DATE_NOT_VALID;
        }
    }

    public function isDateRangeValid($startDate, $endDate)
    {
        //end Ts corresponds to the very beginning of the day, the 1st second of the day to be exact, adding time so that ad code good for entire day.
        $startTs = (!$startDate) ? '' : $startTs = strtotime($startDate);
        $endTs = (!$endDate) ? '' : $endDate = strtotime($endDate) + 86400;

        //$nowZendDate = new Zend_Date(strtotime('now'),'', Mage::app()->getLocale()->getLocale());
        $nowZendDate = new Zend_Date(Mage::app()->getLocale()->getLocale());
        $nowZendDate->setTimezone(Mage::app()->getStore()->getConfig(Mage_Core_Model_Locale::XML_PATH_DEFAULT_TIMEZONE));
        $now = strtotime($nowZendDate->toString('y-MM-dd'));

        $isDateRangeValid = false;
        if(!$startTs && !$endTs) {
            $isDateRangeValid = true;
        } elseif(!$startTs && $now <= $endTs) {
            $isDateRangeValid = true;
        } elseif(!$startTs && $now > $endTs) {
            $this->getRedirectsSession()->addRedirectError(self::ERROR_AD_CODE_EXPIRED);
        } elseif(!$endTs && $now >= $startTs) {
            $isDateRangeValid = true;
        } elseif(!$endTs && $now < $startTs) {
            $this->getRedirectsSession()->addRedirectError(self::ERROR_AD_CODE_NOT_YET_STARTED);
        } elseif($now < $endTs && $now >= $startTs) {
            $isDateRangeValid = true;
        } elseif($startTs && $endTs && $now > $endTs) {
            $this->getRedirectsSession()->addRedirectError(self::ERROR_AD_CODE_EXPIRED);
        } elseif($startTs && $endTs && $now < $startTs) {
            $this->getRedirectsSession()->addRedirectError(self::ERROR_AD_CODE_NOT_YET_STARTED);
        }

        return $isDateRangeValid;
    }

    public function retrieveAdCodeRedirectObject()
    {

        if(!Mage::registry('ad_code_redirect_object')) {
            $adCodeRedirectId = $this->getRedirectsSession()->getAdCodeRedirectId();
            $validAdCode = $this->retrieveValidAdCode();

            if($adCodeRedirectId && $validAdCode) {
                $urlRedirectMatch = Mage::getModel('adcoderouter/redirects')->load($adCodeRedirectId);
                Mage::register('ad_code_redirect_object', $urlRedirectMatch);
            } else {
                $urlRedirectMatch = "nomatch";
                Mage::register('ad_code_redirect_object', $urlRedirectMatch);
            }
        }

        return Mage::registry('ad_code_redirect_object');
    }

    public function getAdCodeRedirectValue($fieldName)
    {
        $value = null;

        $adCodeRedirectObject = $this->retrieveAdCodeRedirectObject();

        if($adCodeRedirectObject instanceof Tgc_Adcoderouter_Model_Redirects) {
            $value = $adCodeRedirectObject->getData($fieldName);
        }

        return $value;
    }

    public function isSpaceAd()
    {
        return $this->getAdCodeRedirectValue('ad_type') == Tgc_Adcoderouter_Model_Field_Source_Adtype::SPACE_AD_ID? true : false;
    }

    public function isDrtvAd()
    {
        return $this->_productGalleryImageHelper()->shouldDisplayDRTVimage() ? true : false;
    }

    public function isDrtvAdType()
    {
        return $this->getAdCodeRedirectValue('ad_type') == Tgc_Adcoderouter_Model_Field_Source_Adtype::DRTV_AD_ID ? true : false;
    }

    public function isAdCodeValid($adCode = '')
    {
        $isAdCodeValid = false;

        $connection = Mage::getSingleton('core/resource')->getConnection('write');

        if($adCode) {
            $adCodeTable = $this->_catalogHelper()->getTable('tgc_price/adCode');
            $customerGroupTable = $this->_catalogHelper()->getTable('customer/customer_group');

            $selectAdCode = $connection->select()
                ->from(array('a' => $adCodeTable), array('code','customer_group_id'))
                ->joinLeft(array('c' => $customerGroupTable), 'a.customer_group_id = c.customer_group_id')
                ->where('a.code = :code');

            $stmt = $connection->query($selectAdCode, array('code'=> $adCode));
            if($stmt->rowCount() == 1) {
                $isAdCodeValid = true;
            }
        }

        return $isAdCodeValid;
    }

    public function getRedirectsSession()
    {
        return Mage::getSingleton('adcoderouter/redirects_session');
    }

    protected function _catalogHelper()
    {
        return Mage::helper('enterprise_catalog/product');
    }

    /**
     * Returns the product gallery image helper
     * @return Tgc_ProductGallery_Helper_Image
     */
    protected function _productGalleryImageHelper()
    {
        return Mage::helper('tgc_productgallery/image');
    }
}