<?php

class Ves_Blog_Model_System_Config_Source_Imagetypes
{
    public function toOptionArray()
    {
    	if (strlen($code = Mage::getSingleton('adminhtml/config_data')->getStore())) // store level
		{
		    $store_id = Mage::getModel('core/store')->load($code)->getId();
		}
		elseif (strlen($code = Mage::getSingleton('adminhtml/config_data')->getWebsite())) // website level
		{
		    $website_id = Mage::getModel('core/website')->load($code)->getId();
		    $store_id = Mage::app()->getWebsite($website_id)->getDefaultStore()->getId();
		}
		else // default level
		{
		    $store_id = 0;
		}
        return array(
        	array('value'=>'xl', 'label'=>Mage::helper('ves_blog')->__('Extra Large')." (".Mage::getStoreConfig('ves_blog/general_setting/extralarge_imagesize',$store_id) .")" ),
        	array('value'=>'l', 'label'=>Mage::helper('ves_blog')->__('Large')." (".Mage::getStoreConfig('ves_blog/general_setting/large_imagesize',$store_id) .")" ),
            array('value'=>'m', 'label'=>Mage::helper('ves_blog')->__('Medium')." (".Mage::getStoreConfig('ves_blog/general_setting/medium_imagesize',$store_id) .")"),
            array('value'=>'s', 'label'=>Mage::helper('ves_blog')->__('Small')." (".Mage::getStoreConfig('ves_blog/general_setting/small_imagesize',$store_id) .")"),

        );
    }    
}
