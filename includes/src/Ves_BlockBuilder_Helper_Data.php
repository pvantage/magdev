<?php

class Ves_BlockBuilder_Helper_Data extends Mage_Core_Helper_Abstract {
	
	public function getShortCode($key, $alias = "") {
		if($key && $alias) {
			return '{{widget type="'.trim($key).'" block_id="'.trim($alias).'"}}';
		}
		return  ;
	}

    public function generateBlockBuilder($alias = "") {
        if($alias) {
            $short_code = $this->getShortCode("ves_blockbuilder/widget_builder", $alias);
            $processor = Mage::helper('cms')->getPageTemplateProcessor();
            return $processor->filter($short_code);
        }
        return ;
    }

     public function runShortcode($short_code = "") {
        if($short_code) {
            $processor = Mage::helper('cms')->getPageTemplateProcessor();
            return $processor->filter($short_code);
        }
        return ;
    }

	public function checkModuleInstalled( $module_name = "") {
        $modules = Mage::getConfig()->getNode('modules')->children();
        $modulesArray = (array)$modules;
        if($modulesArray) {
            $tmp = array();
            foreach($modulesArray as $key=>$value) {
                $tmp[$key] = $value;
            }
            $modulesArray = $tmp;
        }

        if(isset($modulesArray[$module_name])) {

            if((string)$modulesArray[$module_name]->active == "true") {
                return true;
            } else {
                return false;
            }
            
        } else {
            return false;
        }
    }

    public function getWidgetFormUrl($target_id = "") {
        $params = array();
        if($target_id) {
            $params['widget_target_id'] = $target_id;
        }

        $admin_route = Mage::getConfig()->getNode('admin/routers/adminhtml/args/frontName');
        $admin_route = $admin_route?$admin_route:"admin";

        $url = Mage::getSingleton('adminhtml/url')->getUrl('*/widget/loadOptions', $params);
        $url = str_replace("/blockbuilder/","/{$admin_route}/", $url);
        return $url;
    }

    public function getListWidgetsUrl($target_id = "") {
        //return Mage::helper("adminhtml")->getUrl("*/*/listwidgets"); 
        $params = array();
        if($target_id) {
            $params['widget_target_id'] = $target_id;
        }

        $admin_route = Mage::getConfig()->getNode('admin/routers/adminhtml/args/frontName');
        $admin_route = $admin_route?$admin_route:"admin";
        
        $url = Mage::getSingleton('adminhtml/url')->getUrl('*/widget/index', $params);
        $url = str_replace("/blockbuilder/","/{$admin_route}/", $url);
        return $url;
    }

    public function getWidgetDataUrl() {
        return Mage::helper("adminhtml")->getUrl("*/*/widgetdata");
    }

    public function getImageUrl() {
        return str_replace(array('index.php/', 'index.php'), '', Mage::getBaseUrl('media'));
    }

    /**
     * Handles CSV upload
     * @return string $filepath
     */
    public function getUploadedFile() {
        $filepath = null;

        if(isset($_FILES['importfile']['name']) and (file_exists($_FILES['importfile']['tmp_name']))) {
            try {

                $uploader = new Varien_File_Uploader('importfile');
                $uploader->setAllowedExtensions(array('csv','txt', 'json', 'xml')); // or pdf or anything
                $uploader->setAllowRenameFiles(false);
                $uploader->setFilesDispersion(false);

                $path = Mage::helper('ves_blockbuilder')->getImportPath();
                $file_type = "csv";
                if($_FILES['importfile']['tmp_name']['type'] == "application/json") {
                    $file_type = "json";
                }
                $uploader->save($path, "ves_pagebuilder_sample_data.".$file_type);
                $filepath = $path . "ves_pagebuilder_sample_data.".$file_type;

            } catch(Exception $e) {
                // log error
                Mage::logException($e);
            } // end if

        } // end if

        return $filepath;

    }

    public function getImportPath($theme = ""){
        $path = Mage::getBaseDir('var') . DS . 'cache'.DS;

        if (is_dir_writeable($path) != true) {
            mkdir ($path, '0744', $recursive  = true );
        } // end

        return $path;
    }
    public function getAllStores() {
        $allStores = Mage::app()->getStores();
        $stores = array();
        foreach ($allStores as $_eachStoreId => $val) 
        {
            $stores[]  = Mage::app()->getStore($_eachStoreId)->getId();
        }
        return $stores;
    }
}

?>