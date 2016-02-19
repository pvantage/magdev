<?php
/**
 * Config - Payment Action
 * 
 * @copyright Juicy Media Ltd, 2011
 * @license Commercial, visit: www.juicyextensions.co.uk 
 * @author chenry (craig.henry@juicymedia.co.uk)
 * @version 1.0
 */
class Juicy_Barclayspspid_Model_Source_PaymentAction 
{
    public function toOptionArray()
    {
        return array(
            array('value' => 'SAL', 'label' => Mage::helper('barclayspspid')->__('Sale')),
            array('value' => 'RES', 'label' => Mage::helper('barclayspspid')->__('Authorisation')),
        );
    }
}