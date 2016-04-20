<?php
/**
 * MageWorx
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MageWorx EULA that is bundled with
 * this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.mageworx.com/LICENSE-1.0.html
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade the extension
 * to newer versions in the future. If you wish to customize the extension
 * for your needs please refer to http://www.mageworx.com/ for more information
 *
 * @category   MageWorx
 * @package    MageWorx_ShoppingList
 * @copyright  Copyright (c) 2011 MageWorx (http://www.mageworx.com/)
 * @license    http://www.mageworx.com/LICENSE-1.0.html
 */

/**
 * Shopping List extension
 *
 * @category   MageWorx
 * @package    MageWorx_ShoppingList
 * @author     MageWorx Dev Team
 */

class MageWorx_Shoppinglist_Model_Mysql4_Item_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{

    public function _construct()
    {
        $this->_init('shoppinglist/item');
    }

    public function addShoppingListFilter($shoppinglistId = 0)
    {
        $this->addFieldToFilter('shoppinglist_id', $shoppinglistId);
        return $this;
    }
    
    public function addStoreFilter($storeIds = null)
    {
        if (Mage::helper('shoppinglist')->isScope()==1) {
            // scope = website
            if (is_null($storeIds)) {            
                $storeIds = Mage::app()->getWebsite()->getStoreIds(true);
            }        

            if (!is_array($storeIds)) {
                $storeIds = array($storeIds);
            }

            $storeIds = implode(',', $storeIds);
            $this->addFieldToFilter('store_id', array('in'=>$storeIds));                            
        }
        // scope = global        
        return $this;
    }
    
    public function addProductFilter($productId = 0)
    {
        $this->addFieldToFilter('product_id', $productId);
        return $this;
    }
    
    public function addMd5HashFilter($md5 = 0)
    {
        $this->addFieldToFilter('md5_hash', $md5);
        return $this;
    }
    
    
}
