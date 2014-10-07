<?php

/**
 * GoSquared PHP SDK.
 *
 * Created: Jan 2013
 * Modified: Oct 2014
 * Version: 1.0.0
 */

require_once(__DIR__ . '/Person.php');
require_once(__DIR__ . '/Transaction.php');

if(!defined('GOSQUARED_DEBUG')){
  define('GOSQUARED_DEBUG', false);
}
if(!defined('GOSQUARED_CURL_TIMEOUT')){
  define('GOSQUARED_CURL_TIMEOUT', 10);
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

  function exec($path, $params = array(), $body = false){
    $url = $this->generate_url($path, $params);

    if(!GOSQUARED_CURL){
      $this->debug('cURL is required for the GoSquared SDK. See http://php.net/manual/en/book.curl.php for more info.');
      return false;
    }
    $c = curl_init();

    $this->debug('Requesting ' . $url, E_USER_NOTICE);
    curl_setopt($c, CURLOPT_URL, $url);
    curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
    if($body){
      $body = json_encode($body);
      $this->debug($body, E_USER_NOTICE);
      curl_setopt($c, CURLOPT_POSTFIELDS, $body);
      curl_setopt($c, CURLOPT_HTTPHEADER, array('Content-type: application/json', 'Content-length: ' . strlen($body)));
    }
    curl_setopt($c, CURLOPT_TIMEOUT, GOSQUARED_CURL_TIMEOUT);

    $response = curl_exec($c);
    $error_number = curl_errno($c);
    $error_message = curl_error($c);

    if($error_number){
      $this->debug("cURL encountered error. Code: $error_number. Message: $error_message", E_USER_WARNING);
      return false;
    }

    if(!$this->validate_response($response)) return false;

    return $response;
  }

  function generate_url($route, $params = array()){
    return
      'https://data.gosquared.com/' .
      $this->site_token . '/v1' .
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
   * https://beta.gosquared.com/docs/tracking/api/#events
   * @param  string $name       Event name
   * @param  array  $params     Any additional data to persist with the event
   * @return mixed              Decoded JSON response object, or false on failure.
   */
  function track_event($name, $params = array(), $personID = false){
    if(!$name || !is_string($name)){
      $this->debug('Events must have a name', E_USER_WARNING);
      return false;
    }

    $query_params = array();
    $query_params['name'] = $name;
    if ($personID) $query_params['personID'] = $personID;
    return $this->exec('/event', $query_params, $params);
  }

  /**
   * Create a new Person class
   * https://beta.gosquared.com/docs/tracking/api/#identify
   * @param  string $id         Person ID
   * @return Person             GoSquaredPerson class
   */
  function create_person($id){
    return new GoSquaredPerson($this, $id);
  }
  function Person($id) {
    return $this->create_person($id);
  }

  /**
   * Create a new Transaction class
   * https://beta.gosquared.com/docs/tracking/api/#transactions
   * @param  string $id         Unique transaction ID
   * @param  array  $opts       Custom options for this transaction
   * @return Transaction        GoSquaredTransaction class
   */
  function create_transaction($id, $opts = array()){
    return new GoSquaredTransaction($this, $id, $opts);
  }
  function Transaction($id, $opts = array()) {
    return $this->create_transaction($id, $opts);
  }
}

?>
