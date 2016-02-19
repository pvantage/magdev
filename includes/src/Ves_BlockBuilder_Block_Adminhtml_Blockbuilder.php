<?php


class Ves_BlockBuilder_Block_Adminhtml_Blockbuilder extends Mage_Adminhtml_Block_Widget_Grid_Container
{

    public function __construct()
    {

        $this->_controller = "adminhtml_blockbuilder";
        $this->_blockGroup = "ves_blockbuilder";
        $this->_headerText = Mage::helper("ves_blockbuilder")->__("Block Profile Manager");
        $this->_addButtonLabel = Mage::helper("ves_blockbuilder")->__("Add New Item");
        parent::__construct();

    }

     protected function _prepareLayout() {
	
        $this->setChild('import_button',
                $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                'label'     => Mage::helper('ves_blockbuilder')->__('Import CSV'),
                'onclick'   => "setLocation('".$this->getUrl('*/*/uploadCsv')."')",
                'class'   => 'add'
                ))
        );

        return parent::_prepareLayout();
    }

    public function getImportButtonHtml() {
        return $this->getChildHtml('import_button');
    }

}