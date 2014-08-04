<?php
class Tgc_Events_Block_Adminhtml_Events_Edit_Tab_Professors extends Mage_Adminhtml_Block_Widget_Grid
{
    
	/**
     * Set grid params
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('events_professors_grid');
        $this->setDefaultSort('professor_id');
        $this->setUseAjax(true);	
        $this->setDefaultFilter(array('in_professors'=>1));
    }
    /**
     * Retirve currently edited professor model
     *
     * @return Tgc_Professor_Model_Professor
     */
    protected function _getProfessor()
    {
        return Mage::registry('current_events_professors');
    }
    /**
     * Add filter
     *
     * @param object $column
     * @return Mage_Adminhtml_Block_Catalog_Product_Edit_Tab_Related
     */
    protected function _addColumnFilterToCollection($column)
    {
        // Set custom filter for in product flag
        if ($column->getId() == 'in_professors')
		{
		    $professorIds = $this->_getSelectedProfessors();
            if (empty($professorIds))
			{
                $professorIds = 0;
            }
            if ($column->getFilter()->getValue())
			{
                $this->getCollection()->addFieldToFilter('professor_id', array('in'=>$professorIds));
            }
			else
			{
                if($professorIds)
				{
                    $this->getCollection()->addFieldToFilter('professor_id', array('nin'=>$professorIds));
                }
            }
        }
		else
		{
            parent::_addColumnFilterToCollection($column);
        }
		
        return $this;
    }

    /**
     * Prepare collection
     *
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareCollection()
    {
		
        $collection = Mage::getModel('profs/professor')->getCollection();

		 if ($this->isReadonly())
		 {
            $professorIds = $this->_getSelectedProfessors();
            if (empty($professorIds))
			{
                $professorIds = array(0);
            }
            $collection->addFieldToFilter('professor_id', array('in'=>$professorIds));
        }

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * Checks when this block is readonly
     *
     * @return boolean
     */
    public function isReadonly()
    {
        return 0;
    }

    /**
     * Add columns to grid
     *
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareColumns()
    {
        if (!$this->isReadonly()) {
            $this->addColumn('in_professors', array(
                'header_css_class'  => 'a-center',
                'type'              => 'checkbox',
                'name'              => 'in_professors',
                'values'            => $this->_getSelectedProfessors(),
                'align'             => 'center',
                'index'             => 'professor_id'
            ));
        }

        $this->addColumn('professor_id', array(
            'header'    => Mage::helper('catalog')->__('ID'),
            'sortable'  => true,
            'width'     => 60,
            'index'     => 'professor_id'
        ));
        $this->addColumn('first_name', array(
            'header'    => Mage::helper('catalog')->__('First Name'),
            'index'     => 'first_name'
        ));

        $this->addColumn('last_name', array(
            'header'    => Mage::helper('catalog')->__('Last Name'),
            'index'     => 'last_name'
        ));
        $this->addColumn('title', array(
            'header' => $this->__('Title'),
            'index'  => 'title',
        ));
        $this->addColumn('qual', array(
            'header' => $this->__('Qualification'),
            'index'  => 'qual',
        ));

        return parent::_prepareColumns();
    }

    /**
     * Rerieve grid URL
     *
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getData('grid_url')
            ? $this->getData('grid_url')
            : $this->getUrl('*/*/professorsGrid', array('_current'=>true));
    }


	 /**
     * Retrieve selected related products
     *
     * @return array
     */
    public function _getSelectedProfessors()
    {
        $professors = $this->getEventsProfessorsRelated();
        if (!is_array($professors)) {
            $professors = array_keys($this->getEventsProfessors());
        }
        return $professors;
    }

	 /**
     * Retrieve related products
     *
     * @return array
     */
    public function getEventsProfessors()
    {
	
		$id = $this->getRequest()->getParam('id');
       	$professorsArr = array();
        foreach (Mage::registry('current_events_professors')->getProfessorIds() as $professor)
		{
           $professorsArr[$professor["professor_id"]] = array('position' => '0');
        }
        return $professorsArr;
    }
	
}