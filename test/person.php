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
  'name' => 'PHP SDK',
  'email' => 'test-php@gosquared.com',
  'props_set' => false
));

if(!$result){
  $GS->debug("Identify failed", E_USER_WARNING);
}

$result = $person->set_properties(array(
  'name' => 'PHP SDK',
  'email' => 'test-php@gosquared.com',
  'props_set' => true
));

if(!$result){
  $GS->debug("Properties failed", E_USER_WARNING);
}

?>
