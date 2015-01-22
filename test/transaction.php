#!/usr/bin/env php

<?php
/**
 * Super bare-bones test
 */
require(__DIR__ . '/setup.php');
require(__DIR__ . '/../main.php');

$GS = new GoSquared(array(
  'site_token' => SITE_TOKEN,
  'api_key'    => API_KEY
));

$transaction = $GS->create_transaction('php-module-test');

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

$person = $GS->Person('php-module-test');
$transaction = $person->Transaction('php-module-test');

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
