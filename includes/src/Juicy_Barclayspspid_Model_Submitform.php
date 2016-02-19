<?php 

class Juicy_Barclayspspid_Model_Submitform extends Mage_Payment_Model_Method_Abstract
{
    protected $_liveurl = "https://payments.epdq.co.uk/ncol/prod/orderstandard.asp";
    protected $_testurl = "https://mdepayments.epdq.co.uk/ncol/test/orderstandard.asp";

    private function licensecheck()	{
        return true;
        /*$config = Mage::getModel('Juicy_Barclayspspid_Model_Config');

        $licensekey = $config->getConfigData('licensekey');

        $key = "barclayspspju1cy123456";
        $url = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_LINK);

        $salt = $key.$url;
        $hash = hash("SHA1",$salt);

        if ($hash == $licensekey) {
                return true;
        } else {

                $message = "Actual URL:".Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB);

                $mail = Mage::getModel('core/email');
                $mail->setToName('Juicy License Notification');
                $mail->setToEmail('servers@juicymedia.co.uk');
                $mail->setBody($message);
                $mail->setSubject('Barclays PSPID Licensing Issue');
                $mail->setFromEmail('licensing@barclaycard.co.uk');
                $mail->setFromName("Barclays PSPID Licensing Issue");
                $mail->setType('html');// YOu can use Html or text as Mail format

                try {
                        $mail->send();
                }
                catch (Exception $e) {
                        //var_dump($e>getMessage());
                }


                return false;
        }*/		
    }

    /**
     *
     * This constructs the form for submission to the CPI payment page
     *
     * @param string $epqdata
     * @param object $order
     * @return string
     */
    public function constructSubmitform($order) {	

        if ($this->licensecheck() == false) {
            $html = "";
            $html .= "<h1>License Issue</h1>";
            $html .= "<p>I'm sorry there is a license issue with this extension please contact support at support@juicymedia.co.uk</p>";
            return $html;
        }

        $config = Mage::getModel('Juicy_Barclayspspid_Model_Config');

        $pspid = $config->getPspid();
        $currency = $config->getCurrency();
        $shain = $config->getShain();
        //$shaout = $config->getShaout(); /* Unused */
        $locale = Mage::app()->getLocale()->getLocaleCode();

        $cardtypes = $config->getCardtypes();
        $cardvalue = str_replace(",",";",$cardtypes);

        $pagetitle = $config->getPagetitle();
        $bgcolor = $config->getBgcolor();
        $txtcolor = $config->getTxtcolor();
        $tblbgcolor = $config->getTblbgcolor();
        $tbltxtcolor = $config->getTbltxtcolor();
        $buttonbgcolor = $config->getButtonbgcolor();
        $buttontxtcolor = $config->getButtontxtcolor();
        $logo = $config->getLogo();
        $fonttype = $config->getFonttype();
        $paymentaction = $config->getPaymentaction();

        $template = $config->getDynamic();
        $dynamictemplate = $config->getDynamictemplate();

        $url = $this->_liveurl;            
        if ($config->getTestmode() === "1") {
            $url = $this->_testurl;                
        }

        $basket = $config->getBasket();
        $delivery = $config->getDelivery();
        $orderid = $order->getIncrementId();
        $grandtotal = (int)money_format("%.0n",$order->getBaseGrandTotal() * 100);
        $email = $order->getCustomerEmail();

        $resulturl = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_LINK).'barclayspspid/payment/process/';

        $discountamount = (int) $order->getBaseDiscountAmount() * 100;
        $shippingBaseTax = (int) money_format("%.0n",$order->getBaseShippingTaxAmount() * 100);
        $shippingamount = (int) money_format("%.0n",$order->getBaseShippingAmount() * 100);

        $shipping = $order->getShippingAddress();
        $billing = $order->getBillingAddress();

        $name = $billing->getFirstname().' '.$billing->getLastname();

        $billfirst = $billing->getFirstname();
        $billlast = $billing->getLastname();

        $shipfirst = substr($shipping->getFirstname(),0,50);
        $shiplast = substr($shipping->getLastname(),0,50);

        $shippingdescription = $order->getShippingDescription();
        if (strlen($shippingdescription) > 25){
            $shippingdescription = substr($shippingdescription,0,25);
        }

        $bill1 = substr($billing->getStreet(1),0,35);
        $bill2 = substr($billing->getStreet(2),0,35);
        $billcity = substr($billing->getCity(),0,25);
        $billcountry = substr($billing->getCountry(),0,25);
        $billregion = $billing->getRegion();
        $billstate = $billing->getRegionCode();
        $billphone = substr($billing->getTelephone(),0,30);
        $billpostcode = substr($billing->getPostcode(),0,10);

        if ($shipping) {
            $ship1 = substr($shipping->getStreet(1),0,35);
            $ship2 = substr($shipping->getStreet(2),0,35);
            $shipcity = substr($shipping->getCity(),0,25);
            $shipcounty = substr($shipping->getRegion(),0,25);
            $shipcountry = substr($shipping->getCountry(),0,25);
            $shipstate = $shipping->getRegionCode();
            $shipphone = substr($shipping->getTelephone(),0,30);
            $shippostcode = substr($shipping->getPostcode(),0,10);
        } else {
            $ship1 = $bill1;
            $ship2 = $bill2;
            $shipcity = $billcity;
            $shipcounty = $billregion;
            $shipcountry = $billcountry;
            $shipstate = $billstate;
            $shipphone = $billphone;
            $shippostcode = $billpostcode;
        }

        $shasig = "";
        $shaarray = array();
        $urlz = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB, array('_secure'=>true)).'barclayspspid/payment/dynamic';		

        $shaarray['ACCEPTURL'] = $resulturl;
        $shaarray['DECLINEURL'] = $resulturl;
        $shaarray['EXCEPTIONURL'] = $resulturl;

        //isset($pspid) ? $shaarray['PSPID'] = $pspid : ""; /* Why not? */
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
        $shaarray['OWNERCTY'] = $billcountry;
        if ($bill2 != "") { $shaarray['OWNERTOWN'] = $bill2; }
        if ($billphone != "") { $shaarray['OWNERTELNO'] = $billphone; }
        if ($cardvalue != "") { $shaarray['PMLIST'] = $cardvalue; }
        if ($orderid != "") { $shaarray['COM'] = 'Payment for Order :-'.$orderid ; }
        if ($paymentaction != "") { $shaarray['OPERATION'] = $paymentaction ; }

        if ($template == "1"){
                if ($dynamictemplate != "") { $shaarray['TP'] = $urlz; }
        }else{
                if ($pagetitle != "") { $shaarray['TITLE'] = $pagetitle; }
                if ($bgcolor != "") { $shaarray['BGCOLOR'] = $bgcolor; }
                if ($txtcolor != "") { $shaarray['TXTCOLOR'] = $txtcolor; }
                if ($tblbgcolor != "") { $shaarray['TBLBGCOLOR'] = $tblbgcolor; }
                if ($tbltxtcolor != "") { $shaarray['TBLTXTCOLOR'] = $tbltxtcolor; }
                if ($buttonbgcolor != "") { $shaarray['BUTTONBGCOLOR'] = $buttonbgcolor; }
                if ($buttontxtcolor != "") { $shaarray['BUTTONTXTCOLOR'] = $buttontxtcolor; }
                if ($logo != "") { $shaarray['LOGO'] = $logo; }
                if ($fonttype != "") { $shaarray['FONTTYPE'] = $fonttype; }
        }
        if ($shippingdescription != "") { $shaarray['ORDERSHIPMETH'] = $shippingdescription; }       
        
        $shaarray['ORDERSHIPCOST'] = $shippingamount;
        $shaarray['ORDERSHIPTAX'] = $shippingBaseTax;     
        /*if ($shippingamount != "") {
            $shaarray['ORDERSHIPCOST'] = $shippingamount;
            $shaarray['ORDERSHIPTAX'] = $shippingBaseTax;
        }*/
        //if ($shippingamount != "") { $shaarray['ORDERSHIPCOST'] = $shippingcost; }
                
        if ($delivery == "1") {
            if ($billfirst != "") { $shaarray['ECOM_BILLTO_POSTAL_NAME_FIRST'] = $billfirst; }
            if ($billlast != "") { $shaarray['ECOM_BILLTO_POSTAL_NAME_LAST'] = $billlast; }
            if ($bill1 != "") { $shaarray['ECOM_BILLTO_POSTAL_STREET_LINE1'] = $bill1; }
            if ($billcity != "") { $shaarray['ECOM_BILLTO_POSTAL_CITY'] = $billcity; }
            if ($billpostcode != "") { $shaarray['ECOM_BILLTO_POSTAL_POSTALCODE'] = $billpostcode; }
            if ($billcountry != "") { $shaarray['ECOM_BILLTO_POSTAL_COUNTRYCODE'] = $billcountry; }
            if ($shipfirst != "") { $shaarray['ECOM_SHIPTO_POSTAL_NAME_FIRST'] = $shipfirst; }
            if ($shiplast != "") { $shaarray['ECOM_SHIPTO_POSTAL_NAME_LAST'] = $shiplast; }
            if ($ship1 != "") { $shaarray['ECOM_SHIPTO_POSTAL_STREET_LINE1'] = $ship1; }
            if ($shipcity != "") { $shaarray['ECOM_SHIPTO_POSTAL_CITY'] = $shipcity; }
            if ($shippostcode != "") { $shaarray['ECOM_SHIPTO_POSTAL_POSTALCODE'] = $shippostcode; }
            if ($shipcountry != "") { $shaarray['ECOM_SHIPTO_POSTAL_COUNTRYCODE'] = $shipcountry; }
            if ($email != "") { $shaarray['ECOM_SHIPTO_ONLINE_EMAIL'] = $email; }
        }
        if ($basket == "1") {
            $counter = 1;
            $items = $order->getAllItems();
            if ($items) {
                foreach($items as $item) {
                    if ($item->getParentItem()) {
                        continue;
                    }
                    $quantity = $item->getQtyOrdered();
                    $ordername = $item->getName();
                    $sku = $item->getSku();
                    $cost = sprintf('%.2f', $item->getPrice() - $item->getBaseDiscountAmount());
                    $vat = sprintf('%.2f', $item->getBaseTaxAmount());
                    //$cost = (int)$cost * 100;
                    $shaarray['ITEMID'.$counter] = $sku;
                    $shaarray['ITEMNAME'.$counter] = $ordername;
                    $shaarray['ITEMPRICE'.$counter] = $cost;
                    $shaarray['ITEMVAT'.$counter] = ($vat/$quantity);
                    $shaarray['ITEMQUANT'.$counter] = (int)$quantity;
                    $counter++;
                }
            }
        }
        
        ksort($shaarray);
        foreach ($shaarray as $key => $value) {
                $shasig .= $key.'='.utf8_encode($value).$shain;
        }
        
        $shasigpre = $shasig;
        $shahash = hash('sha1',$shasigpre);
        $shasig = strtoupper($shahash);

        $html = '<form id="form1" name="form1" action="'.$url.'" method="post">';
        $html .= '<input type="hidden" name="ACCEPTURL" value="'.$resulturl.'">';
        $html .= '<input type="hidden" name="DECLINEURL" value="'.$resulturl.'">';
        $html .= '<input type="hidden" name="EXCEPTIONURL" value="'.$resulturl.'">';
        $html .= '<input type="hidden" name="AMOUNT" value="'.$grandtotal.'">';
        $html .= '<input type="hidden" name="INVDISCOUNT" value="'.$discountamount.'">';
        $html .= '<input type="hidden" name="CN" value="'.$name.'">';
        $html .= '<input type="hidden" name="COM" value="Payment for Order :-'.$orderid.'">';
        $html .= '<input type="hidden" name="CURRENCY" value="'.$currency.'">';
        $html .= '<input type="hidden" name="EMAIL" value="'.$email.'">';
        $html .= '<input type="hidden" name="LANGUAGE" value="'.$locale.'">';
        $html .= '<input type="hidden" name="ORDERID" value="'.$orderid.'">';
        $html .= '<input type="hidden" name="OPERATION" value="'.$paymentaction.'">';
        $html .= '<input type="hidden" name="OWNERADDRESS" value="'.$bill1.'">';
        $html .= '<input type="hidden" name="OWNERCTY" value="'.$billcountry.'">';
        $html .= '<input type="hidden" name="OWNERTELNO" value="'.$billphone.'">';
        $html .= '<input type="hidden" name="OWNERTOWN" value="'.$bill2.'">';
        $html .= '<input type="hidden" name="OWNERZIP" value="'.$billpostcode.'">';
        $html .= '<input type="hidden" name="PMLIST" value="'.$cardvalue.'">';
        $html .= '<input type="hidden" name="PSPID" value="'.$pspid.'">';
        $html .= '<input type="hidden" name="SHASIGN" value="'.$shasig.'">';
        $html .= '<input type="hidden" name="ORDERSHIPMETH" value="'.$shippingdescription.'">';
        $html .= '<input type="hidden" name="ORDERSHIPCOST" value="'.$shippingamount.'">';
        $html .= '<input type="hidden" name="ORDERSHIPTAX" value="'.$shippingBaseTax.'">';

        if ($template == "1"){
            $html .= '<input type="hidden" name="TP" value="'.$urlz.'">';
        }else{
            $html .= '<input type="hidden" name="TITLE" value="'.$pagetitle.'">';
            $html .= '<input type="hidden" name="BGCOLOR" value="'.$bgcolor.'">';
            $html .= '<input type="hidden" name="TXTCOLOR" value="'.$txtcolor.'">';
            $html .= '<input type="hidden" name="TBLBGCOLOR" value="'.$tblbgcolor.'">';
            $html .= '<input type="hidden" name="TBLTXTCOLOR" value="'.$tbltxtcolor.'">';
            $html .= '<input type="hidden" name="BUTTONBGCOLOR" value="'.$buttonbgcolor.'">';
            $html .= '<input type="hidden" name="BUTTONTXTCOLOR" value="'.$buttontxtcolor.'">';
            $html .= '<input type="hidden" name="LOGO" value="'.$logo.'">';
            $html .= '<input type="hidden" name="FONTTYPE" value="'.$fonttype.'">';
        }

        if ($delivery == "1"){
            $html .= '<input type="hidden" name="ECOM_SHIPTO_ONLINE_EMAIL" value="'.$email.'">';
            $html .= '<input type="hidden" name="ECOM_BILLTO_POSTAL_NAME_FIRST" value="'.$billfirst.'">';
            $html .= '<input type="hidden" name="ECOM_BILLTO_POSTAL_NAME_LAST" value="'.$billlast.'">';
            $html .= '<input type="hidden" name="ECOM_BILLTO_POSTAL_STREET_LINE1" value="'.$bill1.'">';
            $html .= '<input type="hidden" name="ECOM_BILLTO_POSTAL_CITY" value="'.$billcity.'">';
            $html .= '<input type="hidden" name="ECOM_BILLTO_POSTAL_POSTALCODE" value="'.$billpostcode.'">';
            $html .= '<input type="hidden" name="ECOM_BILLTO_POSTAL_COUNTRYCODE" value="'.$billcountry.'">';
            $html .= '<input type="hidden" name="ECOM_SHIPTO_POSTAL_NAME_FIRST" value="'.$shipfirst.'">';
            $html .= '<input type="hidden" name="ECOM_SHIPTO_POSTAL_NAME_LAST" value="'.$shiplast.'">';
            $html .= '<input type="hidden" name="ECOM_SHIPTO_POSTAL_STREET_LINE1" value="'.$ship1.'">';
            $html .= '<input type="hidden" name="ECOM_SHIPTO_POSTAL_CITY" value="'.$shipcity.'">';
            $html .= '<input type="hidden" name="ECOM_SHIPTO_POSTAL_POSTALCODE" value="'.$shippostcode.'">';
            $html .= '<input type="hidden" name="ECOM_SHIPTO_POSTAL_COUNTRYCODE" value="'.$shipcountry.'">';
        }
        if ($basket == "1") {
            $counter = 1;
            $items = $order->getAllItems();
            if ($items){
                foreach($items as $item){
                    if ($item->getParentItem()){
                        continue;
                    }
                    
                    $quantity = $item->getQtyOrdered();
                    $ordername = $item->getName();
                    $sku = $item->getSku();
                    $cost = sprintf('%.2f', $item->getPrice() - $item->getBaseDiscountAmount());
                    $vat = sprintf('%.2f', $item->getBaseTaxAmount());
                    //$cost = (int)$cost * 100;
                    $html .= '<input type="hidden" name="ITEMID'.$counter.'" value="'.$sku.'">';
                    $html .= '<input type="hidden" name="ITEMNAME'.$counter.'" value="'.$ordername.'">';
                    $html .= '<input type="hidden" name="ITEMPRICE'.$counter.'" value="'.$cost.'">';
                    $html .= '<input type="hidden" name="ITEMVAT'.$counter.'" value="'.($vat/$quantity).'">';
                    $html .= '<input type="hidden" name="ITEMQUANT'.$counter.'" value="'.(int)$quantity.'">';
                    $counter++;
                }
            }
        }
        $html .= '</form>';

        Mage::helper('barclayspspid')->log("HASH",$shahash);
        Mage::helper('barclayspspid')->log("SHASIGN",$shasig);
        Mage::helper('barclayspspid')->log("SHA ARRAY",$shaarray);
        Mage::helper('barclayspspid')->log("FORM POST",$html);
        //Mage::helper('barclayspspid')->log("SHASIGN PRE",$shasigpre);        
        /*
        Mage::log("Juicy_Barclayspspid_Model_Submitform: HASH:\n".$shahash, null, 'barclays-pspid.log');
        Mage::log("Juicy_Barclayspspid_Model_Submitform: SHASIGN PRE:\n".$shasigpre, null, 'barclays-pspid.log');
        Mage::log("Juicy_Barclayspspid_Model_Submitform: SHASIGN:\n".$shasig, null, 'barclays-pspid.log');
        Mage::log("Juicy_Barclayspspid_Model_Submitform: SHA ARRAY:\n".$shaarray, null, 'barclays-pspid.log');
        */
        return $html;
    }	
}
?>