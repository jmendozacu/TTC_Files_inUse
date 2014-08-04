<?php
/**
 * Digital Library
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DigitalLibrary
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_DigitalLibrary_Block_Adminhtml_AccessRights_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();

        $this->setId('accessRightsGrid');
        $this->_defaultLimit = 200;
        $this->setDefaultSort('id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        /** @var $collection Tgc_DigitalLibrary_Model_Resource_AccessRights_Collection */
        $collection = Mage::getModel('tgc_dl/accessRights')->getCollection();
        $collection->getSelect()
            ->joinLeft(array('customer' => 'customer_entity'),
                'main_table.web_user_id = customer.web_user_id',
                array('dax_customer_id'))
            ->joinLeft(array('product' => 'catalog_product_entity'),
                'main_table.course_id = product.entity_id',
                array())
            ->joinLeft(array('eav' => 'eav_attribute'),
                'product.entity_type_id = eav.entity_type_id AND eav.attribute_code = \'course_id\'',
                array())
            ->joinLeft(array('attribute' => 'catalog_product_entity_varchar'),
                'eav.entity_type_id = attribute.entity_type_id AND eav.attribute_id = attribute.attribute_id AND product.entity_id = attribute.entity_id AND attribute.store_id = 0',
                array('course_id_attribute' => 'attribute.value'));
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('entity_id', array(
            'header'    => Mage::helper('tgc_dl')->__('Access ID'),
            'align'     => 'left',
            'width'     => '50px',
            'index'     => 'entity_id',
        ));

        $this->addColumn('course_id', array(
            'header'    => Mage::helper('tgc_dl')->__('Product ID'),
            'align'     => 'left',
            'width'     => '50px',
            'index'     => 'course_id',
        ));

        $this->addColumn('course_id_attribute', array(
            'header'    => Mage::helper('tgc_dl')->__('Course ID'),
            'align'     => 'left',
            'width'     => '50px',
            'index'     => 'course_id_attribute',
            'filter_index' => 'attribute.value',
        ));

        $this->addColumn('format', array(
            'header'    => Mage::helper('tgc_dl')->__('Format'),
            'align'     => 'left',
            'width'     => '50px',
            'index'     => 'format',
            'type'      => 'options',
            'options'   => Mage::getModel('tgc_dl/source_format')->toOptionArray(),
        ));

        $this->addColumn('web_user_id', array(
            'header'       => Mage::helper('tgc_dl')->__('Web User Id'),
            'align'        => 'left',
            'width'        => '50px',
            'index'        => 'web_user_id',
            'filter_index' => 'main_table.web_user_id',
        ));

        $this->addColumn('dax_customer_id', array(
            'header'    => Mage::helper('tgc_dl')->__('Dax Customer Id'),
            'align'     => 'left',
            'width'     => '50px',
            'index'     => 'dax_customer_id',
            'filter_index' => 'customer.dax_customer_id',
        ));

        $this->addColumn('date_purchased', array(
            'header'    => Mage::helper('tgc_dl')->__('Date Purchased'),
            'width'     => '50px',
            'index'     => 'date_purchased',
            'type'      => 'date',
            'gmtoffset' => true,
        ));

        $this->addColumn('is_downloadable', array(
            'header'    => Mage::helper('tgc_dl')->__('Downloadable'),
            'align'     => 'left',
            'width'     => '50px',
            'index'     => 'is_downloadable',
            'type'      => 'options',
            'options'   => array('0' => 'No', '1' => 'Yes'),
        ));

        $this->addColumn('digital_transcript_purchased', array(
            'header'    => Mage::helper('tgc_dl')->__('Digital Transcript Purchased'),
            'align'     => 'left',
            'width'     => '50px',
            'index'     => 'digital_transcript_purchased',
            'type'      => 'options',
            'options'   => array('0' => 'No', '1' => 'Yes'),
        ));

        $this->addColumn('action', array(
            'header'    =>  Mage::helper('tgc_dl')->__('Action'),
            'width'     => '60',
            'type'      => 'action',
            'getter'    => 'getId',
            'actions'   => array(
                array(
                    'caption'   => Mage::helper('tgc_dl')->__('Edit'),
                    'url'       => array('base' => '*/*/edit'),
                    'field'     => 'id'
                )
            ),
            'filter'    => false,
            'sortable'  => false,
            'index'     => 'stores',
            'is_system' => true,
        ));

        $this->addExportType('*/*/exportCsv', Mage::helper('tgc_dl')->__('CSV'));
        $this->addExportType('*/*/exportXml', Mage::helper('tgc_dl')->__('XML'));

        return parent::_prepareColumns();
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('id');
        $this->getMassactionBlock()->setFormFieldName('accessRights');

        $this->getMassactionBlock()->addItem('delete', array(
            'label'   => Mage::helper('tgc_dl')->__('Delete access right(s)'),
            'url'     => $this->getUrl('*/*/massDelete'),
            'confirm' => Mage::helper('tgc_dl')->__('Really delete the selected access right(s)?')
        ));

        return $this;
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }
}
