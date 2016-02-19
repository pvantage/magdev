<?php
/******************************************************
 * @package Ves Autosearch module for Magento 1.4.x.x and Magento 1.9.x.x
 * @version 1.0.0.1
 * @author http://landofcoder.com
 * @copyright	Copyright (C) December 2010 LandOfCoder.com <@emai:landofcoder@gmail.com>.All rights reserved.
 * @license		GNU General Public License version 2
*******************************************************/
class Ves_Socialsidebar_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
  		$this->loadLayout();     
  		$this->renderLayout();
    }
}