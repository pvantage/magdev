<?php
class Ves_Base_Block_Widget_Image extends Mage_Core_Block_Template implements Mage_Widget_Block_Interface{

	/**
	 * Contructor
	 */
	public function __construct($attributes = array())
	{
		parent::__construct( $attributes );
		$this->setTemplate( "ves/base/image.phtml" );

	}
	protected function _toHtml(){
		if(!Mage::getStoreConfig('ves_base/general_setting/show')) {
			return ;
		}
		$widget_heading = $this->getConfig("title");
		$image_file = $this->getConfig('file');
		$imagesize = $this->getConfig('image_size');
		$imageurl = Mage::getBaseUrl("media").$image_file;

		$array_size = explode("x", $imagesize);
		$image_width = isset($array_size[0])?(int)$array_size[0]:0;
		$image_width = $image_width?$image_width: 0;
		$image_height = isset($array_size[1])?(int)$array_size[1]:0;
		$image_height = $image_height?$image_height: 0;

		$thumbnailurl = "";
		if ($image_file) {
            $thumbnailurl = Mage::helper("ves_base")->resizeImage($image_file, (int)$image_width, (int)$image_height);
        }

        $this->assign('widget_heading', $widget_heading);
		$this->assign('imageurl', $imageurl);
		$this->assign('thumbnailurl', $thumbnailurl);
		$this->assign('addition_cls', $this->getConfig('addition_cls'));
		$this->assign('stylecls', $this->getConfig('stylecls'));
		$this->assign('animation', $this->getConfig('animation'));
		$this->assign('alignment', $this->getConfig('alignment'));
		$this->assign('ispopup',$this->getConfig('popup'));
		$this->assign('link_url',$this->getConfig('link'));

		return parent::_toHtml();
	}
	/**
	 * get value of the extension's configuration
	 *
	 * @return string
	 */
	public function getConfig( $key, $default = ""){
	    $value = $this->getData($key);
	    //Check if has widget config data
	    if($this->hasData($key) && $value !== null) {

	      if($value == "true") {
	        return 1;
	      } elseif($value == "false") {
	        return 0;
	      }
	      
	      return $value;
	      
	    }
	    return $default;
	}
}