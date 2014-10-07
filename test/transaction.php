#!/usr/bin/env php

<?php
/**
 * Super bare-bones test
 */
error_reporting(E_ALL);
define('GOSQUARED_DEBUG', true);
define('SITE_TOKEN', getenv('SITE_TOKEN'));
require(__DIR__ . '/../main.php');

$GS = new GoSquared(SITE_TOKEN);

$transaction = $GS->create_transaction('Transaction ID');

$transaction->add_item(array(
  'name' => 'Item Name',
  'revenue' => 5.99
));

$transaction->add_item('Item Name', array(
  'revenue' => 6.99
));

$result = $transaction->track();

if(!$result){
  $GS->debug("Transaction failed", E_USER_WARNING);
}

$person = $GS->Person('PersonID');
$transaction = $person->Transaction('Transaction ID');

$transaction->add_items(array(
  array(
    'name' => 'Item Name',
    'revenue' => 5.99
  )
));

$result = $transaction->track();
if(!$result){
  $GS->debug("Transaction failed", E_USER_WARNING);
}

?>
