<?php

session_start();

require "../../vendor/autoload.php";

use Abraham\TwitterOAuth\TwitterOAuth;

$dotenv = new Dotenv\Dotenv("../");
$dotenv->load();

define('CONSUMER_KEY', getenv('CONSUMER_KEY'));
define('CONSUMER_SECRET', getenv('CONSUMER_SECRET'));
define('OAUTH_CALLBACK', getenv('OAUTH_CALLBACK'));

$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
$request_token = $connection->oauth('oauth/request_token', array('oauth_callback' => OAUTH_CALLBACK));

$_SESSION["oauth_token"] = $request_token["oauth_token"];
$_SESSION["oauth_token_secret"] = $request_token["oauth_token_secret"];

$url = $connection->url('oauth/authorize', array('oauth_token' => $request_token['oauth_token']));

header('Location: '.$url);