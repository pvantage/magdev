<?php
class Ves_Base_Block_Widget_Video extends Mage_Core_Block_Template implements Mage_Widget_Block_Interface{

	/**
	 * Contructor
	 */
	public function __construct($attributes = array())
	{
		
		parent::__construct($attributes);
	}

	protected function _toHtml(){
		if(!Mage::getStoreConfig('ves_base/general_setting/show')) {
			return ;
		}
		$this->setTemplate($this->getConfig('video_template'));
		$this->assign('id',$this->getConfig('id'));
		$this->assign('title',$this->getConfig('title'));
		$this->assign('height',str_replace('px','',$this->getConfig('height')));
		$this->assign('width',str_replace('px','',$this->getConfig('width')));
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