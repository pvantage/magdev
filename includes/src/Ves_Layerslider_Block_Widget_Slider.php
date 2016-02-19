<?php
class Ves_Layerslider_Block_Widget_Slider extends Ves_Layerslider_Block_List implements Mage_Widget_Block_Interface
{

	
	/**
	 * Contructor
	 */
	public function __construct($attributes = array())
	{
        parent::__construct();

        $my_template = $this->getTemplate();
 		if(empty($my_template)) {
 			$my_template = "ves/layerslider/default.phtml";
 		}
        $this->setTemplate($my_template);
		
	}

	public function _toHtml() {
		$this->_show = $this->getData("show");
 		if($this->_show == null) {
 			$this->_show = $this->getConfig("show");
 		}
		if(!$this->_show) return;
		/*End init meida files*/

        $alias = null;
        $banner_id = $this->getData("bannerId");
		$banner_id = $banner_id?$banner_id:0;
        $alias = $this->getData('alias');
        $this->_banner = null;

        if($banner_id) {
			$this->_banner  = Mage::getModel('ves_layerslider/banner')->load( $banner_id );
		}

        if(!$this->_banner && $alias) {
        	$this->_banner = Mage::getModel('ves_layerslider/banner')->getSliderByAlias($alias);
        }

 		$banner  = $this->getSliderBanner();
		if(!$this->_show || empty($banner)) return;

		$is_active =  $banner->getData("is_active");

		if($is_active) {
			$banners = array();
			$setting = array();
			$params = $banner->getData("params");
			$params = unserialize(base64_decode($params) );

			$options = $banner->getData("options");
			$setting = unserialize($options);
			$setting['width'] = isset($setting['width'])?$setting['width']:1070;
			$setting['height'] = isset($setting['height'])?$setting['height']:460;

			if($params) {
				foreach($params as $key => $slider) {
					if(strpos($key, "slide-container-") !== false && $slider) {
						if(isset($slider['type']) && $slider['type'] == 'image' && $slider['src']) {
							$slider['src'] = Mage::helper("ves_layerslider")->getImage( $slider['src'] );
						}

						$banners[] = $slider;
						
					}
				}
				
				$setting['general'] = isset($params['bg'])?$params['bg']:array();

				if(isset($setting['general']['src']) && $setting['general']['src']) {
					$setting['general']['src'] = Mage::helper("ves_layerslider")->getImage( $setting['general']['src'] );
				}
				$setting['width'] = isset($params['ss']['width'])?(int)$params['ss']['width']:$setting['width'];
				$setting['height'] = isset($params['ss']['height'])?(int)$params['ss']['height']:$setting['height'];
			}

			$this->assign("sliderParams", $setting);
			$this->assign("setting", $setting);
			$this->assign("params", $params);
			$this->assign("banners", $banners);

		}

        return parent::_toHtml();
	}
	
}