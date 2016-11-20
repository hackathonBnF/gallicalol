<?php

$dotenv = new Dotenv\Dotenv("../");
$dotenv->load();

define('CONSUMER_KEY', getenv('CONSUMER_KEY'));
define('CONSUMER_SECRET', getenv('CONSUMER_SECRET'));
define('OAUTH_CALLBACK', getenv('OAUTH_CALLBACK'));