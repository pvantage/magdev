<?php
/**
 * Redirection to Barclays CPI
 * 
 * @copyright Juicy Media Ltd, 2011
 * @license Commercial, visit: www.juicyextensions.co.uk 
 * @author chenry (craig.henry@juicymedia.co.uk)
 * @version 1.0
 */

class Juicy_Barclayspspid_Block_Dynamic extends Mage_Core_Block_Abstract
{
    protected function _toHtml()
    {     
    	$html = $this->getformhtml();
        return $html;
    }
}