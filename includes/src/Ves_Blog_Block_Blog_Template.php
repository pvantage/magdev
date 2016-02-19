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
class Ves_Blog_Block_Blog_Template extends Mage_Core_Block_Template
{
	
	/**
	 * @var string $_theme store currently theme.
	 *
	 * @access protected;
	 */
	protected $_theme = 'default';
	
	/**
	 * @var string $_type type of layout
	 *
	 * @access protected;
	 */
	protected $_type='';
	
	/**
	 * @var string $_pageTitle it is page title.
	 *
	 * @access protected;
	 */
	protected $_pageTitle = '';
	

	public function __construct($attributes = array())
	{
		parent::__construct( $attributes );
		if(!$this->getGeneralConfig("show")) {
			return;
		}
	}
	
	public function getPostConfig( $key ){  
		return Mage::getStoreConfig('ves_blog/post_setting/'.$key);
	}
	
	public function getGeneralConfig( $key ){
		return Mage::getStoreConfig('ves_blog/general_setting/'.$key);
	}
	
	public function getCategoryConfig( $key ){
		return  Mage::getStoreConfig('ves_blog/category_setting/'.$key);
	}
	
	public function getListConfig( $key ){
		return  Mage::getStoreConfig('ves_blog/list_setting/'.$key);
	}
	
		
	public function setType( $type ){
		$this->_type = $type;
		return $this;
	}
	
	public function getType(){
		return $this->_type;
	}
	
	public function setPageTitle( $ptitle ){
		$this->_pageTitle = $ptitle;
		return $this;
	}
	
	public function getPageTitle(){
		return $this->_pageTitle;
	}
	
	public function setHeadInfo( $keyword='',$metadesc='',$rss='' ){		
		if ( $head = $this->getLayout()->getBlock('head') ) {

			$head->setTitle( $this->_pageTitle );
			if( $keyword ) {
				$head->setKeywords( $keyword );
			}
			if( $metadesc ) {
				$head->setDescription( $metadesc );
			}
			//	Mage::helper('mtcms')->addRssToHead($head, Mage::helper('')->getBlogUrl() . "rss");
	
		}
		return $this;
	}

	public function getPostImage($post, $layout_mode = "", $image_mode = "", $quality = 100) {
		$image_info = array();
		$file = $post->getFile();
		$file = preg_replace("#\s+#","_", $file);

		if(!$image_mode && $layout_mode) {
			if($layout_mode == "second") {
				$image_mode = $this->getListConfig("second_image_size");
				$image_mode = $image_mode?$image_mode:"m";
			} elseif($layout_mode == "grid" || $layout_mode == "thumb_view" || $layout_mode == "thumb") {
				$image_mode = $this->getListConfig("grid_image_size");
				$image_mode = $image_mode?$image_mode:"s";
			} else{
				$image_mode = $this->getListConfig("list_leadingimage");
				$image_mode = $image_mode?$image_mode:"l";
			}
			
			
		}

		if($image_mode) {
			$sizes = Mage::helper("ves_blog")->getImageSizeModes();
			$key = "large_imagesize";
			$size = "l";
			foreach( $sizes as $k => $v ){
				if($v == $image_mode) {
					$key = $k;
					$size = $v;
					break;
				}
			}

			$image_size = $this->getGeneralConfig($key);
			$tmp = explode( "x", $image_size );
			if( count($tmp) > 0 && (int)$tmp[0] ){
				$image_info['width'] = (int)$tmp[0];
				$image_info['height'] = (int)$tmp[1];
                $image_info['url'] = Mage::helper('ves_blog')->resizeImage( $file, $size, (int)$tmp[0], (int)$tmp[1], null, (int)$quality);
            }

		}

		if(!isset($image_info['url'])) {
			$image_info['url'] = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA).$file;
		}
		
        return $image_info;
	}

}
?>