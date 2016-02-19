<?php
/**
 * Base for Venus Extensions
 *
 * @category   Ves
 * @package    Ves_Base
 * @author     venustheme <venustheme@gmail.com>
 * @copyright  Copyright (c) 2009 Venustheme.com <venustheme@gmail.com>
 */
class Ves_Base_Helper_Data extends Mage_Core_Helper_Abstract{
	public function renderMediaChooser(Varien_Data_Form_Element_Abstract $element) {
		if (Mage::getSingleton('admin/session')->isAllowed('cms/media_gallery')) {

            $layout = $element->getForm()->getParent()->getLayout();
            $id = $element->getHtmlId();

            if ($url = $element->getValue()) {
                $linkStyle = "display:inline;";

                if(!preg_match("/^http\:\/\/|https\:\/\//", $url)) {
                    $url = Mage::getBaseUrl('media') . $url;
                }
            }else{
                $linkStyle = "display:none;";
                $url = "#";
            }

            $hiddenField = '<input type="hidden" name="hidden_file" onchange="return changeFieldImage(this,\''.$id.'\')" id="hidden_file_'.$id.'" class="hidden-file-path" value=""/>';
            $imagePreview = '<a id="' . $id . '_link" href="' . $url . '" style="text-decoration: none; ' . $linkStyle . '"'
                . ' onclick="imagePreview(\'' . $id . '_image\'); return false;">'
                . ' <img src="' . $url . '" id="' . $id . '_image" title="' . $element->getValue() . '"'
                . ' alt="' . $element->getValue() . '" height="30" class="small-image-preview v-middle"/>'
                . ' </a>';

            $selectButtonId = 'add-image-' . mt_rand();
            $chooserUrl = Mage::getUrl('adminhtml/cms_wysiwyg_images_chooser/index', array('target_element_id' => $id));
            $label = ($element->getValue()) ? $this->__('Change Image') : $this->__('Select Image');


            // Select/Change Image Button
            $chooseButton = $layout->createBlock('adminhtml/widget_button')
                ->setType('button')
                ->setClass('add-image')
                ->setId($selectButtonId)
                ->setLabel($label)
                ->setOnclick('openImageManager(this, \'hidden_file_'.$id.'\')')
                ->setDisabled($element->getReadonly())
                ->setStyle('display:inline;margin-top:7px');

            // Remove Image Button
            $onclickJs = '
                document.getElementById(\''. $id .'\').value=\'\';
                document.getElementById(\'hidden_file_'. $id .'\').value=\'\';
                if(document.getElementById(\''. $id .'_image\')){
                    document.getElementById(\''. $id .'_image\').parentNode.style.display = \'none\';
                }
                document.getElementById(\''. $selectButtonId .'\').innerHTML=\'<span><span><span>' . addslashes($this->__('Select Image')) . '</span></span></span>\';
            ';

            $removeButton = $layout->createBlock('adminhtml/widget_button')
                ->setType('button')
                ->setClass('delete')
                ->setLabel($this->__('Remove Image'))
                ->setOnclick($onclickJs)
                ->setDisabled($element->getReadonly())
                ->setStyle('margin-top:7px');


            $wrapperStart = '<div id="buttons_' . $id . '" class="buttons-set" style=" width: 325px;">';
            $wrapperEnd = '</div>';

            // Add our custom HTML after the form element
            $element->setAfterElementHtml($wrapperStart . $hiddenField. $imagePreview . $chooseButton->toHtml() . $removeButton->toHtml() . $wrapperEnd);
        }

        return $element;
	}

    public function resizeImage($image, $width = 100, $height = 100){
        if($width == 0 || $height == 0) {
            return Mage::getBaseUrl("media").$image;
        }
        $_imageUrl = Mage::getBaseDir('media').DS.$image;
        $_imageResized = Mage::getBaseDir('media').DS."resized".DS.(int)$width."x".(int)$height.DS.$image;

        if (!file_exists($_imageResized)&&file_exists($_imageUrl)){
            $imageObj = new Varien_Image($_imageUrl);
            $imageObj->constrainOnly(TRUE);
            $imageObj->keepAspectRatio(TRUE);
            $imageObj->keepTransparency(true);
            $imageObj->keepFrame(FALSE);
            $imageObj->resize($width, $height);
            $imageObj->save($_imageResized);
        }
        return Mage::getBaseUrl("media")."resized/".(int)$width."x".(int)$height."/".$image;
    }

    /*
    * Recursively searches and replaces all occurrences of search in subject values replaced with the given replace value
    * @param string $search The value being searched for
    * @param string $replace The replacement value
    * @param array $subject Subject for being searched and replaced on
    * @return array Array with processed values
    */
    public function recursiveReplace($search, $replace, $subject)
    {
        if(!is_array($subject))
        return $subject;
    
        foreach($subject as $key => $value)
        if(is_string($value))
        $subject[$key] = str_replace($search, $replace, $value);
        elseif(is_array($value))
        $subject[$key] = self::recursiveReplace($search, $replace, $value);
    
        return $subject;
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
    
}