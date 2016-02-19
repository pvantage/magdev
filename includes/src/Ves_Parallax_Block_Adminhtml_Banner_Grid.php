<?php

class Ves_Parallax_Block_Adminhtml_Banner_Grid extends Mage_Adminhtml_Block_Widget_Grid {
    public function __construct() {
		
        parent::__construct();
	
        $this->setId('bannerGrid');
        $this->setDefaultSort('date_from');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
		
    }

  //  protected function _getStore() {
   //     $storeId = (int) $this->getRequest()->getParam('store', 0);
   //     return Mage::app()->getStore($storeId);
   // }

    protected function _prepareCollection() {
        $collection = Mage::getModel('ves_parallax/banner')->getCollection();
        //$store = $this->_getStore();
        //if ($store->getId()) {
        //    $collection->addStoreFilter($store);
       // }
		
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {  
        $this->addColumn('banner_id', array(
                'header'    => Mage::helper('ves_parallax')->__('ID'),
                'align'     =>'right',
                'width'     => '50px',
                'index'     => 'banner_id',
        ));

		
        $this->addColumn('file', array(
                'header'    => Mage::helper('ves_parallax')->__('File'),
                'align'     =>'left',
                'index'     => 'file',
                'renderer'  => 'Ves_Parallax_Block_Adminhtml_Renderer_Image'
        ));	

         if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn('store_id', 
                    array (
                            'header' => Mage::helper('cms')->__('Store view'), 
                            'index' => 'store_id', 
                            'type' => 'store', 
                            'store_all' => true, 
                            'store_view' => true, 
                            'sortable' => false, 
                            'filter_condition_callback' => array (
                                    $this, 
                                    '_filterStoreCondition' ) ));
        }	
		
        $this->addColumn('position', array(
                'header'    => Mage::helper('ves_parallax')->__('Position'),
                'align'     =>'left',
                'index'     => 'position',
        ));
		
        $this->addColumn('is_active', array(
                'header'    => Mage::helper('ves_parallax')->__('Status'),
                'align'     => 'left',
                'width'     => '80px',
                'index'     => 'is_active',
                'type'      => 'options',
                'options'   => array(
                        1 => Mage::helper('ves_parallax')->__('Enabled'),
                        0 => Mage::helper('ves_parallax')->__('Disabled'),
                //3 => Mage::helper('ves_parallax')->__('Hidden'),
                ),
        ));

        $this->addColumn('action',
                array(
                'header'    =>  Mage::helper('ves_parallax')->__('Action'),
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                        array(
                                'caption'   => Mage::helper('ves_parallax')->__('Edit'),
                                'url'       => array('base'=> '*/*/edit'),
                                'field'     => 'id'
                        ),
                        array(
                                'caption'   => Mage::helper('ves_parallax')->__('Delete'),
                                'url'       => array('base'=> '*/*/delete'),
                                'field'     => 'id'
                        )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
        ));

        return parent::_prepareColumns();
    }

    protected function _prepareMassaction() { 
        $this->setMassactionIdField('banner_id');
        $this->getMassactionBlock()->setFormFieldName('banner');

        $this->getMassactionBlock()->addItem('delete', array(
                'label'    => Mage::helper('ves_parallax')->__('Delete'),
                'url'      => $this->getUrl('*/*/massDelete'),
                'confirm'  => Mage::helper('ves_parallax')->__('Are you sure?')
        ));

        $statuses = array(
                1 => Mage::helper('ves_parallax')->__('Enabled'),
                0 => Mage::helper('ves_parallax')->__('Disabled')
				);
        array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('status', array(
                'label'=> Mage::helper('ves_parallax')->__('Change status'),
                'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
                'additional' => array(
                        'visibility' => array(
                                'name' => 'status',
                                'type' => 'select',
                                'class' => 'required-entry',
                                'label' => Mage::helper('ves_parallax')->__('Status'),
                                'values' => $statuses
                        )
                )
        ));
        return $this;
    }
     /**
     * Helper function to add store filter condition
     *
     * @param Mage_Core_Model_Mysql4_Collection_Abstract $collection Data collection
     * @param Mage_Adminhtml_Block_Widget_Grid_Column $column Column information to be filtered
     */
    protected function _filterStoreCondition($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }
        
        $this->getCollection()->addStoreFilter($value);
    }
    public function getRowUrl($row) {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

}