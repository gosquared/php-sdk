<?php

error_reporting(E_ALL);
define('GOSQUARED_DEBUG', true);
$endpoint = getenv('API_ENDPOINT');
if($endpoint) define('GOSQUARED_API_ENDPOINT', $endpoint);
define('SITE_TOKEN', getenv('SITE_TOKEN'));
define('API_KEY', getenv('API_KEY'));

?>
