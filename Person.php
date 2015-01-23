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
   * @param  array  $props      Properties to be set on this person
   * @return mixed              Decoded JSON response object, or false on failure.
   */
  function identify($id, $props = array()){

    if (is_array($id)) {
      return $this->set_properties($id);
    }

    $previous_id = $this->id;
    $body = array(
      'visitor_id' => $previous_id,
      'person_id' => $id
    );

    if($props) $body['properties'] = $props;

    $result = $this->GS->exec('/tracking/v1/identify', array(), $body, $this);
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
    $previous_id = $this->id;
    $body = array(
      'visitor_id' => $previous_id,
      'person_id' => $id
    );
    return $this->GS->exec('/tracking/v1/alias', array(), $body, $this);
  }

  /**
   * Set properties on a person
   * https://beta.gosquared.com/docs/tracking/api/#properties
   * @param  array  $params     Properties to be set on this person
   * @return mixed              Decoded JSON response object, or false on failure.
   */
  function set_properties($props){
    $body = array(
      'person_id' => $this->id
    );

    if($props) $body['properties'] = $props;

    return $this->GS->exec('/tracking/v1/properties', array(), $body, $this);
  }

}

?>
