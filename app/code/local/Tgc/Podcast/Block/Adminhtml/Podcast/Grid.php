<?php

/**
 * @category   Tgc
 * @package    Tgc_Podcast
 * @copyright  Copyright (c) 2014
 * @author     Guidance
 */

class Tgc_Podcast_Block_Adminhtml_Podcast_Grid extends RocketWeb_Podcast_Block_Adminhtml_Podcast_Grid
{

    protected function _prepareColumns()
    {
        parent::_prepareColumns();

        $this->removeColumn('short_content');

        $this->addColumn('long_content', array(
            'header' => Mage::helper('podcast')->__('Long Description'),
            'index' => 'long_content',
        ));

        $this->addColumn('episode_number', array(
            'header' => Mage::helper('podcast')->__('Episode Number'),
            'align' => 'left',
            'index' => 'episode_number',
        ));

        $this->addColumn('episode_duration', array(
            'header' => Mage::helper('podcast')->__('Episode Duration'),
            'align' => 'left',
            'index' => 'episode_duration',
        ));

        $this->addColumnsOrder('episode_number', 'title');
        $this->addColumnsOrder('episode_duration', 'episode_number');
        $this->addColumnsOrder('long_content', 'episode_duration');
        $this->sortColumnsByOrder();
    }

}