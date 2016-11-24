<?php

require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;

$app = new Silex\Application();

$app['debug'] = true;

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/views',
));
$app->register(new Silex\Provider\SessionServiceProvider());

$app['db'] = new SQLite3("../db/gallicalol.db");

$app->get('/', function(Request $request) use ($app) {
    $query = $request->query->get('query', 'Bibliothèque');

    return $app['twig']->render('index.twig', [
        'query' => $query,
    ]);
});

$app->get('/create', function(Request $request) use ($app) {
    $query = $request->query->get('query');

    $access_token = $app['session']->get('access_token');
    $access_token_secret = $app['session']->get('access_token_secret');

    $is_authenticated = !empty($access_token) && !empty($access_token_secret);

    return $app['twig']->render('create.twig', [
        'query' => $query,
        'is_authenticated' => $is_authenticated,
    ]);
});

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