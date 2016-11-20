<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title>Gallica.lol : mets du LOL dans ton Gallica</title>

  <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">


  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.5/css/bootstrap.min.css" integrity="sha384-AysaV+vQoT3kOAXZkl02PThvDr8HYKPZhNT5h/CXfBThSRXQ6jW5DO2ekP5ViFdi" crossorigin="anonymous">

  <link rel="stylesheet" type="text/css" href="/static/styles/main.css">

</head>
<body>

<div class="container">


<nav class="navbar navbar-light">

  <a class="navbar-brand" href="/">gallical.lol</a>


  <div class="nav navbar-inline float-xs-right">
    <a class="browse nav-link" href="#">chercher</a>
    <a class="create nav-link" href="/create.php">créer</a>
  </div>
</nav>

<div class="row">
<div>
<form id="search" class="form-inline">
  <div class="row">
	<div class="col-xs-10"><input type="text" id="query" name="query" class="form-control input-sm" maxlength="64" placeholder="Chercher une image" value="<?php if ( !empty($_GET["query"])) { echo $_GET["query"]; } else { echo "Bibliothèque"; } ?>" /></div>
	<div class="col-xs-2"><button type="submit" class="btn btn-primary">chercher</button></div>
  </div>
</form>

<div id="results" class="grid"></div>

</div>

</div>

<? include("includes/footer.php") ?>

<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.5/js/bootstrap.min.js" integrity="
  sha384-BLiI7JTZm+JWlgKa0M0kGRpJbF2J8q+qreVrKBC47e3K6BW78kGLrCkeRX6I9RoK" crossorigin="anonymous"></script>

<script src="/static/scripts/meme.js"></script>
<script src="https://unpkg.com/masonry-layout@4.1/dist/masonry.pkgd.min.js"></script>
<script src="https://npmcdn.com/imagesloaded@4.1/imagesloaded.pkgd.min.js"></script>
<script src="/static/js/main.js"></script>

</body>
</html>
