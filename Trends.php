<?php

class GoSquaredTrends{
  public $GS;
  public $api = array(
    'aggregate',
    'browser',
    'country',
    'event',
    'language',
    'organisation',
    'os',
    'page',
    'path1',
    'screenDimensions',
    'sources'
  );

  function __construct($GS){
    $this->GS = $GS;
  }

  function __call($name, $args){
    $params = array();
    if(isset($args[0])) $params = $args[0];

    return $this->GS->exec('/trends/v2/' . $name, $params);
  }
}

?>
