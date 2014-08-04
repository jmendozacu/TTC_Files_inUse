<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Guidance
 * @package     Setup
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Setup_Model_Resource_Setup extends Guidance_Setup_Model_Resource_Setup
{
    const US_WEBSITE_CODE  = 'base';
    const US_EN_STORE_CODE = 'default';
    const UK_WEBSITE_CODE  = 'uk';
    const UK_EN_STORE_CODE = 'uk_en';
    const AU_WEBSITE_CODE  = 'au';
    const AU_EN_STORE_CODE = 'au_en';

    /**
     * Returns US website
     *
     * @return Mage_Core_Model_Website
     */
    public function getUsWebsite()
    {
        return Mage::getModel('core/website')->load(self::US_WEBSITE_CODE);
    }

    /**
     * Returns UK website
     *
     * @return Mage_Core_Model_Website
     */
    public function getUkWebsite()
    {
        return Mage::getModel('core/website')->load(self::UK_WEBSITE_CODE);
    }

    /**
     * Returns Australian website
     *
     * @return Mage_Core_Model_Website
     */
    public function getAuWebsite()
    {
        return Mage::getModel('core/website')->load(self::AU_WEBSITE_CODE);
    }

    /**
     * Uploads URL rewrites for old URLs into the database from simple CSV file, separated by ";".
     *
     * @param string $filepath
     * @param string $redirectType
     * @param string $homepageUrl
     */
    public function uploadUrlRewritesFromCsv($filepath, $redirectType = 'RP', $homepageUrl)
    {
        $urls = $this->_prepareUrlRewriteDataFromCsv($filepath, $redirectType, $homepageUrl);
        foreach ($urls as $urlParams) {
            $redirect = Mage::getModel('enterprise_urlrewrite/redirect');
            $redirect->load($urlParams['identifier'], 'identifier');
            if (!$redirect->getId()
                || $redirect->getTargetPath() != $urlParams['target_path']
                || $redirect->getOptions() != $urlParams['options'])
            {
                $redirect->addData($urlParams);
                $redirect->save();
            }
        }
    }

    /**
     * Prepares array for URL rewrite models
     *
     * @param string $filepath
     * @param string $redirectType
     * @param string $homepageUrl
     * @return array
     */
    protected function _prepareUrlRewriteDataFromCsv($filepath, $redirectType = 'RP', $homepageUrl)
    {
        $urlsExploded = explode("\n", file_get_contents($filepath));
        $urls = array();
        foreach ($urlsExploded as $url) {
            $urlExploded = explode(';', trim($url));
            if (!isset($urlExploded[1])) {
                continue;
            }
            $urls[] = array(
                'options' => $redirectType,
                'identifier' => trim(trim($urlExploded[1]), '/'),
                'target_path' => isset($urlExploded[2]) && trim(trim($urlExploded[2]), '/') ?
                    trim(trim($urlExploded[2]), '/') :
                    $homepageUrl,
                'description' => trim($urlExploded[0])
            );
        }
        return $urls;
    }

}