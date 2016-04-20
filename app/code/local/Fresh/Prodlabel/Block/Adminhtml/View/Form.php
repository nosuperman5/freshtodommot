<?php

class Fresh_Prodlabel_Block_Adminhtml_View_Form extends Mage_Adminhtml_Block_Widget_Form {

    protected function _prepareForm() {

        $form = new Varien_Data_Form(array(
            'id' => 'edit_form',
            'action' => $this->getUrl('*/*/save', array('order_id' => $this->getRequest()->getParam('order_id'))),
            'method' => 'post'
        ));
        $this->setForm($form);

        $data = array();

        $layout_fieldset = $form->addFieldset('prodlabel_layout', array('legend' => Mage::helper('prodlabel')->__('Layout information'), 'fieldset_container_id' => 'prodlabel_layout_wrapper'));
        $content_fieldset = $form->addFieldset('prodlabel_content', array('legend' => Mage::helper('prodlabel')->__('Content information'), 'fieldset_container_id' => 'prodlabel_content_wrapper'));
        $button_fieldset = $form->addFieldset('prodlabel_button', array('legend' => Mage::helper('prodlabel')->__('Submit Label'), 'fieldset_container_id' => 'prodlabel_button_wrapper'));

        $layout_fieldset->addField('label_size', 'text', array(
            'label' => Mage::helper('prodlabel')->__('Label size'),
            'name' => 'label_size',
            'class' => 'required-entry',
            'validate_class' => 'required-entry',
            'required' => true,
            'value' => Mage::getStoreConfig('prodlabel/layout_info/label_size'),
        ));

        $layout_fieldset->addField('paper_size', 'select', array(
            'label' => Mage::helper('prodlabel')->__('Paper size'),
            'required' => false,
            'name' => 'paper_size',
            'values' => Mage::getSingleton('prodlabel/source_papersize')->toOptionArray(),
            'value' => Mage::getStoreConfig('prodlabel/layout_info/paper_size'),
        ));

        $layout_fieldset->addField('first_location', 'text', array(
            'label' => Mage::helper('prodlabel')->__('Location of first label'),
            'name' => 'first_location',
            'class' => 'required-entry',
            'required' => true,
            'value' => Mage::getStoreConfig('prodlabel/layout_info/first_location'),
        ));

        $layout_fieldset->addField('row_spacing', 'text', array(
            'label' => Mage::helper('prodlabel')->__('Row spacing between labels'),
            'name' => 'row_spacing',
            'class' => 'required-entry',
            'required' => true,
            'value' => Mage::getStoreConfig('prodlabel/layout_info/row_spacing'),
        ));

        $layout_fieldset->addField('column_spacing', 'text', array(
            'label' => Mage::helper('prodlabel')->__('Column spacing between labels'),
            'name' => 'column_spacing',
            'class' => 'required-entry',
            'required' => true,
            'value' => Mage::getStoreConfig('prodlabel/layout_info/column_spacing'),
        ));

        $content_fieldset->addField('product_name', 'select', array(
            'label' => Mage::helper('prodlabel')->__('Product name'),
            'required' => false,
            'name' => 'product_name',
            'values' => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray(),
            'value' => Mage::getStoreConfig('prodlabel/content_info/product_name'),
        ));
        $content_fieldset->addField('order_qty', 'select', array(
            'label' => Mage::helper('prodlabel')->__('Order Qty'),
            'required' => false,
            'name' => 'order_qty',
            'values' => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray(),
            'value' => Mage::getStoreConfig('prodlabel/content_info/order_qty'),
        ));
        $content_fieldset->addField('invoice_price', 'select', array(
            'label' => Mage::helper('prodlabel')->__('Order price as shown on the invoice'),
            'required' => false,
            'name' => 'invoice_price',
            'values' => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray(),
            'value' => Mage::getStoreConfig('prodlabel/content_info/invoice_price'),
        ));
        $content_fieldset->addField('unit_price', 'select', array(
            'label' => Mage::helper('prodlabel')->__('Unit price for product as displayed on website'),
            'required' => false,
            'name' => 'unit_price',
            'values' => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray(),
            'value' => Mage::getStoreConfig('prodlabel/content_info/unit_price'),
        ));
        $content_fieldset->addField('slaughtered_farmed', 'select', array(
            'label' => Mage::helper('prodlabel')->__('Slaughtered/Farmed on'),
            'required' => false,
            'name' => 'slaughtered_farmed',
            'values' => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray(),
            'value' => Mage::getStoreConfig('prodlabel/content_info/slaughtered_farmed'),
        ));
        $content_fieldset->addField('source_market', 'select', array(
            'label' => Mage::helper('prodlabel')->__('Source Market'),
            'required' => false,
            'name' => 'source_market',
            'values' => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray(),
            'value' => Mage::getStoreConfig('prodlabel/content_info/source_market'),
        ));
        $content_fieldset->addField('order_no', 'select', array(
            'label' => Mage::helper('prodlabel')->__('Order no'),
            'required' => false,
            'name' => 'order_no',
            'values' => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray(),
            'value' => Mage::getStoreConfig('prodlabel/content_info/order_no'),
        ));
        $content_fieldset->addField('background_logo', 'select', array(
            'label' => Mage::helper('prodlabel')->__('Dimmed out logo as background'),
            'required' => false,
            'name' => 'background_logo',
            'values' => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray(),
            'value' => Mage::getStoreConfig('prodlabel/content_info/background_logo'),
        ));


        $button_fieldset->addField('label_submit', 'submit', array(
            'value' => Mage::helper('prodlabel')->__('Submit Label'),
            'name' => 'label_submit',
            'class' => 'form-button'
        ));

        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }

    protected function _prepareLayout() {
        parent::_prepareLayout();
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
        }
    }

}
