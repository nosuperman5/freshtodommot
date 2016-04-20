<?php
class Fresh_Prodlabel_Model_Adminhtml_Observer
{
    public function addProdLabelButton($observer) {
        $block = Mage::app()->getLayout()->getBlock('sales_order_edit');
        if (!$block){
            return $this;
        }
        $order = Mage::registry('current_order');
        $url = Mage::helper("adminhtml")->getUrl(
            "*/prodlabel/view",
            array('order_id'=>$order->getId())
        );
        $block->addButton('prodlabel_resubmit', array(
                'label'     => Mage::helper('sales')->__('Product Label'),
                'onclick'   => 'setLocation(\'' . $url . '\')',
                'class'     => 'go'
        ));
        return $this;
    }
}