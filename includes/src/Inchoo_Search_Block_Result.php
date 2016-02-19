<?php

class Inchoo_Search_Block_Result extends Mage_CatalogSearch_Block_Advanced_Result
{
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if ($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs')) {
            $breadcrumbs->addCrumb('home', array(
                'label'=>Mage::helper('catalogsearch')->__('Home'),
                'title'=>Mage::helper('catalogsearch')->__('Go to Home Page'),
                'link'=>Mage::getBaseUrl()
            ))->addCrumb('search', array(
                'label'=>Mage::helper('catalogsearch')->__('Search'),
                'link'=>$this->getUrl('*')
            ))->addCrumb('search_result', array(
                'label'=>Mage::helper('catalogsearch')->__('Results')
            ));
        }
        $title = $this->__("Search results for: '%s'", $this->getRequest()->getQuery('sku'));
        $this->getLayout()->getBlock('head')->setTitle($title);
    }

    public function getFormUrl()
    {
        return Mage::getModel('core/url')
            ->setQueryParams($this->getRequest()->getQuery())
            ->getUrl('*', array('_escape' => true)); //URL: example.com/module_frontname/
    }
}
