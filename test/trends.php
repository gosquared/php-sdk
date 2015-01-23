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

foreach($GS->trends->api as $f){
  $result = $GS->trends->{$f}();

  if(!$result){
    $GS->debug("Trends API request failed", E_USER_WARNING);
  }
}

?>
