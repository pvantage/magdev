<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Phoenix
 * @package     Phoenix_Moneybookers
 * @copyright   Copyright (c) 2013 Phoenix Medien GmbH & Co. KG (http://www.phoenix-medien.de)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Juicy_Barclayspspid_Block_Motoinfo extends Mage_Payment_Block_Info
{
    protected $_template = 'barclayspspid/motoinfo.phtml';

    /**
     * Returns code of payment method
     *
     * @return string
     */
    
    protected function _construct()
    {
    	parent::_construct();
    	$this->setTemplate('barclayspspid/motoinfo.phtml');
    }
    
    public function getMethodCode()
    {
        return $this->getInfo()->getMethodInstance()->getCode();
    }

    public function getStuff() {
    
    	$data = $this->getInfo()->getOrder()->getPayment()->getAdditionalData();
    	$data = unserialize($data);
    	
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