<?php
$installer = $this;
$installer->startSetup();
$installer->run("
ALTER TABLE `{$installer->getTable('sales/quote_item')}` 
ADD `itemcomment` VARCHAR( 255 ) NOT NULL;
  
ALTER TABLE `{$installer->getTable('sales/order_item')}` 
ADD `itemcomment` VARCHAR( 255 ) NOT NULL;

");
$installer->endSetup();