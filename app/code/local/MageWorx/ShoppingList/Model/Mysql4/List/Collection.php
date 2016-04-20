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

class MageWorx_ShoppingList_Model_Mysql4_List_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract {

    protected function _construct() {        
        $this->_init('shoppinglist/list');
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
    
    public function addCustomerFilter($customerId = null)
    {
        if (is_null($customerId)) {
            $customerId = Mage::getSingleton('customer/session')->getCustomerId();
        }

        if (!is_array($customerId)) {
            $customerId = array($customerId);
        }

        $this->addFieldToFilter('customer_id', $customerId);
        return $this;
    }
    
    public function addTitleFilter($title = '')
    {        

        if (!is_array($title)) {
            $title = array($title);
        }

        $this->addFieldToFilter('title', $title);
        return $this;
    }
    
    public function addSelectCount()
    {        
        $this->getSelect()->reset(Zend_Db_Select::COLUMNS)->columns(array('list_count'=>'COUNT(*)'));   
        return $this;
    }
    
    public function addProductCount()
    {
        $this->getSelect()->columns(array('product_count' =>new Zend_Db_Expr('IFNULL((SELECT SUM(`qty`) FROM '.$this->getTable('shoppinglist/item').' WHERE shoppinglist_id=main_table.shoppinglist_id),0)')));
        return $this;
    }
    
    public function addExcludeFilter($shoppinglistId = 0)
    {        
        $this->addFieldToFilter('shoppinglist_id', array('neq'=>$shoppinglistId));
        return $this;
    }
}