<?php
/**
 * User: mhidalgo
 * Date: 24/04/14
 * Time: 09:39
 */

class Tgc_Catalog_Block_Product_Rightsells extends Mage_Catalog_Block_Product
{
    protected $_helper = null;
    protected $_set = null;
    protected $_setChildren = null;
    protected $_setOptionToChildMap;
    protected $_setChildrenOptionsHtml = '';
    protected $_setChildrenButtonsHtml = '';

    protected $_members = null;
    protected $_membersCount = 0;
    protected $_membersHtml = '';

    protected $_imgWidth = 320;
    protected $_imgHeight = 250;
    protected $_helperImg = null;

    protected $_saveQty = 0;

    protected function _construct() {
        parent::_construct();
        $this->_helper = Mage::helper('tgc_catalog/product_view');
        $this->_helperImg = Mage::helper('infortis/image');
    }

    // FUNCTIONS RELATED TO PRODUCT VIEW SET PRODUCT AND CHILDREN //////////////////////////////////////

    public function setConfigurableAttribute(Mage_Catalog_Model_Product_Type_Configurable_Attribute $attribute)
    {
        $this->setData('configurable_attribute', $attribute);
        $this->_setOptionToChildMap = array();
        $productAttribute = $attribute->getProductAttribute();
        foreach ($this->getSetChildren() as $child) {
            $this->_setOptionToChildMap[$child->getData($productAttribute->getAttributeCode())] = $child;
        }
    }

    public function getConfigurableAttribute()
    {
        if (!$this->hasConfigurableAttribute()) {
            $this->setConfigurableAttribute(
                $this->getSet()->getTypeInstance(true)->getConfigurableAttributes($this->getSet())->getFirstItem()
            );
        }

        return $this->getData('configurable_attribute');
    }

    public function getSet($product = null) {
        if (is_null($this->_set) && !is_null($product)) {
            $this->_set = $this->_helper->getSetFromProduct($product);
        }
        return $this->_set;
    }

    public function getSetChildren() {
        if (is_null($this->_setChildren)) {
            $skipSaleableCheck = Mage::helper('catalog/product')->getSkipSaleableCheck();
            $allProducts       = $this->getSet()->getTypeInstance(true)
                ->getUsedProducts(null, $this->getSet());

            $this->_setChildren = $allProducts;
        }
        return $this->_setChildren;
    }

    public function getOptionsHtml() {
        if ($this->_setChildrenOptionsHtml == '') {

            $both = true;
            if ($this->_setChildrenButtonsHtml != '') {
                $both = false;
            }

            foreach ($this->getConfigurableAttribute()->getPrices() as $optionData) {
                $this->addSetChildToHtml($optionData, $both, true);
            }
        }
        return $this->_setChildrenOptionsHtml;
    }

    public function getButtonsHtml() {
        if ($this->_setChildrenButtonsHtml == '') {

            $both = true;
            if ($this->_setChildrenOptionsHtml != '') {
                $both = false;
            }

            foreach ($this->getConfigurableAttribute()->getPrices() as $optionData) {
                $this->addSetChildToHtml($optionData, $both, false, true);
            }
        }
        return $this->_setChildrenButtonsHtml;
    }

    public function addProductToOptionHtml($optionData) {
        $html = '';
        $html .='<option id="'.$optionData['value_index'].'" value="'.$optionData['value_index'].'">'.$this->escapeHtml($optionData['label']).'</option>';
        $this->_setChildrenOptionsHtml .= $html;
    }

    public function addProductToButtonHtml($optionData) {
        $html = '';
        $attributeId = $this->getConfigurableAttribute()->getAttributeId();
        $inputId = $attributeId . '_' . $optionData['value_index'] . '_format';
        $html .= '<div class="format-block" onclick="setAttributeOption()">';
        $html .=     '<input id="' . $inputId . '" value="'.$optionData['value_index'].'" name="super_attribute[' . $attributeId . ']" type="radio"/>';
        $html .=     '<label for="' . $inputId . '">' . $this->escapeHtml($optionData['label']) . '</label>';
        if (isset($this->_setOptionToChildMap[$optionData['value_index']])) {
            $priceHtml = Mage::helper('core')->currency(
                $this->_setOptionToChildMap[$optionData['value_index']]->getFinalPrice(), true, false
            );
            $html .= '<div class="format-price">' . $priceHtml . '</div>';
        }
        $html .= '</div>';
        $this->_setChildrenButtonsHtml .= $html;
    }

    public function addSetChildToHtml($optionData, $both = true, $option = false, $button = false) {
        if ($both) {
            $this->addProductToOptionHtml($optionData);
            $this->addProductToButtonHtml($optionData);
        } elseif($option) {
            $this->addProductToOptionHtml($optionData);
        } elseif($button) {
            $this->addProductToButtonHtml($optionData);
        }
    }

    // FUNCTIONS RELATED TO PRODUCT VIEW SET MEMBERS //////////////////////////////////////

    public function getMembers() {
        if (is_null($this->_members)) {
            if ($members = $this->getSet()->getSetMembers()) {
                $ids = explode(',',$members);

                $members = Mage::getModel('catalog/product')->getCollection()
                    ->addAttributeToFilter('course_id',array('in' => $ids))
                    ->addAttributeToFilter('sku',array('in' => $ids))
                    ->addStoreFilter()
                    ->addMinimalPrice()
                    ->addFinalPrice()
                    ->addTaxPercents()
                    ->addAttributeToSelect(Mage::getSingleton('catalog/config')->getProductAttributes())
                    ->addUrlRewrite();

                $this->_members = $members;

                $this->_membersCount = count($this->_members);
            }
        }
        return $this->_members;
    }

    public function addMemberToHtml(Mage_Catalog_Model_Product $member)
    {
        $html = '';
        $html .= '<div class="upsell-block">';
        $html .=    '<a href="'.Mage::helper('tgc_catalog')->getSetUrl($member).'">';
        $html .=        '<div class="product-image">';
        $html .=        '<img src="'.$this->_helperImg->getImg($member, $this->_imgWidth, $this->_imgHeight, 'small_image').'" alt="upsell title" />';
        $html .=        '</div>';
        $html .=        '<div class="product-desc">';
        $html .=            '<h3 class="product-name">';
        $html .=                $this->escapeHtml($member->getName());
        $html .=            '</h3>';
        $html .=        '</div>';
        $html .=    '</a>';
        $html .= '</div>';
        $this->_membersCount--;

        if ($this->_membersCount) {
            $html .= '<div class="plus-block"></div>';
        }

        $this->_membersHtml .= $html;
    }

    public function getMembersHtml()
    {
        if ($this->_membersHtml == '') {
            $members = $this->getMembers();
            $this->_membersCount = count($members);

            foreach ($members as $member) {
                $this->addMemberToHtml($member);
            }
        }
        return $this->_membersHtml;
    }

    public function getSaveQty()
    {
        return Mage::helper('tgc_price/calc')->getMaxSaving($this->getSet());
    }
}