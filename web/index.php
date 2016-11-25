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

$app->get('/memes', function(Request $request) use ($app) {

    $request = $app['db']->prepare( 'SELECT * FROM memes ORDER BY clicked DESC LIMIT 50');
    $result = $request->execute();

    $memes = [];
    if ($result) {
        while ($meme = $result->fetchArray()) {
            $memes[] = $meme;
        }
    }

    return $app['twig']->render('memes.twig', [
        'memes' => $memes,
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
    $url_query = 'http://gallica.bnf.fr/services/OAIRecord?ark=' . urlencode( $ark[1][0] );
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