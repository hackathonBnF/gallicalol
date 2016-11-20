<?php
header('Content-Type: application/json');
session_start();

$check["login"] = isset($_SESSION["token"]) && ($_SESSION["verifier"]);
$check["token"] = $_SESSION["token"];

echo(json_encode($check));
?>