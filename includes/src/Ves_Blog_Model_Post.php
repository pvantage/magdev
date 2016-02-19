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
class Ves_Blog_Model_Post extends Mage_Core_Model_Abstract
{
    protected function _construct() {
        $this->_init('ves_blog/post');
    }
	
	/**
	 *
	 */
	public function getURL(){
		return Mage::getBaseUrl().Mage::getModel('core/url_rewrite')->loadByIdPath('venusblog/post/'.$this->getId())->getRequestPath();
	}
	
	public function getImageURL( $type = "l" ){
		// Gets the current store's id
		$storeId = Mage::app()->getStore()->getStoreId();
		$image = str_replace("/",DS, $this->getFile());
		$image = str_replace("blog".DS,"", $image);
		if($type == "original") {
			return Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA)."blog/".$image;
		} else {
			if(!$storeId){
				return Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA)."resized/".$type."/".$this->getFile();
			}else{
				$imageDir = Mage::getBaseDir('media').DS."resized".DS.$type.DS."blog".DS.$storeId.DS.$image;
				if (file_exists($imageDir)) {
					return Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA)."resized/".$type."/blog/".$storeId."/".$image;
				}else{
					return Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA)."resized/".$type."/".$this->getFile();
				}
			}
		}
		
	}
	
	public function getCategoryTitle(){
		return Mage::getModel('ves_blog/category')->load($this->getCategoryId())->getTitle();
	}

	public function getCatTitle(){
		return $this->getCategoryTitle();
	}
	
	
	public function getCategoryLink(){
		return  Mage::getBaseUrl().Mage::getModel('core/url_rewrite')->loadByIdPath('venusblog/category/'.$this->getCategoryId())->getRequestPath();
	}
	
	public function getAuthor(){
		$author = Mage::getModel('admin/user')->load($this->getUserId());
        return $author->getFirstname().' '.$author->getLastname();
	}
	
	public function getAuthorURL(){
		return Mage::getBaseUrl().Mage::getModel('core/url_rewrite')->loadByIdPath('venusblog/list/show/'.$this->getUserId())->getRequestPath();
	}
}