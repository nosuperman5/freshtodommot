<?php
class Fresh_Local_Model_Observer
{
	public function checkLogin($observer)
  	{
    	if (Mage::getStoreConfig('fresh_local/access/restricted') == 1)
        {
            $controller = $observer->getEvent()->getAction();
			//var_dump($controller->getFullActionName()); die();
			$isLoggedIn = Mage::getSingleton('customer/session')->isLoggedIn();
			$allowedActionNames = Mage::getStoreConfig('fresh_local/access/allowed');
			//$allowedActionNames = str_replace('/', '_', $allowed);
            
			/*$allowedPath = false;
            // set the customer account action names you want users to be able to access whitout being logged in
        		$allowedPathInfos = Mage::getStoreConfig('wmdlogincheck/pages/allowed');
            
            $allowedAction = false;
            // set the cms pages url keys you want users to be able to access whitout being logged in 
            $allowedActionNames = Mage::getStoreConfig('wmdlogincheck/actions/allowed');
            
            // make sure the account login page remains accessible
            $allowedActionNames .= ',\'customer_account_login\',\'customer_account_confirm\',\'customer_account_resetpassword\'';
            
            if (0 == Mage::getStoreConfig('wmdlogincheck/contacts/protect'))
            {
                $allowedPathInfos .= ',contacts'; 
                $allowedActionNames .= ',\'contacts_index_post\',\'contacts_index_index\'';   
            }
            
            // call event from observer
            $event = $observer->getEvent();
            // call action from event
        		$controller = $event->getAction();
            
            // get pathInfo of controller request
            $requestPathInfo = $controller->getRequest()->getPathInfo();
            
            // set allowedPath to true if allowedPathInfos contains the current PathInfo  
            if ($path = str_replace('/','', $requestPathInfo)) 
            {
                $allowedPath = is_int(strpos($allowedPathInfos, $path));
            }
            elseif ('/' == $requestPathInfo)
            {
                $allowedPath = is_int(strpos($allowedPathInfos, Mage::getStoreConfig('web/default/cms_home_page')));
            }
            */
            // set allowedAction to true if allowedActionNames contains the current FullActionName
            $allowedAction = is_int(strpos($allowedActionNames, $controller->getFullActionName()));

            if (!$isLoggedIn/* && !$allowedPath*/ && !$allowedAction){
				$redirect_url = Mage::getStoreConfig('fresh_local/access/redirect_url');
                $controller->getResponse()->setRedirect(Mage::getUrl($redirect_url));
            }
        }                  
    	return $this;
  	}
	
	public function addToTopmenu(Varien_Event_Observer $observer)
    {
		/*try{
			$menu = $observer->getMenu();
			$tree = $menu->getTree();
			$new_data = array(
				'name'   => 'Blog',
				'id'     => 'blog',
				'url'    => Mage::app()->getStore()->getUrl('blog'),
			);
			$newSubNode = new Varien_Data_Tree_Node($new_data, 'id', $tree, $menu);
			$menu->addChild($newSubNode);
		} catch (Exception $e) {
            
		}*/
    }
	
	public function customerFormatData($observer)
	{
		$customer = $observer->getEvent()->getCustomer();
		$customer = $customer->setFirstname(ucfirst(strtolower(($customer->getFirstname()))))
			->setMiddlename(ucfirst(strtolower(($customer->getMiddlename()))))
			->setLastname(ucfirst(strtolower($customer->getLastname())))
			->setEmail(strtolower($customer->getEmail()));
		$observer->getEvent()->setCustomer($customer);
		return $this;
	}
	
	public function changeRobots($observer)
	{
		if($observer->getEvent()->getAction()->getFullActionName() == 'catalog_category_view')
		{
			$uri = $observer->getEvent()->getAction()->getRequest()->getRequestUri();
			if((stristr($uri, "?")) || (stristr($uri, "filter"))): // looking for a ?
				$layout = $observer->getEvent()->getLayout();
				$product_info = $layout->getBlock('head');
				$layout->getUpdate()->addUpdate('<reference name="head"><action method="setRobots"><value>NOINDEX,FOLLOW</value></action></reference>');
				$layout->generateXml();
			endif;
		}
		return $this;
	}
	
}