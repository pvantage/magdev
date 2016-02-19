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
class Ves_Blog_Model_Config extends Mage_Catalog_Model_Product_Media_Config {

    const CACHE_BLOCK_LATEST_TAG = 'ves_blog_block_latest';
    const CACHE_WIDGET_LATEST_TAG = 'ves_blog_widget_latest';
    const CACHE_BLOCK_MENU_TAG = 'ves_blog_block_menu';
    const CACHE_BLOCK_ARCHIVES_TAG = 'ves_blog_block_archives';
    const CACHE_BLOCK_TAGS_TAG = 'ves_blog_block_tags';

    public function getBaseMediaPath() {
        return Mage::getBaseDir('media') .DS. 'blog';
    }

    public function getBaseMediaUrl() {
        return Mage::getBaseUrl('media') . 'blog';
    }

    public function getBaseTmpMediaPath() {
        return Mage::getBaseDir('media') .DS. 'tmp' .DS. 'blog';
    }

    public function getBaseTmpMediaUrl() {
        return Mage::getBaseUrl('media') . 'tmp/blog';
    }

}