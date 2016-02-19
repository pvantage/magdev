<?php
class Ves_Base_Block_Widget_Ourservice extends Mage_Core_Block_Template implements Mage_Widget_Block_Interface{


	public function __construct($attributes = array())
	{
		parent::__construct($attributes);

		if($this->hasData("template")) {
        	$my_template = $this->getData("template");
        }else{
 			$my_template = "ves/base/ourservice.phtml";
 		}
        $this->setTemplate($my_template);
	}



	protected function _toHtml(){
		if(!Mage::getStoreConfig('ves_base/general_setting/show')) {
			return ;
		}

		$this->assign('widget_heading', $this->getConfig('title'));
		$this->assign('stylecls', $this->getConfig('stylecls'));
		
		$image_file = $this->getConfig('file');
		$imageurl = Mage::getBaseUrl("media").$image_file;

		$content_html = $this->getConfig('content_html');
		$content_html = base64_decode($content_html);

		if($content_html) {
			$processor = Mage::helper('cms')->getPageTemplateProcessor();
			$content_html = $processor->filter($content_html);
		}

		$this->assign('icon', $this->getConfig('icon'));
		$this->assign('content', $content_html);

		$imagesize = $this->getConfig('imagesize');
		$array_size = explode("x", $imagesize);
		$image_width = isset($array_size[0])?(int)$array_size[0]:0;
		$image_width = $image_width?$image_width: 0;
		$image_height = isset($array_size[1])?(int)$array_size[1]:0;
		$image_height = $image_height?$image_height: 0;
		
		$thumbnailurl = "";
		if ($image_file) {
            $thumbnailurl = Mage::helper("ves_base")->resizeImage($image_file, (int)$image_width, (int)$image_height);
        }

        $font_size = $this->getConfig('font_size','');
        $this->assign('font_size', $font_size);
        $this->assign('thumbnailurl', $thumbnailurl);
        $this->assign('imageurl', $imageurl);
		$this->assign('icon_position', $this->getConfig('icon_position'));

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