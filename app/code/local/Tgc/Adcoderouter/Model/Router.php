<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Guidance
 * @package     Adcoderouter
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_Adcoderouter_Model_Router extends Mage_Core_Controller_Varien_Router_Abstract
{
    const DATE_NOT_VALID = 'datenotvalid';
    const PAGE_NOT_FOUND = 'The %s page associated with this ad code could not be found.';
    const INVALID_AD_CODE = 'The ad code associated with this url is invalid.';

    protected $path_prefix_categories = array('catalog','category','view','id');
    protected $path_prefix_professors = array('professors','professor','view','id');
    protected $path_prefix_courses = array('catalog','product','view','id');
    protected $path_prefix_cms = array('cms','page','view','page_id');
    protected $path_prefix_dateinvalid = array('boutique');
    protected $path_prefix_adcodeinvalid = array('boutique');

    protected $_listValidPageIds;
    protected $_adCodeRouterConnection;
    protected $_pageNotFoundError;

    private $_isProcessed = false;

    public function __construct()
    {
        $this->_pageNotFoundError['professors'] = sprintf(self::PAGE_NOT_FOUND, 'professor');
        $this->_pageNotFoundError['categories'] = sprintf(self::PAGE_NOT_FOUND, 'category');
        $this->_pageNotFoundError['cms'] = 'The page associated with this ad code could not be found.';
        $this->_pageNotFoundError['course'] = sprintf(self::PAGE_NOT_FOUND, 'course');;
    }

    /**
     * @param Zend_Controller_Request_Http $request
     * @return bool
     */
    public function match(Zend_Controller_Request_Http $request)
    {
        if(Mage::isInstalled()){
            if($match = $this->_helper()->retrieveMatchIfExists()) {
               return $this->mapMatchToRoute($match);  //if match found returns true, true prevents any other routers from executing (only one left is cms router)
            }
        }
    }


    /**
     * @param $match
     * @return bool
     */
    public function mapMatchToRoute($match)
    {
        if ($this->_isProcessed) {
            return false;
        } else {
            $this->_isProcessed = true;
        }

        /**
         * Function determines path for the page type the user is being redirected to.  Page types include category, course (product), cms page, professor.
         */
        $adCodeRedirect = Mage::getModel('adcoderouter/redirects')->load($match);
        if($adCodeRedirect->getSearchExpression()) {
            if($this->_helper()->isAdCodeValid($adCodeRedirect->getAdCode())) {
                $this->_generateListOfValidPageIds(); //creates an array listing all valid page ids for each page type.

                if($courseId = $adCodeRedirect->getCourseId()) {
                    if($productId = $this->_helperTgcCatalog()->getProductIdFromCourseId($courseId)) {
                        $this->setRedirectRequest($this->path_prefix_courses, $productId);
                        return true;
                    } else {
                        $this->addAdcodeRedirectError($this->_pageNotFoundError['course']);
                        return false;
                    }
                }
                if($categoryId = $adCodeRedirect->getCategoryId()) {
                    $this->setRedirectRequest($this->path_prefix_categories, $categoryId);
                    if(!in_array($categoryId, $this->_listValidPageIds['categories'])) {
                        $this->addAdcodeRedirectError($this->_pageNotFoundError['categories']);
                    }
                    return true;
                }
                if($cmsPageId = $adCodeRedirect->getCmsPageId()) {
                    $this->setRedirectRequest($this->path_prefix_cms, $cmsPageId);
                    if(!in_array($cmsPageId, $this->_listValidPageIds['cms'])) {
                        $this->addAdcodeRedirectError($this->_pageNotFoundError['cms']);
                    }
                    return true;
                }
                if($professorId = $adCodeRedirect->getProfessorId()) {
                    $this->setRedirectRequest($this->path_prefix_professors, $professorId);
                    if(!in_array($professorId, $this->_listValidPageIds['professors'])) {
                        $this->addAdcodeRedirectError($this->_pageNotFoundError['professors']);
                    }
                    return true;
                }
            } else {
                $this->setRedirectRequest($this->path_prefix_adcodeinvalid);
                $this->setAdCodeHardRedirectPath();
                $this->addAdcodeRedirectError(self::INVALID_AD_CODE);
                return true;
            }
        }

        if($match == self::DATE_NOT_VALID) {
            $this->setRedirectRequest($this->path_prefix_dateinvalid);
            $this->setAdCodeHardRedirectPath();
            $this->addAdcodeRedirectError(Tgc_Adcoderouter_Helper_Data::ERROR_AD_CODE_EXPIRED);
            return true;
        }

        return false;
    }

    /**
     * @param $pathPrefix
     * @param $id
     * @return bool
     */
    public function setRedirectRequest($pathPrefix, $id = '')
    {
        $request = Mage::app()->getFrontController()->getRequest();
        $request->setModuleName($pathPrefix[0]);

        if(isset($pathPrefix[1]) && $pathPrefix[1]) {
            $request->setControllerName($pathPrefix[1]);
        }

        if(isset($pathPrefix[2]) && $pathPrefix[2]) {
            $request->setActionName($pathPrefix[2]);
        }

        if(isset($pathPrefix[3])  && $id) {
            $request->setParam($pathPrefix[3], $id); //adds querystring tells Magento specific page to go to within a section of Magento (such as category pages, product pages, cms etc)
        }

        return true;
    }

    protected function _generateListOfValidPageIds()
    {
        $this->_adCodeRouterConnection = Mage::getSingleton('core/resource')->getConnection('write');

        $selectValidCmsIds = $this->_adCodeRouterConnection->select()
            ->from('cms_page', array('page_id'))
            ->where('is_active = :is_active');

        $selectValidCategoryIds = $this->_adCodeRouterConnection->select()
            ->from('catalog_category_entity', array('entity_id'));

        $selectValidProfessorIds = $this->_adCodeRouterConnection->select()
            ->from('professor', array('professor_id'));

        $this->_listValidPageIds['cms'] = $this->_adCodeRouterConnection->fetchCol($selectValidCmsIds, array('is_active' => 1));
        $this->_listValidPageIds['categories'] = $this->_adCodeRouterConnection->fetchCol($selectValidCategoryIds);
        $this->_listValidPageIds['professors'] = $this->_adCodeRouterConnection->fetchCol($selectValidProfessorIds);
    }

    public function addAdcodeRedirectError($message)
    {
        $this->_helper()->getRedirectsSession()->addRedirectError($message);
    }

    public function setAdCodeHardRedirectPath()
    {
        $redirectPath = $this->formatHardRedirectRequest();
        $this->_helper()->getRedirectsSession()->setAdCodeHardRedirectPath($redirectPath);
    }

    public function formatHardRedirectRequest()
    {
        $request = Mage::app()->getFrontController()->getRequest();
        $formattedHardRedirectRequest = trim($request->getModuleName() . '/' . $request->getControllerName() . '/' . $request->getActionName(),'/');
        return $formattedHardRedirectRequest;
    }

    protected function _helper()
    {
        return Mage::helper('adcoderouter');
    }

    protected function _helperTgcCatalog()
    {
        return Mage::helper('tgc_catalog');
    }
}