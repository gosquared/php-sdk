<?php

/**
 * GoSquared PHP SDK.
 * 
 * Created: Jan 2013
 * Version: 0.0.3
 */

define('GOSQUARED_TRANSPORT_PROTOCOL', 'https');
define('GOSQUARED_HOST', 'data.gosquared.com');
define('GOSQUARED_EVENT_ROUTE', '/event');
define('GOSQUARED_PAGEVIEW_ROUTE', '/pageview');
if(!defined('GOSQUARED_DEBUG')){
  define('GOSQUARED_DEBUG', false);
}
if(!defined('GOSQUARED_CURL_TIMEOUT')){
  define('GOSQUARED_CURL_TIMEOUT', 2);
}
define('GOSQUARED_CURL', extension_loaded('curl'));

class GoSquared{
  public $site_token;

  function __construct($site_token){
    if(!$site_token || !is_string($site_token)){
      $this->debug('Site token is not specified or invalid', E_USER_WARNING);
      return false;
    }
    $this->site_token = $site_token;
  }

  function debug($message, $level = E_USER_NOTICE){
    if(!GOSQUARED_DEBUG) return false;
    $message = "[GoSquared]: $message";
    trigger_error($message, $level);
  }

  function exec($url, $post_body = false){
    if(!GOSQUARED_CURL){
      $this->debug('cURL is required for the GoSquared SDK. See http://uk3.php.net/manual/en/book.curl.php for more info.');
      return false;
    }
    $c = curl_init();

    $this->debug($url, E_USER_NOTICE);
    curl_setopt($c, CURLOPT_URL, $url);
    curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
    if($post_body){
      curl_setopt($c, CURLOPT_POSTFIELDS, $post_body);
      curl_setopt($c, CURLOPT_HTTPHEADER, array('Content-type: application/json', 'Content-length: ' . strlen($post_body)));
    }
    curl_setopt($c, CURLOPT_TIMEOUT, GOSQUARED_CURL_TIMEOUT);
    
    $response = curl_exec($c);
    $error_number = curl_errno($c);
    $error_message = curl_error($c);

    if($error_number){
      $this->debug("cURL encountered error. Code: $error_number. Message: $error_message", E_USER_WARNING);
      return false;
    }

    return $response;
  }

  function generate_url($route, $params){
    return
      GOSQUARED_TRANSPORT_PROTOCOL . '://' .
      GOSQUARED_HOST .
      $route . '?' .
      http_build_query($params);
  }

  function validate_response($body){
    if(!$body) return false;
    $decoded = json_decode($body);
    if(!$decoded) return false;

    if(!isset($decoded->success) || !$decoded->success) return false;
    return true;
  }

  /**
   * Trigger an event
   * @param  string $site_token Token 
   * @param  array  $params     Any additional data to persist with the event. Keys can be anything except _name and a, which are reserved
   * @return mixed              Decoded JSON response object, or false on failure.
   */
  function store_event($name, $params = array()){
    if(!$name || !is_string($name)){
      $this->debug('Events must have a name', E_USER_WARNING);
      return false;
    }
    $post_body = $params;
    if($post_body && !is_string($post_body)){
      $post_body = json_encode($post_body);
    }

    $query_params = array();
    $query_params['name'] = $name;
    $query_params['site_token'] = $this->site_token;
    $url = $this->generate_url(GOSQUARED_EVENT_ROUTE, $query_params);
    $res = $this->exec($url, $post_body);
    if(!$this->validate_response($res)) return false;
    return $res;
  }

}

?>