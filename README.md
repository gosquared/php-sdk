# GoSquared PHP SDK

The official GoSquared PHP module for integrating the [GoSquared API][docs] into your PHP app with ease.

## Installation

Available on [packagist](https://packagist.org/packages/gosquared/php-sdk)

## Usage

### Tracking API

See the [Tracking API][tracking-docs] docs site for full documentation.

### Reporting API

The reporting APIs and their functions are listed on [Reporting API][reporting-docs] docs site.

## Quick guide
```php
require_once('gosquared-php-sdk/main.php');

$opts = array(
  'site_token' => 'your-site-token',
  'api_key' => 'your-api-key'
);
$GS = new GoSquared($opts);

// Track events
$result = $GS->track_event('Event Name');

// Create anonymous Person
$person = $GS->create_person();

// Identify person
$response = $person->identify(array(
  'id' => 'user-id',
  'name' => 'Foo Bar',
  'email' => 'foo@bar.com'
));

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

Debug output will then be sent to the standard output streams. Common places to find the output are your console (if run with CLI), your web server logs, or php-fpm logs.

[tracking-docs]: https://gosquared.com/docs/tracking/api/
[reporting-docs]: https://www.gosquared.com/developer/api/
[docs]: https://gosquared.com/docs
