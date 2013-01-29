#!/usr/bin/env php

<?php
/**
 * Super bare-bones test
 */
error_reporting(E_ALL);
define('GOSQUARED_DEBUG', true);
define('SITE_TOKEN', getenv('SITE_TOKEN'));
require(__DIR__ . '/../main.php');

$event_data = array(
  'user' => 'Geffro Wagliatelli',
  'twitter' => '@TheDeveloper'
);

$GS = new GoSquared(SITE_TOKEN);
$result = $GS->store_event('Test Event', $event_data);
if(!$result){
  $GS->debug("Event failed", E_USER_WARNING);
}

$result = $GS->store_event('Test Event');
if(!$result){
  $GS->debug("Event failed", E_USER_WARNING);
}
?>