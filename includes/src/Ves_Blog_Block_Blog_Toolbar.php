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
class Ves_Blog_Block_Blog_Toolbar extends Ves_Blog_Block_Blog_Template
{
	protected function _prepareLayout() {			 
		
	}
	public function getTotal() {
		return Mage::registry('paginateTotal');
    }
    
    public function getPages() {
		return ceil(($this->getTotal())/(int)$this->getLimitPerPage() );
    }
	
	public function getLimitPerPage(){
		return Mage::registry('paginateLimitPerPage');
	}

	public function getCurrentLink() {
		$module = $this->getRequest()->getModuleName();
		$controller = $this->getRequest()->getControllerName();
		$module = strtolower($module);
		if($module == "ves_blog" || $module == "venusblog"){
			if($controller == "list" || $controller == "index") {
				$tag = $this->getRequest()->getParam( "tag" );
				$archive = $this->getRequest()->getParam( "archive" );
				$author = (int)$this->getRequest()->getParam( "user" );

				$keyword = $this->getRequest()->getParam( "search_query" );
				$keyword = trim($keyword);
				$orderby = $this->getRequest()->getParam( "orderby" );
				$orderway = $this->getRequest()->getParam( "orderway" );
				

				if( $tag ){
					return Mage::helper("ves_blog")->getTagUrl($tag);
				} elseif( $archive ){
					return Mage::helper("ves_blog")->getArchiveUrl($archive);
				} elseif( $author ) {
					return Mage::helper("ves_blog")->getAuthorUrl($author);
				} elseif($keyword && strlen($keyword) >= 3) {
					$route = trim( Mage::getStoreConfig('ves_blog/general_setting/route') );
					return  Mage::getBaseUrl().$route."?orderby=".$orderby."&orderway=".$orderway."&search_query=".$keyword;
				} else {
					$route = trim( Mage::getStoreConfig('ves_blog/general_setting/route') );
					return  Mage::getBaseUrl().$route;
				}
				
			} elseif($controller == "category") {
				 
				$id = $this->getRequest()->getParam('id');
 				return Mage::getModel( 'ves_blog/category' )->load($id)
 							->getCategoryLink();
			}
			
		}
 		return;
 	}
}

?>