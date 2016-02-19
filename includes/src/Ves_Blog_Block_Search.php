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
class Ves_Blog_Block_Search extends Ves_Blog_Block_List 
{
	/**
	 * Contructor
	 */
	public function __construct($attributes = array()){
		parent::__construct( $attributes );

		$my_template = "";
        if(isset($attributes['template']) && $attributes['template']) {
            $my_template = $attributes['template'];
        }elseif($this->hasData("template")) {
        	$my_template = $this->getData("template");
        }else {
 			$my_template = "ves/blog/block/search.phtml";
 		}

        $this->setTemplate($my_template);

	}

	
	public function _toHtml(){
		$keyword = $this->getRequest()->getParam( "search_query" );
		$keyword = trim($keyword);
		$this->assign("keyword", $keyword);
		return parent::_toHtml();	
	}

	public function getFormAction() {
		return Mage::getBaseUrl().Mage::getStoreConfig('ves_blog/general_setting/route');
	}

}
?>