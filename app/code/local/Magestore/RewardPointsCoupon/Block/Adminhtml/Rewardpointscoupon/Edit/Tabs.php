<?php
/**
 * Magestore
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the Magestore.com license that is
 * available through the world-wide-web at this URL:
 * http://www.magestore.com/license-agreement.html
 * 
 * DISCLAIMER
 * 
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 * 
 * @category    Magestore
 * @package     Magestore_RewardPointsCoupon
 * @copyright   Copyright (c) 2012 Magestore (http://www.magestore.com/)
 * @license     http://www.magestore.com/license-agreement.html
 */

/**
 * Rewardpointscoupon Edit Tabs Block
 * 
 * @category    Magestore
 * @package     Magestore_RewardPointsCoupon
 * @author      Magestore Developer
 */
class Magestore_RewardPointsCoupon_Block_Adminhtml_Rewardpointscoupon_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('rewardpointscoupon_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('rewardpointscoupon')->__('Reward Coupon '));
    }
    
    
}