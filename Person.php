<?php

require_once(__DIR__ . '/Transaction.php');

class GoSquaredPerson{
  public $id;

  function __construct($GS, $id){
    if(!$id){
      $GS->debug('Person ID is not specified', E_USER_WARNING);
      return false;
    }

    $this->GS = $GS;
    $this->set_id($id);
  }

  /**
   * Set a new ID for this person (and update auth param if in use)
   * @param  string $id         The new person ID
   */
  function set_id($id){
    $this->id = $id;
    $tracking_key = isset($this->GS->opts['tracking_key']) ? $this->GS->opts['tracking_key'] : false;
    $this->auth = $tracking_key ? hash_hmac('sha256', $id, $tracking_key) : false;
  }

  /**
   * Get query string params for personID and authentication
   * @return array              Array of parameters for use
   */
  function get_params(){
    $p = array(
      'personID' => $this->id
    );
    if ($this->auth) $p['auth'] = $this->auth;
    return $p;
  }

  /**
   * Trigger an event for a person
   * https://beta.gosquared.com/docs/tracking/api/#events
   * @param  string $name       Event name
   * @param  array  $params     Any additional data to persist with the event
   * @return mixed              Decoded JSON response object, or false on failure.
   */
  function track_event($name, $params = array()){
    return $this->GS->track_event($name, $params, $this);
  }

  /**
   * Create a new Transaction class
   * https://beta.gosquared.com/docs/tracking/api/#transactions
   * @param  string $id         Unique transaction ID
   * @param  array  $opts       Custom options for this transaction
   * @return Transaction        GoSquaredTransaction class
   */
  function create_transaction($id, $opts = array()){
    return new GoSquaredTransaction($this->GS, $id, $opts, $this);
  }
  function Transaction($id, $opts = array()) {
    return $this->create_transaction($id, $opts);
  }

  /**
   * Set properties and an alias on a person
   * https://beta.gosquared.com/docs/tracking/api/#identify
   * @param  string $id         The new PersonID/UserID
   * @param  array  $params     Properties to be set on this person
   * @return mixed              Decoded JSON response object, or false on failure.
   */
  function identify($id, $params = array()){

    // allow usage as if it's the set_properties function
    if (is_array($id)) {
      return $this->set_properties($id);
    }

    $oldID = $this->id;
    $result = $this->GS->exec('/people/' . $oldID . '/identify/' . $id, array(), $params, $this);
    if ($result) $this->set_id($id);
    return $result;
  }

  /**
   * Alias two people together by their IDs
   * https://beta.gosquared.com/docs/tracking/api/#alias
   * @param  string $id         The PersonID/UserID to alias to this personID
   * @return mixed              Decoded JSON response object, or false on failure.
   */
  function create_alias($id){
    return $this->GS->exec('/people/' . $this->id . '/alias/' . $id, array(), array(), $this);
  }

  /**
   * Set properties on a person
   * https://beta.gosquared.com/docs/tracking/api/#properties
   * @param  array  $params     Properties to be set on this person
   * @return mixed              Decoded JSON response object, or false on failure.
   */
  function set_properties($params){
    return $this->GS->exec('/people/' . $this->id . '/properties', array(), $params, $this);
  }

}

?>
