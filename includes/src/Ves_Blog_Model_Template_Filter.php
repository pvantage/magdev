<?php

class Ves_Blog_Model_Template_Filter extends Mage_Widget_Model_Template_Filter{
    /*public function customDirective($construction){



        $params = $this->_getIncludeParameters($construction[2]); 


        echo "<pre>";
        print_r($params);
        echo "</pre>";


        return print_r($params, 2);
    }*/

    public function customDirective($construction)
    {
        $params = $this->_getIncludeParameters($construction[2]);

        // Determine what name block should have in layout
        $name = null;
        if (isset($params['name'])) {
            $name = $params['name'];
        }

        // validate required parameter type or id
        if (!empty($params['type'])) {
            $type = $params['type'];
        } elseif (!empty($params['id'])) {
            $preconfigured = Mage::getResourceSingleton('widget/widget')
                ->loadPreconfiguredWidget($params['id']);
            $type = $preconfigured['widget_type'];
            $params = $preconfigured['parameters'];
        } else {
            return '';
        }

        // we have no other way to avoid fatal errors for type like 'cms/widget__link', '_cms/widget_link' etc.
        $xml = Mage::getSingleton('widget/widget')->getXmlElementByType($type);
        if ($xml === null) {
            return '';
        }

        // define widget block and check the type is instance of Widget Interface
        $widget = Mage::app()->getLayout()->createBlock($type, $name, $params);
        if (!$widget instanceof Mage_Widget_Block_Interface) {
            return '';
        }

        return $widget->toHtml();
    }
}