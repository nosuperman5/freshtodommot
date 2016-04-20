<?php
$installer = $this;
$installer->startSetup();
    $installer->getConnection()->addColumn(
        $installer->getTable('shoppinglist/list'),
            'ingredients', 
            "varchar(2000) DEFAULT NULL"        
    );
    $installer->getConnection()->addColumn(
        $installer->getTable('shoppinglist/list'),
            'directions', 
            "varchar(2000) DEFAULT NULL"        
    );
    $installer->getConnection()->addColumn(
        $installer->getTable('shoppinglist/list'),
            'image', 
            "varchar(255) DEFAULT NULL"        
    );

$installer->endSetup();