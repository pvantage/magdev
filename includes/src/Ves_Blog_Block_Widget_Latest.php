<?php
class Ves_Blog_Block_Widget_Latest extends Ves_Blog_Block_Latest implements Mage_Widget_Block_Interface
{

	/**
	 * Contructor
	 */
	public function __construct($attributes = array())
	{
		if(is_array($attributes)) {
			$attributes['block_type'] = "widget_latest";
		}
		parent::__construct( $attributes );

	}
	
	public function _toHtml(){
		return parent::_toHtml();
	}
}