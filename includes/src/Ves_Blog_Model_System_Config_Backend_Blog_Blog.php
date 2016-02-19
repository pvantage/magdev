<?php
class Ves_Blog_Model_System_Config_Backend_Blog_Blog extends Mage_Core_Model_Config_Data {
    protected function _afterSave() {
	    Mage::app()->cleanCache( array(
		        Mage_Core_Model_Store::CACHE_TAG,
		        Mage_Cms_Model_Block::CACHE_TAG,
		        Ves_Blog_Model_Config::CACHE_WIDGET_LATEST_TAG
		    ) );
	    
	    Mage::app()->cleanCache( array(
	        Mage_Core_Model_Store::CACHE_TAG,
	        Mage_Cms_Model_Block::CACHE_TAG,
	        Ves_Blog_Model_Config::CACHE_BLOCK_LATEST_TAG
	    ) );

	    Mage::app()->cleanCache( array(
	        Mage_Core_Model_Store::CACHE_TAG,
	        Mage_Cms_Model_Block::CACHE_TAG,
	        Ves_Blog_Model_Config::CACHE_BLOCK_MENU_TAG
	    ) );

	    Mage::app()->cleanCache( array(
	        Mage_Core_Model_Store::CACHE_TAG,
	        Mage_Cms_Model_Block::CACHE_TAG,
	        Ves_Blog_Model_Config::CACHE_BLOCK_ARCHIVES_TAG
	    ) );
	    
	    Mage::app()->cleanCache( array(
	        Mage_Core_Model_Store::CACHE_TAG,
	        Mage_Cms_Model_Block::CACHE_TAG,
	        Ves_Blog_Model_Config::CACHE_BLOCK_TAGS_TAG
	    ) );
	}
}