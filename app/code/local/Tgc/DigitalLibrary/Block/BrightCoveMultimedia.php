<?php
/**
 * BrightCove widget
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DigitalLibrary
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_DigitalLibrary_Block_BrightCoveMultimedia extends Tgc_DigitalLibrary_Block_BrightCove
implements Mage_Widget_Block_Interface
{

    public function getContentId() {
        if ($this->getData('content_id')==null){
            return (string)$this->getData('player_id');
        }else{
            return (string)$this->getData('content_id');
        }
    }

    public function getIsVid(){
        if (is_int($this->getData('is_vid'))){
            if ($this->getData('is_vid')==1){
                return "true";
            }else{
                return "false";
            }
        }else{
            return (string)$this->getData('is_vid');
        }
    }

    public function getIsUi(){
        if (is_int($this->getData('is_ui'))){
            if ($this->getData('is_ui')==1){
                return "true";
            }else{
                return "false";
            }
        }else{
            return (string)$this->getData('is_ui');
        }
    }

}
