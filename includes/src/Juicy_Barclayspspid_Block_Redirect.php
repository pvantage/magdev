<?php
/**
 * Redirection to Barclays CPI
 * 
 * @copyright Juicy Media Ltd, 2014
 * @license Commercial, visit: www.juicyextensions.co.uk 
 * @author chenry (support@juicymedia.co.uk)
 * @version 1.0
 */

class Juicy_Barclayspspid_Block_Redirect extends Mage_Core_Block_Abstract
{
    protected function _toHtml()
    {
       
        //Mage::log('Juicy_Barclayspspid_Block_Redirect: Start', null, 'barclays-pspid.log');
        
    	$form = $this->getepdqform();
    	
    	//Mage::log('Juicy_Barclayspspid_Block_Redirect: Form: '.$form, null, 'barclays-pspid.log');
 	
        $html = '<html><body>';
        $html .= '<div style="color:#2f2f2f; font:11px/1.35em Verdana, Arial, Helvetica, sans-serif;">';
        $html .= $this->__('You will be redirected to the Barclays website to make your payment, please wait a few seconds.');
        $html .= '</div>';
        $html .= $form;
        $html .= '<script type="text/javascript">document.getElementById("form1").submit();</script>';
        $html .= '</body></html>';
       
        return $html;
    }
}