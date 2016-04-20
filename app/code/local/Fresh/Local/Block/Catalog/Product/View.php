<?php 
class Fresh_Local_Block_Catalog_Product_View extends Mage_Catalog_Block_Product_View
{
	protected function _prepareLayout()
    {
        return Mage_Catalog_Block_Product_Abstract::_prepareLayout();
    }
    
    public function getCustomerLists() {
        return Mage::getResourceModel('shoppinglist/list_collection')->addStoreFilter()->addCustomerFilter()->addProductCount();
    }
	
}