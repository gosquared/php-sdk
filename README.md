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

### Functions
```gosquared_event( $name, $params )```


 * @param  string $name     Readable string, such as "New Signup" or event ID such as 'new_signup'
* @param  array  $params   Any additional data to persist with the event. Keys can be anything except _name which is reserved and overwritten with value in $name
 * @return mixed            Decoded JSON response object, or false on failure.


### Requirements
* PHP >= 5.2.0
* cURL