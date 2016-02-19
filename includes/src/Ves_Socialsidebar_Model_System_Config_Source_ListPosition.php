<?php

class Ves_Socialsidebar_Model_System_Config_Source_ListPosition
{
 public function toOptionArray()
    {
        return array(
            array('value' => "right", 'label'=>Mage::helper('adminhtml')->__('OutSite Right')),
            array('value' => "left", 'label'=>Mage::helper('adminhtml')->__('OutSite Left')),
        );
    }
}
