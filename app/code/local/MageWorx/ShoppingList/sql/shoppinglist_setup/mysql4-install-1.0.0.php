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

$installer = $this;

/* @var $installer Mage_Core_Model_Resource_Setup */

$installer->startSetup();

$installer->run("

-- DROP TABLE IF EXISTS {$this->getTable('shoppinglist_list')};
CREATE TABLE IF NOT EXISTS {$this->getTable('shoppinglist_list')} ( 
  `shoppinglist_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` int(10) unsigned NOT NULL DEFAULT '0',
  `store_id` smallint(5) unsigned DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `created_on` datetime DEFAULT NULL,
  `description` varchar(2000) DEFAULT NULL,
  PRIMARY KEY (`shoppinglist_id`),
  KEY `IDX_CUSTOMER` (`customer_id`),
  KEY `store_id` (`store_id`), 
  CONSTRAINT `FK_SHOPPINGLIST_CUSTOMER` FOREIGN KEY (`customer_id`) REFERENCES `{$this->getTable('customer_entity')}` (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_SHOPPINGLIST_STORE` FOREIGN KEY (`store_id`) REFERENCES `{$this->getTable('core_store')}` (`store_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Shoppinglist main';

-- DROP TABLE IF EXISTS {$this->getTable('shoppinglist_item')};
CREATE TABLE IF NOT EXISTS {$this->getTable('shoppinglist_item')} (
  `shoppinglist_item_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shoppinglist_id` int(10) unsigned NOT NULL DEFAULT '0',
  `product_id` int(10) unsigned NOT NULL DEFAULT '0',
  `store_id` smallint(5) unsigned DEFAULT NULL,
  `added_at` datetime DEFAULT NULL,
  `description` varchar(2000) DEFAULT NULL,
  `qty` int(10) unsigned NOT NULL DEFAULT '1',
  `params` text NOT NULL,
  PRIMARY KEY (`shoppinglist_item_id`),
  KEY `IDX_SHOPPINGLIST` (`shoppinglist_id`),
  KEY `IDX_PRODUCT` (`product_id`),
  KEY `IDX_STORE` (`store_id`),
  CONSTRAINT `FK_SHOPPINGLIST_ITEM_PRODUCT` FOREIGN KEY (`product_id`) REFERENCES `{$this->getTable('catalog_product_entity')}` (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_SHOPPINGLIST_ITEM_STORE` FOREIGN KEY (`store_id`) REFERENCES `{$this->getTable('core_store')}` (`store_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `FK_SHOPPINGLIST_ITEM_SHOPPINGLIST` FOREIGN KEY (`shoppinglist_id`) REFERENCES `{$this->getTable('shoppinglist_list')}` (`shoppinglist_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Shoppinglist items';

");

$installer->endSetup();