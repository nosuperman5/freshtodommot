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

class Ikantam_ExtendedFaq_Adminhtml_FaqController extends Mage_Adminhtml_Controller_Action
{
	 public function indexAction()
    {
        $this->_title($this->__('Manage FAQ Questions'));
        $this->loadLayout();
        $this->_setActiveMenu('Ikantam_ExtendedFaq');
        $this->renderLayout();
    }

	public function newAction()
    {
        $this->_title($this->__('New FAQ Question'));
        $this->loadLayout();
        $this->_setActiveMenu('Ikantam_ExtendedFaq');

        $this->renderLayout();
    }
    
    public function editAction()
    {
        $this->_title($this->__('Edit FAQ Question'));
        $this->loadLayout();
        $this->_setActiveMenu('Ikantam_ExtendedFaq');
        $this->_addBreadcrumb(Mage::helper('extendedfaq')->__('FAQ'), Mage::helper('extendedfaq')->__('FAQ'));
        $this->renderLayout();
    }
	
	public function deleteAction()
    {
        $tipId = $this->getRequest()->getParam('id', false);
 
        try {
            Mage::getModel('extendedfaq/faq')->setId($tipId)->delete();
            Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('extendedfaq')->__('Question successfully deleted'));
            
            return $this->_redirect('*/*/');
        } catch (Mage_Core_Exception $e){
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        } catch (Exception $e) {
            Mage::logException($e);
            Mage::getSingleton('adminhtml/session')->addError($this->__('Unexpected error'));
        }
        $this->_redirectReferer();
    }
	
	public function saveAction()
    {
        $data = $this->getRequest()->getPost();
        if (!empty($data)) {
            try {
                Mage::getModel('extendedfaq/faq')->setData($data)
                    ->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('extendedfaq')->__('Question successfully saved'));
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError($this->__('Unexpected error'));
            }
        }
        return $this->_redirect('*/*/');
    }
	
	    public function massDeleteAction()
    {
        $count = 0;
        $helper = Mage::helper('extendedfaq');
        $questionIds = $this->getRequest()->getParam('id');
        if (!is_array($questionIds)) {
            $this->_getSession()->addError($this->__('Please select question(s).'));
        } else {
            try {
                foreach ($questionIds as $questionId) {
                    $question = Mage::getSingleton('extendedfaq/faq')->load($questionId);
                        
                    if ($question->getId()) {
                        $question->delete();
                        $count++;
                    }
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) have been deleted.', $count)
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    public function massEnableAction()
    {
        $count = 0;
        $questionIds = $this->getRequest()->getParam('id');
        if (!is_array($questionIds)) {
            $this->_getSession()->addError($this->__('Please select question(s).'));
        } else {
            try {
                foreach ($questionIds as $questionId) {
                    $question = Mage::getSingleton('extendedfaq/faq')->load($questionId);
                    if ($question->getId()) {
                        $question->setIsActive(1)->save();
                        $count++;
                    }                        
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) have been changed.', $count)
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
	
	public function massDisableAction()
    {
        $count = 0;
        $questionIds = $this->getRequest()->getParam('id');
        if (!is_array($questionIds)) {
            $this->_getSession()->addError($this->__('Please select question(s).'));
        } else {
            try {
                foreach ($questionIds as $questionId) {
                    $question = Mage::getSingleton('extendedfaq/faq')->load($questionId);
                    if ($question->getId()) {
                        $question->setIsActive(0)->save();
                        $count++;
                    }                        
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) have been changed.', $count)
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
}
