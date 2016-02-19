<?php 
class Ves_Base_Model_Mage_Widget extends Mage_Widget_Model_Widget
{
    public function getWidgetDeclaration($type, $params = array(), $asIs = true)
    {
        if( preg_match('~(^ves_base/widget_facebook)~', $type) || preg_match('~(^ves_base/widget_facebook)~', $type) )
        {
            $params['custom_css'] = base64_encode($params['custom_css']);
        }

        if( preg_match('~(^ves_base/widget_tab)~', $type) || preg_match('~(^ves_base/widget_accordion)~', $type) || preg_match('~(^ves_base/widget_carousel)~', $type))
        {
            $count = 32;

            for($i=1; $i <= $count; $i++) {
                if(isset($params['content_'.$i])) {
                    $params['content_'.$i] = base64_encode($params['content_'.$i]);
                }
            }

        }
        /*Orther module widgets*/
        if(isset($params['pretext'])) {
            $params['pretext'] = base64_encode($params['pretext']);
        }

        if(isset($params['content_html'])) {
            $params['content_html'] = base64_encode($params['content_html']);
        }

        if(isset($params['html'])) {
            $params['html'] = base64_encode($params['html']);
        }

        if(isset($params['raw_html'])) {
            $params['raw_html'] = base64_encode($params['raw_html']);
        }

        if(isset($params['content'])) {
            $params['content'] = base64_encode($params['content']);
        }
       
        if( preg_match('~(^ves_blog/widget_latest)~', $type) )
        {
            $params['latestmod_desc'] = base64_encode($params['latestmod_desc']);
        }

        return parent::getWidgetDeclaration($type, $params, $asIs);
    }
}