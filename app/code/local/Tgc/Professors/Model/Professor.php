<?php
/**
 * Professor model
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category
 * @package
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 *
 * @method string getFirstName()
 * @method string getLastName()
 * @method string getQual() Returns qualification
 * @method string getBio() Returns biography
 * @method int getRank()
 * @method string getQuote()
 * @method string getTitle()
 * @method int getCategoryId()
 * @method string getPhoto()
 * @method string getEmail()
 * @method string getFacebook()
 * @method string getTwitter()
 * @method string getPinterest()
 * @method string getYoutube()
 * @method string getTestimonial()
 * @method Tgc_Professors_Model_Professor setFirstName() setFirstName(string $name)
 * @method Tgc_Professors_Model_Professor setLastName() setLastName(string $name)
 * @method Tgc_Professors_Model_Professor setQual() setQual(string $qualification)
 * @method Tgc_Professors_Model_Professor setBio() setBio(string $biography)
 * @method Tgc_Professors_Model_Professor setRank() setRank(int $rank)
 * @method Tgc_Professors_Model_Professor setQuote() setQuote(string $quote)
 * @method Tgc_Professors_Model_Professor setTitle() setTitle(string $title)
 * @method Tgc_Professors_Model_Professor setCategoryId() setCategoryId(int $categoryId)
 * @method Tgc_Professors_Model_Professor setPhoto() setPhoto($fileName)
 * @method Tgc_Professors_Model_Professor setEmail() setEmail(string $email)
 * @method Tgc_Professors_Model_Professor setFacebook() setFacebook(string $facebookPageUrl)
 * @method Tgc_Professors_Model_Professor setTwitter() setTwitter(string $twitterUrl)
 * @method Tgc_Professors_Model_Professor setPinterest() setPinterest(string $pinterestUrl)
 * @method Tgc_Professors_Model_Professor setYoutube() setYoutube(string $youTubeChannelUrl)
 * @method Tgc_Professors_Model_Professor setTestimonial() setTestimonial(string $testimonials)
 */
class Tgc_Professors_Model_Professor extends Mage_Core_Model_Abstract
{
    private $_almaMaters;
    private $_teachingAt;

    protected $_eventPrefix = 'professor';
    protected $_eventObject = 'professor';

    protected function _construct()
    {
        $this->_init('profs/professor');
    }

    /**
     * Returns true if professor has some contact info
     *
     * @return boolean
     */
    public function hasContactInfo()
    {
        return $this->getEmail() || $this->getFacebook() || $this->getTwitter()
                || $this->getPinterest() || $this->getYoutube();
    }

    /**
     * Returns professor's products
     *
     * @return Tgc_Professors_Model_Resource_Product_Collection
     */
    public function getProducts()
    {
        $products = Mage::getResourceModel('profs/product_collection')->addFilterByProfessor($this);
        $products->addAttributeToSelect(Mage::getSingleton('catalog/config')->getProductAttributes())
            ->addUrlRewrite(0);
        Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($products);
        Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($products);

        return $products;
    }

    /**
     * Returns alma mater IDs
     *
     * @return array<int>
     */
    public function getAlmaMaterIds()
    {
        $key = 'alma_mater_ids';
        $almaMaters = $this->_getData($key);

        if (!is_array($almaMaters)) {
            $almaMaters = $this->getResource()->getAlmaMaterIds($this->getId());
            $this->setData($key, $almaMaters);
        }

        return $almaMaters;
    }

    /**
     * Returns collection of alma maters
     *
     * @return Varien_Data_Collection
     */
    public function getAlmaMaters()
    {
        if (!$this->_almaMaters) {
            $this->_almaMaters = $this->_loadAlmaMaters();
        }

        return $this->_almaMaters;
    }

    /**
     * Loads collection of alma maters
     *
     * @return Varien_Data_Collection|Tgc_Professors_Model_Resource_Institution_Collection
     */
    protected function _loadAlmaMaters()
    {
        return count($this->getAlmaMaterIds())
            ? $this->_createInstitutionCollection()
                ->addFieldToFilter('institution_id', array('in' => $this->getAlmaMaterIds()))
            : new Varien_Data_Collection;
    }

    /**
     * Returns array of institution IDs where professor is teaching
     *
     * @return array<int>
     */
    public function getTeachingAtIds()
    {
        $key = 'teaching_at_ids';
        $teachingAt = $this->_getData($key);

        if (!is_array($teachingAt)) {
            $teachingAt = $this->getResource()->getTeachingAtIds($this->getId());
            $this->setData($key, $teachingAt);
        }

        return $teachingAt;
    }

    /**
     * Returns collection of institutions where professor is teaching
     *
     * @return Varien_Data_Collection|Tgc_Professors_Model_Resource_Institution_Collection
     */
    public function getTeachingAt()
    {
        if (!$this->_teachingAt) {
            $this->_teachingAt = $this->_loadTeachingAt();
        }

        return $this->_teachingAt;
    }

    /**
     * Loads collection of institutions where professor is teaching
     *
     * @return Varien_Data_Collection|Tgc_Professors_Model_Resource_Institution_Collection
     */
    protected function _loadTeachingAt()
    {
        return count($this->getTeachingAtIds())
            ? $this->_createInstitutionCollection()
                ->addFieldToFilter('institution_id', array('in' => $this->getTeachingAtIds()))
            : new Varien_Data_Collection;
    }

    /**
     * Creates institution collection
     *
     * @return Tgc_Professors_Model_Resource_Institution_Collection
     */
    protected function _createInstitutionCollection()
    {
        return Mage::getResourceModel('profs/institution_collection');
    }

    /**
     * Returns true if professor has photo
     *
     * @return boolean
     */
    public function hasPhoto()
    {
        return (bool)$this->_getData('photo');
    }

    /**
     * Returns URL of photo
     *
     * @return Tgc_Professors_Helper_Image
     */
    public function getPhoto()
    {
        return Mage::helper('profs/image')->init($this->_getData('photo'));
    }

    public function load($id, $field = null)
    {
        if (null === $field && !$this->_isInteger($id)) {
            $field = 'url_key';
        }

        return parent::load($id, $field);
    }

    private function _isInteger($value)
    {
        return is_int($value) || (string)(int)$value === $value;
    }

    protected function _afterSave()
    {
       $this->_saveAlmaMaters();
       $this->_saveTeachingAt();
    }

    private function _saveAlmaMaters()
    {
        $almaMaters = $this->_getData('alma_mater_ids');
        if (is_array($almaMaters)) {
            $this->getResource()->saveAlmaMaterIds($this->getId(), $almaMaters);
        }
    }

    private function _saveTeachingAt()
    {
        $teachingAt = $this->_getData('teaching_at_ids');
        if (is_array($teachingAt)) {
            $this->getResource()->saveTeachingAtIds($this->getId(), $teachingAt);
        }
    }

}