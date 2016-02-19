<?php
class Ves_Socialsidebar_Helper_Data extends Mage_Core_Helper_Abstract {
	
	public function getGeneralConfig( $key ){
		return $this->getConfig( $key );
	}
 
	public function getSourceConfig( $key ){
		return $this->getConfig( $key, "catalog_source_setting" );
	}
}
