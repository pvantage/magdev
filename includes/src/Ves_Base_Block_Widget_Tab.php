<?php
class Ves_Base_Block_Widget_Tab extends Mage_Core_Block_Template implements Mage_Widget_Block_Interface{


	public function __construct($attributes = array())
	{
		parent::__construct($attributes);

		if($this->hasData("template")) {
        	$my_template = $this->getData("template");
        }else{
 			$my_template = "ves/base/tab.phtml";
 		}
        $this->setTemplate($my_template);
	}



	protected function _toHtml(){
		if(!Mage::getStoreConfig('ves_base/general_setting/show')) {
			return ;
		}
		$tabs = array();
		$limit = 20;
		$processor = Mage::helper('cms')->getPageTemplateProcessor();

		for($i=1; $i<=$limit; $i++) {
			$tmp = array();
			$tmp['content'] = $this->getConfig("content_".$i);
			$tmp['header'] = $this->getConfig("header_".$i);
			if($tmp['content'] && $tmp['header']) {
				$tmp['content'] = base64_decode($tmp['content']);
				$tmp['content'] = $processor->filter($tmp['content']);
				$tabs[] = $tmp;
			}
		}

		$this->assign('addition_cls', $this->getConfig('addition_cls'));
		$this->assign('tabs', $tabs );
		$this->assign('stylecls', $this->getConfig('stylecls'));
		$this->assign('widget_heading', $this->getConfig('title'));

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