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
 
class Ikantam_ExtendedFaq_SearchController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
		if ($this->_isEnabled()) {
			$this->loadLayout();
			$head = $this->getLayout()->getBlock('head');
			
			$head->setTitle($this->_getMetaTitle())
				->setDescription($this->_getMetaDescription())
				->setKeywords($this->_getMetaKeywords());
			$this->renderLayout();
		} else {
			$this->getResponse()->setHeader('HTTP/1.1','404 Not Found');
    		$this->_forward('defaultNoRoute');
		}
	}
	
	protected function _isEnabled()
	{
		return Mage::helper('extendedfaq')->isEnabled();
	}
	
	protected function _getMetaTitle()
	{
		return Mage::helper('extendedfaq')->getMetaTitle();
	}

	protected function _getMetaKeywords()
	{
		return Mage::helper('extendedfaq')->getMetaKeywords();
	}
	
	protected function _getMetaDescription()
	{
		return Mage::helper('extendedfaq')->getMetaDescription();
	}
	
}
