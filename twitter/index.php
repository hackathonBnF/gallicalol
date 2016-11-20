<?php
session_start();
$_SESSION["token"] = $_GET["oauth_token"];
$_SESSION["verifier"] = $_GET["oauth_verifier"];
?>

<html>
<boddy>
<script type="text/javascript">window.close();</script>
</boddy>
</html>