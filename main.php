<?php

define('GOSQUARED_TRANSPORT_PROTOCOL', 'https');
define('GOSQUARED_HOST', 'data.gosquared.com');
define('GOSQUARED_EVENT_ROUTE', '/event');
define('GOSQUARED_PAGEVIEW_ROUTE', '/pageview');
if(!defined('GOSQUARED_DEBUG')){
  define('GOSQUARED_DEBUG', false);
}
define('GOSQUARED_CURL', extension_loaded('curl'));

function gosquared_debug($message, $level = E_USER_NOTICE){
  if(!GOSQUARED_DEBUG) return false;
  $message = "[GoSquared]: $message";
  trigger_error($message, $level);
}

function gosquared_exec($url){
  if(!GOSQUARED_CURL){
    gosquared_debug('cURL is required for the GoSquared SDK. See http://uk3.php.net/manual/en/book.curl.php for more info.');
    return false;
  }
  $c = curl_init();

  gosquared_debug($url, E_USER_NOTICE);
  curl_setopt($c, CURLOPT_URL, $url);
  curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
  
  $response = curl_exec($c);
  $error_number = curl_errno($c);
  $error_message = curl_error($c);

  if($error_number){
    gosquared_debug("cURL encountered error. Code: $error_number. Message: $error_message", E_USER_WARNING);
    return false;
  }

  return $response;
}

function gosquared_generate_url($route, $params){
  return
    GOSQUARED_TRANSPORT_PROTOCOL . '://' .
    GOSQUARED_HOST .
    $route . '?' .
    http_build_query($params);
}

function validate_event_response($body){
  if(!$body) return false;
  $decoded = json_decode($body);
  if(!$decoded) return false;

  if(!isset($decoded->success) || !$decoded->success) return false;
  return true;
}

/**
 * Trigger an event
 * @param  string $name     Readable string, such as "New Signup" or event ID such as 'new_signup'
 * @param  array  $params   Any additional data to persist with the event. Keys can be anything except _name which is reserved and overwritten with value in $name
 * @return mixed            Decoded JSON response object, or false on failure.
 */
function gosquared_event($name, $params = array()){
  if(!$name || !is_string($name)){
    gosquared_debug('Events must have a name', E_USER_WARNING);
    return false;
  }
  $params['_name'] = $name;
  $url = gosquared_generate_url(GOSQUARED_EVENT_ROUTE, $params);
  $res = gosquared_exec($url);
  if(!validate_event_response($res)) return false;
  return $res;
}

?>