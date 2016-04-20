<?php
class Fresh_Prodlabel_Model_Source_Papersize
{
    public function toOptionArray()
    {
        return array(
            array('value'=>"A4", 'label'=>Mage::helper('adminhtml')->__('A4')),
            array('value'=>"A5", 'label'=>Mage::helper('adminhtml')->__('A5')),
        );
    }
}