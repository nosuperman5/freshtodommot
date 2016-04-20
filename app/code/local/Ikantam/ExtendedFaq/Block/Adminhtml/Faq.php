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

class Ikantam_ExtendedFaq_Block_Adminhtml_Faq extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    protected function _construct()
    {
        $this->_blockGroup = 'extendedfaq';
        $this->_controller = 'adminhtml_faq';
		$this->_headerText = Mage::helper("extendedfaq")->__("Manage FAQ Questions");
		$this->_addButtonLabel = Mage::helper("extendedfaq")->__("Add FAQ Question");
    }
}
