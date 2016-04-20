<?php
require_once 'Mage/Catalog/controllers/ProductController.php';
class Fresh_Local_ProductController extends Mage_Catalog_ProductController
{
	public function quickviewAction(){
        //if (!$this->getRequest()->isXmlHttpRequest()) {
        //    $this->_redirect('/');
        //}

        if ($product = $this->_initProduct()) {
            /*$this->getResponse()
				->setBody($this->getLayout()
				->createBlock('fresh_local/product_quickview')
				->setProduct($product)
				->toHtml());*/
			echo $this->loadLayout()->getLayout()
				->getBlock('product.quick.view')
				->setProduct($product)
				->toHtml();
        } else {
            echo Mage::helper('catalog')->__('Product not found');
        }
	}
	
	/*public function viewAction()
    {
        // Get initial data from request
        $categoryId = (int) $this->getRequest()->getParam('category', false);
        $productId  = (int) $this->getRequest()->getParam('id');
        $specifyOptions = $this->getRequest()->getParam('options');

        // Prepare helper and params
        $viewHelper = Mage::helper('catalog/product_view');

        $params = new Varien_Object();
        $params->setCategoryId($categoryId);
        $params->setSpecifyOptions($specifyOptions);

        // Render page
        try {
            $viewHelper->prepareAndRender($productId, $this, $params);
        } catch (Exception $e) {
            if ($e->getCode() == $viewHelper->ERR_NO_PRODUCT_LOADED) {
                if (isset($_GET['store'])  && !$this->getResponse()->isRedirect()) {
                    $this->_redirect('');
                } elseif (!$this->getResponse()->isRedirect()) {
                    $this->_forward('noRoute');
                }
            } else {
                Mage::logException($e);
                $this->_forward('noRoute');
            }
        }
    }*/
	
	
	/*public function ajaxAddToCartAction(){
		
	}*/
}