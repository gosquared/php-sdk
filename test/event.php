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

$result = gosquared_event(SITE_TOKEN, 'Test Event', $event_data);
if(!$result){
  gosquared_debug("Event failed", E_USER_WARNING);
}

$result = gosquared_event(SITE_TOKEN, 'Test Event');
if(!$result){
  gosquared_debug("Event failed", E_USER_WARNING);
}
?>