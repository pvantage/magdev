<?php
/**
 * Session file
 * 
 * @copyright Juicy Media Ltd, 2011
 * @license Commercial, visit: www.juicyextensions.co.uk 
 * @author chenry (craig.henry@juicymedia.co.uk)
 * @version 1.0
 */
class Juicy_Barclaysepdq_Model_Session extends Mage_Core_Model_Session_Abstract
{
    public function __construct()
    {
        $this->init('barclayspspid');
    }
}
