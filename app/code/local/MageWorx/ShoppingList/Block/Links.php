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

class MageWorx_ShoppingList_Block_Links extends Mage_Page_Block_Template_Links_Block
{
    /**
     * Position in link list
     * @var int
     */
    protected $_position = 35;

    /**
     * Set link title, label and url
     */
    public function __construct()
    {
        parent::__construct();
        if ($this->helper('shoppinglist')->isEnabled()) {
            $text = $this->__('My Shopping List');            
            $this->_label = $text;
            $this->_title = $text;
            $this->_url = $this->getUrl('shoppinglist');
        }
    }        
    
}
