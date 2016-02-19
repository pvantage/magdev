<?php
/**
 * Config - Credit Card Types
 * 
 * @copyright Juicy Media Ltd, 2011
 * @license Commercial, visit: www.juicyextensions.co.uk 
 * @author chenry (craig.henry@juicymedia.co.uk)
 * @version 1.0
 */
class Juicy_Barclayspspid_Model_Source_CreditCards 
{
    public function toOptionArray()
    {
        
        
        $array = array(
            array('value' => 'VISA', 'label' => Mage::helper('barclayspspid')->__('VISA')),
            array('value' => 'MasterCard', 'label' => Mage::helper('barclayspspid')->__('MasterCard')),
            array('value' => 'American Express', 'label' => Mage::helper('barclayspspid')->__('American Express')),
           
        );
        
       
    	return $array;
    }
}
?>