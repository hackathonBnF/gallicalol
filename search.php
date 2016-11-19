<?php
  $context  = stream_context_create(array('http' => array('header' => 'Accept: application/xml')));
  $url_query = 'http://gallica.bnf.fr/SRU?operation=searchRetrieve&version=1.2&query=dc.type%20any%20image%20and%20gallica%20any%20"' . $_GET['query'] . '"';
	$xml = file_get_contents( $url_query , false, $context);
  $xml = simplexml_load_string($xml);

?>

<!DOCTYPE html>
<html>
<head>
  <title></title>
<style>
* { box-sizing: border-box; }

body {
  padding-top: 50px;
}

.starter-template {
  padding: 40px 15px;
}

.grid {
  max-width: 1200px;
}

/* clearfix */
.grid:after {
  content: '';
  display: block;
  clear: both;
}

/* ---- grid-item ---- */

.grid-sizer,
.grid-item {
  width: 20%;
}

.grid-item {
  height: 120px;
  float: left;
  border-radius: 5px;
}

.grid-item--width2 { width:  40%; }
.grid-item--width3 { width:  60%; }

.grid-item--height2 { height: 200px; }
.grid-item--height3 { height: 260px; }
.grid-item--height4 { height: 360px; }

</style>
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
<form action="/search.php" method="get">
	<input type="text" id="query" name="query" class="form-control input-sm" maxlength="64" placeholder="Chercher une image" value="<?php echo $_GET['query'] ?>" />
	<button type="submit" class="btn btn-primary btn-sm">Search</button>
</form>

</div>

<div>
<div class="grid">
<div class="grid-sizer"></div>
<?php
  foreach ($xml->xpath('//dc:identifier') as $identifier) {
	  if ( preg_match( '/gallica.bnf.fr/', $identifier ) ) {
?>
<div class="grid-item">
<a href="<?php echo $identifier[0] ?>/f1.highres" target="_blank"><img src="<?php echo $identifier[0] ?>.thumbnail" /></a>
</div>
<?php
		}
  }
?>
</div>

</div>

<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.5/js/bootstrap.min.js" integrity="
  sha384-BLiI7JTZm+JWlgKa0M0kGRpJbF2J8q+qreVrKBC47e3K6BW78kGLrCkeRX6I9RoK" crossorigin="anonymous"></script>
<script src="/static/scripts/meme.js"></script>
<script src="https://unpkg.com/masonry-layout@4.1/dist/masonry.pkgd.min.js"></script>
	<script>
		$('.grid').masonry({
		// options
		  itemSelector: '.grid-item',
		  columnWidth: '.grid-sizer',
			percentPosition: true
		});
	</script>

</body>
</html>
