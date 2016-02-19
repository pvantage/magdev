<?php 
 /*------------------------------------------------------------------------
  # Ves Blog Module 
  # ------------------------------------------------------------------------
  # author:    Ves.Com
  # copyright: Copyright (C) 2012 http://www.ves.com. All Rights Reserved.
  # @license: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
  # Websites: http://www.ves.com
  # Technical Support:  http://www.ves.com/
-------------------------------------------------------------------------*/

class Ves_Blog_Model_System_Config_Source_ListLayoutMode
{	
 
    public function toOptionArray()
    {

  		$output = array();
  		$output[] = array("value"=>"" , "label" => Mage::helper('adminhtml')->__("Default Layout"));
  		$output[] = array("value"=>"list" , "label" => Mage::helper('adminhtml')->__("List - Large Image Layout"));
  		$output[] = array("value"=>"grid" , "label" => Mage::helper('adminhtml')->__("Grid Layout"));
      $output[] = array("value"=>"second" , "label" => Mage::helper('adminhtml')->__("Second - Small Image Layout"));
      $output[] = array("value"=>"masonry" , "label" => Mage::helper('adminhtml')->__("Masonry Layout"));
      $output[] = array("value"=>"custom" , "label" => Mage::helper('adminhtml')->__("Custom Layout"));
	$output[] = array("value"=>"thumb_view" , "label" => Mage::helper('adminhtml')->__("Thumb view layout"));	

      return $output ;
    }    
}
