<?php

class Ves_Socialsidebar_Block_System_Config_Form_Field_Information  extends Mage_Adminhtml_Block_Abstract implements Varien_Data_Form_Element_Renderer_Interface
{
	public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $useContainerId = $element->getData('use_container_id');
        return sprintf('<td colspan="2">If you not account at <a target="_BLANK" href="https://www.addthis.com">Add This</a>, you can <a target="_BLANK" href="https://www.addthis.com/register">create</a> a account and <a target="_BLANK" href="https://www.addthis.com/settings/publisher">get data-id </a>to input in this param.</td>', $element->getHtmlId(), $element->getHtmlId(), $element->getLabel()
        );
    }
}
?>