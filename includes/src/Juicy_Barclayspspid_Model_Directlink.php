<?php 
/**
 * Standard model that does the hard work of sending data to Barclays
 *
 * @copyright Juicy Media Ltd, 2011
 * @license Commercial, visit: www.juicyextensions.co.uk
 * @author chenry (craig.henry@juicymedia.co.uk)
 * @version 1.0
 */


class Juicy_Barclayspspid_Model_Directlink extends Mage_Core_Model_Abstract
{
	
	protected $_liveurl = "https://payments.edpq.co.uk/ncol/prod/maintenancedirect.asp";
	protected $_testurl = "https://mdepayments.epdq.co.uk/ncol/test/maintenancedirect.asp";
	
	protected $_liveorderurl = "https://payments.epdq.co.uk/ncol/prod/orderdirect.asp";
	protected $_testorderurl = "https://mdepayments.epdq.co.uk/ncol/test/orderdirect.asp";
	
	/*
	 * Get the config model
	 */
	private function getConfig()
	{
		$config = new Juicy_Barclayspspid_Model_Config();
		return $config;
	}
	
	/*
	 * Refund the payment
	 */
	public function refundpayment($payment, $amount)
	{
		$config = $this->getConfig();
			
		$info = $payment->getadditional_data();
		$info = unserialize($info);
		$payid = $info['PAYID'];
		$orderid = $payment->getOrder()->getIncrementId();
		
		$order = $payment->getOrder();
		
		
		$parameters = array();
		$parameters['ORDERID'] = $orderid;
		$parameters['AMOUNT'] = $amount * 100;
		
		if ($order->getBaseTotalRefunded() == $order->getBaseGrandTotal()) 
		{
			$parameters['OPERATION'] = "RFS";
		} else {
			$parameters['OPERATION'] = "RFD";
		}
		
		
		$test = $config->getTestmode();
		if ($test == "1") {
			$url = $this->_testurl;
		} else { 
			$url = $this->_liveurl;
		}
		
		Mage::helper('barclayspspid')->log("Refund Post Parameters",$parameters);
		
		$result = $this->performRequest($parameters, $url);
	
		Mage::helper('barclayspspid')->log("Refund Reply Parameters",$result);
		
		return $result;
		
	}
	
	/*
	 * Refund the payment
	*/
	public function capturepayment($payment, $amount)
	{
		$config = $this->getConfig();
			
		$info = $payment->getadditional_data();
		$info = unserialize($info);
		$payid = $info['PAYID'];
		$orderid = $payment->getOrder()->getIncrementId();
	
		$order = $payment->getOrder();
	
	
		$parameters = array();
		$parameters['ORDERID'] = $orderid;
		$parameters['AMOUNT'] = $amount * 100;
	
		
		$parameters['OPERATION'] = "SAS";
		
	
	
		$test = $config->getTestmode();
	
		if ($test == "1") {
			$url = $this->_testurl;
		} else {
			$url = $this->_liveurl;
		}
	
		
		Mage::helper('barclayspspid')->log("Capture Post Parameters",$parameters);
	
		$result = $this->performRequest($parameters, $url);
		
		Mage::helper('barclayspspid')->log("Capture Reply Parameters",$result);
		
		return $result;
	}
	
	/*
	 * Authorize the payment using direct link API
	 */
	public function authorizepayment($payment, $amount)
	{
		
		$order = $payment->getOrder();
		
		$config = Mage::getModel('Juicy_Barclayspspid_Model_Config');
		
		$pspid = $config->getPspid();
		$testmode = $config->getTestmode();
		$cardtypes = $config->getCardtypes();
		$currency = $config->getCurrency();
		$shain = $config->getShain();
		$shaout = $config->getShaout();
		$locale = Mage::app()->getLocale()->getLocaleCode();
		
		
		$paymentaction = $config->getPaymentaction();
		
		
		if ($testmode == "0") {
			$url = $this->_liveorderurl;
		} else {
			$url = $this->_testorderurl;
		}
		
		
		$basket = $config->getBasket();
		$delivery = $config->getDelivery();
		
		$orderid = $order->getIncrementId();
	
		$grandtotal = (int)money_format("%.0n",$amount * 100);
		
		$email = $order->getCustomerEmail();
		
		
		$discountamount = (int)$order->getBaseDiscountAmount() * 100;
		
		$shippingamount = number_format($order->getbase_shipping_amount(),2);
		
		$shipping = $order->getShippingAddress();
		$billing = $order->getBillingAddress();
		
		$name = $billing->getFirstname().' '.$billing->getLastname();
		
		$billfirst = $billing->getFirstname();
		$billlast = $billing->getLastname();
		
		$shipfirst = $shipping->getFirstname();
		$shiplast = $shipping->getLastname();
		
		$shippingdescription = $order->getShippingDescription();
		$shippingcost = (int)money_format("%.0n",$order->getBaseShippingInclTax() * 100);
		
		$bill1 = $billing->getStreet(1);
		$bill2 = $billing->getStreet(2);
		$billcity = $billing->getCity();
		$billcountry = $billing->getCountry();
		$billregion = $billing->getRegion();
		$billpostcode = $billing->getPostcode();
		$billstate = $billing->getRegionCode();
		$billphone = $billing->getTelephone();
		
		if ($shipping) {
			$shippostcode = $shipping->getPostcode();
			$ship1 = $shipping->getStreet(1);
			$ship2 = $shipping->getStreet(2);
			$shipcity = $shipping->getCity();
			$shipcounty = $shipping->getRegion();
			$shipcountry = $shipping->getCountry();
			$shipstate = $shipping->getRegionCode();
			$shipphone = $shipping->getTelephone();
		
		} else {
			$ship1 = $bill1;
			$ship2 = $bill2;
			$shipcity = $billcity;
			$shipcounty = $billregion;
			$shipcountry = $billcountry;
			$shippostcode = $billpostcode;
			$shipstate = $billstate;
			$shipphone = $billphone;
		}
		
		$shasig = "";
		$shaarray = array();
		
		if ($pspid != "") { $shaarray['PSPID'] = $pspid; }
		if ($orderid != "") { $shaarray['ORDERID'] = $orderid; }
		if ($grandtotal != "") { $shaarray['AMOUNT'] = $grandtotal; }
		$shaarray['INVDISCOUNT'] = $discountamount;
		if ($currency != "") { $shaarray['CURRENCY'] = $currency; }
		if ($locale != "") { $shaarray['LANGUAGE'] = $locale; }
		if ($name != "") { $shaarray['CN'] = $name; }
		if ($email != "") { $shaarray['EMAIL'] = $email; }
		if ($billpostcode != "") { $shaarray['OWNERZIP'] = $billpostcode; }
		if ($bill1 != "") { $shaarray['OWNERADDRESS'] = $bill1; }
		if ($billcity != "") { $shaarray['OWNERCTY'] = $billcity; }
		if ($bill2 != "") { $shaarray['OWNERTOWN'] = $bill2; }
		if ($billphone != "") { $shaarray['OWNERTELNO'] = $billphone; }
		if ($orderid != "") { $shaarray['COM'] = 'Payment for Order :-'.$orderid ; }
		if ($paymentaction != "") { $shaarray['OPERATION'] = $paymentaction ; }
		
		$cardcv2 = $payment->getCcCid();
		$cardname = $payment->getCcOwner();
		$cardnumber = $payment->getCcNumber();
		$cardissuenumber = $payment->getCcSsIssue();
		$cardexpmonth = $payment->getCcExpMonth();
		$cardexpyear = $payment->getCcExpYear();
		$cardstartmonth = $payment->getCcSsStartMonth();
		$cardstartyear = $payment->getCcSsStartYear();
		
		$shaarray['ECI'] = "1";
		$shaarray['CARDNO'] = $cardnumber;
		$shaarray['ED'] = $cardexpmonth.$cardexpyear;
		$shaarray['CVC'] = $cardcv2; 
		
		
		Mage::helper('barclayspspid')->log("Authorize Post Parameters",$shaarray);
		
		$result = $this->performRequest($shaarray, $url);
		
		Mage::helper('barclayspspid')->log("Authorize Reply Parameters",$result);
		
		
		return $result;
		
	}
	
	
	
	/**
	 * Perform a CURL call and log request end response to logfile
	 *
	 * @param array $params
	 * @return mixed
	 */
	public function call($params, $url)
	{
		try {
			$http = new Varien_Http_Adapter_Curl();
			$config = array('timeout' => 30);
			$http->setConfig($config);
			$http->write(Zend_Http_Client::POST, $url, '1.1', array(), http_build_query($params, null, '&'));
			$response = $http->read();
			$response = substr($response, strpos($response, "<?xml"), strlen($response));
			return $response;
		} catch (Exception $e) {
			Mage::logException($e);
			Mage::throwException(
			Mage::helper('barclayspspid')->__('Barclays server is temporarily not available, please try again later.')
			);
		}
		
		
		return $response;
	}
	
	/**
	 * Performs a POST request to the Direct Link Gateway with the given
	 * parameters and returns the result parameters as array
	 *
	 * @param array $params
	 * @return array
	 */
	public function performRequest($requestParams, $url, $storeId = 0)
	{
		$helper = Mage::helper('barclayspspid');
		$params = $this->getEncodedParametersWithHash(
				array_merge($requestParams,$this->buildAuthenticationParams($storeId)) //Merge Logic Operation Data with Authentication Data
				, null, $storeId);
		
		$responseParams = $this->getResponseParams($params, $url);
		
		
		
		
		
		$this->checkResponse($responseParams);
	
		return $responseParams;
	
	}
	
	public function getEncodedParametersWithHash($params, $shaCode=null, $storeId)
	{
		$shain = $this->getConfig()->getShain();
		$shaCode = $shain;
		
		$params['SHASIGN'] = $this->shaCrypt(iconv('iso-8859-1', 'utf-8', $this->getSHASign($params, $shaCode, $storeId)));
	
		return $params;
	}
	
	
	/**
	 *
	 * wraps the request and response handling and repeats request/response
	 * if there are errors
	 *
	 * @param array $params - request params
	 * @param string $url - the url for the request
	 * @param int $retryCount - current request count
	 * @return array | null - null if requests were not successful, array containing Ogone payment data otherwise
	 *
	 */
	protected function getResponseParams($params, $url, $retryCount = 0)
	{
		$responseParams = null;
		
		
		
		if ($retryCount < 3) {
			try {
				$responseParams = $this->getParamArrFromXmlString(
						$this->call($params, $url)
				);
			} catch (Exception $e) {
				
				Mage::helper('barclayspspid')->log('DirectLink::getResponseParams failed: ' .
						$e->getMessage() . ' current retry count: ' . $retryCount . ' for quote ' .$params['ORDERID']
				);
				$responseParams = $this->getResponseParams($params, $url, ++$retryCount);
			}
		} else {
			Mage::throwException(Mage::helper('barclayspspid')->__('An error occured during the Ogone request. Your action could not be executed.'));
		}
		return $responseParams;
	}
	 
	/**
	 * Return Authentication Params for OPS Call
	 *
	 * @return array
	 */
	protected function buildAuthenticationParams($storeId = 0)
	{
		return array(
				'PSPID' => $this->getConfig()->getPspid(),
				'USERID' =>$this->getConfig()->getUsername(),
				'PSWD' => $this->getConfig()->getUserpassword()
		);
	}
	
	/**
	 * Parses the XML-String to an array with the result data
	 *
	 * @param string xmlString
	 * @return array
	 */
	public function getParamArrFromXmlString($xmlString)
	{
		try {
			$xml = new SimpleXMLElement($xmlString);
			foreach($xml->attributes() as $key => $value) {
				$arrAttr[$key] = (string)$value;
			}
			foreach($xml->children() as $child) {
				$arrAttr[$child->getName()] = (string) $child;
			}
			return $arrAttr;
		} catch (Exception $e) {
			Mage::log('Could not convert string to xml in ' . __FILE__ . '::' . __METHOD__ . ': ' . $xmlString);
			Mage::logException($e);
			throw $e;
		}
	}
	 
	/**
	 * Check if the Response from OPS reports Errors
	 *
	 * @param array $responseParams
	 * @return mixed
	 */
	public function checkResponse($responseParams)
	{
		if (false === is_array($responseParams) || false === array_key_exists('NCERROR', $responseParams) || $responseParams['NCERROR'] > 0) {
			if (empty($responseParams['NCERRORPLUS'])) {
				$responseParams['NCERRORPLUS'] = Mage::helper('ops')->__('Invalid payment information')." Errorcode:".$responseParams['NCERROR'];
			}
	
			//avoid exception if STATUS is set with special values
			if (isset($responseParams['STATUS']) && is_numeric($responseParams['STATUS'])) {
				return;
			}
	
			Mage::throwException(
			Mage::helper('ops')->__('An error occured during the Barclays request. Your action could not be executed. Message: "%s".',$responseParams['NCERRORPLUS'])
			);
		}
	}
	
	/*
	 * Get SHA-1-IN hash for ops-authentification
	*
	* All Parameters have to be alphabetically, UPPERCASE
	* Empty Parameters shouldn't appear in the secure string
	*
	* @param array  $formFields
	* @param string $shaCode
	*
	* @return string
	*/
	public function getSHASign($formFields, $shaCode = null, $storeId = null)
	{
		if (is_null($shaCode)) {
			$shaCode = $this->getConfig()->getShaout();
		}
		$formFields = array_change_key_case($formFields, CASE_UPPER);
	
		unset($formFields['ORDERSHIPMETH']);
	
		uksort($formFields, 'strnatcasecmp');
		$plainHashString = '';
		foreach ($formFields as $formKey => $formVal) {
			if (is_null($formVal) || '' === $formVal || $formKey == 'SHASIGN') {
				continue;
			}
			$plainHashString .= strtoupper($formKey) . '=' . $formVal . $shaCode;
		}
	
		return $plainHashString;
	}
	
	/**
	 * Crypt Data by SHA1 ctypting algorithm by secret key
	 *
	 * @param array  $data
	 * @param string $key
	 *
	 * @return hash
	 */
	public function shaCrypt($data, $key = '')
	{
		if (is_array($data)) {
			return hash('SHA1', implode("", $data));
		}
		if (is_string($data)) {
			return hash('SHA1', $data);
		} else {
			return "";
		}
	}
	
}