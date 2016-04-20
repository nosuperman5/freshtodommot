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

abstract class MageWorx_ShoppingList_Block_Abstract extends Mage_Catalog_Block_Product_Abstract
{  
    
    protected function _construct()
    {
        parent::_construct();
    }

    public function getFormatedDate($date)
    {    
        return $this->formatDate($date, Mage_Core_Model_Locale::FORMAT_TYPE_MEDIUM);
    }
    
    public function getAjaxUrl()
    {        
        return $this->getUrl('shoppinglist/ajax/');
    }
    
    public function getBackUrl() {
        if ($this->getRefererUrl()) {
            return $this->getRefererUrl();
        }
        return $this->getUrl('shoppinglist/');
    }               
    
    public function getShoppingLists() {                                    
        return Mage::getResourceModel('shoppinglist/list_collection')->addStoreFilter()->addCustomerFilter()->addProductCount();                               
    }
    
    public function getProductById($productId, $storeId = false, $params = '')
    {        
        return Mage::helper('shoppinglist')->getProductById($productId, $storeId, $params);
    }
    
    
    public function getOptions($product, $params)
    {        
        return Mage::helper('shoppinglist')->getOptions($product, $params);
    }
    
    
    public function getPriceHtml($product, $displayMinimalPrice = false, $idSuffix = '')
    {
        if ($product->getFinalPrice()>0) {
            return '<div class="price-box"><p class="price">'.Mage::helper('core')->currency($product->getFinalPrice(), true, false).'</p></div>';
        } else {
            return '<br/>';
        }    
    }  
    
    
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $headBlock = $this->getLayout()->getBlock('head');
        if ($headBlock) {
            $headBlock->setTitle($this->__('My Shopping Lists'));
        }
    }

    
}
