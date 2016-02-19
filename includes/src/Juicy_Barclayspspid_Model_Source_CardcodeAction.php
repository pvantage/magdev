<?php 
/**
 * Config - Mandate CSC Option
 * 
 * @copyright Juicy Media Ltd, 2011
 * @license Commercial, visit: www.juicyextensions.co.uk 
 * @author chenry (craig.henry@juicymedia.co.uk)
 * @version 1.0
 */
class Juicy_Barclaysepdq_Model_Source_CardcodeAction 
{
    public function toOptionArray()
    {
       
    	$array = array(
            array('value' => '1', 'label' => Mage::helper('barclayspspid')->__('Yes')),
            array('value' => '2', 'label' => Mage::helper('barclayspspid')->__('No'))
        );
        
       
    	return $array; 
    }
}

?>