## GoSquared PHP SDK

### Usage
```php
require_once('gosquared-php-sdk/main.php');
$event_data = array(
  'name' => 'signup',
  'user' => 'Geffro Wagliatelli',
  'twitter' => '@TheDeveloper'
);

$site_token = 'GSN-181546-E';
$GS = new GoSquared($site_token);
$result = $GS->store_event($event_data);
if(!$result){
  $GS->debug("Event failed", E_USER_WARNING);
}
````

Instantiate the class:
```php
$GS = new GoSquared($site_token);
```

Where ```$site_token``` is the token of a site registered with GoSquared.

### Functions
```php
$GS->store_event( $name, $params );
```


 * Trigger an event
 * @param  string $name       Required. Readable string, such as "New Signup" or event ID such as 'new_signup'
 * @param  array  $params     Optional. Any additional data to persist with the event. Keys can be anything except _name and a, which are reserved
 * @return mixed              Decoded JSON response object, or false on failure.


### Requirements
* PHP >= 5.2.0
* cURL

### Running tests
```bash
make test
```
	
To test on a site, you can prefix this command with a SITE_TOKEN environment variable, which should be a valid token for a site registered with GoSquared.

### Debugging
To switch on debug logs, place the following define statement before including this library:

```php
define('GOSQUARED_DEBUG', true);
```

Debug output will then be sent to the standard ouput streams. Common places to find the output are your console (if run with CLI), or your web server's logs.