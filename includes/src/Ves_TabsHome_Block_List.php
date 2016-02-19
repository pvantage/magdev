<?php

class Ves_TabsHome_Block_List extends Mage_Catalog_Block_Product_Abstract {

    protected $_config = '';
    protected $_categories;


    public function __construct($attributes = array()) {
        $helper = Mage::helper('ves_tabshome/data');
        $this->_config = $helper->get($attributes);

        $config = $this->_config;
        $this->_config['list_cat'] = (empty($config['list_cat']) ? '': $config['list_cat']);
        
        if( !isset($this->_config["show"])  || (isset($this->_config["show"]) && !$this->_config["show"] )) {  return ;    }

        parent::__construct();

        if($this->getConfig("template")) {

          $config['template'] = $this->getConfig("template");

        }elseif (!$this->hasData("template")) {

          $config['template'] = 'ves/tabshome/default/default.phtml';

        } else {

          $config['template'] = $this->getData("template");

        }

        $this->setTemplate($config['template']);

        /*Cache Block*/
        $enable_cache = $this->getConfig("enable_cache", 0 );
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
          Ves_TabsHome_Model_Product::CACHE_BLOCK_TAG
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
           'VES_TABSHOME_BLOCK_LIST',
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

    /**
     * get value of the extension's configuration
     *
     * @return string
     */
    public function getConfig( $key, $default = "", $panel='ves_tabshome'){
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
          $return = Mage::getStoreConfig("ves_tabshome/$panel/$key");
        }
        if($return == "" && $default) {
          $return = $default;
        }

      }

      return $return;
    }


     /**
     * Rendering block content
     *
     * @return string
     */
    public function _toHtml() {
     
        $config = $this->_config;
    		if( !isset($this->_config["show"])  || (isset($this->_config["show"]) && !$this->_config["show"] )) {  return ;    }

    		$news = $featured = $specical =	$bestseller = $mostview = $related = $upsell = array();
    		
    		
    		if( $this->getConfig('enable_new',1) ){
    			$news = Mage::getModel('ves_tabshome/product')->getListLatestProducts( $config );
    		}
    		
    	
    		if( $this->getConfig('enable_feature',1) ){
    			$featured = Mage::getModel('ves_tabshome/product')->getListFeaturedProducts( $config );
    		}
    		if( $this->getConfig('enable_bestseller',1) ){
    			$bestseller = Mage::getModel('ves_tabshome/product')->getListBestSellerProducts( $config );
    		}
    		if( $this->getConfig('enable_mostview',1) ){
    			$mostview = Mage::getModel('ves_tabshome/product')->getListMostViewedProducts( $config );
    		}	

    		if( $this->getConfig('enable_special',1) ){  
    			$specical = Mage::getModel('ves_tabshome/product')->getListSpecialProducts( $config );
    			
    		}

        if( $this->getConfig('enable_related', 0) ){  
            $related = Mage::getModel('ves_tabshome/product')->getListRelatedProducts( $config );
            
        }

        if( $this->getConfig('enable_upsell', 0) ){  
            $upsell = Mage::getModel('ves_tabshome/product')->getListUpsellProducts( $config );
            
        }		
		

		$currency = ''.Mage::app()->getLocale()->currency(Mage::app()->getStore()->getCurrentCurrencyCode())->getSymbol();
		
		$cms = "";

 		$cms_block_id = $this->getConfig('cmsblock');
 		if($cms_block_id){
 			$cms = Mage::getSingleton('core/layout')->createBlock('cms/block')->setBlockId($cms_block_id)->toHtml();
 		}

 		$this->assign( 'cms', $cms );
		$this->assign( 'bestseller', $bestseller );
		$this->assign( 'mostview', $mostview );
		$this->assign( 'news', $news );
		$this->assign( 'featured', $featured );
		$this->assign( 'specical', $specical );
        $this->assign( 'related', $related );
        $this->assign( 'upsell', $upsell );
		
		$this->assign('currency', $currency);
		
        $this->assign('config', $config);
        
        return parent::_toHtml();
    }
 
    public function getPro()
    {
        $storeId    = Mage::app()->getStore()->getId();
        $products = Mage::getResourceModel('reports/product_collection')
            ->addAttributeToSelect('*')
            ->addAttributeToSelect(array('name', 'price', 'small_image')) //edit to suit tastes
            ->setStoreId($storeId)
            ->addStoreFilter($storeId);

		
        Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($products);
        Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($products);

        $products->setPageSize(6)->setCurPage(1);

        $this->setProductCollection($products);
    }
    
    function inArray($source, $target) {
		for($i = 0; $i < sizeof ( $source ); $i ++) {
			if (in_array ( $source [$i], $target )) {
			return true;
			}
		}
    }
   

    public function subString( $text, $length = 100, $replacer ='...', $is_striped=true ){
    		$text = ($is_striped==true)?strip_tags($text):$text;
    		if(strlen($text) <= $length){
    			return $text;
    		}
    		$text = substr($text,0,$length);
    		$pos_space = strrpos($text,' ');
    		return substr($text,0,$pos_space).$replacer;
	}

}
