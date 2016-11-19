<?php
session_start();
$session["token"] = $_GET["oauth_token"];
$session["verifier"] = $_GET["oauth_verifier"];
header('Location: '."../create.php");
?>
