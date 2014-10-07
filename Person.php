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
    return $this->GS->store_event($name, $params, $this->id);
  }

  /**
   * Create a new Transaction class
   * https://beta.gosquared.com/docs/tracking/api/#transactions
   * @param  string $id         Unique transaction ID
   * @param  array  $opts       Custom options for this transaction
   * @return Transaction        GoSquaredTransaction class
   */
  function create_transaction($id, $opts = array()){
    return new GoSquaredTransaction($this->GS, $id, $opts, $this->id);
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
    if($params && !is_string($params)){
      $params = json_encode($params);
    }

    $oldID = $this->id;
    $this->id = $id;

    return $this->GS->exec('/people/' . $oldID . '/identify/' . $id, array(), $params);
  }

  /**
   * Alias two people together by their IDs
   * https://beta.gosquared.com/docs/tracking/api/#alias
   * @param  string $id         The new PersonID/UserID
   * @return mixed              Decoded JSON response object, or false on failure.
   */
  function create_alias($id){
    $oldID = $this->id;
    $this->id = $id;
    return $this->GS->exec('/people/' . $oldID . '/alias/' . $id);
  }

  /**
   * Set properties on a person
   * https://beta.gosquared.com/docs/tracking/api/#properties
   * @param  array  $params     Properties to be set on this person
   * @return mixed              Decoded JSON response object, or false on failure.
   */
  function set_properties($params){
    if($params && !is_string($params)){
      $params = json_encode($params);
    }

    return $this->GS->exec('/people/' . $this->id . '/properties', array(), $params);
  }

}

?>
