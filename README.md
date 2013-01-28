## GoSquared PHP SDK

### Usage
````
require_once('gosquared-php-sdk/main.php');
$event_data = array(
  'name' => 'signup',
  'user' => 'Geffro Wagliatelli',
  'twitter' => '@TheDeveloper'
);

$result = gosquared_event($event_data);
if(!$result){
  gosquared_debug("Event failed", E_USER_WARNING);
}
````

### Requirements
* PHP >= 5.2.0
* cURL