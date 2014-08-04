<?php

class Tgc_Adcoderouter_Block_Adminhtml_Adcoderedirectlist_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();

        $this->setDefDir('asc');
        $this->setDefaultSort('id');
        $this->setId('id');
        $this->setSaveParametersInSession(true);
        $this->setColumnRenderers(array(
            'customdatetimestart' => 'adcoderouter/adminhtml_widget_grid_column_renderer_customdatetimestart',
            'customdatetimeend' => 'adcoderouter/adminhtml_widget_grid_column_renderer_customdatetimeend',
        ));
        $this->setColumnFilters(array(
            'customdatetimestart' => 'adminhtml/widget_grid_column_filter_datetime',
            'customdatetimeend' => 'adminhtml/widget_grid_column_filter_datetime',
        ));
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('adcoderouter/redirects_collection');
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('id', array(
            'header' => 'id col',
            'index'  => 'id',
            'width'  => '50px',
        ));

        $this->addColumn('search_expression', array(
            'header' => 'Request Path',
            'index'  => 'search_expression',
        ));

        $this->addColumn('start_date', array(
            'header' => 'Start Date',
            'type'   => 'customdatetimestart',
            'index'  => 'start_date',
            'format' => 'Y-MM-dd',
        ));

        $this->addColumn('end_date', array(
            'header' => 'End Date',
            'type'   => 'customdatetimeend',
            'index'  => 'end_date',
            'format' => 'Y-MM-dd',
        ));

        $this->addColumn('ad_code', array(
            'header' => 'Ad Code',
            'index'  => 'ad_code',
        ));

        $this->addColumn('ad_type', array(
            'header' => 'Ad Type',
            'index'  => 'ad_type',
            'type'  => 'options',
            'options' => Mage::getSingleton('adcoderouter/field_source_adtype')->getAllOptions(),
        ));

        $this->addColumn('querystring', array(
            'header' => 'Redirect Querystring',
            'index'  => 'redirect_querystring',
        ));

        $this->addColumn('pid', array(
            'header' => 'Welcome Message',
            'index'  => 'pid',
        ));

        $this->addColumn('store_id', array(
            'header' => 'Store Id',
            'index'  => 'store_id',
        ));

        $this->addColumn('description', array(
            'header' => 'Description',
            'index'  => 'description',
        ));

        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }
}