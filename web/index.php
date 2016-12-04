<?php

$filename = __DIR__.preg_replace('#(\?.*)$#', '', $_SERVER['REQUEST_URI']);
if (php_sapi_name() === 'cli-server' && is_file($filename)) {
    return false;
}

require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;

$app = new Silex\Application();

$app['debug'] = true;

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/views',
));
$app->register(new Silex\Provider\SessionServiceProvider());

$app['db'] = new SQLite3(__DIR__."/../db/gallicalol.db");

define('DEFAULT_SEARCH_QUERY', 'barbe');
define('ITEMS_PER_PAGE', 48);

$app->get('/', function(Request $request) use ($app) {
    $query = $request->query->get('q', DEFAULT_SEARCH_QUERY);

    return $app['twig']->render('index.twig', [
        'query' => $query,
    ]);
});

$app->get('/ark:/{naan}/{name}.meme', function($naan, $name, Request $request) use ($app) {

    $identifier = $request->getPathInfo();

    $query = 'http://gallica.bnf.fr'.$identifier.'/f1.highres';

    $access_token = $app['session']->get('access_token');
    $access_token_secret = $app['session']->get('access_token_secret');

    $is_authenticated = !empty($access_token) && !empty($access_token_secret);

    return $app['twig']->render('create.twig', [
        'query' => $query,
        'is_authenticated' => $is_authenticated,
    ]);
})
->assert('naan', '\d+')
->assert('name', '[a-z0-9]+');

$app->post('/save', function(Request $request) use ($app) {

    $stmt = $app['db']->prepare('INSERT INTO memes (gallica_url, top_text, bottom_text, image, scale) VALUES (:url, :top, :bottom, :image, :scale);');

    $stmt->bindValue(':url', $request->request->get('query'));
    $stmt->bindValue(':top', $request->request->get('top_text'));
    $stmt->bindValue(':bottom', $request->request->get('bottom_text'));
    $stmt->bindValue(':image', $request->request->get('download_hidden'));
    $stmt->bindValue(':scale', $request->request->get('scale'));

    $result = $stmt->execute();

    $meme_id = $app['db']->lastInsertRowID();

    return $app->redirect("/memes/{$meme_id}");
});

$app->get('/memes', function(Request $request) use ($app) {

    $totalItems = 0;

    $stmt = $app['db']->prepare('SELECT COUNT(*) FROM memes');
    if ($result = $stmt->execute()) {
        $res = $result->fetchArray(SQLITE3_NUM);
        $totalItems = $res[0];
    }

    $page = $request->query->get('p', 1);
    $pages = (int) ceil($totalItems / ITEMS_PER_PAGE);

    $offset = ITEMS_PER_PAGE * ($page - 1);

    $stmt = $app['db']->prepare("SELECT * FROM memes ORDER BY id DESC LIMIT {$offset}, " . ITEMS_PER_PAGE);
    $result = $stmt->execute();

    $memes = [];
    if ($result) {
        while ($meme = $result->fetchArray()) {
            $memes[] = $meme;
        }
    }

    return $app['twig']->render('memes.twig', [
        'memes' => $memes,
        'pages' => $pages,
        'page' => $page,
    ]);
});

$app->get('/memes/{id}', function($id, Request $request) use ($app) {

    $request = $app['db']->prepare('SELECT * FROM memes WHERE id = :id');
    $request->bindValue(':id', $id);
    $result = $request->execute();
    $meme = $result->fetchArray();

    $gallica_url = $meme['gallica_url'];
    $pattern = '/(ark.*)\/f1.highres/';
    preg_match($pattern, $gallica_url, $ark, PREG_OFFSET_CAPTURE);

    $context  = stream_context_create(array('http' => array('header' => 'Accept: application/xml')));

    $url_query = 'http://gallica.bnf.fr/services/OAIRecord?ark=' . urlencode( str_replace(".meme", "", $ark[1][0]) );

    $xml = file_get_contents($url_query , false, $context);
    $xml = simplexml_load_string($xml);

    $title = $xml->xpath('//title');
    $date = $xml->xpath('//date');

    $source = [
        'url' => 'http://gallica.bnf.fr/' . $ark[1][0],
        'title' => $title[0],
        'date' => $date[0],
    ];

    return $app['twig']->render('meme.twig', [
        'meme' => $meme,
        'source' => $source,
    ]);
});

$app->run();
