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
class Ves_Blog_Block_Tags extends Ves_Blog_Block_List 
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
 			$my_template = "ves/blog/block/tags.phtml";
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
          Ves_Blog_Model_Config::CACHE_BLOCK_TAGS_TAG
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
           "VES_BLOG_BLOCK_TAGS",
           $this->getNameInLayout(),
           Mage::app()->getStore()->getId(),
           Mage::getDesign()->getPackageName(),
           Mage::getDesign()->getTheme('template'),
           Mage::getSingleton('customer/session')->getCustomerGroupId(),
           'template' => $this->getTemplate(),
        );
    }
	
	public function _toHtml(){

		$collection = Mage::getModel( 'ves_blog/post' )
						->getCollection();
		$collection->addPostTagFilter();
		$collection ->setOrder( 'created', 'DESC' );

		$tags = array();
		if(count($collection) > 0) {
			foreach($collection as $item) {
				$tmp_tags = $item->getTags();
				$tmp_array_tags = explode(",",$tmp_tags);
				if($tmp_array_tags) {
					foreach($tmp_array_tags as $tag) {
						$tag = trim($tag);
						if(!isset($tags[$tag])) {
							$tags[$tag] = 1;
						} else {
							$tags[$tag] += 1;
						}
					}
				}
				
			}
		}

		$this->assign( 'tags', $tags );
		return parent::_toHtml();	
	}
}
?>