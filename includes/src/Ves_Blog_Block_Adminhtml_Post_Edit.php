<?php

class Ves_Blog_Block_Adminhtml_Post_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();

        $this->_objectId    = 'id';
        $this->_blockGroup  = 'ves_blog';
        $this->_controller  = 'adminhtml_post';

        $this->_updateButton('save', 'label', Mage::helper('ves_blog')->__('Save Record'));
        $this->_updateButton('delete', 'label', Mage::helper('ves_blog')->__('Delete Record'));
	   
		$this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_addButton("duplicate", array(
            "label" => Mage::helper("ves_blog")->__("Duplicate"),
            "onclick" => "duplicateBlock()",
            "class" => "save",
        ), -100);

        $this->_formScripts[] = "
            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
            function duplicateBlock() {
                editForm.submit($('edit_form').action+'duplicate/1/');
            }
        ";
		
    }

    protected function _prepareLayout() {
         /**
         * Display store switcher if system has more one store
         */
        if (!Mage::app()->isSingleStoreMode()) {
            $this->setChild('store_switcher',
                   $this->getLayout()->createBlock('adminhtml/store_switcher')
                   ->setUseConfirm(false)
                   ->setSwitchUrl($this->getUrl('*/*/*/id/'.Mage::registry('post_data')->get('post_id'), array('store'=>null)))
           );
        }

        return parent::_prepareLayout();
    }

    public function getHeaderText()
    {
		if( Mage::registry('post_data')->getId() ) {
			return Mage::helper('ves_blog')->__("Edit Record '%s'", $this->htmlEscape(Mage::registry('post_data')->getTitle()));
		}else {
			return Mage::helper('ves_blog')->__( "Add New Post" );
		}
	}
}