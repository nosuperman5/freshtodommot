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

class MageWorx_ShoppingList_Helper_Data extends Mage_Core_Helper_Abstract {
    
    const XML_SCOPE = 'mageworx_customers/shoppinglist/scope';
    const XML_ENABLED = 'mageworx_customers/shoppinglist/enabled';      
    
    public function isScope() {
        return Mage::getStoreConfig(self::XML_SCOPE);        
    }
    
    public function isEnabled() {
        return Mage::getStoreConfig(self::XML_ENABLED);        
    }    
    
    public function defaultDescriptionString()
    {
        return $this->__('Please, enter your description...');
    }
    
    public function defaultCommentString()
    {
        return $this->__('Please, enter your comments...');
    }
    
    public function getProductById($productId, $storeId = false, $params = '')
    {        
        if ($productId>0) {           
            
            if (!$storeId) $storeId = Mage::app()->getStore()->getId();
            
            if (!is_array($params)) {if ($params) $params = unserialize($params); else $params = array();}
            
            $product = Mage::getModel('catalog/product')->setStoreId($storeId)->load($productId);
            
            if (count($params)>0) {
                $_buyRequest = new Varien_Object($params);                
                $cartCandidates = $product->getTypeInstance(true)->processConfiguration($_buyRequest, $product);                    
                $product->setCartCandidates($cartCandidates);
                
                if ($product->getTypeId()==Mage_Catalog_Model_Product_Type::TYPE_SIMPLE && $product->getTypeInstance(true)->hasRequiredOptions($product)) {

                    $product->setFinalPrice($product->getFinalPrice());
                
                } elseif ($product->getTypeId()==Mage_Catalog_Model_Product_Type::TYPE_GROUPED) { //getGroupedOptions
                    $associatedProducts = $product->getTypeInstance(true)->getAssociatedProducts($product);
                    $totalFinalPrice = 0;
                    if ($associatedProducts) {
                        foreach ($associatedProducts as $associatedProduct) {                            
                            $price = $associatedProduct->getFinalPrice();
                            if (isset($params['super_group'][$associatedProduct->getId()])) {
                                $qty = intval($params['super_group'][$associatedProduct->getId()]);
                                $price = $price * $qty;
                            } 
                            $totalFinalPrice += $price;
                        }
                        
                    }
                    $product->setFinalPrice($totalFinalPrice);
                }    
            }    
            
            /**
             * Reset product final price because it related to custom options
            */                                  
            //$product->setFinalPrice(null);
            //$product->setCustomOptions(array()); //$this->_optionsByCode
            return $product;
        }      
        return null;
    }
    
    
    public function getOptions($product, $params)
    {        
                
        $options = array();
        
        if  ($product->getTypeId()==Mage_Catalog_Model_Product_Type::TYPE_SIMPLE && !$product->getTypeInstance(true)->hasRequiredOptions($product)) return $options;
        if (!is_array($params)) {if ($params) $params = unserialize($params); else $params = array();}
                                
        
        $cartCandidates = $product->getCartCandidates();                
        if (!$cartCandidates) {                    
            $_buyRequest = new Varien_Object($params);
            $cartCandidates = $product->getTypeInstance(true)->processConfiguration($_buyRequest, $product);
        }
                        
                
        switch ($product->getTypeId()) {
            
            case Mage_Catalog_Model_Product_Type::TYPE_SIMPLE: //SIMPLE            
                                
                if ($option_ids = $product->getCustomOption('option_ids')) {
                    $option_ids = explode(',', $option_ids->getValue());                    
                    
                    foreach ($option_ids as $optionId) {
                        if ($option = $product->getOptionById($optionId)) {                                                        
                            $optionValue = $product->getCustomOption('option_'.$option->getId())->getValue();                           
                            $group = $option->groupFactory($option->getType())->setOption($option);                                                    
                            $options[] = array('label' => $option->getTitle(), 'value' => $group->getFormattedOptionValue($optionValue));
                        }
                    }
                }
                return $options;
                break;
            
            
            case Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE: //getConfigurableOptions            
                $options = $product->getTypeInstance(true)->getSelectedAttributesInfo($product);
                return $options;
                break;
            
            case Mage_Catalog_Model_Product_Type::TYPE_GROUPED: //getGroupedOptions            
                $associatedProducts = $product->getTypeInstance(true)->getAssociatedProducts($product);

                if ($associatedProducts) {
                    foreach ($associatedProducts as $associatedProduct) {
                                                                        
                        $qty = intval($associatedProduct->getQty());
                        $price = $associatedProduct->getFinalPrice();
                        
                        if (isset($params['super_group'][$associatedProduct->getId()])) {
                            $qty = intval($params['super_group'][$associatedProduct->getId()]);
                            $price = $price * $qty;
                        }
                                                                        
                        $options[] = array(
                            'label' => $associatedProduct->getName(),
                            'value' => $qty . ' x ' . Mage::helper('core')->currency($price) //($qty && $qty->getValue()) ? $qty->getValue() : 0
                        );
                    }
                }
                
                return $options;
                break;
                            
             case Mage_Catalog_Model_Product_Type::TYPE_BUNDLE: //getBundleOptions:                                                   
                 
                 foreach ($cartCandidates as $bundleOption) {            
                     if ($bundleOption->getId()==$product->getId()) continue;
                     
                     $qty = intval($bundleOption->getQty());
                     if ($qty==0) $qty = 1;
                     
                     $price = $bundleOption->getFinalPrice();
                     
                     if (isset($params['bundle_option_qty'][$bundleOption->getOptionId()])) {
                         $qty = $qty * intval($params['bundle_option_qty'][$bundleOption->getOptionId()]);
                         $price = $price * $qty;
                     }    
                     
                     $title = $bundleOption->getOption()->getTitle();
                     
                     $options[$title]['label'] = $title;
                     $options[$title]['value'][] = $qty . ' x ' . $this->htmlEscape($bundleOption->getName()) . ' ' . Mage::helper('core')->currency($price);                    
                 }
                 $options = array_values($options);
                
                 return $options;
                 break;                
        } 
        
        return $options;
        
    }
    
    public function getListToCartUrl($list){
		if($list->getShoppinglistId()){
			$key =  Mage::getSingleton('core/session')->getFormKey();
			return Mage::getUrl('shoppinglist/ajax/addtocart', array('list' => $list->getShoppinglistId())); //'form_key' => $key
		}
		return '';
	}
    
}