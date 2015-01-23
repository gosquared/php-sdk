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

foreach($GS->now->api as $f){
  $result = $GS->now->{$f}();

  if(!$result){
    $GS->debug("Now API request failed", E_USER_WARNING);
  }
}

?>
