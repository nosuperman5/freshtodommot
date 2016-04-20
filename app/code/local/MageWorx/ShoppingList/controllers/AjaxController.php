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
class MageWorx_ShoppingList_AjaxController extends Mage_Core_Controller_Front_Action {

    public function removeListAction() {
        $listId = intval($this->getRequest()->getParam('list_id', false));
        $listCount = intval($this->getRequest()->getParam('list_count', 0)) - 1;
        if ($listId > 0) {
            $list = Mage::getModel('shoppinglist/list')->load($listId, 'shoppinglist_id');
            if ($list->getCustomerId() == Mage::getSingleton('customer/session')->getCustomerId()) {
                try {
                    $list->delete();
                    if ($listCount > 0) {
                        $this->getResponse()->setBody('ok');
                    } else {
                        $this->getLayout()->getUpdate()->addHandle('shoppinglist_ajax_list');
                        $this->loadLayoutUpdates()->_initLayoutMessages('checkout/session');
                        $this->generateLayoutXml()->generateLayoutBlocks();
                        $this->renderLayout();
                    }
                    return true;
                } catch (Exception $e) {
                    
                }
            }
        }
        $this->getResponse()->setBody('no');
    }

    public function duplicateListAction() {
        $listId = intval($this->getRequest()->getParam('list_id', false));
        if ($listId > 0) {
            $list = Mage::getModel('shoppinglist/list')->load($listId, 'shoppinglist_id');
            if ($list->getCustomerId() == Mage::getSingleton('customer/session')->getCustomerId()) {
                try {
                    $data = $list->getData();
                    unset($data['shoppinglist_id']);
                    $data['title'] .= '_copy';
                    $listCopy = Mage::getModel('shoppinglist/list')->setData($data)->save();
                    $listCopyId = $listCopy->getId();

                    $items = Mage::getResourceModel('shoppinglist/item_collection')->addShoppingListFilter($listId)->addStoreFilter();
                    if ($items && count($items) > 0) {
                        foreach ($items as $item) {
                            $data = $item->getData();
                            unset($data['shoppinglist_item_id']);
                            $data['shoppinglist_id'] = $listCopyId;
                            $data['created_on'] = Mage::getSingleton('core/date')->gmtDate();
                            Mage::getModel('shoppinglist/item')->setData($data)->save();
                        }
                    }
                    $this->getLayout()->getUpdate()->addHandle('shoppinglist_ajax_list');
                    $this->loadLayoutUpdates()->_initLayoutMessages('checkout/session');
                    $this->generateLayoutXml()->generateLayoutBlocks();
                    $this->renderLayout();
                    return true;
                } catch (Exception $e) {
                    
                }
            }
        }
        $this->getResponse()->setBody('no');
    }

    public function saveListAction() {
        $listId = intval($this->getRequest()->getParam('list_id', false));
        $title = trim($this->getRequest()->getParam('title', ''));
        $description = trim($this->getRequest()->getParam('description', ''));
        $ingredients = trim($this->getRequest()->getParam('ingredients', ''));
        $directions = trim($this->getRequest()->getParam('directions', ''));
        $image = trim($this->getRequest()->getParam('image', ''));
        if (Mage::helper('shoppinglist')->defaultDescriptionString() == $description) {
            $description = '';
        }
        if ($image == '') {
            $image = Mage::getBaseUrl('media') . "shippinglist" . DS . Mage::getStoreConfig('mageworx_customers/shoppinglist/image');
        }
        if ($listId > 0 && strlen($title) > 0) {
            $list = Mage::getModel('shoppinglist/list')->load($listId, 'shoppinglist_id');
            if ($list->getCustomerId() == Mage::getSingleton('customer/session')->getCustomerId()) {
                try {
                    $list->setTitle($title)
                            ->setDescription($description)
                            ->setIngredients($ingredients)
                            ->setDirections($directions)
                            ->setImage($image)
                            ->save();
                    $this->getResponse()->setBody('ok');
                    return true;
                } catch (Exception $e) {
                    
                }
            }
        }
        $this->getResponse()->setBody('no');
    }

    public function removeItemAction() {
        $listItemId = intval($this->getRequest()->getParam('item_id', false));
        $listItemCount = intval($this->getRequest()->getParam('item_count', 0)) - 1;
        if ($listItemId > 0) {
            $listItem = Mage::getModel('shoppinglist/item')->load($listItemId, 'shoppinglist_item_id');
            $listId = $listItem->getShoppinglistId();
            if ($listId > 0) {
                $list = Mage::getModel('shoppinglist/list')->load($listId, 'shoppinglist_id');
                if ($list->getCustomerId() == Mage::getSingleton('customer/session')->getCustomerId()) {
                    try {
                        $listItem->delete();
                        if ($listItemCount > 0) {
                            $this->getResponse()->setBody('ok');
                        } else {
                            $this->getRequest()->setParams(array('id' => $listId));
                            $this->getLayout()->getUpdate()->addHandle('shoppinglist_ajax_view');
                            $this->loadLayoutUpdates()->_initLayoutMessages('checkout/session');
                            $this->generateLayoutXml()->generateLayoutBlocks();
                            $this->renderLayout();
                        }
                        return true;
                    } catch (Exception $e) {
                        
                    }
                }
            }
        }
        $this->getResponse()->setBody('no');
    }

    public function moveItemAction() {
        $listItemId = intval($this->getRequest()->getParam('item_id', false));
        $toListId = intval($this->getRequest()->getParam('list_id', false));
        $listItemCount = intval($this->getRequest()->getParam('item_count', 0)) - 1;
        $customerId = Mage::getSingleton('customer/session')->getCustomerId();

        if ($listItemId > 0 && $toListId > 0) {
            $listItem = Mage::getModel('shoppinglist/item')->load($listItemId, 'shoppinglist_item_id');
            $listId = $listItem->getShoppinglistId();
            if ($listId > 0) {
                $list = Mage::getModel('shoppinglist/list')->load($listId, 'shoppinglist_id');
                $toList = Mage::getModel('shoppinglist/list')->load($toListId, 'shoppinglist_id');

                if ($list->getCustomerId() == $customerId && $toList->getCustomerId() == $customerId) {
                    $storeId = Mage::app()->getStore()->getId();
                    $existProduct = Mage::getResourceModel('shoppinglist/item_collection')->addShoppingListFilter($toListId)->addMd5HashFilter($listItem->getMd5Hash())->addStoreFilter()->getFirstItem();
                    try {
                        if ($existProduct->getShoppinglistItemId() > 0) {
                            $comment = $existProduct->getDescription();
                            if ($comment == '')
                                $comment = $listItem->getDescription();
                            Mage::getModel('shoppinglist/item')->setShoppinglistItemId($existProduct->getShoppinglistItemId())
                                    ->setQty($listItem->getQty() + $existProduct->getQty())
                                    ->setDescription($comment)
                                    ->save();
                            $listItem->delete();
                        } else {
                            $listItem->setShoppinglistId($toListId)
                                    ->setAddedAt(Mage::getSingleton('core/date')->gmtDate())
                                    ->save();
                        }

                        if ($listItemCount > 0) {
                            $this->getResponse()->setBody('ok');
                        } else {
                            $this->getRequest()->setParams(array('id' => $listId));
                            $this->getLayout()->getUpdate()->addHandle('shoppinglist_ajax_view');
                            $this->loadLayoutUpdates()->_initLayoutMessages('checkout/session');
                            $this->generateLayoutXml()->generateLayoutBlocks();
                            $this->renderLayout();
                        }
                        return true;
                    } catch (Exception $e) {
                        
                    }
                }
            }
        }
        $this->getResponse()->setBody('no');
    }

    public function moveItemToNewAction() {
        $listItemId = intval($this->getRequest()->getParam('item_id', false));
        $title = trim($this->getRequest()->getParam('title', false));
        $customerId = Mage::getSingleton('customer/session')->getCustomerId();
        $date = Mage::getSingleton('core/date')->gmtDate();

        if ($listItemId > 0 && strlen($title) > 0) {
            $listItem = Mage::getModel('shoppinglist/item')->load($listItemId, 'shoppinglist_item_id');
            $listId = $listItem->getShoppinglistId();
            if ($listId > 0) {
                $list = Mage::getModel('shoppinglist/list')->load($listId, 'shoppinglist_id');

                if ($list->getCustomerId() == $customerId) {
                    $storeId = Mage::app()->getStore()->getId();
                    $existListId = Mage::getResourceModel('shoppinglist/list_collection')->addCustomerFilter($customerId)->addStoreFilter()->addTitleFilter($title)
                                    ->getFirstItem()->getShoppinglistId();
                    try {
                        if ($existListId > 0) {
                            $newListId = $existListId;
                        } else {
                            $newList = Mage::getModel('shoppinglist/list')->setCustomerId($customerId)
                                    ->setStoreId($storeId)
                                    ->setCreatedOn($date)
                                    ->setTitle($title)
                                    ->setDescription('')
                                    ->setIngredients('')
                                    ->setDirections('')
                                    ->setImage(Mage::getBaseUrl('media') . "shippinglist" . DS . Mage::getStoreConfig('mageworx_customers/shoppinglist/image'))
                                    ->save();
                            $newListId = $newList->getShoppinglistId();
                        }
                        if ($newListId > 0) {

                            $existProduct = Mage::getResourceModel('shoppinglist/item_collection')->addShoppingListFilter($newListId)->addMd5HashFilter($listItem->getMd5Hash())->addStoreFilter()->getFirstItem();
                            if ($existProduct->getShoppinglistItemId() > 0) {
                                Mage::getModel('shoppinglist/item')->setShoppinglistItemId($existProduct->getShoppinglistItemId())
                                        ->setQty($listItem->getQty() + $existProduct->getQty())
                                        ->save();
                                $listItem->delete();
                            } else {
                                $listItem->setShoppinglistId($newListId)
                                        ->setAddedAt($date)
                                        ->save();
                            }
                            $this->getResponse()->setBody($newListId);
                            return true;
                        }
                    } catch (Exception $e) {
                        
                    }
                }
            }
        }
        $this->getResponse()->setBody('0');
    }

    public function searchAction() {
        header('Content-Type: text/html; charset=utf-8');
        $result = '';
        $term = $this->getRequest()->getParam('q', false);
        $query = Mage::getModel('catalogsearch/query')->setQueryText($term)->prepare();
        $fulltextResource = Mage::getResourceModel('catalogsearch/fulltext')->prepareResult(
                Mage::getModel('catalogsearch/fulltext'), $term, $query
        );

        $collection = Mage::getResourceModel('catalog/product_collection');
        $collection->getSelect()->joinInner(
                array('search_result' => $collection->getTable('catalogsearch/result')), $collection->getConnection()->quoteInto(
                        'search_result.product_id=e.entity_id AND search_result.query_id=?', $query->getId()
                ), array('relevance' => 'relevance')
        );

        $productIds = array();
        $res = array();
        $productIds = $collection->getAllIds();

        foreach ($productIds as $productId) {
            $product = Mage::getModel('catalog/product')->load($productId);
            $categories = $product->getCategoryCollection()->addAttributeToSelect('name');
            foreach ($categories as $category) {
                if (!isset($res[$category->getId()])) {
                    $res[$category->getId()]['name'] = $category->getName();
                }
                $res[$category->getId()]['products'][] = $productId;
            }
        }

        if (empty($res)) {
            $result .= '<h3 class="muted">';
            $result .= $this->__('No Results! Please try another search.');
            $result .= '</h3>';
        } else {
            foreach ($res as $cat) {
                $result .= '<div class="list-search__box">';
                $result .= '<div class="list-search__box__title-box">';
                $result .= '<h3 class="list-search__box__title">';
                $result .= $cat['name'];
                $result .= '</h3>';
                $result .= '</div>';
                $result .= '<div class="list-search__box__list">';
                foreach ($cat['products'] as $productId) {
                    $product = Mage::getModel('catalog/product')->load($productId);
                    $productBlock = $this->getLayout()->createBlock('catalog/product_price');
                    $result .= '<div class="list-search__box__list__item">';
                    $result .= '<div class="list-search__box__list__item__img">';
                    $result .= '<img src="' . $product->getImageUrl() . '" alt="' . $product->getName() . '" />';
                    $result .= '</div>';
                    $result .= '<div class="list-search__box__list__item__content">';
                    $result .= '<h4 class="list-search__box__list__item__title">' . $product->getName() . '</h4>';
                    $result .= '<span class="list-search__box__list__item__info">' . $productBlock->getPriceHtml($product) . '</span>';
                    $result .= '<a href="#" onclick="addProductToList('.$productId.'); return false;" class="list-search__box__list__item__add"><i class="fa fa-plus"></i></a>';
                    $result .= '</div></div>';
                }
                $result .= '</div></div>';
            }
        }

        $this->getResponse()->setBody($result);
    }
    
    public function afterCartUpdateAction() {
        header('Content-Type: text/html; charset=utf-8');
        $result = '';
        $helper = Mage::helper('fresh_local');
        $productId = $this->getRequest()->getParam('p', false);
        $product = Mage::getModel('catalog/product')->load($productId);
        $cartQty = $helper->cartExist($product);

        $result .= '<button type="button" class="products__item__btn update-cart minus" onclick="afterCartUpdate('.$productId.')" data-redirect="'.$helper->getRemoveFromCartUrl($product).'"';
        if (!$cartQty){
            $result .= ' style="display:none;"';        
        }
        $result .= '><i class="ic-icn-minus-bold"></i></button>';
        $result .= '<button type="button" class="products__item__btn update-cart plus" onclick="afterCartUpdate('.$productId.')" data-redirect="'.$helper->getAddToCartUrl($product).'"><i class="ic-icn-plus-bold"></i><span>'.$this->__('Add').'</span></button>';

        $this->getResponse()->setBody($result);
    }
	
	public function addtocartAction() {
		$listId = $this->getRequest()->getParam('list', false);
		if($listId){
			$listItems = Mage::getResourceModel('shoppinglist/item_collection')
				->addShoppingListFilter($listId)
				->addStoreFilter();
			$productIds = array();
			foreach($listItems as $listItem){
				$productIds[] = $listItem->getProductId();
			}
			$cart = Mage::getSingleton('checkout/cart');
			foreach ($productsIds as $productId) {
			   $product = Mage::getModel('catalog/product');
			   $product->load($productId);
			   $cart->addProduct($product, array('qty' => 1));
			   $cart->save();
			}
		}
        /*header('Content-Type: text/html; charset=utf-8');
        $result = '';
        $helper = Mage::helper('fresh_local');
        $productId = $this->getRequest()->getParam('p', false);
        $product = Mage::getModel('catalog/product')->load($productId);
        $cartQty = $helper->cartExist($product);

        $result .= '<button type="button" class="products__item__btn update-cart minus" onclick="afterCartUpdate('.$productId.')" data-redirect="'.$helper->getRemoveFromCartUrl($product).'"';
        if (!$cartQty){
            $result .= ' style="display:none;"';        
        }
        $result .= '><i class="ic-icn-minus-bold"></i></button>';
        $result .= '<button type="button" class="products__item__btn update-cart plus" onclick="afterCartUpdate('.$productId.')" data-redirect="'.$helper->getAddToCartUrl($product).'"><i class="ic-icn-plus-bold"></i><span>'.$this->__('Add').'</span></button>';

        $this->getResponse()->setBody($result);*/
    }

}
