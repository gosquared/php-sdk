# GoSquared PHP SDK

Full documentation for this SDK can be found at https://beta.gosquared.com/docs/tracking/api/.

## Usage
```php
require_once('gosquared-php-sdk/main.php');

$opts = array(
  'site_token' => 'your-site-token',
  'api_key' => 'your-api-key'
);
$GS = new GoSquared($opts);

// Track events
$result = $GS->track_event('Event Name');

// Retrieve live data
$result = $GS->now->concurrents();

// Retrieve historical data
$result = $GS->trends->aggregate();

// Retrieve Ecommerce data
$result = $GS->ecommerce->transactions();
```

## Requirements
* PHP >= 5.2.0
* cURL

## Running tests
```bash
make test
```

To test with your own site token and api key, you can prefix this command with the SITE_TOKEN and API_KEY environment variables containing your keys. **WARNING: this will track test data under your account**

## Debugging
To switch on debug logs, place the following define statement before including this library:

```php
define('GOSQUARED_DEBUG', true);
```

Debug output will then be sent to the standard output streams. Common places to find the output are your console (if run with CLI), or your web server logs, or php-fpm logs.
