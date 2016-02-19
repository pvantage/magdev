<?php
/**
 * Standard controller that does the work of handling payments
 * 
 * @copyright Juicy Media Ltd, 2011
 * @license Commercial, visit: www.juicyextensions.co.uk 
 * @author chenry (support@juicymedia.co.uk)
 * @version 1.0
 */

class Juicy_Barclayspspid_PaymentController extends Mage_Core_Controller_Front_Action
{
	/**
	 *  
	 * @var boolean $isValidResponse
	 */
    public $isValidResponse = false;

    /**
     * Get singleton with sagepay strandard
     *
     * @return object Juicy_Barclayscpi_Model_Standard
     */
    public function getStandard()
    {
        return Mage::getSingleton('Juicy_Barclayspspid_Model_Barclayspspid');
    }

    /**
     * Get Config model
     *
     * @return object Juicy_Barclayscpi_Model_Config
     */
    public function getConfig()
    {
        return $this->getStandard()->getConfig();
    }

    /**
     *  Return debug flag
     *
     *  @return  boolean
     */
    public function getDebug()
    {
        return $this->getStandard()->getDebug();
    }
    
    public function dynamicAction() 
    {
    	$config = $this->getConfig();
    	$form = $config->getDynamictemplate();
    	
    	
    	$header = $config->getMagheader();
    	
    	if ($header == "1") {
    	
	    	$this->loadLayout();
	    	
	    	$block = $this->getLayout()->createBlock('Juicy_Barclayspspid_Block_Dynamic')->setformhtml($form);
	    	
	    	$template = Mage::getConfig()->getNode('global/page/layouts/one_column/template');
	    	$this->getLayout()->getBlock('root')->setTemplate($template);
	    	$this->getLayout()->getBlock('content')->append($block);
	    	
	    	$this->renderLayout();
	    	
    	} else {
    		$this->getResponse()
    		 ->setBody($this->getLayout()
    		 		->createBlock('Juicy_Barclayspspid_Block_Dynamic')
    		 		->setformhtml($form)
    		 		->toHtml());
    		
    	}
    	
    	
    }
    

    /**
     * When a customer chooses Barclays CPI on Checkout/Payment page
     *
     */
    public function redirectAction()
    {
    	
    	$this->loadLayout(false);
    	$post = $this->getRequest()->getPost();
    	
    	
        $session = Mage::getSingleton('Mage_Checkout_Model_Session');
        $session->setBarclaysQuoteID($session->getQuoteId());
		
        //Tell us the customer went to Barclays
        $order = Mage::getModel('Mage_Sales_Model_Order');
        $order->loadByIncrementId($session->getLastRealOrderId());
        $order->addStatusToHistory(
            $order->getStatus(),
            Mage::helper('barclayspspid')->__('Customer was redirected to Barclays ePDQ')
        );        
        $order->save();
        
        //Get figures
		$grandtotal = $order->getBaseGrandTotal();
		//Mage::log('Juicy_Barclayspspid_PaymentController: redirectAction: gt = '.$grandtotal, null, 'barclays-pspid.log');
		
        $formz = Mage::getModel('Juicy_Barclayspspid_Model_Submitform');
        
        $orderid = $session->getLastRealOrderId();
        
        //Mage::log('Juicy_Barclayspspid_PaymentController: redirectAction: oid = '.$orderid, null, 'barclays-pspid.log');
        
        //Construct the from
        $form = $formz->constructSubmitform($order);
        
        
        //display form in block and redirect
        $this->getResponse()
            ->setBody($this->getLayout()
                ->createBlock('Juicy_Barclayspspid_Block_Redirect')
                ->setOrder($order)
                ->setepdqform($form)
                ->toHtml());

        $session->unsQuoteId();
    	
    }
     
    public function processAction() {
    	
    	$postData = $this->getRequest()->getParams();
    	
    	Mage::helper('barclayspspid')->log("Front End Post Back",$postData);
    	
    	$standard = Mage::getModel('Juicy_Barclayspspid_Model_Barclayspspid');
    	
    	$verify = $standard->checkShaout($postData);
    	
    	
    	$order = Mage::getModel('Mage_Sales_Model_Order');
    	$order->load($postData['orderID'], "increment_id");
    	
    	
    	if ($verify == true) {
    		
    		$status = (int)$postData['STATUS'];
    		
    		if ($status == 5 || $status == 9) {
    			$order->getPayment()->setTransactionId($postData['PAYID']);
    			$order->getPayment()->setadditional_data(serialize($postData));
    			$order->addStatusToHistory(
    					$order->getStatus(),
    					Mage::helper('barclayspspid')->__('Customer successfully returned from Barclays ePDQ')
    			);
    			
    			if ($status == 9) {
	    			if ($order->canInvoice()) {
	    				$invoice = $order->prepareInvoice();
	    				$invoice->register()->capture();
	    				Mage::getModel('Mage_Core_Model_Resource_Transaction')
	    				->addObject($invoice)
	    				->addObject($invoice->getOrder())
	    				->save();
	    			}
	    			$order->setState(Mage_Sales_Model_Order::STATE_PROCESSING, true);
	    			$order->save();
    			} else {
    				if ($order->canInvoice()) {
    					$invoice = $order->prepareInvoice();
    					$invoice->register();
    					Mage::getModel('Mage_Core_Model_Resource_Transaction')
    					->addObject($invoice)
    					->addObject($invoice->getOrder())
    					->save();
    				}
    				$order->setState(Mage_Sales_Model_Order::STATE_PROCESSING, 'barclayspspid_authorized', "Transaction was successfully pre-authorized", true);
    				$order->save();

    			}
    			$session = Mage::getModel("Mage_Checkout_Model_Session");
    			$session->addSuccess('Your Order was Successfully processed and the authorisation code was '.$postData['ACCEPTANCE']);

    			// PKD added to send customer notification of new order
    			try
    			{
    			    $order->sendNewOrderEmail();
    			    
    			} catch (Exception $ex) {  }    			
    			
    			$this->_redirect('checkout/onepage/success');
    			
    		} else {
    			$order->getPayment()->setadditional_data(serialize($postData));
    			$order->addStatusToHistory(
    					$order->getStatus(),
    					Mage::helper('barclayspspid')->__('Transaction was cancelled due to unsuccessful payment')
    			);
    			$session = Mage::getModel("Mage_Checkout_Model_Session");
    			$session->addError('Transaction was cancelled due to unsuccessful payment');
    			$order->cancel();
    			$order->save();
    			$this->_redirect('checkout/onepage/failure');
    		}
    		
    	} else {
    		$order->getPayment()->setadditional_data(serialize($postData));
    		$order->addStatusToHistory(
    				$order->getStatus(),
    				Mage::helper('barclayspspid')->__('There was a verification issue and the order was cancelled')
    		);
    		$session = Mage::getModel("Mage_Checkout_Model_Session");
    		$session->addError('There was a verification issue and the order was cancelled');
    		$order->cancel();
    		$order->save();
    		$this->_redirect('barclayspspid/payment/failure');
    	}
    	
    }
    
    
	
        
    /**
     * 
     * This displays the failure page
     */
    public function failureAction() 
    {
    	$session = Mage::getSingleton('Mage_Checkout_Model_Session');

        if (!$session->getErrorMessage()) {
            $this->_redirect('checkout/cart');
            return;
        }
        
        $this->loadLayout();
        $this->renderLayout();
    }   
    
}