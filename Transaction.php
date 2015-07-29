<?php

/**
 * Track transactions
 * https://www.gosquared.com/docs/tracking/api/#transactions
 */

class GoSquaredTransaction{
  public $id;

  function __construct($GS, $id, $opts = array(), $trackingData = array()){
    $this->GS = $GS;
    $this->id = $id;
    $this->items = array();
    $this->opts = $opts;
    $this->trackingData = $trackingData;
  }

  /**
   * Add a single item to the transaction to be tracked
   * @param  string  $name      The name of the item/product
   * @param  array   $opts      Details about the item - revenue, quantity etc
   */
  function add_item($name, $opts = array()){
    if (is_array($name)) {
      $opts = $name;
    } else {
      $opts['name'] = $name;
    }

    $this->items[] = $opts;
  }

  /**
   * Add an array of items to the transaction to be tracked
   * @param  array   $items     Array of items containing details about the item - name, revenue, quantity etc
   */
  function add_items($items){
    foreach($items as $item){
      $this->add_item($item);
    }
  }

  /**
   * Send the transaction off to be tracked
   * @return mixed              Decoded JSON response object, or false on failure.
   */
  function track(){
    $body = $this->trackingData;
    $body['transaction'] = array(
      'id' => $this->id,
      'items' => $this->items,
      'opts' => $this->opts
    );

    return $this->GS->exec('/tracking/v1/transaction', array(), $body);
  }
}

?>
