<?php
/**
 * Gets variables from Magentos config
 * 
 * @copyright Juicy Media Ltd, 2011
 * @license Commercial, visit: www.juicyextensions.co.uk 
 * @author chenry (craig.henry@juicymedia.co.uk)
 * @version 1.0
 */
class Juicy_Barclayspspid_Model_Config extends Varien_Object
{
    const PAYMENT_TYPE_PAYMENT      = 'Auth';
    const PAYMENT_TYPE_AUTHENTICATE = 'PreAuth';
    
    /**
     *  Return config var
     *
     *  @param    string Var key
     *  @param    string Default value for non-existing key
     *  @return	  mixed
     */
    public function getConfigData($key, $default=false)
    {    	
        if (!$this->hasData($key)) {
             $value = Mage::getStoreConfig('payment/barclayspspid_standard/'.$key);
             if (is_null($value) || false===$value) {
                 $value = $default;
             }
            $this->setData($key, $value);
        }
        return $this->getData($key);
    }

	/*
     * Return CSC
     */
    public function getPspid() {
    	return $this->getConfigData('pspid');
    }
    
    public function getDebuglog() {
    	return $this->getConfigData('debuglog');
    }
    
    public function getDebugEmail() {
    	return $this->getConfigData('debugemail');
    }
    
    public function getMagheader() {
    	return $this->getConfigData('magheader');
    }
    
    public function getUsername() {
    	return $this->getConfigData('username');
    }
    
    public function getUserpassword() {
    	return $this->getConfigData('userpassword');
    }
    
    public function getBasket() {
    	return $this->getConfigData('basket');
    }
    
    public function getDelivery() {
    	return $this->getConfigData('delivery');
    }
    
    public function getPagetitle() {
    	return $this->getConfigData('pagetitle');
    }
    
    public function getPaymentaction() {
    	return $this->getConfigData('paymentaction');
    }
    
    public function getDynamic() {
    	return $this->getConfigData('dynamic');
    }
    
    public function getDynamictemplate() {
    	return $this->getConfigData('dynamictemplate');
    }
    
    public function getTestmode() {
    	return $this->getConfigData('testmode');
    }
    
    public function getCardtypes() {
    	return $this->getConfigData('cardtypes');
    }
    
    public function getCurrency() {
    	return $this->getConfigData('currency');
    }
    
    public function getShain() {
    	return $this->getConfigData('shain');
    }
    
    public function getShaout() {
    	return $this->getConfigData('shaout');
    }
    
    public function getBgcolor() {
    	return $this->getConfigData('bgcolor');
    }
    
    public function getTxtcolor() {
    	return $this->getConfigData('txtcolor');
    }
    
    public function getTblbgcolor() {
    	return $this->getConfigData('tblbgcolor');
    }
    
    public function getTbltxtcolor() {
    	return $this->getConfigData('tbltxtcolor');
    }
    
    public function getButtonbgcolor() {
    	return $this->getConfigData('buttonbgcolor');
    }
    
    public function getButtontxtcolor() {
    	return $this->getConfigData('buttontxtcolor');
    }

    public function getLogo() {
    	return $this->getConfigData('logo');
    }
    
    public function getFonttype() {
    	return $this->getConfigData('fonttype');
    }
    
    
   
    
}