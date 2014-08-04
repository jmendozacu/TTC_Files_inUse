<?php
/**
 * Quick search weight model.
 * We need to add more weights.
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Tgc_Solr
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Solr_Model_Source_Weight extends Enterprise_Search_Model_Source_Weight
{
    /**
     * Quick search weights
     *
     * @var array
     */
    protected $_weights = array(1, 2, 3, 4, 5, 6, 7, 8, 9);
}
