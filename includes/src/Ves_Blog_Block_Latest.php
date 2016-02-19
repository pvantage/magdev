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
class Ves_Blog_Block_Latest extends Ves_Blog_Block_List 
{

	
	/**
	 * Contructor
	 */
	public function __construct($attributes = array())
	{

		parent::__construct( $attributes );

		$my_template = "";
        if(isset($attributes['template']) && $attributes['template']) {
            $my_template = $attributes['template'];
        }elseif($this->hasData("template")) {
        	$my_template = $this->getData("template");
        }else {
 			$my_template = "ves/blog/block/latest.phtml";
 		}

        $this->setTemplate($my_template);
	}
	
	public function _toHtml(){
 		if(!$this->getConfig("enable_latestmodule")) {
 			return ;
 		}
		$collection = Mage::getModel( 'ves_blog/post' )
						->getCollection();
	   $catsid = $this->getConfig('catsid');
		if($catsid){
			$catsid = explode(',', $catsid);
			$collection->addCategoriesFilter($catsid);
		}
	    
		if( $this->getConfig("latest_typesource") == "hit" ){
			$collection ->setOrder( 'hits', 'DESC' );
		}else {
			$collection ->setOrder( 'created', 'DESC' );
		}

		$collection->setPageSize( $this->getConfig("limit_items") )->setCurPage( 1 );
		$this->assign( 'posts', $collection );
		
		return parent::_toHtml();
		
	}
	public function getCountingComment( $post_id = 0){

	      $comment = Mage::getModel('ves_blog/comment')->getCollection()
	        ->addEnableFilter( 1  )
	        ->addPostFilter( $post_id  );
	      return count($comment);
 	}
}	