<?php
$installer = $this;
$installer->startSetup();
$installer->run("
ALTER TABLE `{$installer->getTable('sales/invoice_item')}` 
ADD `itemcomment` VARCHAR( 255 ) NOT NULL;
  
ALTER TABLE `{$installer->getTable('sales/creditmemo_item')}` 
ADD `itemcomment` VARCHAR( 255 ) NOT NULL;

ALTER TABLE `{$installer->getTable('sales/shipment_item')}` 
ADD `itemcomment` VARCHAR( 255 ) NOT NULL;

");
$installer->endSetup();