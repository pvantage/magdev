<?php
/*------------------------------------------------------------------------
 # VenusTheme ContentSlider Module 
 # ------------------------------------------------------------------------
 # author:    VenusTheme.Com
 # copyright: Copyright (C) 2012 http://www.venustheme.com. All Rights Reserved.
 # @license: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 # Websites: http://www.venustheme.com
 # Technical Support:  http://www.venustheme.com/
-------------------------------------------------------------------------*/
class Ves_Parallax_Block_List extends Mage_Core_Block_Template 
{
	/**
	 * @var string $_config
	 * 
	 * @access protected
	 */
	protected $_config = '';
	
	/**
	 * @var string $_config
	 * 
	 * @access protected
	 */
	protected $_listDesc = array();
	
	/**
	 * @var string $_config
	 * 
	 * @access protected
	 */
	protected $_show = 0;
	protected $_theme = "";
	
	/**
	 * Contructor
	 */
	public function __construct($attributes = array())
	{
		$this->convertAttributesToConfig($attributes);
		if(isset($attributes['show']) && $attributes['show']) {
			$this->setConfig( "show", $attributes['show']);
		}

		$this->_show = $this->getConfig("show");
 		
		if(!$this->_show) return;

		if($this->hasData("template") && $this->getData("template")) {
			$this->setTemplate($this->getData("template"));
		} else {
			$this->setTemplate("ves/parallax/default.phtml");
		}
		

		parent::__construct();

		/*Cache Block*/
        $cache_lifetime = $this->getConfig("cache_lifetime", 86400 );

        $this->addData(array('cache_lifetime' => $cache_lifetime));

        $this->addCacheTag(array(
          Mage_Core_Model_Store::CACHE_TAG,
          Mage_Cms_Model_Block::CACHE_TAG,
          Ves_Parallax_Model_Config::CACHE_BLOCK_TAG
        ));

        /*End Cache Block*/	
	}

	/**
     * Get Key pieces for caching block content
     *
     * @return array
     */
    public function getCacheKeyInfo()
    {
        return array(
           'VES_PARALLAX_BLOCK_LIST',
           $this->getNameInLayout(),
           Mage::app()->getStore()->getId(),
           Mage::getDesign()->getPackageName(),
           Mage::getDesign()->getTheme('template'),
           Mage::getSingleton('customer/session')->getCustomerGroupId(),
           'template' => $this->getTemplate(),
        );
    }

    public function convertAttributesToConfig($attributes = array()) {
      if($attributes) {
        foreach($attributes as $key=>$val) {
            $this->setConfig($key, $val);
        }
      }
    }

	/**
     * Rendering block content
     *
     * @return string
     */
	function _toHtml() 
	{
		if(!$this->_show) return;
		
		$_model  = Mage::getModel('ves_parallax/banner');
        
    
        $collection = $_model->getCollection();

		$collection  = $collection->addEnableFilter(1);

		if($collection->getSize() > 0 ) {
			$banners = array();
			foreach($collection as $item){
				$banner = array();
				$banner['percent'] = $item->getPercent();
				$banner['scroll'] = $item->getScroll();
				$banner['image_width'] = $item->getImageWidth();
				$banner['image_height'] = $item->getImageHeight();
				$banner['position'] = $item->getPosition();

				$banner['image'] = $item->getFile();

				$banners[] = $banner;
	        }

			$this->assign("banners", $banners);
			
			return parent::_toHtml();
		}
		return ;
    }
	
	public function resizeImage( $image, $width = "", $height = "" ){
		if($width == "auto" || $height == "auto" || empty($width) || empty($height)) {
			return Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA).str_replace(DS,"/", $image);
		}

		$image= str_replace("/",DS, $image);
		$_imageUrl = Mage::getBaseDir('media').DS.$image;
		$imageResized = Mage::getBaseDir('media').DS."resized".DS."{$width}x{$height}".DS.$image;

		if (!file_exists($imageResized)&&file_exists($_imageUrl)) {
			$imageObj = new Varien_Image($_imageUrl);
			$imageObj->constrainOnly(true);
		    $imageObj->keepAspectRatio(true);
		    $imageObj->keepFrame(false);
		    $imageObj->keepTransparency(true);
			$imageObj->resize( $width, $height);
			$imageObj->save($imageResized);
			
		}
		return Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA).'resized/'."{$width}x{$height}/".str_replace(DS,"/",$image);
	}

	/**
	 * get value of the extension's configuration
	 *
	 * @return string
	 */
	function getConfig( $key, $panel='ves_parallax', $default = "" ){

		$return = "";
	    $value = $this->getData($key);
	    //Check if has widget config data
	    if($this->hasData($key) && $value !== null) {
	      if($key == "pretext") {
	      	$value = base64_decode($value);
	      }
	      if($value == "true") {
	        return 1;
	      } elseif($value == "false") {
	        return 0;
	      }
	      
	      return $value;
	      
	    } else {

	      if(isset($this->_config[$key])){
	        	$return = $this->_config[$key];

		        if($return == "true") {
		            $return = 1;
		        } elseif($return == "false") {
		            $return = 0;
		        }
	      }else{
	        $return = Mage::getStoreConfig("ves_parallax/$panel/$key");
	      }
	      if($return == "" && $default) {
	        $return = $default;
	      }

	    }

	    return $return;
	}
	
	/**
     * overrde the value of the extension's configuration
     *
     * @return string
     */
    function setConfig($key, $value) {
		if($value == "true") {
	        $value =  1;
	    } elseif($value == "false") {
	        $value = 0;
	    }
    	if($value != "") {
	      	$this->_config[$key] = $value;
	    }
    	return $this;
    }	

}
