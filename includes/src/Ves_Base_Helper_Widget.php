<?php
/**
 * Base for Venus Extensions
 *
 * @category   Ves
 * @package    Ves_Base
 * @author     venustheme <venustheme@gmail.com>
 * @copyright  Copyright (c) 2009 Venustheme.com <venustheme@gmail.com>
 */
class Ves_Base_Helper_Widget extends Mage_Core_Helper_Abstract{

	var $_widgetinfo = "/Ves/Base/etc/widgetinfo.xml";

	public function getListWidgetTypes($type = "array", $available_widgets = array()) {
		$widgets = array();
		$controller_name = Mage::app()->getRequest()->getControllerName();
		$module_name = Mage::app()->getRequest()->getModuleName();
		$module_controller = $module_name."/".$controller_name;

		/*Get Widget Information*/
		if(Mage::registry("widgets_data")) {
			$widgets = Mage::registry("widgets_data");
		} else {
			$widgetinfo_xml = Mage::getBaseDir('code').'/community'.$this->_widgetinfo;

			if( file_exists($widgetinfo_xml)  ){

				$xmlObj = new Varien_Simplexml_Config($widgetinfo_xml);
				$type_widgets = array();
				$info = $xmlObj->getNode();
				if($info->widget) {
	                    foreach($info->widget as $widget) {
	                    	$attributes = $widget->attributes();
	                    	$type = isset($attributes['type'])?trim($attributes['type']):"";
	                    	$type_widgets[$type] = $type;
	                    	$tmp = array();
	                    	$show_in_extensions = array();
	                    	$checked = true;
	                    	if($widget->show) {
	                    		$show_in_extensions = explode(",", (string)$widget->show);
	                    	}
	                    	if($show_in_extensions) {
	                    		if(!in_array($module_name."/".$controller_name, $show_in_extensions)) {
	                    			$checked = false;
	                    		}
	                    	}
	                    	if(!$checked)
	                    		continue;

	                    	$tmp['type'] = $type;
	                    	$tmp['title'] = (string)$widget->title;
	                    	$tmp['code'] = (string)$widget->code;
	                    	$tmp['description'] = (string)$widget->description;
	                    	$tmp['icon'] = (string)$widget->icon;
	                    	$tmp['group'] = (string)$widget->group;

	                    	$widgets[] = $tmp;
	                    }
				}
			}
			/*Get other available widgets*/
			$tmp_available_widgets = array();

			if(is_array($available_widgets) && $available_widgets) {
				foreach($available_widgets as $widget) {

					$tmp_available_widgets[$widget['type']] = $widget['type'];

					if(is_array($type_widgets) && in_array($widget['type'], $type_widgets)) 
						continue;
					$tmp = array();
					$tmp['type'] = $widget['type'];
	                $tmp['title'] = (string)$widget['name'];
	                $tmp['code'] = (string)$widget['code'];
	                $tmp['description'] = (string)$widget['description'];
	                $tmp['icon'] = (string)$widget['code'];
	                $tmp['group'] = "others";
	                $widgets[] = $tmp;
				}
			}
			/*Remove not available widget*/
			if($widgets && $tmp_available_widgets) {
				$tmp_widgets = array();
				foreach($widgets as $widget) {
					if(in_array($widget['type'], $tmp_available_widgets)) {
						$tmp_widgets[] = $widget;
					}
				}
				$widgets = $tmp_widgets;
			}

			Mage::register("widgets_data", $widgets);
		}
		if($type == "json") {
			return Zend_Json::encode($widgets);
		}

		return $widgets;
	}

	public function getWidgetInfo($widget_type = "") {

	}
}