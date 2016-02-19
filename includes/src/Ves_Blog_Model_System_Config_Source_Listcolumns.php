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

class Ves_Blog_Model_System_Config_Source_Listcolumns
{	
 
    public function toOptionArray()
    {

  		$output = array();
  		$output[] = array("value"=>"" , "label" => Mage::helper('adminhtml')->__("Auto"));
  		$output[] = array("value"=>"1" , "label" => 1);
  		$output[] = array("value"=>"2" , "label" => 2);
      $output[] = array("value"=>"3" , "label" => 3);
      $output[] = array("value"=>"4" , "label" => 4);
      $output[] = array("value"=>"5" , "label" => 5);
      $output[] = array("value"=>"6" , "label" => 6);

      return $output ;
    }    
}
