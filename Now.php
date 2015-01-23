<?php

class GoSquaredNow{
  public $GS;
  public $api = array(
    'aggregateStats',
    'campaigns',
    'concurrents',
    'engagement',
    'geo',
    'overview',
    'pages',
    'sources',
    'timeSeries',
    'visitors'
  );

  function __construct($GS){
    $this->GS = $GS;
  }

  function __call($name, $args){
    $params = array();
    if(isset($args[0])) $params = $args[0];

    return $this->GS->exec('/now/v3/' . $name, $params);
  }
}

?>
