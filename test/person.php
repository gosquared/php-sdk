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

$person = $GS->Person('PersonID');

$result = $person->identify('newPersonID', array(
  'name' => 'Test User',
  'email' => 'test@email.com'
));

if(!$result){
  $GS->debug("Identify failed", E_USER_WARNING);
}

$result = $person->create_alias('test@email.com');

if(!$result){
  $GS->debug("Alias failed", E_USER_WARNING);
}

$result = $person->set_properties(array(
  'name' => 'Test User',
  'email' => 'test@email.com',
  'phone' => '07901229693'
));

if(!$result){
  $GS->debug("Properties failed", E_USER_WARNING);
}

?>
