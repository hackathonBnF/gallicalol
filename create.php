<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>

  <title></title>

  <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">


  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.5/css/bootstrap.min.css" integrity="sha384-AysaV+vQoT3kOAXZkl02PThvDr8HYKPZhNT5h/CXfBThSRXQ6jW5DO2ekP5ViFdi" crossorigin="anonymous">

  <link rel="stylesheet" type="text/css" href="/static/styles/main.css">

  <script src="https://use.fontawesome.com/354985ad2d.js"></script>


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

<div class="image col-lg-8">

  <canvas id="canvas" width="600" height="400"></canvas>

  <hr>

  Redimensionner : <input id="scale" max="4" min="0.1" step="0.01" type="range" value="1" />

</div>

<div class="caption col-lg-4">

  <input type="url" class="form-control" id="image-url" placeholder="Entrez l'URL de l'image"
    value="http://gallica.bnf.fr/ark:/12148/btv1b90130097/f1.highres">
  <button id="download-into-canvas" class="btn btn-block btn-primary">Télécharger</button>

  <label>Texte du haut</label>
  <textarea class="form-control" id="text-1"></textarea>

  <label>Texte du bas</label>
  <textarea class="form-control" id="text-2"></textarea>

  <div class="actions">

    <a class="btn btn-block btn-success" id="download" href="#" download="gallica.jpg"><i class="fa fa-download" aria-hidden="true"></i>
 Télécharger</a>
    <hr>

    <button class="btn btn-info"><i class="fa fa-twitter" aria-hidden="true"></i>
 Se connecter sur twitter</button>

    <button class="btn btn-info"><i class="fa fa-twitter" aria-hidden="true"></i>
 Partager sur twitter</button>

  </div>

</div>

</div> <!-- big row -->
</div>

<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.5/js/bootstrap.min.js" integrity="
  sha384-BLiI7JTZm+JWlgKa0M0kGRpJbF2J8q+qreVrKBC47e3K6BW78kGLrCkeRX6I9RoK" crossorigin="anonymous"></script>
<script src="/static/scripts/meme.js"></script>

</body>
</html>
