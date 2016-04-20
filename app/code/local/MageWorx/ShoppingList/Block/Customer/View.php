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

class MageWorx_ShoppingList_Block_Customer_View extends MageWorx_ShoppingList_Block_Abstract {

    protected $_shoppinglist = null;
    
    public function getShoppingList() {                
        if (!isset($this->_shoppinglist)) {
            $listId = intval($this->getRequest()->getParam('id', false));
            if ($listId>0) {            
                $this->_shoppinglist = Mage::getModel('shoppinglist/list')->load($listId, 'shoppinglist_id');
                if ($this->_shoppinglist->getCustomerId()!=Mage::getSingleton('customer/session')->getCustomerId()) {
                    $this->_shoppinglist = null;
                }
            }
        }
        return $this->_shoppinglist;                            
        
    }
    
    public function getShoppingListItems() {        
        if (!isset($this->_shoppinglist)) $this->getShoppingList();        
        if ($this->_shoppinglist->getCustomerId()!=Mage::getSingleton('customer/session')->getCustomerId()) return array();            
        
        return Mage::getResourceModel('shoppinglist/item_collection')
                ->addShoppingListFilter($this->_shoppinglist->getShoppinglistId())
                ->addStoreFilter();                                         
        
    }
    
    public function getOtherShoppingLists() {        
        if (!isset($this->_shoppinglist)) $this->getShoppingList();                
        if ($this->_shoppinglist->getCustomerId()!=Mage::getSingleton('customer/session')->getCustomerId()) return array();
        
        return Mage::getResourceModel('shoppinglist/list_collection')->addStoreFilter()->addCustomerFilter()
                    ->addExcludeFilter($this->_shoppinglist->getShoppinglistId())
                    ->addProductCount();                            
    }    

}