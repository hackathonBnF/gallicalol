<?php
  $context  = stream_context_create(array('http' => array('header' => 'Accept: application/xml')));
  $url_query = 'http://gallica.bnf.fr/SRU?operation=searchRetrieve&version=1.2&query=dc.type%20any%20image%20and%20gallica%20any%20"' . $_POST['query'] . '"';
	$xml = file_get_contents( $url_query , false, $context);
  $xml = simplexml_load_string($xml);

?>

<!DOCTYPE html>
<html>
<head>
  <title></title>

  <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">


  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.5/css/bootstrap.min.css" integrity="sha384-AysaV+vQoT3kOAXZkl02PThvDr8HYKPZhNT5h/CXfBThSRXQ6jW5DO2ekP5ViFdi" crossorigin="anonymous">

  <link rel="stylesheet" type="text/css" href="/static/styles/main.css">



</head>
<body>

<div class="container">


<nav class="navbar navbar-light">

  <a class="navbar-brand" href="#">gallical.lol</a>


  <div class="nav navbar-inline float-xs-right">
    <a class="browse nav-link" href="#">chercher</a>
    <a class="create nav-link" href="#">créer</a>
  </div>
</nav>

<div class="row">
<div>
<form action="/search.php" method="post">
	<input type="text" id="query" name="query" class="form-control input-sm" maxlength="64" placeholder="Chercher une image" value="<?php echo $_POST['query'] ?>" />
	<button type="submit" class="btn btn-primary btn-sm">Search</button>
</form>

</div>

<div>
<?php
  foreach ($xml->xpath('//dc:identifier') as $identifier) {
	  if ( preg_match( '/gallica.bnf.fr/', $identifier ) ) {
      echo $identifier[0];
  		echo '<br />';
		}
  }

?>
</div>

</div>

<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.5/js/bootstrap.min.js" integrity="
  sha384-BLiI7JTZm+JWlgKa0M0kGRpJbF2J8q+qreVrKBC47e3K6BW78kGLrCkeRX6I9RoK" crossorigin="anonymous"></script>
<script src="/static/scripts/meme.js"></script>

</body>
</html>
