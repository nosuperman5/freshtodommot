<?php

$installer = $this;
//echo get_class($installer); die();
$installer->startSetup();

/*$this->addAttribute('customer_address', 'address_label', array(
    'type' => 'varchar',
    'input' => 'text',
    'label' => 'Address Label',
    'global' => 1,
    'visible' => 1,
    'required' => 0,
    'user_defined' => 1,
    'visible_on_front' => 1
));
Mage::getSingleton('eav/config')
    ->getAttribute('customer_address', 'address_label')
    ->setData('used_in_forms', array('customer_address_edit','adminhtml_customer_address'))
    ->save();


$this->addAttribute('customer_address', 'address_type', array(
    'type' => 'int',
    'input' => 'select',
    'label' => 'Address Type',
    'global' => 1,
    'visible' => 1,
    'required' => 0,
    'user_defined' => 1,
    'visible_on_front' => 1,
	'source' => 'fresh_local/config_source_addresstype'
));
Mage::getSingleton('eav/config')
    ->getAttribute('customer_address', 'address_type')
    ->setData('used_in_forms', array('customer_address_edit','adminhtml_customer_address'))
    ->save();


$this->addAttribute('customer_address', 'address_description', array(
    'type' => 'text',
    'input' => 'textarea',
    'label' => 'Address Description',
    'global' => 1,
    'visible' => 1,
    'required' => 0,
    'user_defined' => 1,
    'visible_on_front' => 1
));
Mage::getSingleton('eav/config')
    ->getAttribute('customer_address', 'address_description')
    ->setData('used_in_forms', array('customer_address_edit','adminhtml_customer_address'))
    ->save();


$this->addAttribute('customer_address', 'address_instruction', array(
    'type' => 'text',
    'input' => 'textarea',
    'label' => 'Address Instruction',
    'global' => 1,
    'visible' => 1,
    'required' => 0,
    'user_defined' => 1,
    'visible_on_front' => 1
));
Mage::getSingleton('eav/config')
    ->getAttribute('customer_address', 'address_instruction')
    ->setData('used_in_forms', array('customer_address_edit','adminhtml_customer_address'))
    ->save();
*/	

$this->addAttribute('customer', 'phone', array(
    'type' => 'varchar',
    'input' => 'text',
    'label' => 'Phone',
    'global' => 1,
    'visible' => 1,
    'required' => 1,
    'user_defined' => 1,
    'visible_on_front' => 1
));
Mage::getSingleton('eav/config')
    ->getAttribute('customer', 'phone')
    ->setData('used_in_forms', array('adminhtml_customer','customer_account_edit','customer_account_create'))
    ->save();



$this->addAttribute('customer', 'whatsapp_exist', array(
    'type' => 'int',
    'input' => 'select',
    'label' => 'Whatsapp Exist',
    'global' => 1,
    'visible' => 1,
    'required' => 0,
    'user_defined' => 1,
    'visible_on_front' => 1,
	'source' => 'eav/entity_attribute_source_boolean'
));
Mage::getSingleton('eav/config')
    ->getAttribute('customer', 'whatsapp_exist')
    ->setData('used_in_forms', array('adminhtml_customer','customer_account_edit'))
    ->save();

	
$this->addAttribute('customer', 'profile_image', array(
    'type' => 'varchar',
    'input' => 'file',
    'label' => 'Profile Image',
    'global' => 1,
    'visible' => 1,
    'required' => 0,
    'user_defined' => 1,
    'visible_on_front' => 1 //,
	//'source' => 'eav/entity_attribute_source_boolean'
));
Mage::getSingleton('eav/config')
    ->getAttribute('customer', 'profile_image')
    ->setData('used_in_forms', array('adminhtml_customer','customer_account_edit'))
    ->save();

$installer->endSetup();
