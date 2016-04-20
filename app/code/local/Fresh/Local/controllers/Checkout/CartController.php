<?php
require_once 'Mage/Checkout/controllers/CartController.php';
class Fresh_Local_Checkout_CartController extends Mage_Checkout_CartController
{
	public function ajaxAddAction()
    {
		if (!$this->_validateFormKey()) {
            //$this->_goBack();
			echo 'No Valid Key';
            return;
        }
        $cart   = $this->_getCart();
        $params = $this->getRequest()->getParams();
		
		//try {
            if (isset($params['qty'])) {
                $filter = new Zend_Filter_LocalizedToNormalized(
                    array('locale' => Mage::app()->getLocale()->getLocaleCode())
                );
                $params['qty'] = $filter->filter($params['qty']);
            }

            $product = $this->_initProduct();
            $related = $this->getRequest()->getParam('related_product');

			if (!$product) {
                //$this->_goBack();
                return;
            }

            $cart->addProduct($product, $params);
            if (!empty($related)) {
                $cart->addProductsByIds(explode(',', $related));
            }

            $cart->save();
            $this->_getSession()->setCartWasUpdated(true);

            Mage::dispatchEvent('checkout_cart_add_product_complete',
                array('product' => $product, 'request' => $this->getRequest(), 'response' => $this->getResponse())
            );

        //    if (!$this->_getSession()->getNoCartRedirect(true)) {
        //        if (!$cart->getQuote()->getHasError()) {
        //            $message = $this->__('%s was added to your shopping cart.', Mage::helper('core')->escapeHtml($product->getName()));
        //            $this->_getSession()->addSuccess($message);
        //        }
                //$this->_goBack();
		//		
        //    }
        //} catch (Mage_Core_Exception $e) {
        //    if ($this->_getSession()->getUseNotice(true)) {
        //        $this->_getSession()->addNotice(Mage::helper('core')->escapeHtml($e->getMessage()));
        //    } else {
        //        $messages = array_unique(explode("\n", $e->getMessage()));
        //        foreach ($messages as $message) {
        //            $this->_getSession()->addError(Mage::helper('core')->escapeHtml($message));
        //        }
        //    }
			
            //$url = $this->_getSession()->getRedirectUrl(true);
            //if ($url) {
            //    $this->getResponse()->setRedirect($url);
            //} else {
            //    $this->_redirectReferer(Mage::helper('checkout/cart')->getCartUrl());
            //}
        //} catch (Exception $e) {
        //    $this->_getSession()->addException($e, $this->__('Cannot add the item to shopping cart.'));
        //    Mage::logException($e);
            //$this->_goBack();
        //}
		
		$cartQty = Mage::helper('fresh_local')->cartExist($product);
		$result = array();
		$layout = $this->loadLayout()->getLayout();
		$result['global_messages'] = $layout->getBlock('global_messages')->toHtml();
		$result['minicart'] = $layout->getBlock('minicart_head')->toHtml();
		$result['qty'] = $cartQty;
		$result['id'] = $product->getId();
		//$result['product_view'] = $layout->getBlock('product.quick.view')->setProduct($product)->toHtml();
		//$result['product_block'] = $layout->getBlock('product.quick.view')->toHtml();
		
		//$this->getResponse()->setHeader('Content-type', 'application/json');
		//$this->getResponse()->setBody($result);
		
		echo Mage::helper('core')->jsonEncode($result);
		//$this->renderLayout();
    }
	
	
	 public function ajaxUpdateItemAction()
    {
        /*$cart   = $this->_getCart();
        $id = (int) $this->getRequest()->getParam('id');
		$product = new Varien_Object(array('id' => $id));
		$quoteItem = $cart->getQuote()->getItemByProduct($product);
		//var_dump($quoteItem->getItemId()); die();
		$id = $quoteItem->getItemId();
        $params = $this->getRequest()->getParams();

        if (!isset($params['options'])) {
            $params['options'] = array();
        }
        //try {
            if (isset($params['qty'])) {
				
				$qty = Mage::helper('fresh_local')->cartExist($product);
				//if($qty > 0){
				$params['qty'] = $qty - $params['qty'];
				//}
				
                $filter = new Zend_Filter_LocalizedToNormalized(
                    array('locale' => Mage::app()->getLocale()->getLocaleCode())
                );
                $params['qty'] = $filter->filter($params['qty']);
            }

            $quoteItem = $cart->getQuote()->getItemById($id);
            if (!$quoteItem) {
                Mage::throwException($this->__('Quote item is not found.'));
            }
var_dump($params);
die();
            $item = $cart->updateItem($id, new Varien_Object($params));
            if (is_string($item)) {
                Mage::throwException($item);
            }
            if ($item->getHasError()) {
                Mage::throwException($item->getMessage());
            }

            $related = $this->getRequest()->getParam('related_product');
            if (!empty($related)) {
                $cart->addProductsByIds(explode(',', $related));
            }

            $cart->save();

            $this->_getSession()->setCartWasUpdated(true);

            Mage::dispatchEvent('checkout_cart_update_item_complete',
                array('item' => $item, 'request' => $this->getRequest(), 'response' => $this->getResponse())
            );*/
			
			
		if (!$this->_validateFormKey()) {
            Mage::throwException('Invalid form key');
        }
        $id = (int)$this->getRequest()->getParam('id');
        $qty = $this->getRequest()->getParam('qty');
        $result = array();
        if ($id) {
            //try {
                $cart = $this->_getCart();
				$product = new Varien_Object(array('id' => $id));
				$quoteItem = $cart->getQuote()->getItemByProduct($product);
				$id = $quoteItem->getItemId();

                if (isset($qty)) {
					$iQty = Mage::helper('fresh_local')->cartExist($product);
					$qty = $iQty - $qty;
					
                    $filter = new Zend_Filter_LocalizedToNormalized(
                        array('locale' => Mage::app()->getLocale()->getLocaleCode())
                    );
                    $qty = $filter->filter($qty);
                }

                $quoteItem = $cart->getQuote()->getItemById($id);
                if (!$quoteItem) {
                    Mage::throwException($this->__('Quote item is not found.'));
                }

                if ($qty == 0) {
                    $cart->removeItem($id);
                } else {
                    $quoteItem->setQty($qty)->save();
                }
                $this->_getCart()->save();

            /*    $this->loadLayout();
                $result['content'] = $this->getLayout()->getBlock('minicart_content')->toHtml();

                $result['qty'] = $this->_getCart()->getSummaryQty();

                if (!$quoteItem->getHasError()) {
                    $result['message'] = $this->__('Item was updated successfully.');
                } else {
                    $result['notice'] = $quoteItem->getMessage();
                }
                $result['success'] = 1;
            } catch (Exception $e) {
                $result['success'] = 0;
                $result['error'] = $this->__('Can not save item.');
            }*/
			
			
			$cartQty = Mage::helper('fresh_local')->cartExist($product);
			$result = array();
			$layout = $this->loadLayout()->getLayout();
			$result['global_messages'] = $layout->getBlock('global_messages')->toHtml();
			$result['minicart'] = $layout->getBlock('minicart_head')->toHtml();
			$result['qty'] = $cartQty;
			$result['id'] = $product->getId();
			
			echo Mage::helper('core')->jsonEncode($result);
        }
        //    if (!$this->_getSession()->getNoCartRedirect(true)) {
        //        if (!$cart->getQuote()->getHasError()) {
        //            $message = $this->__('%s was updated in your shopping cart.', Mage::helper('core')->escapeHtml($item->getProduct()->getName()));
        //            $this->_getSession()->addSuccess($message);
        //        }
        //        $this->_goBack();
        //    }
        //} catch (Mage_Core_Exception $e) {
        //    if ($this->_getSession()->getUseNotice(true)) {
        //        $this->_getSession()->addNotice($e->getMessage());
        //    } else {
        //        $messages = array_unique(explode("\n", $e->getMessage()));
        //        foreach ($messages as $message) {
        //            $this->_getSession()->addError($message);
        //        }
        //    }
		//
        //    $url = $this->_getSession()->getRedirectUrl(true);
        //    if ($url) {
        //        $this->getResponse()->setRedirect($url);
        //    } else {
        //        $this->_redirectReferer(Mage::helper('checkout/cart')->getCartUrl());
        //    }
        //} catch (Exception $e) {
        //    $this->_getSession()->addException($e, $this->__('Cannot update the item.'));
        //    Mage::logException($e);
        //    $this->_goBack();
        //}
        //$this->_redirect('*/*');
		
		/* $cartQty = Mage::helper('fresh_local')->cartExist($product);
		$result = array();
		$layout = $this->loadLayout()->getLayout();
		$result['global_messages'] = $layout->getBlock('global_messages')->toHtml();
		$result['minicart'] = $layout->getBlock('minicart_head')->toHtml();
		$result['qty'] = $cartQty;
		$result['id'] = $product->getId();
		
		echo Mage::helper('core')->jsonEncode($result); */
    }

	
}
