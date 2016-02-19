<?php

class Ves_ProductCarousel2_Block_List extends Mage_Catalog_Block_Product_Abstract 
{
	/**
	 * @var string $_config
	 * 
	 * @access protected
	 */
	protected $_config = '';

	protected $_current_page = 1;
	
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

		$theme = ($this->getConfig('theme')!="") ? $this->getConfig('theme') : "default";
		
		parent::__construct();
		

		if(isset($attributes['template']) && $attributes['template']) {
			$this->setTemplate($attributes['template']);
		} elseif($this->hasData("template")) {
			$this->setTemplate($this->getData('template'));
		} else {
			$template = 'ves/productcarousel2/default.phtml';
			$this->setTemplate( $template );
		}
			
		/*Cache Block*/
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
          Ves_ProductCarousel2_Model_Config::CACHE_BLOCK_TAG
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
           'VES_PRODUCTCAROUSEL2_BLOCK_LIST',
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
	public function _toHtml() {

		if( !$this->getConfig('show') ) return;		 
		$cms = "";

		$cms_block_id = $this->getConfig('cmsblock');
		if($cms_block_id){
			$cms = Mage::getSingleton('core/layout')->createBlock('cms/block')->setBlockId($cms_block_id)->toHtml();
		}

		$items = $this->getListProducts();

		$this->assign( "items", $items );
		
		$this->assign( "cms", $cms );

        return parent::_toHtml();
	}
	
	public function getEffectConfig( $key ){
		return $this->getConfig( $key, "effect_setting" );
	}
	/**
	 * get value of the extension's configuration
	 *
	 * @return string
	 */
	function getConfig( $key, $panel='ves_productcarousel2', $default = "" ){

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
	        $return = Mage::getStoreConfig("ves_productcarousel2/$panel/$key");
	      }
	      if($return == "" && !$default) {
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
 	
  	 
	 
	function set($params){
		
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
		$mode = $this->getConfig('sourceProductsMode', "catalog_source_setting" );

		switch ($mode) {
			case 'latest' :
			$products = $this->getListLatestProducts();
			break;
			case 'sale' :
			$products = $this->getListSaleProducts();
			break;
			case 'best_buy' : 
			$products = $this->getListBestSellerProducts();
			break;
			case 'most_viewed' :
			$products = $this->getListMostViewedProducts();
			break;
			case 'featured' :
			$products = $this->getListFeaturedProducts();
			break;
			case 'top_rated' :
			$products = $this->getListTopRatedProducts();
			break;
			default   :
			$products = $this->getListNewProducts();
			break;
			
		}
		
		return $products;
	}
	public function getListTopRatedProducts() {
		$limit = $this->getConfig('limit_item', 'catalog_source_setting');
		$limit = empty($limit)?6:(int)$limit;
		$storeId    = Mage::app()->getStore()->getId();
		$cateids = $this->getConfig('catsid', 'catalog_source_setting');
		if($cateids && $cateids != "1") {
			$cateids = explode(",", $cateids);
			$productIds = $this->getProductByCategory();
			$products = Mage::getResourceModel('reports/product_collection')
                   ->addAttributeToSelect('*')
                   ->addAttributeToFilter('visibility', array('neq'=>1))
                   ->addIdFilter($productIds);

			$products->joinField('rating_summary_field', 'review/review_aggregate', 'rating_summary', 'entity_pk_value=entity_id',  array('entity_type' => 1, 'store_id' => Mage::app()->getStore()->getId()), 'left');

			$products->setOrder('rating_summary_field', 'desc');
			
			$products->load();

		} else {
			$products = Mage::getResourceModel('reports/product_collection')
                   ->addAttributeToSelect('*')
                   ->addAttributeToFilter('visibility', array('neq'=>1));
			$products->joinField('rating_summary_field', 'review/review_aggregate', 'rating_summary', 'entity_pk_value=entity_id',  array('entity_type' => 1, 'store_id' => Mage::app()->getStore()->getId()), 'left');                
			$products->setOrder('rating_summary_field', 'desc');
			
			$products->load();
		}
		

		Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($products);
        Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($products);
        $products->setPageSize( $limit )->setCurPage($this->_current_page);
        $this->setProductCollection($products);

		$this->_addProductAttributesAndPrices($products);
        $list = array();                  
		if (($_products = $this->getProductCollection ()) && $_products->getSize ()) {            
			$list = $products;
		}
		
		return $list;
	}

	public function getListSaleProducts(){

		$limit = $this->getConfig('limit_item', 'catalog_source_setting');
		$limit = empty($limit)?6:(int)$limit;
		$storeId    = Mage::app()->getStore()->getId();
		$todayDate  = Mage::app()->getLocale()->date()->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);

		$cateids = $this->getConfig('catsid', 'catalog_source_setting');
		if($cateids && $cateids != "1") {
			$productIds = $this->getProductByCategory();
			$products = Mage::getModel('catalog/product')->getCollection();
			$products->addAttributeToSelect('*')
			                   ->addFieldToFilter('visibility', array(
			                               Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH,
			                               Mage_Catalog_Model_Product_Visibility::VISIBILITY_IN_CATALOG
			                   )) //showing just products visible in catalog or both search and catalog
			                   ->addMinimalPrice()
							   ->addUrlRewrite()
							   ->addTaxPercents()
							   ->addStoreFilter($storeId)
							   ->addIdFilter($productIds)
			                   ->addFinalPrice()
			                   ->getSelect()
			                   ->where('price_index.final_price < price_index.price');
    	} else {
		    $products = Mage::getModel('catalog/product')->getCollection();
			$products->addAttributeToSelect('*')
			                   ->addFieldToFilter('visibility', array(
			                               Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH,
			                               Mage_Catalog_Model_Product_Visibility::VISIBILITY_IN_CATALOG
			                   )) //showing just products visible in catalog or both search and catalog
			                   ->addMinimalPrice()
							   ->addUrlRewrite()
							   ->addTaxPercents()
							   ->addStoreFilter($storeId)
			                   ->addFinalPrice()
			                   ->getSelect()
			                   ->where('price_index.final_price < price_index.price');
    	}

    	Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($products);
        Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($products);
        $products->setPageSize( $limit )->setCurPage($this->_current_page);
        $this->setProductCollection($products);

		$this->_addProductAttributesAndPrices($products);
        $list = array();                  
		if (($_products = $this->getProductCollection ()) && $_products->getSize ()) {            
			$list = $products;
		}
		
		return $list;
	}
    
    public function getListLatestProducts($fieldorder = 'updated_at', $order = 'desc')
    {
    	$limit = $this->getConfig('limit_item', 'catalog_source_setting');
		$limit = empty($limit)?6:(int)$limit;
    	$storeId = Mage::app()->getStore()->getId();
    	$cateids = $this->getConfig('catsid', 'catalog_source_setting');

    	$todayStartOfDayDate  = Mage::app()->getLocale()->date()
            ->setTime('00:00:00')
            ->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);

        $todayEndOfDayDate  = Mage::app()->getLocale()->date()
            ->setTime('23:59:59')
            ->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);
            
    	if($cateids && $cateids != "1") {
    	    $productIds = $this->getProductByCategory();
			$products = Mage::getResourceModel('catalog/product_collection')
					    ->addAttributeToSelect('*')
					    ->addAttributeToFilter('news_from_date', array('or'=> array(
			                0 => array('date' => true, 'to' => $todayEndOfDayDate),
			                1 => array('is' => new Zend_Db_Expr('null')))
			            ), 'left')
			            ->addAttributeToFilter('news_to_date', array('or'=> array(
			                0 => array('date' => true, 'from' => $todayStartOfDayDate),
			                1 => array('is' => new Zend_Db_Expr('null')))
			            ), 'left')
			            ->addAttributeToSort('news_from_date', 'desc')
			            ->addAttributeToSort($fieldorder, $order)
					    ->addMinimalPrice()
					    ->addUrlRewrite()
					    ->addTaxPercents()
					    ->addStoreFilter($storeId)
					    ->addIdFilter($productIds);
    	} else {
	   		$products = Mage::getResourceModel('catalog/product_collection')
					    ->addAttributeToSelect('*')
					    ->addAttributeToFilter('news_from_date', array('or'=> array(
			                0 => array('date' => true, 'to' => $todayEndOfDayDate),
			                1 => array('is' => new Zend_Db_Expr('null')))
			            ), 'left')
			            ->addAttributeToFilter('news_to_date', array('or'=> array(
			                0 => array('date' => true, 'from' => $todayStartOfDayDate),
			                1 => array('is' => new Zend_Db_Expr('null')))
			            ), 'left')
			            ->addAttributeToSort('news_from_date', 'desc')
			            ->addAttributeToSort($fieldorder, $order)
					    ->addMinimalPrice()
					    ->addFinalPrice()
					    ->addStoreFilter($storeId)
					    ->addUrlRewrite()
					    ->addTaxPercents();
    	}		
        Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($products);
        Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($products);
        $products->setPageSize( $limit )->setCurPage($this->_current_page);
        $this->setProductCollection($products);
		
		$this->_addProductAttributesAndPrices($products);
        $list = array();                  
		if (($_products = $this->getProductCollection ()) && $_products->getSize ()) {            
			$list = $products;
		}
		
		return $list;
    }
    
    public function getListBestSellerProducts($fieldorder = 'ordered_qty', $order = 'desc')
    {
    	$limit = $this->getConfig('limit_item', 'catalog_source_setting');
		$limit = empty($limit)?6:(int)$limit;
    	$fieldorder = 'created_at';
    	$order = 'desc';
    	$this->_config = $config;
    	$storeId    = Mage::app()->getStore()->getId();
    	$cateids = $this->getConfig('catsid');
    	$todayStartOfDayDate  = Mage::app()->getLocale()->date()
            ->setTime('00:00:00')
            ->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);

        $todayEndOfDayDate  = Mage::app()->getLocale()->date()
            ->setTime('23:59:59')
            ->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);
    	if($cateids && $cateids != "1") {
    	    $productIds = $this->getProductByCategory();
			$products = Mage::getResourceModel('catalog/product_collection')
		    ->addAttributeToSelect('*')
		    ->addAttributeToFilter('news_from_date', array('or'=> array(
                0 => array('date' => true, 'to' => $todayEndOfDayDate),
                1 => array('is' => new Zend_Db_Expr('null')))
            ), 'left')
            ->addAttributeToFilter('news_to_date', array('or'=> array(
                0 => array('date' => true, 'from' => $todayStartOfDayDate),
                1 => array('is' => new Zend_Db_Expr('null')))
            ), 'left')
            ->addAttributeToFilter(
                array(
                    array('attribute' => 'news_from_date', 'is'=>new Zend_Db_Expr('not null')),
                    array('attribute' => 'news_to_date', 'is'=>new Zend_Db_Expr('not null'))
                    )
              )
            ->addAttributeToSort('news_from_date', 'desc')
		    ->addMinimalPrice()
		    ->addUrlRewrite()
		    ->addTaxPercents()
		    ->addStoreFilter()
		    ->addIdFilter($productIds);
    	} else {
	    $products = Mage::getResourceModel('catalog/product_collection')
		    ->addAttributeToSelect('*')
		    ->addAttributeToFilter('news_from_date', array('or'=> array(
                0 => array('date' => true, 'to' => $todayEndOfDayDate),
                1 => array('is' => new Zend_Db_Expr('null')))
            ), 'left')
            ->addAttributeToFilter('news_to_date', array('or'=> array(
                0 => array('date' => true, 'from' => $todayStartOfDayDate),
                1 => array('is' => new Zend_Db_Expr('null')))
            ), 'left')
            ->addAttributeToFilter(
                array(
                    array('attribute' => 'news_from_date', 'is'=>new Zend_Db_Expr('not null')),
                    array('attribute' => 'news_to_date', 'is'=>new Zend_Db_Expr('not null'))
                    )
              )
            ->addAttributeToSort('news_from_date', 'desc')
		    ->addMinimalPrice()
		    ->addFinalPrice()
		    ->addStoreFilter()
		    ->addUrlRewrite()
		    ->addTaxPercents();
    	}		
        Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($products);
        Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($products);
        $products->setPageSize( $limit )->setCurPage($this->_current_page);
        $this->setProductCollection($products);
		
		$this->_addProductAttributesAndPrices($products);
        $list = array();                  
		if (($_products = $this->getProductCollection ()) && $_products->getSize ()) {            
			$list = $products;
		}
		
		return $list;
    }
    
    public function getListMostViewedProducts()
    {	
    	$limit = $this->getConfig('limit_item', 'catalog_source_setting');
		$limit = empty($limit)?6:(int)$limit;
    	$storeId    = Mage::app()->getStore()->getId();
    	$cateids = $this->getConfig('catsid', 'catalog_source_setting');
    	if($cateids) {
	    $productIds = $this->getProductByCategory();
	    $products = Mage::getResourceModel('reports/product_collection')
			->addAttributeToSelect('*')
			->addMinimalPrice()
			->addUrlRewrite()
			->addTaxPercents()
			->addAttributeToSelect(array('name', 'price', 'small_image')) //edit to suit tastes            
			->setStoreId($storeId)
			->addStoreFilter($storeId)
			->addViewsCount()
			->addIdFilter($productIds);
		} else {  
			$products = Mage::getResourceModel('reports/product_collection')
			->addAttributeToSelect('*')
			->addMinimalPrice()
			->addUrlRewrite()
			->addTaxPercents()
			->addAttributeToSelect(array('name', 'price', 'small_image')) //edit to suit tastes
			->setStoreId($storeId)
			->addStoreFilter($storeId)
			->addViewsCount();
    	}
    	
        Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($products);
        Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($products);
        $products->setPageSize( $limit )->setCurPage($this->_current_page);
        $this->setProductCollection($products);
		$this->_addProductAttributesAndPrices($products);
        $list = array();                  
		if (($_products = $this->getProductCollection ()) && $_products->getSize ()) {            
			$list = $products;
		}
		
		
		return $list;
    }
    
    public function getListFeaturedProducts()
    {
    	$limit = $this->getConfig('limit_item', 'catalog_source_setting');
		$limit = empty($limit)?6:(int)$limit;
    	$storeId    = Mage::app()->getStore()->getId();
    	$cateids = $this->getConfig('catsid', 'catalog_source_setting');
    	if($cateids) {
		    $productIds = $this->getProductByCategory();
		    $products = Mage::getResourceModel('catalog/product_collection')
			    ->addAttributeToSelect('*')
			    ->addMinimalPrice()
			    ->addUrlRewrite()
			    ->addTaxPercents()
			    ->addStoreFilter($storeId)
			    ->addIdFilter($productIds)
			    ->addAttributeToFilter("featured", 1);
    	} else {
	    $products = Mage::getResourceModel('catalog/product_collection')
		    ->addAttributeToSelect('*')
		    ->addMinimalPrice()
		    ->addUrlRewrite()
		    ->addTaxPercents()
		    ->addStoreFilter($storeId)
		    ->addAttributeToFilter("featured", 1);
    	}
    	
        Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($products);
        Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($products);
        $products->setPageSize( $limit )->setCurPage($this->_current_page);
        $this->setProductCollection($products);
		$this->_addProductAttributesAndPrices($products);
		$list = array();                  
		if (($_products = $this->getProductCollection ()) && $_products->getSize ()) {            
			$list = $products;
		}
		
		return $list;
    }
    
    public function getListNewProducts()
    {
    	$limit = $this->getConfig('limit_item', 'catalog_source_setting');
		$limit = empty($limit)?6:(int)$limit;
    	$todayDate  = Mage::app()->getLocale()->date()->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);
    	$storeId    = Mage::app()->getStore()->getId();
    	$cateids = $this->getConfig('catsid', 'catalog_source_setting');
    	if($cateids) {
		    $productIds = $this->getProductByCategory();
			$products = Mage::getResourceModel('catalog/product_collection')
				    ->addAttributeToSelect('*')
				    ->addMinimalPrice()
				    ->addUrlRewrite()
				    ->addTaxPercents()
				    ->addStoreFilter($storeId)
				    ->addIdFilter($productIds)
				    ->addAttributeToFilter('news_from_date', array('date'=>true, 'to'=> $todayDate))
				    ->addAttributeToFilter(array(array('attribute'=>'news_to_date', 'date'=>true, 'from'=>$todayDate), array('attribute'=>'news_to_date', 'is' => new Zend_Db_Expr('null'))),'','left')
				    ->addAttributeToSort('news_from_date','desc');
    	} else {
		    $products = Mage::getResourceModel('catalog/product_collection')
			    ->addAttributeToSelect('*')
			    ->addMinimalPrice()
			    ->addUrlRewrite()
			    ->addTaxPercents()
			    ->addStoreFilter($storeId)
			    ->addAttributeToFilter('news_from_date', array('date'=>true, 'to'=> $todayDate))
			    ->addAttributeToFilter(array(array('attribute'=>'news_to_date', 'date'=>true, 'from'=>$todayDate), array('attribute'=>'news_to_date', 'is' => new Zend_Db_Expr('null'))),'','left')
			    ->addAttributeToSort('news_from_date','desc');
    	}
        Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($products);
        Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($products);
        $products->setPageSize( $limit )->setCurPage($this->_current_page);
        $this->setProductCollection($products);
		$this->_addProductAttributesAndPrices($products);
        $list = array();                  
		if (($_products = $this->getProductCollection ()) && $_products->getSize ()) {            
			$list = $products;
		}
		
		return $list;
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
        
        function getProductByCategory(){
        	$return = array(); 
        	$pids = array();
        	$catsid = $this->getConfig('catsid', 'catalog_source_setting');
        	$products = Mage::getResourceModel ( 'catalog/product_collection' );
        	
        	foreach ($products->getItems() as $key => $_product){
        		$arr_categoryids[$key] = $_product->getCategoryIds();
        		
        		if($catsid && $catsid != "1"){    
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
