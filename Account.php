<?php

class GoSquaredAccount{
  public $GS;
  public $api = array(
    'blocked',
    'reportPreferences',
    'sharedUsers',
    'sites',
    'taggedVisitors'
  );

  function __construct($GS){
    $this->GS = $GS;
  }

  function __call($name, $args){
    $params = array();
    if(isset($args[0])) $params = $args[0];

    return $this->GS->exec('/account/v1/' . $name, $params);
  }
}

?>
