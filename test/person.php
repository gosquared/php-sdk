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

$person = $GS->Person('php-module-test');

$result = $person->identify(array(
  'name' => 'Test User',
  'email' => 'test@email.com'
));

if(!$result){
  $GS->debug("Identify failed", E_USER_WARNING);
}

$result = $person->create_alias('php_module_test@gosquared.com');

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
