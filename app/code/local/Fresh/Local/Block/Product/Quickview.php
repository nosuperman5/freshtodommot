<?php
class Fresh_Local_Block_Product_Quickview extends Mage_Catalog_Block_Product
{
    private $product;

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('catalog/product/quickview.phtml');
    }

    protected function _toHtml() {
        return parent::_toHtml();
    }
    
    public function setProduct($product) {
        $this->product = $product;
        return $this;
    }
    
    public function getProduct() {
        return $this->product;
    }
}