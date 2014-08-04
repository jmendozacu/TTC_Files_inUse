<?php
/**
 * User: mhidalgo
 * Date: 05/03/14
 * Time: 09:17
 */
class Tgc_Zmag_DcatalogController extends Mage_Core_Controller_Front_Action
{
    public function indexAction(){
        $this->loadLayout();
        $this->renderLayout();
    }
}