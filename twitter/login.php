<?
require "../vendor/autoload.php";

use Abraham\TwitterOAuth\TwitterOAuth;

$dotenv = new Dotenv\Dotenv("../");
$dotenv->load();

define('CONSUMER_KEY', getenv('CONSUMER_KEY'));
define('CONSUMER_SECRET', getenv('CONSUMER_SECRET'));
define('OAUTH_CALLBACK', getenv('OAUTH_CALLBACK'));

$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
$request_token = $connection->oauth('oauth/request_token', array('oauth_callback' => OAUTH_CALLBACK));

//echo($request_token);

$url = $connection->url('oauth/authorize', array('oauth_token' => $request_token['oauth_token']));

# echo "<a href=\"" . $url . "\">authorize gallical.lol</a>";

header('Location: '.$url);
exit;
?>

