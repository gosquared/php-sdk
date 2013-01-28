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
```gosquared_event( $site_token, $name, $params )```


 * Trigger an event
 * @param  string $site_token Token 
 * @param  string $name       Readable string, such as "New Signup" or event ID such as 'new_signup'
 * @param  array  $params     Any additional data to persist with the event. Keys can be anything except _name and a, which are reserved
 * @return mixed              Decoded JSON response object, or false on failure.


### Requirements
* PHP >= 5.2.0
* cURL

### Running tests
	SITE_TOKEN=GSN-000000-X test/*.php
	
Where SITE_TOKEN is a valid token for a site registered with GoSquared.