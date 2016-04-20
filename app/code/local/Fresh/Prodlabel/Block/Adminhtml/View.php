<?php
class Fresh_Prodlabel_Block_Adminhtml_View extends Mage_Adminhtml_Block_Widget_Form_Container {

    public function __construct()
    {
        parent::__construct();
                  
        $this->_objectId = 'id';
        $this->_blockGroup = 'prodlabel';
        $this->_controller = 'adminhtml';
        $this->_mode = 'view';
        
        //$this->getLayout()->createBlock($this->_blockGroup . '/' . $this->_controller . '_' . $this->_mode . '_form')
                 
        $this->_updateButton('save', 'label', Mage::helper('prodlabel')->__('Submit Label'));
        $this->_updateButton('delete', 'label', Mage::helper('prodlabel')->__('Delete'));
         
        
    }
 
    public function getHeaderText()
    { 
        return Mage::helper('prodlabel')->__('Generate product labels');
    }
    
    public function getBackUrl()
    {
        return $this->getUrl('*/sales_order/view', array('order_id' => $this->getRequest()->getParam('order_id')));
    }
}