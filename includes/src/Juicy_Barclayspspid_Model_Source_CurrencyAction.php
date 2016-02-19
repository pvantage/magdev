<?php 
/**
 * Config - Currency Codes
 * 
 * @copyright Juicy Media Ltd, 2011
 * @license Commercial, visit: www.juicyextensions.co.uk 
 * @author chenry (craig.henry@juicymedia.co.uk)
 * @version 1.0
 */
class Juicy_Barclayspspid_Model_Source_CurrencyAction 
{
    public function toOptionArray()
    {
       
    	$array = array(
            array('value' => 'GBP', 'label' => Mage::helper('barclayspspid')->__('GBP')),
            array('value' => 'EUR', 'label' => Mage::helper('barclayspspid')->__('EUR')),
            array('value' => 'AUD', 'label' => Mage::helper('barclayspspid')->__('AUD')),
            array('value' => 'CAD', 'label' => Mage::helper('barclayspspid')->__('CAD')),
            array('value' => 'CNY', 'label' => Mage::helper('barclayspspid')->__('CNY')),
            array('value' => 'CYR', 'label' => Mage::helper('barclayspspid')->__('CYR')),
            array('value' => 'CZK', 'label' => Mage::helper('barclayspspid')->__('CZK')),
            array('value' => 'DKK', 'label' => Mage::helper('barclayspspid')->__('DKK')),
            array('value' => 'EEK', 'label' => Mage::helper('barclayspspid')->__('EEK')),
            array('value' => 'HKD', 'label' => Mage::helper('barclayspspid')->__('HKD')),
            array('value' => 'ISK', 'label' => Mage::helper('barclayspspid')->__('ISK')),
            array('value' => 'INR', 'label' => Mage::helper('barclayspspid')->__('INR')),
            array('value' => 'ILS', 'label' => Mage::helper('barclayspspid')->__('ILS')),
            array('value' => 'JPY', 'label' => Mage::helper('barclayspspid')->__('JPY')),
            array('value' => 'LVL', 'label' => Mage::helper('barclayspspid')->__('LVL')),
            array('value' => 'LTL', 'label' => Mage::helper('barclayspspid')->__('LTL')),
            array('value' => 'MTL', 'label' => Mage::helper('barclayspspid')->__('MTL')),
            array('value' => 'MAL', 'label' => Mage::helper('barclayspspid')->__('MAL')),
            array('value' => 'NZD', 'label' => Mage::helper('barclayspspid')->__('NZD')),
            array('value' => 'NOK', 'label' => Mage::helper('barclayspspid')->__('NOK')),
            array('value' => 'PLN', 'label' => Mage::helper('barclayspspid')->__('PLN')),
            array('value' => 'RUB', 'label' => Mage::helper('barclayspspid')->__('RUB')),
            array('value' => 'SGD', 'label' => Mage::helper('barclayspspid')->__('SGD')),
            array('value' => 'SKK', 'label' => Mage::helper('barclayspspid')->__('SKK')),
            array('value' => 'KRW', 'label' => Mage::helper('barclayspspid')->__('KRW')),
            array('value' => 'SEK', 'label' => Mage::helper('barclayspspid')->__('SEK')),
            array('value' => 'SEF', 'label' => Mage::helper('barclayspspid')->__('SEF')),
            array('value' => 'USD', 'label' => Mage::helper('barclayspspid')->__('USD')),
            array('value' => 'SAR', 'label' => Mage::helper('barclayspspid')->__('SAR')),
            array('value' => 'PLN', 'label' => Mage::helper('barclayspspid')->__('PLN')),
            array('value' => 'THB', 'label' => Mage::helper('barclayspspid')->__('THB')),
            array('value' => 'AED', 'label' => Mage::helper('barclayspspid')->__('AED'))
        );
        
       
    	return $array; 
    }
}

?>