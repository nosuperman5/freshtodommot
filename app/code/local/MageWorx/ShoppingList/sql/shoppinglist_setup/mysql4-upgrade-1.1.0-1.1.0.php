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
$installer->startSetup();
if (!$installer->getConnection()->tableColumnExists($installer->getTable('shoppinglist/item'), 'md5_hash')) {
    $installer->getConnection()->addColumn(
        $installer->getTable('shoppinglist/item'),
        'md5_hash',
        "INT UNSIGNED NOT NULL DEFAULT '0'"
    );
}
$installer->run("ALTER TABLE `{$installer->getTable('shoppinglist/item')}` ADD INDEX (`md5_hash`)");

$installer->endSetup();