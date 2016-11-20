<?php session_start() ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title></title>

  <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">


  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.5/css/bootstrap.min.css" integrity="sha384-AysaV+vQoT3kOAXZkl02PThvDr8HYKPZhNT5h/CXfBThSRXQ6jW5DO2ekP5ViFdi" crossorigin="anonymous">

  <link rel="stylesheet" type="text/css" href="/static/styles/main.css">

  <script src="https://use.fontawesome.com/354985ad2d.js"></script>


</head>
<body>

<div class="container">


<nav class="navbar navbar-light">

  <a class="navbar-brand" href="/">gallical.lol</a>


  <div class="nav navbar-inline float-xs-right">
    <a class="browse nav-link" href="#">chercher</a>
    <a class="create nav-link" href="#">créer</a>
  </div>
</nav>
<form action="./store.php" method="post">
<input type="hidden" name="query" value="<?php echo $_GET['query']; ?>" />
<input type="hidden" name="download_hidden" id="download_hidden" />

<div class="row">

<div class="image col-lg-8">

  <canvas id="canvas" width="600" height="400"></canvas>

  <hr>

  Redimensionner : <input id="scale" name="scale" max="4" min="0.1" step="0.01" type="range" value="1" />

</div>

<div class="caption col-lg-4">

  <div id="media">
  <input type="url" class="form-control" id="image-url" placeholder="Entrez l'URL de l'image"
    value="http://gallica.bnf.fr/ark:/12148/btv1b90130097/f1.highres">
  <button id="download-into-canvas" class="btn btn-block btn-primary">Télécharger</button>

  </div>

  <label>Texte du haut</label>
  <textarea class="form-control" id="text-1" name="top_text"></textarea>

  <label>Texte du bas</label>
  <textarea class="form-control" id="text-2" name="bottom_text"></textarea>

  <div class="actions">

    <button type="submit" formmethod="post" formaction="./store.php" class="btn btn-block btn-success" id="save" ><i class="fa fa-save" aria-hidden="true"></i> Sauver</button>

    <a class="btn btn-block btn-success" id="download" href="#" download="gallica.jpg"><i class="fa fa-download" aria-hidden="true"></i>
 Télécharger</a>
    <hr>

    <button class="btn btn-info" id="twitter-login" data-is-authenticated="<?php echo isset($_SESSION['access_token'], $_SESSION['access_token_secret']) ? 'true' : 'false' ?>">
      <i class="fa fa-twitter" aria-hidden="true"></i> Partager sur twitter
    </button>

  </div>

</div>

</div> <!-- big row -->
</form>
</div>

<!-- Modal to post on Twitter -->
<div id="twitter-modal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Partager sur Twitter</h4>
      </div>
      <div class="modal-body">
        <form>
          <div class="form-group">
            <label for="message-text" class="control-label">Texte du tweet :</label>
            <textarea class="form-control" id="tweet-text"></textarea>
          </div>
        </form>
        <img id="twitter-modal-img" class="img-fluid" src="" style="display: none;">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
        <button type="button" class="btn btn-primary" id="post-to-twitter">Tweeter</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.5/js/bootstrap.min.js" integrity="
  sha384-BLiI7JTZm+JWlgKa0M0kGRpJbF2J8q+qreVrKBC47e3K6BW78kGLrCkeRX6I9RoK" crossorigin="anonymous"></script>
<script src="/static/js/jquery.oauthpopup.js"></script>
<script src="/static/scripts/meme.js"></script>
<script src="/static/js/main.js"></script>

</body>
</html>
