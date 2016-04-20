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

class Ikantam_ExtendedFaq_Block_Adminhtml_Faq_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    protected function _construct()
    {
		$this->_blockGroup = 'extendedfaq';
		$this->_controller = 'adminhtml_faq';
        $this->_mode       = 'edit';

        $question_id = (int) $this->getRequest()->getParam($this->_objectId);
        $question = Mage::getModel('extendedfaq/faq')->load($question_id);
        Mage::register('current_question', $question);
    }
 
    public function getHeaderText()
    {
        $question = Mage::registry('current_question');
        if ($question->getId()) { 
            return Mage::helper('extendedfaq')->__("Edit FAQ Question #%d", $question->getId());
        } else {
            return Mage::helper('extendedfaq')->__("Add FAQ Question");
        }
    }
}
