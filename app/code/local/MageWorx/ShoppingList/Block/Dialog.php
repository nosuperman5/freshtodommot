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

class MageWorx_Shoppinglist_Block_Dialog extends MageWorx_ShoppingList_Block_Abstract {    
    public function getListUrl()
    {        
        $listId = $this->getRequest()->getParam('id', false);
        if ($listId=='wishlist') {
            return $this->getUrl('wishlist/');
        } else {           
            return $this->getUrl('shoppinglist/index/view/id/'.intval($listId).'/');
        }    
    }
    
    public function getUrl($route='', $params=array())
    {
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on') $params['_secure'] = true;
        return $this->_getUrlModel()->getUrl($route, $params);
    }
    
}