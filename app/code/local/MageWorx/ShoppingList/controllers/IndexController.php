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

class MageWorx_ShoppingList_IndexController extends Mage_Core_Controller_Front_Action {

    public function preDispatch()
    {        
        //if (!Mage::helper('shoppinglist')->isEnabled()) return $this->_redirect('/');
        
        parent::preDispatch();

        $dialogMode = intval($this->getRequest()->getParam('dialog_mode', 0));
        if ($dialogMode && !Mage::getSingleton('customer/session')->isLoggedIn()) {                
            $this->getLayout()->getUpdate()->addHandle('shoppinglist_index_dialog_authorization');
            $this->loadLayoutUpdates()->_initLayoutMessages('checkout/session');
            $this->generateLayoutXml()->generateLayoutBlocks();
            $this->renderLayout();                 
            $this->setFlag('', 'no-dispatch', true);
            
            Mage::getSingleton('customer/session')->setBeforeAuthUrl($this->_getRefererUrl());            
            return false;
        }
                
        
        if (!Mage::getSingleton('customer/session')->authenticate($this)) {
            $this->setFlag('', 'no-dispatch', true);
            if(!Mage::getSingleton('customer/session')->getBeforeWishlistUrl()) {
                Mage::getSingleton('customer/session')->setBeforeWishlistUrl($this->_getRefererUrl());
            }                
        }

    } 

    public function indexAction() {        
        $this->loadLayout();
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('checkout/session');
        $this->_initLayoutMessages('catalog/session');
        $this->_initLayoutMessages('shoppinglist/session');
        $this->renderLayout();
    }
    
    
    public function submitListAction() {
        
        if (!$this->_validateFormKey()) {
            return $this->_redirect('*/');
        }
        
        $post=$this->getRequest()->getParams();
        
        $session = Mage::getSingleton('customer/session');
        $customerId=$session->getCustomerId();
        
        
        // delete selected lists
        if (isset($post['do_delete']) && isset($post['checked']) && is_array($post['checked']) && count($post['checked'])>0) {
            $deleteCount=0;            
            foreach ($post['checked'] as $listId=>$v) {
                $list = Mage::getModel('shoppinglist/list')->load($listId, 'shoppinglist_id');  
                if ($list->getCustomerId()==$customerId) {
                    try {
                        $list->delete();
                        $deleteCount++;
                    } catch (Exception $e) {}
                }            
            }            
            if ($deleteCount>0) {
                $session->addSuccess($this->__('The lists were successfully deleted.'));
                return $this->_redirectSuccess(Mage::getURL('*/'));
            } else {
                $session->addError($this->__('Failed to delete lists.'));                
                return $this->_redirectError(Mage::getURL('*/'));
            }            
        }
        
        // add to cart selected lists
        if (isset($post['do_cart']) && isset($post['checked']) && is_array($post['checked']) && count($post['checked'])>0) {                                              
            $addCount = $this->_listToCart(array_keys($post['checked']));            
            if ($addCount>0) {                             
                Mage::getSingleton('checkout/session')->addSuccess($this->__('The selected lists were successfully added to cart.'));
                return $this->_redirectSuccess(Mage::helper('checkout/cart')->getCartUrl());
            }                              
        }
        
                        
        return $this->_redirect('*/');        
    }
    
    
    public function createAction() {
        $this->loadLayout();
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('shoppinglist/session');
        $this->renderLayout();
    }
    
    public function submitCreateAction() {
        if (!$this->_validateFormKey()) {
            return $this->_redirect('*/');
        }
        
        $title = trim($this->getRequest()->getParam('title', ''));
        $descr = trim($this->getRequest()->getParam('description', ''));
        $ingredients = trim($this->getRequest()->getParam('ingredients', ''));        
        $directions = trim($this->getRequest()->getParam('directions', ''));        
        $image = trim($this->getRequest()->getParam('image', ''));
        
        if (Mage::helper('shoppinglist')->defaultDescriptionString()==$descr){
            $descr='';
        }
        if ($image==''){
            $image = Mage::getBaseUrl('media')."shippinglist".DS.Mage::getStoreConfig('mageworx_customers/shoppinglist/image');
        }
        
        
        $session = Mage::getSingleton('customer/session');
        
        if (strlen($title)==0) {            
            $session->addError($this->__('The title must not be empty.'));
            $session->setData('shoppinglist', array('description'=>$descr));
            return $this->_redirectError(Mage::getURL('*/*/create/'));
        }
                                  
        
        $customerId = Mage::getSingleton('customer/session')->getCustomerId();
        $storeId = Mage::app()->getStore()->getId();
        
        $listCount = Mage::getResourceModel('shoppinglist/list_collection')->addCustomerFilter($customerId)->addStoreFilter()->addTitleFilter($title)->addSelectCount()
                ->getFirstItem()->getListCount();        
        
        if ($listCount>0) {
            $session->addError($this->__('List with that title already exists.'));
            $session->setData('shoppinglist', array('description'=>$descr));
            return $this->_redirectError(Mage::getURL('*/*/create/'));
        }
        
        try {
            Mage::getModel('shoppinglist/list')
                ->setCustomerId($customerId)    
                ->setStoreId($storeId)
                ->setCreatedOn(Mage::getSingleton('core/date')->gmtDate())        
                ->setTitle($title)
                ->setDescription($descr)
                ->setIngredients($ingredients)
                ->setDirections($directions)
                ->setImage($image)
                ->save();
            $session->setData('shoppinglist', null);
            $session->addSuccess($this->__('The shopping list was successfully created.'));
            $this->_redirectSuccess(Mage::getURL('*/'));
            return true;
        } catch (Exception $e) {
            $session->addError($this->__('Failed to create list.'));
            $session->setData('shoppinglist', array('title'=>$title, 'description'=>$descr));
            $this->_redirectError(Mage::getURL('*/*/create/'));
        }              
    }
    
    
    public function viewAction()
    {                        
        //check access
        $listId = intval($this->getRequest()->getParam('id', false));
        if ($listId==0) return $this->_redirect('*/');        
        try {
            $list=Mage::getModel('shoppinglist/list')->load($listId, 'shoppinglist_id');
            if ($list->getCustomerId()!=Mage::getSingleton('customer/session')->getCustomerId()) return $this->_redirect('*/');
        } catch (Exception $e) { 
            return $this->_redirect('*/');
        }
        
        $this->loadLayout();
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('checkout/session');
        $this->_initLayoutMessages('catalog/session');
        $this->_initLayoutMessages('shoppinglist/session');
        $this->renderLayout();
    }
    
    public function submitItemAction() {
        
        $post = $this->getRequest()->getParams();
        
       
        if (!$this->_validateFormKey() || !isset($post['shoppinglist_id'])) {
            return $this->_redirect('*/');
        }
        
        $listId = intval($post['shoppinglist_id']);
        if ($listId==0) {
            return $this->_redirect('*/');
        }                
                
        $session = Mage::getSingleton('customer/session');
        $customerId=$session->getCustomerId();
        
        $list = Mage::getModel('shoppinglist/list')->load($listId, 'shoppinglist_id');
        if ($list->getCustomerId()!=$customerId) {
            return $this->_redirect('*/');
        }                
                                
        
        // if delete selected products
        if (isset($post['do_delete']) && isset($post['checked']) && is_array($post['checked']) && count($post['checked'])>0) {
            $deleteCount = 0;             
            foreach ($post['checked'] as $listItemId=>$v) {
                $listItem = Mage::getModel('shoppinglist/item')->load($listItemId, 'shoppinglist_item_id');  
                if ($listItem->getShoppinglistId()==$listId) {
                    try {
                        $listItem->delete();
                        $deleteCount++;
                    } catch (Exception $e) {}
                }            
            }            
            if ($deleteCount>0) {
                $session->addSuccess($this->__('The products were successfully deleted.'));
                return $this->_redirectSuccess(Mage::getURL('*/*/view/id/'.$listId.'/'));
            } else {
                $session->addError($this->__('Failed to delete products.'));                
                return $this->_redirectError(Mage::getURL('*/*/view/id/'.$listId.'/'));
            }            
        }
        
        // if update products
        if (isset($post['do_update']) && isset($post['description']) && is_array($post['description']) && count($post['description'])>0) {
            $updateCount = 0;             
            foreach ($post['description'] as $listItemId=>$descr) {
                $listItem = Mage::getModel('shoppinglist/item')->load($listItemId, 'shoppinglist_item_id');  
                $descr = trim($descr);
                if (Mage::helper('shoppinglist')->defaultCommentString()==$descr) $descr = '';                
                if (isset($post['qty'][$listItemId])) $qty = intval($post['qty'][$listItemId]); else $qty = 0;                
                if ($listItem->getShoppinglistId()==$listId && ($listItem->getDescription()!=$descr || $listItem->getQty()!=$qty)) {
                    try {
                        $listItem->setDescription($descr)->setQty($qty)->save();
                        $updateCount++;
                    } catch (Exception $e) {}
                }            
            }            
            if ($updateCount>0) {
                $session->addSuccess($this->__('The products were successfully updated.'));
                return $this->_redirectSuccess(Mage::getURL('*/*/view/id/'.$listId.'/'));
            }         
        }
        
        
        // if add to cart selected products
        if (isset($post['do_cart']) && isset($post['checked']) && is_array($post['checked']) && count($post['checked'])>0) {
            $addArray = array();            
            foreach ($post['checked'] as $listItemId=>$v) {
                $listItem = Mage::getModel('shoppinglist/item')->load($listItemId, 'shoppinglist_item_id');
                if ($listItem->getShoppinglistId()==$listId) {
                    if (isset($post['qty'][$listItemId])) $qty = intval($post['qty'][$listItemId]); else $qty = 1;
                    $addArray[$listItem->getProductId().':'.$listItem->getMd5Hash()]['qty'] = $qty;                   
                    $addArray[$listItem->getProductId().':'.$listItem->getMd5Hash()]['params'] = $listItem->getParams();
                }            
            } 
            
            $addCount = $this->_addToCart($addArray);
            
            if ($addCount>0) {                
                if ($addCount>1) {
                    Mage::getSingleton('checkout/session')->addSuccess($this->__('The selected products were successfully added to cart.'));
                } else {
                    $product = Mage::getModel('catalog/product')->load($listItem->getProductId());
                    Mage::getSingleton('checkout/session')->addSuccess($this->__('%1$s was successfully added to cart.', $product->getName()));
                }    
                return $this->_redirectSuccess(Mage::helper('checkout/cart')->getCartUrl());                
            }          
        }        
        
        return $this->_redirect('*/*/view/id/'.$listId.'/');                                                
    }
       
    public function addToCartAction() {
        $listItemId = intval($this->getRequest()->getParam('item_id', false));
        $qty = intval($this->getRequest()->getParam('qty', 1));
        
        $listItem = Mage::getModel('shoppinglist/item')->load($listItemId, 'shoppinglist_item_id');                
        $list = Mage::getModel('shoppinglist/list')->load($listItem->getShoppinglistId(), 'shoppinglist_id');
    
        $customerId=Mage::getSingleton('customer/session')->getCustomerId();        
        if ($list->getCustomerId()!=$customerId) {
            return $this->_redirectError($this->_getRefererUrl()); 
        }
        
        $addArray[$listItem->getProductId().':'.$listItem->getMd5Hash()]['qty'] = $qty;
        $addArray[$listItem->getProductId().':'.$listItem->getMd5Hash()]['params'] = $listItem->getParams();
        
        $addCount = $this->_addToCart($addArray);
            
        if ($addCount>0) {                             
            $product = Mage::getModel('catalog/product')->load($listItem->getProductId());
            Mage::getSingleton('checkout/session')->addSuccess($this->__('%1$s was successfully added to cart.', $product->getName()));
            return $this->_redirectSuccess(Mage::helper('checkout/cart')->getCartUrl());
        }
        
        return $this->_redirectError($this->_getRefererUrl());         
        
    }
    
    public function listAddToCartAction() {
        $listId = intval($this->getRequest()->getParam('id', false));                                
        $addCount = $this->_listToCart(array($listId));
            
        if ($addCount>0) {                             
            Mage::getSingleton('checkout/session')->addSuccess($this->__('The selected list was successfully added to cart.'));
            return $this->_redirectSuccess(Mage::helper('checkout/cart')->getCartUrl());
        }
        
        return $this->_redirectError($this->_getRefererUrl());         
        
    }
    
    
    protected function _listToCart($listIds = array())
    {                              
        $addArray = array();
        $customerId = Mage::getSingleton('customer/session')->getCustomerId();
        
        foreach ($listIds as $listId) {
        
            $list = Mage::getModel('shoppinglist/list')->load($listId, 'shoppinglist_id');
            if ($list->getCustomerId()==$customerId) {
                $items = Mage::getResourceModel('shoppinglist/item_collection')
                    ->addShoppingListFilter($listId)
                    ->addStoreFilter();
                foreach ($items as $item) {
                    if (!isset($addArray[$item->getProductId().':'.$item->getMd5Hash()])) {
                        $addArray[$item->getProductId().':'.$item->getMd5Hash()]['qty'] = $item->getQty();                        
                    } else {
                        $addArray[$item->getProductId().':'.$item->getMd5Hash()]['qty'] += $item->getQty();
                    }
                    $addArray[$item->getProductId().':'.$item->getMd5Hash()]['params'] = $item->getParams();
                }
                
            }    
        }
                
        return $this->_addToCart($addArray);
        

        
    }
    
    protected function _addToCart($productIds = array())
    {                                      
        $addCount = 0;
        $cart = Mage::getSingleton('checkout/cart');
        
        foreach ($productIds as $productId=>$value) {            
            $productId = explode(':', $productId);
            $productId = $productId[0];
            
            $qty = $value['qty'];
            $params = $value['params'];
            if ($params) $params = unserialize($params); else $params = array();                
                
            try {                                
                $product = Mage::getModel('catalog/product')->load($productId);            
                $storeId = Mage::app()->getStore()->getId();

                if (!$product->getId() || $product->getStatus() != Mage_Catalog_Model_Product_Status::STATUS_ENABLED) {
                    continue;
                }

                if (!$product->isVisibleInSiteVisibility()) {
                    if ($product->getStoreId() == $storeId) {
                        continue;
                    }
                    $urlData = Mage::getResourceSingleton('catalog/url')
                        ->getRewriteByProductStore(array($product->getId() => $storeId));
                    if (!isset($urlData[$product->getId()])) {
                        continue;
                    }
                    $product->setUrlDataObject(new Varien_Object($urlData));
                    $visibility = $product->getUrlDataObject()->getVisibility();
                    if (!in_array($visibility, $product->getVisibleInSiteVisibilities())) {
                        continue;
                    }
                }            

                if (!$product->isSalable()) {
                    throw new Mage_Core_Exception(null, self::EXCEPTION_CODE_NOT_SALABLE);
                }

                $buyRequest = new Varien_Object($params);
                $buyRequest->setQty($qty);

                $cart->addProduct($product, $buyRequest);                
                $addCount++;

            } catch (Exception $e) {
                continue;
            }
        }    

        try {
            $cart->save();
            return $addCount;
        } catch (Exception $e) {
            return 0;
        }        
        
    }    
    
    
    protected function _getWishlist()
    {
        try {
            $wishlist = Mage::getModel('wishlist/wishlist')
                ->loadByCustomer(Mage::getSingleton('customer/session')->getCustomer(), true);
            Mage::register('wishlist', $wishlist);
        } catch (Mage_Core_Exception $e) {
            Mage::getSingleton('wishlist/session')->addError($e->getMessage());
        } catch (Exception $e) {
            Mage::getSingleton('wishlist/session')->addException($e,
                Mage::helper('wishlist')->__('Cannot create wishlist.')
            );
            return false;
        }
        return $wishlist;
    }            
    
    
    // add product to list
    public function addAction() {
       
        $dialogMode = $this->getRequest()->getParam('dialog_mode', false);        
                
        $productId = intval($this->getRequest()->getParam('product', false));
        $qty = intval($this->getRequest()->getParam('qty', false));
        if ($qty==0) $qty = 1;                
        
        $listId = $this->getRequest()->getParam('id', false);                
        
        $params = $this->getRequest()->getParam('params', '');        
        if (!$params) {
            $params = $this->getRequest()->getParams();
            unset($params['qty']);
            unset($params['product']);
            unset($params['dialog_mode']);
            unset($params['list_title']);
            unset($params['id']);
            unset($params['related_product']);
            //print_r($params); exit;
            $params=serialize($params);            
        }        
        $md5Hash = hexdec(substr(md5($productId.$params),0,8));        
        
        
        $session = Mage::getSingleton('customer/session');
        $customerId=$session->getCustomerId();
        
        if (!$productId) return $this->_redirect('*/');                
        
        $product = Mage::getModel('catalog/product')->load($productId);
        if (!$product->getId() || !$product->isVisibleInCatalog()) {
            $session->addError($this->__('Cannot specify product.'));
            if ($dialogMode) {$this->_forward('addResult'); return true;} else {return $this->_redirect('*/');}
        }                                
        
        if ($listId=='new_list') {
            // create new list
            $title = trim($this->getRequest()->getParam('list_title', ''));            
            
            if (strlen($title)==0) {
                $session->addError($this->__('The title must not be empty.'));
                if ($dialogMode) {$this->_forward('addResult'); return true;} else {return $this->_redirect('*/');}
            }   
            $storeId = Mage::app()->getStore()->getId();
            $existListId = Mage::getResourceModel('shoppinglist/list_collection')->addCustomerFilter($customerId)->addStoreFilter()->addTitleFilter($title)
                        ->getFirstItem()->getShoppinglistId();
            try {
                if ($existListId>0) {                            
                    $listId = $existListId;
                } else {    
                    $newList = Mage::getModel('shoppinglist/list')->setCustomerId($customerId)    
                            ->setStoreId($storeId)
                            ->setCreatedOn(Mage::getSingleton('core/date')->gmtDate())        
                            ->setTitle($title)
                            ->setDescription('')
                            ->setIngredients('')
                            ->setDirections('')
                            ->setImage(Mage::getBaseUrl('media')."shippinglist".DS.Mage::getStoreConfig('mageworx_customers/shoppinglist/image'))                                    
                            ->save();
                    $listId=$newList->getShoppinglistId();
                }
            } catch (Exception $e) {
                $session->addError($this->__('Failed to create list.'));                
                if ($dialogMode) {$this->_forward('addResult'); return true;} else {return $this->_redirect('*/');}
            }                                          
        } elseif ($listId=='wishlist') {            
            // add to wishlist
            if ($dialogMode) {            
                try {
                    $wishlist = $this->_getWishlist();        
                    
                    if (Mage::getVersion()>='1.5.0.0') {
                        $buyRequest = new Varien_Object($this->getRequest()->getParams());
                        $result = $wishlist->addNewItem($product, $buyRequest);
                    } else {
                        $result = $wishlist->addNewItem($productId);
                    }    
                    if (is_string($result)) {
                        Mage::throwException($result);
                    }
                    $wishlist->save();

                    $session->addSuccess($this->__('%1$s was successfully added to your wishlist.', $product->getName()));
                    $this->getRequest()->setParams(array('id'=>$listId));
                    $this->_forward('addResult');
                    return true;
                    
                } catch (Exception $e) {
                    $session->addError($this->__('Failed to add product.'));
                    if ($dialogMode) {$this->_forward('addResult'); return true;} else {return $this->_redirect('*/');}
                }
           } else {
               return $this->_redirect('wishlist/index/add/product/'.$productId.'/qty/'.$qty.'/');
           }
                    
        } else {
            $listId = intval($listId);
            if (!$listId) {                
                if ($dialogMode) {$this->_forward('addResult'); return true;} else {return $this->_redirect('*/');}
            }
        }
                                
        
        $list = Mage::getModel('shoppinglist/list')->load($listId);
        if ($list->getCustomerId()!=$customerId) {
            $session->addError($this->__('Cannot specify shopping list.'));
            if ($dialogMode) {$this->_forward('addResult'); return true;} else {return $this->_redirect('*/');}
        }        
        $storeId = Mage::app()->getStore()->getId();
        $existProduct = Mage::getResourceModel('shoppinglist/item_collection')->addShoppingListFilter($listId)->addMd5HashFilter($md5Hash)->addStoreFilter()->getFirstItem();
        try {
            if ($existProduct->getShoppinglistItemId() > 0) {
                Mage::getModel('shoppinglist/item')->setShoppinglistItemId($existProduct->getShoppinglistItemId())
                        ->setQty($qty + $existProduct->getQty())
                        ->save();
            } else {
                
                if  ($product->getTypeId()==Mage_Catalog_Model_Product_Type::TYPE_SIMPLE && !$product->getTypeInstance(true)->hasRequiredOptions($product)) $params = '';
                
                $listItem=Mage::getModel('shoppinglist/item')->setShoppinglistId($listId)
                        ->setProductId($productId)
                        ->setStoreId($storeId)
                        ->setAddedAt(Mage::getSingleton('core/date')->gmtDate())
                        ->setDescription('')->setQty($qty)
                        ->setParams($params)->setMd5Hash($md5Hash)
                        ->save();                
            }    
            if ($dialogMode) {
                $session->addSuccess($this->__('%1$s was successfully added to your shopping list.', $product->getName()));
                $this->getRequest()->setParams(array('id'=>$listId));
                echo 'ok';
                return true;                
                $this->_forward('addResult');
            } else {
                $referer = $this->_getRefererUrl();
                $session->addSuccess($this->__('%1$s was successfully added to your shopping list. Click <a href="%2$s">here</a> to continue shopping.', $product->getName(), $referer));
                return $this->_redirectSuccess(Mage::getURL('*/*/view/id/'.$listId.'/'));
            }    
                
            
        } catch (Exception $e) {
            $session->addError($this->__('Cannot specify shopping list.'));
            if ($dialogMode) return $this->_redirect('*/*/addResult/'); else return $this->_redirect('*/');
        }                                                                      
    }        
    
    public function addToListsAction() {
        
        $params = $this->getRequest()->getParams();        
        if (isset($params['params']) && $params['params']!='') $params = unserialize($params['params']);        
        $listId = $this->getRequest()->getParam('id', false);
        
        if ($listId!='wishlist') {
            try {            
                $productId = intval($this->getRequest()->getParam('product'));            
                $product = Mage::helper('shoppinglist')->getProductById($productId, false, $params);
                
                                                
                if (
                    $product->isConfigurable() || $product->isGrouped() || $product->getTypeId()==Mage_Catalog_Model_Product_Type::TYPE_BUNDLE ||
                    ($product->getTypeId()==Mage_Catalog_Model_Product_Type::TYPE_SIMPLE && $product->getTypeInstance(true)->hasRequiredOptions($product))
                        
                )  {                            
                    //if ($product->isGrouped() || $product->getTypeInstance(true)->hasRequiredOptions($product) ||
                    //    (Mage::helper('icart')->isExtensionEnabled('MageWorx_CustomPrice') && Mage::helper('customprice')->isCustomPriceAllowed($product)))
                    $options=Mage::helper('shoppinglist')->getOptions($product, $params);                
                    if (count($options)==0 || $product->getFinalPrice()==0) {                                

                        Mage::register('current_product', $product);
                        Mage::register('product', $product);

                        $update = $this->getLayout()->getUpdate()->addHandle('shoppinglist_index_options');                                        
                        $this->addActionLayoutHandles();

                        $update->addHandle('PRODUCT_TYPE_'.$product->getTypeId());
                        $update->addHandle('PRODUCT_'.$product->getId());

                        if ($product->getPageLayout()) {
                            $this->getLayout()->helper('page/layout')
                                ->applyHandle($product->getPageLayout());
                        }                                        

                        $this->loadLayoutUpdates();
                        $update->addUpdate($product->getCustomLayoutUpdate());

                        $this->generateLayoutXml()->generateLayoutBlocks();

                        if ($product->getPageLayout()) {
                            $this->getLayout()->helper('page/layout')
                                ->applyTemplate($product->getPageLayout());
                        }                    
                        $this->renderLayout();

                        return;
                        exit;
                    }
                }            

                /**
                 * Check product availability
                 */
                if (!$product) {
                    Mage::getSingleton('customer/session')->addError($this->__('Product is not available.'));
                    $this->_forward('addResult');
                    return;
                }

            } catch (Exception $e) {
                Mage::getSingleton('customer/session')->addException($e, $this->__('Cannot add the item to shopping cart.'));
                $this->_forward('addResult');
                return;
            }
        }    
        
        $this->_forward('add');
        
    }
    
    
    public function showListsAction() {        
        $this->getLayout()->getUpdate()->addHandle('shoppinglist_index_dialog');
        $this->loadLayoutUpdates()->_initLayoutMessages('checkout/session');
        $this->generateLayoutXml()->generateLayoutBlocks();
        $this->renderLayout();               
    }
    
    public function addResultAction() {               
        $this->getLayout()->getUpdate()->addHandle('shoppinglist_index_dialog_add_result');
        $this->loadLayoutUpdates()->_initLayoutMessages('checkout/session');
        $this->loadLayoutUpdates()->_initLayoutMessages('customer/session');
        $this->generateLayoutXml()->generateLayoutBlocks();
        $this->renderLayout();                
    }
    
    public function showAuthorizationAction() {               
        $this->getLayout()->getUpdate()->addHandle('shoppinglist_index_dialog_authorization');
        $this->loadLayoutUpdates()->_initLayoutMessages('checkout/session');
        $this->generateLayoutXml()->generateLayoutBlocks();
        $this->renderLayout();                
    }
    
    

}