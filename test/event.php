#!/usr/bin/env php

<?php
/**
 * Super bare-bones test
 */
error_reporting(E_ALL);
define('GOSQUARED_DEBUG', true);
define('SITE_TOKEN', getenv('SITE_TOKEN'));
require(__DIR__ . '/../main.php');

$GS = new GoSquared(array(
  'site_token' => SITE_TOKEN,
  'tracking_key' => '12345'
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

$person = $GS->create_person(1);
$result = $person->track_event('Test Event');
if(!$result){
  $GS->debug("Event failed", E_USER_WARNING);
}
?>
