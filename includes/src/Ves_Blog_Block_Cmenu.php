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
class Ves_Blog_Block_Cmenu extends Ves_Blog_Block_List 
{

	
	/**
	 * Contructor
	 */
	public function __construct($attributes = array()){
		Mage::helper('ves_blog/media')->addMediaFile( "js", "ves_blog/menu.js" );
		parent::__construct( $attributes );
		
		$my_template = "";
        if(isset($attributes['template']) && $attributes['template']) {
            $my_template = $attributes['template'];
        }elseif($this->hasData("template")) {
        	$my_template = $this->getData("template");
        }else {
 			$my_template = "ves/blog/block/cmenu.phtml";
 		}

        $this->setTemplate($my_template);

        $enable_cache = $this->getConfig("enable_cache", 1 );
        if(!$enable_cache) {
          $cache_lifetime = null;
        } else {
          $cache_lifetime = $this->getConfig("cache_lifetime", 86400 );
          $cache_lifetime = (int)$cache_lifetime>0?$cache_lifetime: 86400;
        }

        $this->addData(array('cache_lifetime' => $cache_lifetime));

        $this->addCacheTag(array(
          Mage_Core_Model_Store::CACHE_TAG,
          Mage_Cms_Model_Block::CACHE_TAG,
          Ves_Blog_Model_Config::CACHE_BLOCK_MENU_TAG
        ));
	}
	
	/**
     * Get Key pieces for caching block content
     *
     * @return array
     */
    public function getCacheKeyInfo()
    {
        return array(
           "VES_BLOG_BLOCK_MENU",
           $this->getNameInLayout(),
           Mage::app()->getStore()->getId(),
           Mage::getDesign()->getPackageName(),
           Mage::getDesign()->getTheme('template'),
           Mage::getSingleton('customer/session')->getCustomerGroupId(),
           'template' => $this->getTemplate(),
        );
    }

	public function _toHtml(){
		if(!$this->getConfig("enable_cmenumodule")) {
 			return ;
 		}
 		
		$menu = Mage::getModel( "ves_blog/category" )
					->getCollection()
					 ->addFieldToFilter('is_active', 1)
                    ->addFieldToFilter('parent_id', (int)$this->getConfig("cmenu_parent") )->setOrder("position","DESC");
		
		

		$this->assign( 'menus', $menu );	 
		return parent::_toHtml();	
	}
	public function renderTree( $parent , $level=1){
		
		$collection = Mage::getModel( "ves_blog/category" )
					->getCollection()
					 ->addFieldToFilter('is_active', 1)
                    ->addFieldToFilter('parent_id', $parent->getId() )
					->setOrder("position","DESC");
		
		$html = '<li class="level'.($level+1).''.(count($collection)?" parent":"").'">';
			$html .= '<a href="'.$parent->getCategoryLink().'" title="'.$parent->getTitle().'"><span>'.$parent->getTitle().'</span></a>';
		if( count($collection) ){
			$html .= '<span class="head"><a style="float:right;" href="#"></a></span>';
			$html .= '<ul class="level'.($level+1).'">';
				foreach( $collection as $child ){
					$html .= $this->renderTree( $child );
				}
			$html .= '</ul>';	
		}

		$html .= '</li>';
		
		return $html;
	}
}
?>