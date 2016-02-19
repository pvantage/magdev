<?php
/**
 * Standard model that does the hard work of sending data to Barclays
 * 
 * @copyright Juicy Media Ltd, 2011
 * @license Commercial, visit: www.juicyextensions.co.uk 
 * @author chenry (craig.henry@juicymedia.co.uk)
 * @version 1.0
 */
class Juicy_Barclayspspid_Model_Barclayspspid extends Mage_Payment_Model_Method_Abstract
{
    protected $_code  = 'barclayspspid_standard';
    protected $_formBlockType = 'Juicy_Barclayspspid_Block_Form';
    protected $_infoBlockType = 'Juicy_Barclayspspid_Block_Info';
	
    
    const PAYMENT_TYPE_PAYMENT      = 'SAL';
    const PAYMENT_TYPE_AUTHENTICATE = 'RES';
    
    protected $_isGateway               = false;
    protected $_canAuthorize            = true;
    protected $_canCapture              = true;
    protected $_canCapturePartial       = true;
    protected $_canRefundInvoicePartial = true;
    protected $_canRefund               = true;
    protected $_canVoid                 = false;
    protected $_canUseInternal          = false;
    protected $_canUseCheckout          = true;
    protected $_canUseForMultishipping  = false;

    protected $_order = null;
    
    
    public function refund(Varien_Object $payment, $amount)
    {
    	$orderStatus = 'closed';
    	$szMessage = 'Payment refunded';
    	
    	$directlink = new Juicy_Barclayspspid_Model_Directlink();
    	
    	$result = $directlink->refundpayment($payment, $amount);
        
    	$status = (int)$result['STATUS'];
    	
    	$error = false;
    	
    	if ($status != 81) {
    		$error = $result['NCERRORPLUS'];
    	}
    	
    	if($error === false)
    	{
    		$order = $payment->getOrder();
    		$payment = $order->getPayment();
    		$arAdditionalInformationArray = $payment->getAdditionalInformation();
    		 
    		$arAdditionalInformationArray["Refunded"] = 1;
    		$payment->setAdditionalInformation($arAdditionalInformationArray);
    		$payment->save();
    		 
    		$order->setState('refunded', $orderStatus, $szMessage, false);
    		$order->save();
    	}
    	else
    	{
    		Mage::throwException($error);
    	}
    	
    	return $this;

    	
    }
    
    
	/**
     *  Form block description
     *
     *  @return	 object
     */
    public function createFormBlock($name)
    {
        $block = $this->getLayout()->createBlock('Juicy_Barclayspspid_Block_Form', $name);
        $block->setMethod('barclayspspid_standard');
        $block->setPayment($this->getPayment());
        $block->setTemplate('form.phtml');

        return $block;
    }
    
	/**
     * Get Config model
     *
     * @return object Juicy_Barclayscpi_Model_Config
     */
    public function getConfig()
    {
        return Mage::getSingleton('Juicy_Barclayspspid_Model_Config');
    }
    
   
    public function checkShaout($data)
    {
    	$config = Mage::getModel('Juicy_Barclayspspid_Model_Config');
    	
    	$shaout = $config->getShaout();
    	
    	$datacheck = array();
    	
    	foreach ($data as $key => $value) {
    		if ($value == "") {
    			continue;
    		}
    		$datacheck[$key] = $value;
    	}
    	
    	$origsig = $datacheck['SHASIGN'];
    	
    	unset($datacheck['SHASIGN']);
    	
    	uksort($datacheck, 'strcasecmp');
    	$shasign = "";
    	
    	$shasig = null;
    	foreach ($datacheck as $key => $value) {
    		$shasig .= trim(strtoupper($key)).'='.utf8_encode(trim($value)).$shaout;
    	}
    	
    	$shasig = strtoupper(hash('sha1',$shasig));
    	
    	if ($shasig == $origsig) {
    		return true;
    	} else {
    		return false;
    	}
    	
    }
    
    public function capture(Varien_Object $payment, $amount)
    {
    	 
    	$order = $payment->getOrder();
    	 
    	$data = unserialize($payment->getadditional_data());

    	if ($data['STATUS'] != 9) {
    	
	    	$directlink = new Juicy_Barclayspspid_Model_Directlink();
	    	 
	    	$result = $directlink->capturepayment($payment, $amount);
 
	    	if ($result['STATUS'] == 91) {
	    
	    		$order->getPayment()->setTransactionId($result['PAYID']);
	    		$order->save();
	    	} else {
	    		Mage::throwException('Capture Failed');
	    	}
    	}
    	 
    	return $this;

    }
	
    
	/**
     *  Return Order Place Redirect URL
     *
     *  @return	  string Order Redirect URL
     */
    public function getOrderPlaceRedirectUrl()
    {
        return Mage::getUrl('barclayspspid/payment/redirect');
    }
}