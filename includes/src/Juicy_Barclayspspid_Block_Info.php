<?php

class Juicy_Barclayspspid_Block_Info extends Mage_Payment_Block_Info
{
    protected $_template = 'barclayspspid/info.phtml';

    /**
     * Returns code of payment method
     *
     * @return string
     */
    
    protected function _construct()
    {
    	parent::_construct();
    	$this->setTemplate('barclayspspid/info.phtml');
    }
    
    public function getMethodCode()
    {
        return $this->getInfo()->getMethodInstance()->getCode();
    }

    public function getStuff() {
    
    	$data_in = $this->getInfo()->getOrder()->getPayment()->getAdditionalData();
    	$data = unserialize($data_in);
    	
    	Mage::log('Juicy_Barclayspspid_Block_Info: '.$data_in, null, 'barclays-pspid.log');
    	
    	unset($data['SHASIGN']);
    	unset($data['ED']);
    	unset($data['NCERROR']);
    	unset($data['BIN']);
    	unset($data['ECI']);
    	unset($data['SCORING']);
    	unset($data['SCO_CATEGORY']);
    	unset($data['VC']);
    	
    	foreach ($data as $key => $value) {
    		
    		
    		switch ($key) {
    			case "SUBBRAND":
    				$key2 = "Card Holders Bank";
    				break;
    			case "CVCCheck":
    				$key2 = "CVC Check";
    				break;
    			case "AAVCheck";
    				$key2 = "AVS Check";
    				break;
    			case  "AAVZIP":
    				$key2 = "AVS Postcode";
    				break;
    			case "AAVADDRESS":
    				$key2 = "AVS Address";
    				break;
    			case "IPCTY":
    				$key2 = "Order Placed from";
    				break;
    			case "CCCTY":
    				$key2 = "Bank Country of Origin";
    				break;
    			case "orderID":
    				$key2 = "Order ID";
    				break;
    			case "CN":
    				$key2 = "Card Holder Name";
    				break;
    			case "CARDNO":
    				$key2 = "Credit Card Number (Masked)";
    				break;
    			case "currency":
    				$key2 = "Payment Currency";
    				break;
    			case "amount":
    				$key2 = "Amount Paid";
    				break;
    			case "PM":
    				$key2 = "Payment Method";
    				break;
    			case "TRXDATE":
    				$key2 = "Transaction Date";
    				break;
    			case "IP":
    				$key2 = "IP address of Payee";
    				break;
    			case "PAYID":
    				$key2 = "Payment ID";
    				break;
    			case "ACCEPTANCE":
    				$key2 = "Authorization Code";
    				break;
    			case "BRAND":
    				$key2 = "Card Type";
    				break;
    			case "STATUS":
    				$key2 = "Status";
    				break;
    			default:
    				$key2 = $key;
    				break;
    			
    			
    		}
    		
    		
    		
    		$data2[$key2] = $value;
    	}
    	
    	
    	return $data2;
    
    }
}