<?php
class Tgc_Events_Block_Adminhtml_Renderer_Typeicon extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
	public function render(Varien_Object $row)
	{
		$src = Mage::getBaseUrl('media'). DS .$row['type_icon'];
		$a = "<img src ='".$src."' height ='48px'/>";
		
		return $a;
	}
}