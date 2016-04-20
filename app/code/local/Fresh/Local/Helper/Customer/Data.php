<?php

class Fresh_Local_Helper_Customer_Data extends Mage_Customer_Helper_Data
{
    public function getDashboardUrl()
    {
        return $this->_getUrl('customer/account/edit');
    }
	
	
	public function getAddressHtml($address)
    {
		$customer = Mage::getModel('customer/customer')->load($address->getParentId());
		//var_dump($address->getData());
		$street = $address->getStreet();
		return $customer->getFirstname() . ' ' . $customer->getLastname() . '<br>'
			. $street[0] . ' ' . $street[1] . '<br>'
			. $address->getRegion() . ' ' . $address->getCountryId() . '<br>';
		//return $address->format('html');
    }
	
}
