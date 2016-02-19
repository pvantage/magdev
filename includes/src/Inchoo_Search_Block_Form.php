<?php

class Inchoo_Search_Block_Form extends Mage_CatalogSearch_Block_Advanced_Form
{
    public function _prepareLayout()
    {
        parent::_prepareLayout();
        // add Home breadcrumb
        if ($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs')) {
            $breadcrumbs->addCrumb('home', array(
                'label'=>Mage::helper('catalogsearch')->__('Home'),
                'title'=>Mage::helper('catalogsearch')->__('Go to Home Page'),
                'link'=>Mage::getBaseUrl()
            ))->addCrumb('search', array(
                'label'=>Mage::helper('catalogsearch')->__('Search')
            ));
        }
    }

    /**
     * Retrieve search form action url
     *
     * @return string
     */
    public function getSearchPostUrl()
    {
        return $this->getUrl('*/results/for');//URL: example.com/module_frontname/results/for
    }
}
