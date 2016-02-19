<?php 
/**
 * Failure message block
 * 
 * @copyright Juicy Media Ltd, 2011
 * @license Commercial, visit: www.juicyextensions.co.uk 
 * @author chenry (craig.henry@juicymedia.co.uk)
 * @version 1.0
 */
class Juicy_Barclayspspid_Block_Failure extends Mage_Core_Block_Template
{
    /**
     *  Return StatusDetail field value from Response
     *
     *  @return	  string
     */
    public function getErrorMessage ()
    {
        $error = Mage::getSingleton('Mage_Checkout_Model_Session')->getErrorMessage();
        Mage::getSingleton('Mage_Checkout_Model_Session')->unsErrorMessage();
        Mage::log('Juicy_Barclayspspid_Block_Failure: '.$error, null, 'barclays-pspid.log');
        return $error;
    }

    /**
     * Get continue shopping url
     */
    public function getContinueShoppingUrl()
    {
        return Mage::getUrl('checkout/cart');
    }
}

?>