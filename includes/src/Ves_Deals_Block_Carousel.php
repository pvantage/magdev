<?php

class Ves_Deals_Block_Carousel extends Mage_Catalog_Block_Product_Abstract 
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

		if($attributes) {
			$this->convertAttributesToConfig($attributes);
		}

		$this->_show = $this->getConfig("show");

		if(!$this->_show) return;
		
		parent::__construct();	

		if($this->hasData("template") && $this->getData("template")) {
			$this->setTemplate( $this->getData("template") );
		} else {
			$template = 'ves/deals/block/carousel.phtml';
			$this->setTemplate( $template );
		}

		/*Cache Block*/
    $enable_cache = $this->getConfig("enable_cache", "ves_deals", 1 );
    if(!$enable_cache) {
      $cache_lifetime = null;
    } else {
      $cache_lifetime = $this->getConfig("cache_lifetime", "ves_deals", 86400 );
      $cache_lifetime = (int)$cache_lifetime>0?$cache_lifetime: 86400;
    }

    $this->addData(array('cache_lifetime' => $cache_lifetime));
    $this->addCacheTag(array(
      Mage_Core_Model_Store::CACHE_TAG,
      Mage_Cms_Model_Block::CACHE_TAG,
      Ves_Deals_Model_Config::CACHE_BLOCK_TAG
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
           'VES_DEALS_BLOCK_LIST',
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
	function _toHtml() { 		 
		if( !$this->_show || !$this->getConfig('show') ) return;

		$this->listAssign();

    return parent::_toHtml();
	}
	public function listAssign(){
		$module = 0;
		$items = $this->getListProducts();
		$date_start = $this->getSourceConfig('date_start');
		$to_date = $this->getSourceConfig('to_date');
		if(empty($date_start)){
			$date_start = "0000-00-00";
		}
		if(empty($to_date)){
			$to_date = date("Y-m-d");
		}
		$this->assign( "items", $items );
		$this->assign( "background", Mage::getBaseUrl('media').'ves_deals/'.$this->getSourceConfig('image') );
		$this->assign( "date_start",$date_start);
		$this->assign( "to_date", $to_date);
		$this->assign( "module", $module++);
	}
	public function getEffectConfig( $key ){
		return $this->getConfig( $key, "effect_setting" );
	}

	public function getSourceConfig( $key ){
		return $this->getConfig( $key, "catalog_source_setting" );
	}

	
	/**
	 * get value of the extension's configuration
	 *
	 * @return string
	 */
	public function getConfig( $key, $panel='ves_deals', $default = ""){
	    $return = "";
	    $value = $this->getData($key);
	    //Check if has widget config data
	    if($this->hasData($key) && $value !== null) {

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
	        $return = Mage::getStoreConfig("ves_deals/$panel/$key");
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


	public function subString( $text, $length = 100, $replacer ='...', $is_striped=true ){
    		$text = ($is_striped==true)?strip_tags($text):$text;
    		if(strlen($text) <= $length){
    			return $text;
    		}
    		$text = substr($text,0,$length);
    		$pos_space = strrpos($text,' ');
    		return substr($text,0,$pos_space).$replacer;
	}
	public function getListProducts()
    {
    	$products = null;
    	$storeId  = Mage::app()->getStore()->getId();
    	$cateids = $this->getSourceConfig('catsid');
    	$todayDate  = Mage::app()->getLocale()->date()->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);
    	$filter_date_start 	= $this->getSourceConfig('date_start');
    	$filter_date_start 	= trim($filter_date_start);
    	$filter_to_date 		= $this->getSourceConfig('to_date');
    	$filter_to_date 		= trim($filter_to_date);

    	if($cateids) {
	    	$productIds = $this->getProductByCategory();
	        $products = Mage::getResourceModel('catalog/product_collection')
            ->addAttributeToSelect('*')
            ->setStoreId($storeId)
           	->addStoreFilter($storeId)
           	->addIdFilter($productIds)
           	//->addFieldToFilter('special_to_date',array('gteq'=>$todayDate))
           	->addAttributeToFilter(array(
           		array(
           					'attribute' =>'special_to_date',
						        'from' =>date('Y-m-d G:i:s', strtotime($filter_date_start)),
						        'to' => date('Y-m-d G:i:s', strtotime($filter_to_date)),
						        'date' => true,
           				 ),
           		array(
           					'attribute' =>'special_to_date',
           					'gteq' =>date('Y-m-d G:i:s', strtotime($filter_to_date)),
						        'date' => true,
           					),
           		array(
           					'attribute' =>'special_to_date',
           					'gteq' =>date('Y-m-d G:i:s', strtotime($filter_date_start)),
						        'date' => true,
           					),
           		))
			->addAttributeToSort('special_to_date','desc');
		}else{
			$products = Mage::getResourceModel('catalog/product_collection')
            ->addAttributeToSelect('*')
            ->setStoreId($storeId)
           	->addStoreFilter($storeId)
           	//->addFieldToFilter('special_to_date',array('gteq'=>$todayDate))
           	->addAttributeToFilter(array(
           		array(
           					'attribute' =>'special_to_date',
						        'from' =>date('Y-m-d G:i:s', strtotime($filter_date_start)),
						        'to' => date('Y-m-d G:i:s', strtotime($filter_to_date)),
						        'date' => true,
           				 ),
           		array(
           					'attribute' =>'special_to_date',
           					'gteq' =>date('Y-m-d G:i:s', strtotime($filter_to_date)),
						        'date' => true,
           					),
           		array(
           					'attribute' =>'special_to_date',
           					'gteq' =>date('Y-m-d G:i:s', strtotime($filter_date_start)),
						        'date' => true,
           					),
           		))
			->addAttributeToSort('special_to_date','desc');
		}

		Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($products);
        Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($products);
        $products->setPageSize( $this->getConfig('limit_item', 'catalog_source_setting') );
        $this->setProductCollection($products);
		$this->_addProductAttributesAndPrices($products);
        $list = array();

		if (($_products = $this->getProductCollection ()) && $_products->getSize ()) {            
			$list = $products;
		}

		return $list;
    }
	
    
    public function getBought($product_sku = "") {
        $sku = nl2br($product_sku);
        $product = Mage::getResourceModel('reports/product_collection')
            ->addOrderedQty()
            ->addAttributeToFilter('sku', $sku)
            ->setOrder('ordered_qty', 'desc')
            ->getFirstItem();
        return (int)$product->getOrderedQty();
    }
  
    function inArray($source, $target) {
		for($i = 0; $i < sizeof ( $source ); $i ++) {
			if (in_array ( $source [$i], $target )) {
			return true;
			}
		}
    }
	
    function getProductByCategory(){
        $return = array(); 
        $pids = array();
        $catsid = $this->getSourceConfig('catsid');
        $products = Mage::getResourceModel ( 'catalog/product_collection' );
         
        foreach ($products->getItems() as $key => $_product){
            $arr_categoryids[$key] = $_product->getCategoryIds();
            
            if($catsid){    
                if(stristr($catsid, ',') === FALSE) {
                    $arr_catsid[$key] =  array(0 => $catsid);
                }else{
                    $arr_catsid[$key] = explode(",", $catsid);
                }
                
                $return[$key] = $this->inArray($arr_catsid[$key], $arr_categoryids[$key]);
            }
        }
        
        foreach ($return as $k => $v){ 
            if($v==1) $pids[] = $k;
        }    
        
        return $pids;   
    }
}
