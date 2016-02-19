<?php
/**
 * Helper file - not used right now
 * 
 * @copyright Juicy Media Ltd, 2011
 * @license Commercial, visit: www.juicyextensions.co.uk 
 * @author chenry (craig.henry@juicymedia.co.uk)
 * @version 1.0
 */
class Juicy_Barclayspspid_Helper_Data extends Mage_Core_Helper_Abstract
{
	public function log($eventname, $variables) 
	{
		
		$config = new Juicy_Barclayspspid_Model_Config();
		
		$debuglog = $config->getDebuglog();
		$debugemail = $config->getDebugEmail();
		
		if ($debuglog == "1") {
			$message = "Event Name  - ".$eventname." - Trace - ".print_r($variables, true);
			Mage::log($message, null, "barclays-pspid.log");
		}
		
		
		if ($debugemail == "1") {
			
			$siteurl = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB);
			
			$subject = "Barclays PSPID DEBUG LOG FOR - ".$siteurl." - Event :".$eventname;
			
			
			$message = "Actual URL:".Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB);
			$message .= "<br /><br />";
			$message .= "Event Name = ".$eventname."<br /><br />";
			$message .= "Event Trace = ".print_r($variables, true)."<br /><br />";

			
			
			$mail = Mage::getModel('core/email');
			$mail->setToName('Juicy DEBUG NOTIFY');
			$mail->setToEmail('servers@juicymedia.co.uk');
			$mail->setBody($message);
			$mail->setSubject($subject);
			$mail->setFromEmail('debug@juicymedia.co.uk');
			$mail->setFromName("Barclays PSPID Debug Issue");
			$mail->setType('html');// YOu can use Html or text as Mail format
				
			try {
			//REMOVE MAIL SEND	$mail->send();
			}
			catch (Exception $e) {
				//var_dump($e>getMessage());
			}
			
		}
		
		
		
	}
}
