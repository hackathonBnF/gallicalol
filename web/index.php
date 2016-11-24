<!DOCTYPE html>
<html>
<head>
  <?php include("includes/head.php") ?>
</head>
<body data-spy="scroll" data-target="#pagination">

<div class="container">

<div class="row">

  <?php include("includes/navbar.php") ?>

<div>
<form id="search" class="form-inline">
  <div class="row">
	<div class="col-xs-10"><input type="text" id="query" name="query" class="form-control input-sm" maxlength="64" placeholder="Chercher une image" value="<?php if ( !empty($_GET["query"])) { echo $_GET["query"]; } else { echo "Bibliothèque"; } ?>" /></div>
	<div class="col-xs-2"><button type="submit" class="btn btn-primary">chercher</button></div>
  </div>
</form>

<div id="messages">
  <div id="no-results" class="">
    <div>Aucune image trouvée</div>
    <img src="/static/images/search-fail.gif" alt="" loop="1" />
  </div>
</div>

<div id="results" class="grid"></div>

<div id="loading" class="">
  <img src="/static/images/loading.gif" alt="" />
</div>

<nav class="" id="pagination">
  <div class="" id="page">1</div>
  <div class="" id="total"></div>
  <div class="" id="next"><a href="#">afficher plus d'images</a></div>
</nav>

</div>

</div>

<?php include("includes/footer.php") ?>

</div>

<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.5/js/bootstrap.min.js" integrity="
  sha384-BLiI7JTZm+JWlgKa0M0kGRpJbF2J8q+qreVrKBC47e3K6BW78kGLrCkeRX6I9RoK" crossorigin="anonymous"></script>

<script src="https://unpkg.com/masonry-layout@4.1/dist/masonry.pkgd.min.js"></script>
<script src="https://npmcdn.com/imagesloaded@4.1/imagesloaded.pkgd.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-infinitescroll/2.1.0/jquery.infinitescroll.min.js"></script>
<script src="/static/js/main.js"></script>

</body>
</html>
