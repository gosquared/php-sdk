# GoSquared PHP SDK

Full documentation for this SDK can be found at https://beta.gosquared.com/docs/tracking/api/.

## Usage
```php
require_once('gosquared-php-sdk/main.php');

$site_token = 'GSN-181546-E';
$GS = new GoSquared($site_token);
$result = $GS->track_event('Event Name');
```

Instantiate the class:
```php
$GS = new GoSquared($site_token);
```

Where `$site_token` is the token of a site registered with GoSquared (accessible from https://gosquared.com/integration/)

## Requirements
* PHP >= 5.2.0
* cURL

## Running tests
```bash
make test
```
	
To test on a site, you can prefix this command with a SITE_TOKEN environment variable, which should be a valid token for a site registered with GoSquared. **WARNING: this will track test data under that site**

## Debugging
To switch on debug logs, place the following define statement before including this library:

```php
define('GOSQUARED_DEBUG', true);
```

Debug output will then be sent to the standard ouput streams. Common places to find the output are your console (if run with CLI), or your web server's logs.
