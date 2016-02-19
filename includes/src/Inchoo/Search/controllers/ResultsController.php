<?php

class Inchoo_Search_ResultsController extends Mage_Core_Controller_Front_Action
{
    public function forAction()
    {
        $this->loadLayout();
        try {
            $query = $this->getRequest()->getQuery();
            Mage::getSingleton('catalogsearch/advanced')->addFilters($query);
        } catch (Mage_Core_Exception $e) {
            Mage::getSingleton('catalogsearch/session')->addError($e->getMessage());
            $this->_redirectError(
                 Mage::getModel('core/url')
                     ->setQueryParams($this->getRequest()->getQuery())
                     ->getUrl('*')//redirect on exception (e.g. search term is empty); URL: example.com/module_frontname/
            );
        }
        $this->_initLayoutMessages('catalog/session');
        $this->renderLayout();
    }
}