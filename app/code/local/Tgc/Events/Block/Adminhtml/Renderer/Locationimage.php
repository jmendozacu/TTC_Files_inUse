<?php
class Tgc_Events_Block_Adminhtml_Renderer_Locationimage extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
	public function render(Varien_Object $row)
	{
		$src = Mage::getBaseUrl('media'). DS .$row['location_image'];
		$a = "<img src ='".$src."' height ='48px'/>";
		
		return $a;
	}
}