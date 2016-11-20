<?php

$db = new SQLite3("db/gallicalol.db");
$request = $db->prepare('SELECT * FROM memes WHERE id = :id');
$request->bindValue( ':id', $_GET['id'] );
$result = $request->execute();
$meme = $result->fetchArray();

$gallica_url = $meme['gallica_url'];
$pattern = '/(ark.*)\/f1.highres/';
preg_match($pattern, $gallica_url, $ark, PREG_OFFSET_CAPTURE );
?>
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

<div class="row">

<div class="image col-lg-12">
  <div>
       <img alt="Meme n° <?php echo $GET['id'] ?>" src="<?php echo $meme['image'] ?>" />
  </div>
  <div>

<?php

$context  = stream_context_create(array('http' => array('header' => 'Accept: application/xml')));
$url_query = 'http://gallica.bnf.fr/services/OAIRecord?ark=' . urlencode( $ark[1][0] );
$xml = file_get_contents( $url_query , false, $context);
$xml = simplexml_load_string($xml);

$title = $xml->xpath('//title');
$date = $xml->xpath('//date');
?>
       <a href="<?php echo 'http://gallica.bnf.fr/' . $ark[1][0] ?>" target="_blank"><?php echo $title[0] . ' (' . $date[0] . ')'?></a><br />

  </div>
</div>


</div> <!-- big row -->
</form>
</div>

<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.5/js/bootstrap.min.js" integrity="
  sha384-BLiI7JTZm+JWlgKa0M0kGRpJbF2J8q+qreVrKBC47e3K6BW78kGLrCkeRX6I9RoK" crossorigin="anonymous"></script>
<script src="/static/scripts/meme.js"></script>
<script src="/static/js/main.js"></script>

</body>
</html>

