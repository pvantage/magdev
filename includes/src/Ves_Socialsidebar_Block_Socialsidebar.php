<?php
/*------------------------------------------------------------------------
 # VenusTheme Socialsidebar Module 
 # ------------------------------------------------------------------------
 # author:    VenusTheme.Com
 # copyright: Copyright (C) 2012 http://www.venustheme.com. All Rights Reserved.
 # @license: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 # Websites: http://www.venustheme.com
 # Technical Support:  http://www.venustheme.com/
-------------------------------------------------------------------------*/
//require_once(Mage::getBaseDir('code').'/community/Ves/Pagebuider/Helper/widgetbase.php');
class Ves_Socialsidebar_Block_Socialsidebar extends Mage_Core_Block_Template 
{
	/**
	 * Contructor
	 */
	public function __construct($attributes = array())
	{	
		/*End init meida files*/
		$this->_controller = 'socialsidebar';
		$mediaHelper =  Mage::helper('ves_socialsidebar/media');
		if(!$this->getConfig('show') ) return;
		$my_template = $this->getTemplate();
		if(empty($my_template)) {
			$template = 'ves/socialsidebar/default.phtml';
			$this->setTemplate( $template );
		}						
		parent::__construct();
	}
	
	/**
     * Rendering block content
     *
     * @return string
     */
	function _toHtml() 
	{
		if(!$this->getConfig('show') ) return;

		// assign to default.phtml
		$this->assign( "mobile_status", $this->getConfig('mobileStatus', "mobile_config") );
		$this->assign( "idAddthis", $this->getConfig('addID') );
		$this->assign( "share_desktop", $this->getConfig('showDesktop','share_setting') );
		$this->assign( "share_mobile", $this->getConfig('showMobile','share_setting') );
		$this->assign( "follow_desktop", $this->getConfig('showDesktop_follow','follow') );
		$this->assign( "follow_mobile", $this->getConfig('showMobile_follow','follow') );
		$this->assign( "mobile", $this->getConfig('mobileStatus','mobile_config') );
		$this->assign( "theme", $this->getConfig('theme') );
		$this->assign( "domain", $this->getConfig('domain') );
		$this->assign( "maxWidth", $this->getConfig('maxWidth') );
		$this->assign( "minWidth", $this->getConfig('minWidth') );
		$this->assign( "share_position", $this->getConfig('sharePosition','share_setting') );
		$share_service =  $this->getConfig('share_service','share_setting');
		if($share_service) {
			//$data_services = $setting['share_service'];
			if(is_array($share_service)) {
				$services = implode(",", $share_service);
			} else {
				$services = $share_service;
			}
		}else{
			$services = 'facebook,twitter,google_plusone_share,email,print,more';
		}
		$this->assign( "services", $services );
		$this->assign( "share_title", $this->getConfig('share_title','share_setting') );
		$this->assign( "share_mgs", $this->getConfig('share_mgs','share_setting') );
		$this->assign( "share_theme", $this->getConfig('themeShare','share_setting') );
		$this->assign( "follow_services",$this->getFollowServices() );
		$this->assign( "follow_title", $this->getConfig('titleFollow','follow') );
		$this->assign( "follow_thank", $this->getConfig('thankTitle_follow','follow') );
		$this->assign( "follow_theme", $this->getConfig('themeFollow','follow') );
		$this->assign( "mobile_position", "bottom" );
		$this->assign( "mobile_theme", $this->getConfig('mobileTheme','mobile_config') );

		return parent::_toHtml();
	}
	public function getFollowServices(){
		//config follow id services
		$follow_service = array();
		if($this->getConfig('Facebook','follow')){
			$follow_service[]=array( 'service' => 'facebook', 'id' => $this->getConfig('Facebook','follow'));
		}
		if($this->getConfig('Twitter','follow')){
			$follow_service[]=array( 'service' => 'twitter', 'id' => $this->getConfig('Twitter','follow'));
		}
		if($this->getConfig('GoogleFollow','follow')){
			$follow_service[]=array( 'service' => 'google_follow', 'id' => $this->getConfig('GoogleFollow','follow'));
		}
		if($this->getConfig('Youtube','follow')){
			$follow_service[]=array( 'service' => 'youtube', 'id' => $this->getConfig('Youtube','follow'));
		}
		if($this->getConfig('Flickr','follow')){
			$follow_service[]=array( 'service' => 'flickr', 'id' => $this->getConfig('Flickr','follow'));
		}
		if($this->getConfig('Vimeo','follow')){
			$follow_service[]=array( 'service' => 'vimeo', 'id' => $this->getConfig('Vimeo','follow'));
		}
		if($this->getConfig('Pinterest','follow')){
			$follow_service[]=array( 'service' => 'pinterest', 'id' => $this->getConfig('Pinterest','follow'));
		}
		if($this->getConfig('Instagram','follow')){
			$follow_service[]= array( 'service' => 'instagram', 'id' => $this->getConfig('Instagram','follow'));
		}
		if($this->getConfig('Linkedin','follow')){
			$follow_service[]=array( 'service' => 'linkedin', 'id' => $this->getConfig('Linkedin','follow'));
		}
		return $follow_service;
	}
	/**
	  * get value of the extension's configuration
	  *
	  * @return string
	  */
	function getConfig( $key, $panel='ves_socialsidebar' ){
		if(isset($this->_config[$key])) {
			return $this->_config[$key];
		} else {
			return Mage::getStoreConfig("ves_socialsidebar/$panel/$key");
		}
	}
	
}