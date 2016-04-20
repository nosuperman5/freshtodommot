<?php
require_once 'Mage/Checkout/controllers/CartController.php';
class Fresh_Local_Checkout_CartController extends Mage_Checkout_CartController
{

    public function ajaxAddAction()
    {
		//if (!$this->_validateFormKey()) {
        //    $this->_goBack();
        //    return;
        //}
        $cart   = $this->_getCart();
        $params = $this->getRequest()->getParams();
		
		try {
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

            if (!$this->_getSession()->getNoCartRedirect(true)) {
                if (!$cart->getQuote()->getHasError()) {
                    $message = $this->__('%s was added to your shopping cart.', Mage::helper('core')->escapeHtml($product->getName()));
                    $this->_getSession()->addSuccess($message);
                }
                //$this->_goBack();
				
            }
        } catch (Mage_Core_Exception $e) {
            if ($this->_getSession()->getUseNotice(true)) {
                $this->_getSession()->addNotice(Mage::helper('core')->escapeHtml($e->getMessage()));
            } else {
                $messages = array_unique(explode("\n", $e->getMessage()));
                foreach ($messages as $message) {
                    $this->_getSession()->addError(Mage::helper('core')->escapeHtml($message));
                }
            }
			
            //$url = $this->_getSession()->getRedirectUrl(true);
            //if ($url) {
            //    $this->getResponse()->setRedirect($url);
            //} else {
            //    $this->_redirectReferer(Mage::helper('checkout/cart')->getCartUrl());
            //}
        } catch (Exception $e) {
            $this->_getSession()->addException($e, $this->__('Cannot add the item to shopping cart.'));
            Mage::logException($e);
            //$this->_goBack();
        }

		$result = array();
		$layout = $this->loadLayout()->getLayout();
		$result['global_messages'] = $layout->getBlock('global_messages')->toHtml();
		$result['minicart'] = $layout->getBlock('minicart_head')->toHtml();
		//$result['product_view'] = $layout->getBlock('product.quick.view')->setProduct($product)->toHtml();
		//$result['product_block'] = $layout->getBlock('product.quick.view')->toHtml();
		
		//$this->getResponse()->setHeader('Content-type', 'application/json');
		//$this->getResponse()->setBody($result);
		echo Mage::helper('core')->jsonEncode($result);
		//$this->renderLayout();
		
    }

	
}
