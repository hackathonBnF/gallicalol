<?php

session_start();

require "../vendor/autoload.php";
require "../includes/constants.php";

use Abraham\TwitterOAuth\TwitterOAuth;

$request_token = [];
$request_token['oauth_token'] = $_SESSION['oauth_token'];
$request_token['oauth_token_secret'] = $_SESSION['oauth_token_secret'];

if (isset($_GET['oauth_token']) && $request_token['oauth_token'] !== $_GET['oauth_token']) {
    echo 'Wrong token';
    exit(1);
}

$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $request_token['oauth_token'], $request_token['oauth_token_secret']);

$access_token = $connection->oauth("oauth/access_token", ["oauth_verifier" => $_GET["oauth_verifier"]]);

$_SESSION["access_token"] = $access_token["oauth_token"];
$_SESSION["access_token_secret"] = $access_token["oauth_token_secret"];

?>

<html>
<boddy>
<script type="text/javascript">window.close();</script>
</boddy>
</html>