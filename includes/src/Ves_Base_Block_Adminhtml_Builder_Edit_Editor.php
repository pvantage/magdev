<?php
class Ves_Base_Block_Adminhtml_Builder_Edit_Editor extends Mage_Core_Block_Template
{
    var $_model = null;
    /**
     * Contructor
     */
    public function __construct($attributes = array())
    {
        $value = "";
        if (isset($attributes['value'])) {
            $value = $attributes['value'];
        }
        if(isset($attributes['model'])) {
            $this->_model = $attributes['model'];
        } else {
            $this->_model = Mage::registry("block_data");
        }

        $placeholder = Mage::getDesign()->getSkinUrl('images/catalog/product/placeholder/image.jpg',array('_area'=>'frontend'));

        $params = $this->_model->getParams();

        $avaialable_widgets = $this->_getAvailableWidgets();

        $widgets_info = Mage::helper("ves_base/widget")->getListWidgetTypes("array", $avaialable_widgets);
        $widgets_json = $widgets_info?Zend_Json::encode( $widgets_info ): "";
        
        $this->assign("widgets_json", $widgets_json);
        $this->assign("widgets", $widgets_info);
        $this->assign("placeholder", $placeholder);
        $this->assign("value", $value);
        $this->assign("builder_data", $this->_model);
        $this->assign("params", $params);

        $this->setTemplate("ves_base/builder/editor.phtml");

        parent::__construct();
    }
    /**
     * Return array of available widgets based on configuration
     *
     * @return array
     */
    protected function _getAvailableWidgets()
    {
        $result = array();
        $allWidgets = Mage::getModel('widget/widget')->getWidgetsArray();

        $skipped = $this->_getSkippedWidgets();
        foreach ($allWidgets as $widget) {
            if (is_array($skipped) && in_array($widget['type'], $skipped)) {
                continue;
            }
            $result[] = $widget;
        }

        return $result;
    }
    protected function _getSkippedWidgets() {
        return null;
    }
    /**
     * Rendering block content
     *
     * @return string
     */
    function _toHtml() 
    {   
        return parent::_toHtml();
    }
    protected function getBlock()
    {
        return $this->_model;
    }

    public function getRowClass() {
        return array(   "default",
                        "primary",
                        "success",
                        "info",
                        "warning",
                        "danger"
                    );
    }
    public function getRowRepeats() {
        return array(   "" => Mage::helper("ves_base")->__("Theme Default"),
                        "repeat" => Mage::helper("ves_base")->__("Repeat"),
                        "repeat-x" => Mage::helper("ves_base")->__("Repeat X"),
                        "repeat-y" => Mage::helper("ves_base")->__("Repeat Y"),
                        "no-repeat" => Mage::helper("ves_base")->__("No Repeat"),
                        "inherit" => Mage::helper("ves_base")->__("Inherits from parent element")
                    );
    }
    public function getRowAttachments() {
        return array(   "" => Mage::helper("ves_base")->__("Theme Default"),
                        "scroll" => Mage::helper("ves_base")->__("The background scrolls along with the element"),
                        "fixed" => Mage::helper("ves_base")->__("The background is fixed with regard to the viewport"),
                        "local" => Mage::helper("ves_base")->__("The background scrolls along with the elements contents"),
                        "inherit" => Mage::helper("ves_base")->__("Inherits from parent element")
                    );
    }
    public function getRowPositions() {
        return array(   "" => Mage::helper("ves_base")->__("Theme Default"),
                        "left top" => Mage::helper("ves_base")->__("left top"),
                        "left center" => Mage::helper("ves_base")->__("left center"),
                        "left bottom" => Mage::helper("ves_base")->__("left bottom"),
                        "right top" => Mage::helper("ves_base")->__("right top"),
                        "right center" => Mage::helper("ves_base")->__("right center"),
                        "right bottom" => Mage::helper("ves_base")->__("right bottom"),
                        "center top" => Mage::helper("ves_base")->__("center top"),
                        "center center" => Mage::helper("ves_base")->__("center center"),
                        "center bottom"  => Mage::helper("ves_base")->__("center bottom")
                    );
    }
    public function getCSSAnimations(){
        return array(
                  array('value' => "", 'label'=>Mage::helper('adminhtml')->__('No Animation')),
                  array('value' => "bounce", 'label'=>Mage::helper('adminhtml')->__('bounce')),
                  array('value' => "flash", 'label'=>Mage::helper('adminhtml')->__('flash')),
                  array('value' => "pulse", 'label'=>Mage::helper('adminhtml')->__('pulse')),
                  array('value' => "rubberBand", 'label'=>Mage::helper('adminhtml')->__('rubberBand')),
                  array('value' => "shake", 'label'=>Mage::helper('adminhtml')->__('shake')),
                  array('value' => "swing", 'label'=>Mage::helper('adminhtml')->__('swing')),
                  array('value' => "tada", 'label'=>Mage::helper('adminhtml')->__('tada')),
                  array('value' => "wobble", 'label'=>Mage::helper('adminhtml')->__('wobble')),
                  array('value' => "bounceIn", 'label'=>Mage::helper('adminhtml')->__('bounceIn')),
                  array('value' => "bounceInDown", 'label'=>Mage::helper('adminhtml')->__('bounceInDown')),
                  array('value' => "bounceInLeft", 'label'=>Mage::helper('adminhtml')->__('bounceInLeft')),
                  array('value' => "bounceInRight", 'label'=>Mage::helper('adminhtml')->__('bounceInRight')),
                  array('value' => "bounceInUp", 'label'=>Mage::helper('adminhtml')->__('bounceInUp')),
                  array('value' => "fadeIn", 'label'=>Mage::helper('adminhtml')->__('fadeIn')),
                  array('value' => "fadeInDown", 'label'=>Mage::helper('adminhtml')->__('fadeInDown')),
                  array('value' => "fadeInDownBig", 'label'=>Mage::helper('adminhtml')->__('fadeInDownBig')),
                  array('value' => "fadeInLeft", 'label'=>Mage::helper('adminhtml')->__('fadeInLeft')),
                  array('value' => "fadeInLeftBig", 'label'=>Mage::helper('adminhtml')->__('fadeInLeftBig')),
                  array('value' => "fadeInRight", 'label'=>Mage::helper('adminhtml')->__('fadeInRight')),
                  array('value' => "fadeInRightBig", 'label'=>Mage::helper('adminhtml')->__('fadeInRightBig')),
                  array('value' => "fadeInUp", 'label'=>Mage::helper('adminhtml')->__('fadeInUp')),
                  array('value' => "fadeInUpBig", 'label'=>Mage::helper('adminhtml')->__('fadeInUpBig')),
                  array('value' => "flip", 'label'=>Mage::helper('adminhtml')->__('flip')),
                  array('value' => "flipInX", 'label'=>Mage::helper('adminhtml')->__('flipInX')),
                  array('value' => "flipInY", 'label'=>Mage::helper('adminhtml')->__('flipInY')),
                  array('value' => "lightSpeedIn", 'label'=>Mage::helper('adminhtml')->__('lightSpeedIn')),
                  array('value' => "rotateIn", 'label'=>Mage::helper('adminhtml')->__('rotateIn')),
                  array('value' => "rotateInDownLeft", 'label'=>Mage::helper('adminhtml')->__('rotateInDownLeft')),
                  array('value' => "rotateInDownRight", 'label'=>Mage::helper('adminhtml')->__('rotateInDownRight')),
                  array('value' => "rotateInUpLeft", 'label'=>Mage::helper('adminhtml')->__('rotateInUpLeft')),
                  array('value' => "rotateInUpRight", 'label'=>Mage::helper('adminhtml')->__('rotateInUpRight')),
                  array('value' => "hinge", 'label'=>Mage::helper('adminhtml')->__('hinge')),
                  array('value' => "rollIn", 'label'=>Mage::helper('adminhtml')->__('rollIn')),
                  array('value' => "zoomIn", 'label'=>Mage::helper('adminhtml')->__('zoomIn')),
                  array('value' => "zoomInDown", 'label'=>Mage::helper('adminhtml')->__('zoomInDown')),
                  array('value' => "zoomInLeft", 'label'=>Mage::helper('adminhtml')->__('zoomInLeft')),
                  array('value' => "zoomInRight", 'label'=>Mage::helper('adminhtml')->__('zoomInRight')),
                  array('value' => "zoomInUp", 'label'=>Mage::helper('adminhtml')->__('zoomInUp'))
                  );
    }

    public function getWidgetClasses() {
        return array("" => Mage::helper("adminhtml")->__("Default"),
                    "primary" => Mage::helper("adminhtml")->__("Primary"),
                    "danger" => Mage::helper("adminhtml")->__("Danger"),
                    "info" => Mage::helper("adminhtml")->__("Info"),
                    "warning" => Mage::helper("adminhtml")->__("Warning"),
                    "highlighted" => Mage::helper("adminhtml")->__("Highlighted"),
                    "nopadding" => Mage::helper("adminhtml")->__("Nopadding")
                    );
    }

    public function getOffCanvasTypes() {
        return array("" => Mage::helper("adminhtml")->__("Disable"),
                    "left" => Mage::helper("adminhtml")->__("Enable Left Sidebar"),
                    "right" => Mage::helper("adminhtml")->__("Enable Right Sidebar"),
                    "both" => Mage::helper("adminhtml")->__("Enable Both Left & Right Sidebar")
                    );
    }

    public function getOffColTypes() {
        return array("" => Mage::helper("adminhtml")->__("Default"),
                    "left" => Mage::helper("adminhtml")->__("Offcanvas Left"),
                    "right" => Mage::helper("adminhtml")->__("Offcanvas Right"),
                    "main-column" => Mage::helper("adminhtml")->__("Main Column")
                    );
    }

}
