<?php


class Ves_Parallax_Block_Adminhtml_Banner_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $_model = Mage::registry('banner_data');
        $form = new Varien_Data_Form();
        $this->setForm($form);

        $fieldset = $form->addFieldset('slider_form', array('legend'=>Mage::helper('ves_parallax')->__('General Information')));
        
		
		$fieldset->addField('is_active', 'select', array(
            'label'     => Mage::helper('ves_parallax')->__('Is Active'),
            'name'      => 'is_active',
            'values'    => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray(),
            //'value'     => $_model->getIsActive()
        ));

        
        $fieldset->addField('position', 'text', array(
            'label'     => Mage::helper('ves_parallax')->__('Position'),
            'class'     => '',
            'required'  => false,
            'name'      => 'position',
            'after_element_html' => '<p class="nm"><small>' . Mage::helper('ves_parallax')->__('Input theme position, for example: <strong>slideshow</strong> or <strong>massbottom</strong> or <strong>showcase</strong>,.. <br/>Use comma to add more positions') . '</small></p>'
            //'value'     => $_model->getPosition()
        ));

		$fieldset->addField('file', 'image', array(
            'label'     => Mage::helper('ves_parallax')->__('Image'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'file'
        ));

        $fieldset->addField('image_width', 'text', array(
            'label'     => Mage::helper('ves_parallax')->__('Image Width'),
            'class'     => '',
            'required'  => false,
            'name'      => 'image_width',
            'after_element_html' => '<p class="nm"><small>' . Mage::helper('ves_parallax')->__('Input image width (for example: 800), default = auto') . '</small></p>'
        ));

        $fieldset->addField('image_height', 'text', array(
            'label'     => Mage::helper('ves_parallax')->__('Image Height'),
            'class'     => '',
            'required'  => false,
            'name'      => 'image_height',
            'after_element_html' => '<p class="nm"><small>' . Mage::helper('ves_parallax')->__('Input image height (for example: 800), default = auto') . '</small></p>'
        ));
		
        $fieldset->addField('percent', 'text', array(
            'label'     => Mage::helper('ves_parallax')->__('Percent'),
            'class'     => '',
            'required'  => false,
            'name'      => 'percent',
            'after_element_html' => '<p class="nm"><small>' . Mage::helper('ves_parallax')->__('For example: 50%') . '</small></p>'
        ));

        $fieldset->addField('scroll', 'text', array(
            'label'     => Mage::helper('ves_parallax')->__('Scroll'),
            'class'     => '',
            'required'  => false,
            'name'      => 'scroll',
            'after_element_html' => '<p class="nm"><small>' . Mage::helper('ves_parallax')->__('For example: 0.4') . '</small></p>'
        ));

	
        if (!Mage::app()->isSingleStoreMode()) {
            $fieldset->addField('store_id', 'multiselect', array(
                'name' => 'stores[]',
                'label' => Mage::helper('ves_parallax')->__('Store View'),
                'title' => Mage::helper('ves_parallax')->__('Store View'),
                'required' => true,
                'values' => Mage::getSingleton('adminhtml/system_store')
                             ->getStoreValuesForForm(false, true),
            ));
        } else {
            $fieldset->addField('store_id', 'hidden', array(
                'name' => 'stores[]',
                'value' => Mage::app()->getStore(true)->getId()
            ));
        }

		if ( Mage::getSingleton('adminhtml/session')->getBannerData() )
		  {
			  $form->setValues(Mage::getSingleton('adminhtml/session')->getBannerData());
			  Mage::getSingleton('adminhtml/session')->getBannerData(null);
		  } elseif ( Mage::registry('banner_data') ) {
			  $form->setValues(Mage::registry('banner_data')->getData());
		  }
        
        return parent::_prepareForm();
    }
}
