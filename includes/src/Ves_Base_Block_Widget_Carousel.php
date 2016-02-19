<?php
class Ves_Base_Block_Widget_Carousel extends Mage_Core_Block_Template implements Mage_Widget_Block_Interface{


	public function __construct($attributes = array())
	{
		parent::__construct($attributes);

		if($this->hasData("template")) {
        	$my_template = $this->getData("template");
        }else{
 			$my_template = "ves/base/carousel.phtml";
 		}
 		
        $this->setTemplate($my_template);
	}



	protected function _toHtml(){
		if(!Mage::getStoreConfig('ves_base/general_setting/show')) {
			return ;
		}
		$carousels = array();
		$limit = 20;
		$processor = Mage::helper('cms')->getPageTemplateProcessor();

		for($i=1; $i<=$limit; $i++) {
			$tmp = array();
			$tmp['content'] = $this->getConfig("content_".$i);
			$tmp['header'] = $this->getConfig("header_".$i);
			if($tmp['content'] && $tmp['header']) {
				$tmp['content'] = base64_decode($tmp['content']);
				$tmp['content'] = $processor->filter($tmp['content']);
				$carousels[] = $tmp;
			}
		}

		$this->assign('addition_cls', $this->getConfig('addition_cls'));
		$this->assign('carousels', $carousels );
		$this->assign('stylecls', $this->getConfig('stylecls'));
		$this->assign('widget_heading', $this->getConfig('title'));
		/*Carousel settings*/
		$this->assign('loop', $this->getConfig('loop'));
		$this->assign('mouse_drag', $this->getConfig('mouse_drag'));
		$this->assign('touch_drag', $this->getConfig('touch_drag'));
		$this->assign('slide_by', $this->getConfig('slide_by'));
		$this->assign('margin_item', $this->getConfig('margin_item'));
		$this->assign('default_items', $this->getConfig('default_items'));
		$this->assign('mobile_items', $this->getConfig('mobile_items'));
		$this->assign('tablet_small_items', $this->getConfig('tablet_small_items'));
		$this->assign('tablet_items', $this->getConfig('tablet_items'));
		$this->assign('portrait_items', $this->getConfig('portrait_items'));
		$this->assign('large_items', $this->getConfig('large_items'));
		$this->assign('custom_items', $this->getConfig('custom_items'));
		$this->assign('auto_play', $this->getConfig('auto_play'));
		$this->assign('interval', $this->getConfig('interval'));
		$this->assign('show_navigator', $this->getConfig('show_navigator'));

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