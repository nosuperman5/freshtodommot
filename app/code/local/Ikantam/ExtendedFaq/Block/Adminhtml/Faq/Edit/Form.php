<?php
/**
 * iKantam LLC
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the iKantam EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://magento.ikantam.com/store/license-agreement
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade
 * ExtendedFaq Module to newer versions in the future.
 *
 * @category   Ikantam
 * @package    Ikantam_ExtendedFaq
 * @author     iKantam Team <support@ikantam.com>
 * @copyright  Copyright (c) 2012 iKantam LLC (http://www.ikantam.com)
 * @license    http://magento.ikantam.com/store/license-agreement  iKantam EULA
 */

class Ikantam_ExtendedFaq_Block_Adminhtml_Faq_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
		$questionModel  = $this->getModel();

        if (!$questionModel)
        {
            $questionModel = new Ikantam_ExtendedFaq_Model_Faq();
        }
		
        $question = Mage::registry('current_question');
        $form = new Varien_Data_Form();
        $fieldset = $form->addFieldset('edit_question', array(
            'legend' => Mage::helper('extendedfaq')->__('Question Details')
        ));

        if ($question->getId()) {
            $fieldset->addField('id', 'hidden', array(
                'name'     => 'id',
                'required' => true
            ));
        }
   
		$fieldset->addField('category_id', 'select', array(
            'name'     => 'category_id',
            'label'    => Mage::helper('extendedfaq')->__('Category'),
            'values'   => $this->_getGroups(),
            'required' => true,
        ));
 
        $fieldset->addField('question', 'textarea', array(
            'name'      => 'question',
            'label'     => Mage::helper('extendedfaq')->__('Question'),
            'maxlength' => '250',
            'required'  => true,
        ));
        
        $fieldset->addField('answer', 'textarea', array(
            'name'      => 'answer',
            'label'     => Mage::helper('extendedfaq')->__('Answer'),
            'maxlength' => '250',
            'required'  => false,
        ));
        
        $fieldset->addField('sort_order', 'text', array(
            'name'      => 'sort_order',
            'label'     => Mage::helper('extendedfaq')->__('Sort Order'),
            'maxlength' => '5',
            'required'  => false,
        ));
 
		$fieldset->addField('is_active', 'select', array(
            'name'      => 'is_active',
            'label'     => Mage::helper('extendedfaq')->__('Enabled'),
            'values'    => array (
                '0' => Mage::helper('extendedfaq')->__('No'),
                '1' => Mage::helper('extendedfaq')->__('Yes'),
            ),
            'required'  => true,
        ));
		
        $form->setMethod('post');
        $form->setUseContainer(true);
        $form->setId('edit_form');
        $form->setAction($this->getUrl('*/*/save'));
        $form->setValues($question->getData());
        $this->setForm($form);
    }
    
    protected function _getGroups()
    {
    	return $groups = Mage::getResourceModel('extendedfaq/category_collection')
    		->setOrder('sort_order', 'asc')
            ->load()
            ->toOptionHash();
    }
}
