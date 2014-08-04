<?php
/**
 * Digital Library
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DigitalLibrary
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_DigitalLibrary_Block_Adminhtml_CrossPlatformResume_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();

        $this->setId('crossPlatformResumeGrid');
        $this->_defaultLimit = 200;
        $this->setDefaultSort('id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        /** @var $collection Tgc_DigitalLibrary_Model_Resource_CrossPlatformResume_Collection */
        $collection = Mage::getModel('tgc_dl/crossPlatformResume')->getCollection();
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('entity_id', array(
            'header'    => Mage::helper('tgc_dl')->__('Resume ID'),
            'align'     => 'left',
            'width'     => '50px',
            'index'     => 'entity_id',
        ));

        $this->addColumn('lecture_id', array(
            'header'    => Mage::helper('tgc_dl')->__('Lecture ID'),
            'align'     => 'left',
            'width'     => '50px',
            'index'     => 'lecture_id',
        ));

        $this->addColumn('web_user_id', array(
            'header'    => Mage::helper('tgc_dl')->__('Web User ID'),
            'align'     => 'left',
            'width'     => '50px',
            'index'     => 'web_user_id',
        ));

        $this->addColumn('progress', array(
            'header'    => Mage::helper('tgc_dl')->__('Progress'),
            'align'     => 'left',
            'width'     => '50px',
            'index'     => 'progress',
        ));

        $this->addColumn('download_date', array(
            'header'    => Mage::helper('tgc_dl')->__('Download Date'),
            'width'     => '50px',
            'index'     => 'download_date',
            'type'      => 'date',
        ));

        $this->addColumn('stream_date', array(
            'header'    => Mage::helper('tgc_dl')->__('Stream Date'),
            'width'     => '50px',
            'index'     => 'stream_date',
            'type'      => 'date',
        ));

        $this->addColumn('format', array(
            'header'    => Mage::helper('tgc_dl')->__('Format'),
            'width'     => '50px',
            'index'     => 'format',
            'type'      => 'options',
            'options'   => Mage::getModel('tgc_dl/source_format')->toOptionArray(),
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
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('crossPlatformResume');

        $this->getMassactionBlock()->addItem('delete', array(
            'label'   => Mage::helper('tgc_dl')->__('Delete resume data'),
            'url'     => $this->getUrl('*/*/massDelete'),
            'confirm' => Mage::helper('tgc_dl')->__('Really delete the selected resume data?')
        ));

        return $this;
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }
}
