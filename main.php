<?php

require_once(__DIR__ . '/Person.php');
require_once(__DIR__ . '/Transaction.php');
require_once(__DIR__ . '/Now.php');
require_once(__DIR__ . '/Trends.php');
require_once(__DIR__ . '/Ecommerce.php');
require_once(__DIR__ . '/Account.php');

if(!defined('GOSQUARED_DEBUG')){
  define('GOSQUARED_DEBUG', false);
}
if(!defined('GOSQUARED_CURL_TIMEOUT')){
  define('GOSQUARED_CURL_TIMEOUT', 10);
}
if(!defined('GOSQUARED_API_ENDPOINT')){
  define('GOSQUARED_API_ENDPOINT', 'https://api.gosquared.com');
}
define('GOSQUARED_CURL', extension_loaded('curl'));

class GoSquared{
  public $site_token;
  public $api_key;
  public $opts;

  public $now;
  public $trends;
  public $ecommerce;
  public $account;

  function __construct($opts = false){
    if (!$opts) $opts = array();

    if(!isset($opts['site_token'])){
      $this->debug('Site token is not specified', E_USER_WARNING);
      return false;
    }

    if(!isset($opts['api_key'])){
      $this->debug('API key is not specified', E_USER_WARNING);
      return false;
    }

    $this->opts = $opts;
    $this->site_token = $opts['site_token'];
    $this->api_key = $opts['api_key'];

    $this->now = new GoSquaredNow($this);
    $this->trends = new GoSquaredTrends($this);
    $this->ecommerce = new GoSquaredEcommerce($this);
    $this->account = new GoSquaredAccount($this);
  }

  function debug($message, $level = E_USER_NOTICE){
    if(!GOSQUARED_DEBUG) return false;
    $message = "[GoSquared]: $message";
    trigger_error($message, $level);
  }

  function exec($route, $params = array(), $body = false){
    $params['site_token'] = $this->site_token;
    $params['api_key'] = $this->api_key;

    $url = $this->generate_url($route, $params);

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

    $this->debug("Response:" . $response, E_USER_NOTICE);

    return $this->parse_response($response);
  }

  function generate_url($route, $params = array()){
    return GOSQUARED_API_ENDPOINT . $route . '?' . http_build_query($params);
  }

  function parse_response($body){
    if(!$body) return false;
    $decoded = json_decode($body);
    if(!$decoded) return false;
    return $decoded;
  }

  /**
   * Trigger an event
   * https://www.gosquared.com/docs/tracking/api/#events
   * @param  string $name       Event name
   * @param  array  $data       Any additional data to persist with the event
   * @param  object $person     Associate the event with Person if specified
   * @return mixed              Decoded JSON response object, or false on failure.
   */
  function track_event($name, $data = array(), $trackingData = array()){
    if(!$name || !is_string($name)){
      $this->debug('Events must have a name', E_USER_WARNING);
      return false;
    }

    $body = $trackingData;
    $body['event'] = array(
      'name' => $name,
      'data' => $data
    );

    return $this->exec('/tracking/v1/event', array(), $body);
  }

  /**
   * Create a new Person class
   * https://www.gosquared.com/docs/tracking/api/#identify
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
   * https://www.gosquared.com/docs/tracking/api/#transactions
   * @param  string $id         Unique transaction ID
   * @param  array  $opts       Custom options for this transaction
   * @return Transaction        GoSquaredTransaction class
   */
  function create_transaction($id, $opts = array(), $trackingData = array()){
    return new GoSquaredTransaction($this, $id, $opts, $trackingData);
  }
  function Transaction($id, $opts = array(), $trackingData = array()) {
    return $this->create_transaction($id, $opts, $trackingData);
  }
}

?>
