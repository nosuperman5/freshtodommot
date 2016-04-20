<?php
require_once(Mage::getModuleDir('controllers','Mage_Wishlist').DS.'IndexController.php');
class Fresh_Local_Wishlist_IndexController extends Mage_Wishlist_IndexController
{
	public function ajaxAddAction()
    {
        //if (!$this->_validateFormKey()) {
        //    return $this->_redirect('*/*');
        //}
        $this->_addItemToWishList();
    }
	
	protected function _addItemToWishList()
    {
        $wishlist = $this->_getWishlist();
        if (!$wishlist) {
            return $this->norouteAction();
        }

        $session = Mage::getSingleton('customer/session');

        $productId = (int)$this->getRequest()->getParam('product');
		//var_dump($productId);
		//echo 123; die();
        if (!$productId) {
            //$this->_redirect('*/');
            return;
        }

        $product = Mage::getModel('catalog/product')->load($productId);
        if (!$product->getId() || !$product->isVisibleInCatalog()) {
            $session->addError($this->__('Cannot specify product.'));
            //$this->_redirect('*/');
            return;
        }

        //try {
            $requestParams = $this->getRequest()->getParams();
            if ($session->getBeforeWishlistRequest()) {
                $requestParams = $session->getBeforeWishlistRequest();
                $session->unsBeforeWishlistRequest();
            }
            $buyRequest = new Varien_Object($requestParams);

            $result = $wishlist->addNewItem($product, $buyRequest);
            if (is_string($result)) {
                Mage::throwException($result);
            }
            $wishlist->save();

            Mage::dispatchEvent(
                'wishlist_add_product',
                array(
                    'wishlist' => $wishlist,
                    'product' => $product,
                    'item' => $result
                )
            );
//var_dump($result);
			//die();
            //$referer = $session->getBeforeWishlistUrl();
            //if ($referer) {
            //    $session->setBeforeWishlistUrl(null);
            //} else {
            //    $referer = $this->_getRefererUrl();
            //}

            //$session->setAddActionReferer($referer);

            Mage::helper('wishlist')->calculate();

            //$message = $this->__('%1$s has been added to your wishlist. Click <a href="%2$s">here</a> to continue shopping.',
            //    $product->getName(), Mage::helper('core')->escapeUrl($referer));
            //$session->addSuccess($message);
        /*} catch (Mage_Core_Exception $e) {
            $session->addError($this->__('An error occurred while adding item to wishlist: %s', $e->getMessage()));
        }
        catch (Exception $e) {
            $session->addError($this->__('An error occurred while adding item to wishlist.'));
        }*/

        //$this->_redirect('*', array('wishlist_id' => $wishlist->getId()));
		
		$result['qty'] = $product->getId();
		//var_dump($wishlist);
		echo Mage::helper('core')->jsonEncode($result);
    }
}
