<?php

class GoSquaredEcommerce{
  public $GS;
  public $api = array(
    'aggregate',
    'browser',
    'category',
    'country',
    'language',
    'os',
    'product',
    'sources',
    'transaction'
  );

  function __construct($GS){
    $this->GS = $GS;
  }

  function __call($name, $args){
    $params = array();
    if(isset($args[0])) $params = $args[0];

    return $this->GS->exec('/ecommerce/v1/' . $name, $params);
  }
}

?>
