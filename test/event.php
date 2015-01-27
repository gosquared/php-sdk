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

$result = $GS->track_event('Test Event', array(
  'custom' => 'properties',
  'here' => true
));

if(!$result){
  $GS->debug("Event failed", E_USER_WARNING);
}

$result = $GS->track_event('Test Event');
if(!$result){
  $GS->debug("Event failed", E_USER_WARNING);
}

$person = $GS->create_person('php-module-test');
$result = $person->track_event('Test Event');
if(!$result){
  $GS->debug("Event failed", E_USER_WARNING);
}
echo json_encode($result);
?>
