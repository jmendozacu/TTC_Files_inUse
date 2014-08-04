<?php
class Infortis_UltraMegamenu_Block_Navigation extends Mage_Catalog_Block_Navigation
{

    protected $x0b = false;
    protected $x0c = false;
    protected $x0d = '';
    protected $x0e = null;

    public function getCacheKeyInfo()
    {
        $x0f                   = array('CATALOG_NAVIGATION', Mage::app()->getStore()->getId(), Mage::getDesign()->getPackageName(), Mage::getDesign()->getTheme('template'), Mage::getSingleton('customer/session')->getCustomerGroupId(), 'template' => $this->getTemplate(), 'name' => $this->getNameInLayout(), $this->getCurrenCategoryKey(), Mage::helper('ultramegamenu')->getIsOnHome(), (int)Mage::app()->getStore()->isCurrentlySecure(),);
        $x10                   = $x0f;
        $x0f                   = array_values($x0f);
        $x0f                   = implode('|', $x0f);
        $x0f                   = md5($x0f);
        $x10['category_path']  = $this->getCurrenCategoryKey();
        $x10['short_cache_id'] = $x0f;
        return $x10;
    }

    protected function _renderCategoryMenuItemHtml($x11, $x12 = 0, $x13 = false, $x14 = false, $x15 = false, $x16 = '', $x17 = '', $x18 = false)
    {
        if (!$x11->getIsActive()) {
            return '';
        }
        $x19 = array();
        if (Mage::helper('catalog/category_flat')->isEnabled()) {
            $x1a = (array)$x11->getChildrenNodes();
            $x1b = count($x1a);
        } else {
            $x1a = $x11->getChildren();
            $x1b = $x1a->count();
        }
        $x1c = ($x1a && $x1b);
        $x1d = array();
        foreach ($x1a as $x1e) {
            if ($x1e->getIsActive()) {
                $x1d[] = $x1e;
            }
        }
        $x1f = count($x1d);
        $x20 = ($x1f > 0);
        $x21 = Mage::helper('ultramegamenu');
        $x22 = Mage::getModel('catalog/category')->load($x11->getId());
        $x23 = false;
        if ($this->_isWide) {
            $x23 = $x20;
            if ($x21->getCfg('widemenu/show_if_no_children')) {
                $x23 = true;
            }
        }
        $x24 = array();
        $x25 = array();
        $x26 = false;
        $x27 = 0;
        if ($x12 == 0 && $this->_isAccordion == false && $x23) {
            $x28 = $this->_getCatBlock($x22, 'umm_cat_block_right');
            $x29 = 6;
            if ($x2a = $x22->getData('umm_cat_block_proportions')) {
                $x2a = explode("\057", $x2a);
                $x2b = $x2a[0];
                $x2c = $x2a[1];
            } else {
                $x2b = 4;
                $x2c = 2;
            }
            $x27 = $x2b + $x2c;
            if (empty($x28)) {
                $x2b += $x2c;
                $x2c = 0;
                $x2d = 'grid12-12';
            } elseif (!$x20) {
                $x2c += $x2b;
                $x2b = 0;
                $x2e = 'grid12-12';
            } else {
                $x2f = 12 / $x27;
                $x2d = 'grid12-' . ($x2b * $x2f);
                $x2e = 'grid12-' . ($x2c * $x2f);
            }
            $x27 = $x2b + $x2c;
            $x30 = '';
            if ($x30 = $this->_getCatBlock($x22, 'umm_cat_block_top')) {
                $x24[] = '<div class="nav-block nav-block-top grid-full std">';
                $x24[] = $x30;
                $x24[] = '</div>';
            }
            if ($x20) {
                $x31   = 'itemgrid itemgrid-' . $x2b . 'col';
                $x24[] = '<div class="nav-block nav-block-center ' . $x2d . ' ' . $x31 . '">';
                $x25[] = '</div>';
            }
            if ($x28) {
                $x25[] = '<div class="nav-block nav-block-right std ' . $x2e . '">';
                $x25[] = $x28;
                $x25[] = '</div>';
            }
            if ($x30 = $this->_getCatBlock($x22, 'umm_cat_block_bottom')) {
                $x25[] = '<div class="nav-block nav-block-bottom grid-full std">';
                $x25[] = $x30;
                $x25[] = '</div>';
            }
            if (!empty($x24) || !empty($x25)) $x26 = true;
        }
        $x32   = array();
        $x32[] = 'level' . $x12;
        $x32[] = 'nav-' . $this->_getItemPosition($x12);
        if ($this->isCategoryActive($x11)) {
            $x32[] = 'active';
        }
        $x33 = '';
        if ($x15 && $x16) {
            $x32[] = $x16;
            $x33   = ' class="' . $x16 . '"';
        }
        if ($x14) {
            $x32[] = 'first';
        }
        if ($x13) {
            $x32[] = 'last';
        }
        $x34 = ($x20 || $x26) ? true : false;
        if ($x34) {
            $x32[] = 'parent';
        }
        if ($x12 == 1 && $this->_isAccordion == false && $this->_isWide) {
            $x32[] = 'item';
        }
        $x35 = array();
        if (count($x32) > 0) {
            $x35['class'] = implode(' ', $x32);
        }
        if ($x20 && !$x18) {
            $x35['onmouseover'] = 'toggleMenu(this,1)';
            $x35['onmouseout']  = 'toggleMenu(this,0)';
        }
        $x36 = '<li';
        foreach ($x35 as $x37 => $x38) {
            $x36 .= ' ' . $x37 . '="' . str_replace('"', '\"', $x38) . '"';
        }
        $x36 .= '>';
        $x19[] = $x36;
        if ($x12 == 1 && $this->_isAccordion == false && $this->_isWide) {
            if ($x30 = $this->_getCatBlock($x22, 'umm_cat_block_top')) {
                $x19[] = '<div class="nav-block nav-block-level1-top std">';
                $x19[] = $x30;
                $x19[] = '</div>';
            }
        }
        $x39   = $this->_getCategoryLabelHtml($x22, $x12);
        $x19[] = '<a href="' . $this->getCategoryUrl($x11) . '"' . $x33 . '>';
        $x19[] = '<span>' . $this->escapeHtml($x11->getName()) . $x39 . '</span>';
        $x19[] = '</a>';
        $x3a   = '';
        $x3b   = 0;
        foreach ($x1d as $x1e) {
            $x3a .= $this->_renderCategoryMenuItemHtml($x1e, ($x12 + 1), ($x3b == $x1f - 1), ($x3b == 0), false, $x16, $x17, $x18);
            $x3b++;
        }
        if ($x12 == 0 && $this->_isAccordion == false && $this->_isWide) {
            $x17 = 'level0-wrapper dropdown-' . $x27 . 'col';
        }
        if (!empty($x3a) || $x26) {
            if ($this->_isAccordion == true) $x19[] = '<span class="opener">&nbsp;</span>';
            if ($x17) {
                $x19[] = '<div class="' . $x17 . '">';
            }
            $x19[] = implode("", $x24);
            if (!empty($x3a)) {
                $x19[] = '<ul class="level' . $x12 . '">';
                $x19[] = $x3a;
                $x19[] = '</ul>';
            }
            $x19[] = implode("", $x25);
            if ($x17) {
                $x19[] = '</div>';
            }
        }
        if ($x12 == 1 && $this->_isAccordion == false && $this->_isWide) {
            if ($x30 = $this->_getCatBlock($x22, 'umm_cat_block_bottom')) {
                $x19[] = '<div class="nav-block nav-block-level1-bottom std">';
                $x19[] = $x30;
                $x19[] = '</div>';
            }
        }
        $x19[] = '</li>';
        $x19   = implode("\n", $x19);
        return $x19;
    }

    public function renderCategoriesMenuHtml($x3c = false, $x12 = 0, $x16 = '', $x17 = '')
    {
        $this->_isAccordion = $x3c;
        $this->_isWide      = Mage::helper('ultramegamenu')->getCfg('mainmenu/wide_menu');
        $x3d                = array();
        foreach ($this->getStoreCategories() as $x1e) {
            if ($x1e->getIsActive()) {
                $x3d[] = $x1e;
            }
        }
        $x3e = count($x3d);
        $x3f = ($x3e > 0);
        if (!$x3f) {
            return '';
        }
        $x19 = '';
        $x3b = 0;
        foreach ($x3d as $x11) {
            $x19 .= $this->_renderCategoryMenuItemHtml($x11, $x12, ($x3b == $x3e - 1), ($x3b == 0), true, $x16, $x17, true);
            $x3b++;
        }
        return $x19;
    }

    protected function _getCatBlock($x22, $x40)
    {
        if (!$this->_tplProcessor) {
            $this->_tplProcessor = Mage::helper('cms')->getBlockTemplateProcessor();
        }
        return $this->_tplProcessor->filter(trim($x22->getData($x40)));
    }

    protected function _getCategoryLabelHtml($x22, $x12)
    {
        $x41 = $x22->getData('umm_cat_label');
        if ($x41) {
            $x42 = trim(Mage::helper('ultramegamenu')->getCfg('category_labels/' . $x41));
            if ($x42) {
                if ($x12 == 0) {
                    return ' <span class="cat-label cat-label-' . $x41 . ' pin-bottom">' . $x42 . '</span>';
                } else {
                    return ' <span class="cat-label cat-label-' . $x41 . '">' . $x42 . '</span>';
                }
            }
        }
        return '';
    }
}