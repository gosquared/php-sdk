<?php

require_once(__DIR__ . '/Transaction.php');

class GoSquaredPerson{
  public $id;
  public $anonymous_id;

  function __construct($GS, $id){
    $this->GS = $GS;
    $this->set_id($id);
  }

  /**
   * Set a new ID for this person
   * @param  mixed $id  The new person ID, or an object which will be checked for id or email
   */
  function set_id($v){
    if (!$v) return;

    if (is_array($v)) {
      // try to grab the ID out of the object
      if (isset($v['id'])) {
        $this->id = $v['id'];

      // no id in props and none already set, try email
      } else if (!isset($this->id) && isset($v['email'])) {
        $this->id = 'email:' .  $v['email'];
      }
    } else {
      $this->id = $v;
    }
  }

  private function exec($url, $params = array(), $body) {
    return $this->GS->exec('/tracking/v1' . $url, $params, $this->add_ids($body));
  }

  private function verify_id(){
    if (!isset($this->id) && !isset($this->anonymous_id)) {
      $this->GS->debug('Missing ID and anonymous_id', E_USER_WARNING);
      return false;
    }

    return true;
  }

  private function add_ids($trackingData = array()){
    if (isset($this->id)) $trackingData['person_id'] = $this->id;
    if (isset($this->anonymous_id)) $trackingData['visitor_id'] = $this->anonymous_id;
    return $trackingData;
  }

  /**
   * Set properties (requires and identifying property of ID or email)
   * https://www.gosquared.com/docs/tracking/api/#identify
   * @param  array  $props      Properties to be set on this person
   * @return mixed              Decoded JSON response object, or false on failure.
   */
  function identify($props = array()){
    $this->set_id($props);

    if (!$this->id) {
      $this->GS->debug('Missing ID and email', E_USER_WARNING);
      return false;
    }

    return $this->exec('/identify', array(), array('properties' => $props));
  }

  /**
   * Set properties on a person
   * https://www.gosquared.com/docs/tracking/api/#properties
   * @param  array  $params     Properties to be set on this person
   * @return mixed              Decoded JSON response object, or false on failure.
   */
  function set_properties($props){
    $this->set_id($props);

    if (!$this->verify_id()) return false;

    return $this->exec('/properties', array(), array('properties' => $props));
  }


  /**
   * Trigger an event for a person
   * https://www.gosquared.com/docs/tracking/api/#events
   * @param  string $name       Event name
   * @param  array  $data       Any additional data to persist with the event
   * @param  array  $trackingData  Tracking data related to this event, such as IP address
   * @return mixed              Decoded JSON response object, or false on failure.
   */
  function track_event($name, $data = array(), $trackingData = array()){
    if (!$this->verify_id()) return false;

    return $this->GS->track_event($name, $data, $this->add_ids($trackingData));
  }

  /**
   * Create a new Transaction class
   * https://www.gosquared.com/docs/tracking/api/#transactions
   * @param  string $id         Unique transaction ID
   * @param  array  $opts       Custom options for this transaction
   * @param  array  $trackingData  Tracking data related to this event, such as IP address
   * @return Transaction        GoSquaredTransaction class
   */
  function create_transaction($id, $opts = array(), $trackingData = array()){
    return new GoSquaredTransaction($this->GS, $id, $opts, $this->add_ids($trackingData));
  }
  function Transaction($id, $opts = array(), $trackingData = array()) {
    return $this->create_transaction($id, $opts, $trackingData);
  }

}

?>
