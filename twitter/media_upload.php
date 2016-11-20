<?php

session_start();

require "../vendor/autoload.php";
require "../includes/constants.php";

use Abraham\TwitterOAuth\TwitterOAuth;

if (!isset($_SESSION['access_token'], $_SESSION['access_token_secret'])) {
    echo 'Missing access token';
    exit(1);
}

if (!isset($_POST['status'], $_POST['image'])) {
    echo 'Missing parameters';
    exit(1);
}

if (empty($_POST['status']) || empty($_POST['image'])) {
    echo 'Invalid parameters';
    exit(1);
}

$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $_SESSION['access_token'], $_SESSION['access_token_secret']);

// Convert Base64 to binary
list($type, $data) = explode(';', $_POST['image']);
list(, $data)      = explode(',', $data);
$data = base64_decode($data);

// Save file to temp file (TwitterOAuth does a file_get_contents)
$temp_file = tempnam(sys_get_temp_dir(), 'gallicalol-');
file_put_contents($temp_file, $data);

// Upload image first
$media = $connection->upload('media/upload', ['media' => $temp_file]);
unlink($temp_file);

// Send status update with attached media
$parameters = [
    'status' => $_POST['status'],
    'media_ids' => implode(',', [$media->media_id])
];
$result = $connection->post('statuses/update', $parameters);

header('Content-Type: application/json');
echo json_encode($result);