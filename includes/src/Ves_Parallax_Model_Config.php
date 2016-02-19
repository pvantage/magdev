<?php
class Ves_Parallax_Model_Config extends Mage_Catalog_Model_Product_Media_Config {

    const CACHE_BLOCK_TAG = "ves_parallax_block";
    
    public function getBaseMediaPath() {
        return Mage::getBaseDir('media') .DS. 'parallax';
    }

    public function getBaseMediaUrl() {
        return Mage::getBaseUrl('media') . 'parallax';
    }

    public function getBaseTmpMediaPath() {
        return Mage::getBaseDir('media') .DS. 'tmp' .DS. 'parallax';
    }

    public function getBaseTmpMediaUrl() {
        return Mage::getBaseUrl('media') . 'tmp/parallax';
    }

}