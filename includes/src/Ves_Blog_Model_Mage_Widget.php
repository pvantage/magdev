<?php 
class Ves_Blog_Model_Mage_Widget extends Mage_Widget_Model_Widget
{
    public function getWidgetDeclaration($type, $params = array(), $asIs = true)
    {

        if( preg_match('~(^ves_blog/widget_latest)~', $type) )
        {
            $params['latestmod_desc'] = base64_encode($params['latestmod_desc']);
        }

        return parent::getWidgetDeclaration($type, $params, $asIs);
    }
}