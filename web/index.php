<?php

$filename = __DIR__.preg_replace('#(\?.*)$#', '', $_SERVER['REQUEST_URI']);
if (php_sapi_name() === 'cli-server' && is_file($filename)) {
    return false;
}

require_once __DIR__.'/../vendor/autoload.php';

require_once __DIR__.'/includes/functions.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Intervention\Image\ImageManager;

$app = new Silex\Application();

$app['debug'] = true;

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/views',
));
$app->register(new Silex\Provider\SessionServiceProvider());

$app['db'] = new SQLite3(__DIR__."/../db/gallicalol.db");

define('DEFAULT_SEARCH_QUERY', 'barbe');
define('ITEMS_PER_PAGE', 16);
define('IMAGE_DIR', realpath(__DIR__.'/images'));

// Do not activate Piwik in dev
$piwik_tracker = (0 !== strpos($_SERVER['HTTP_HOST'], 'localhost'));
$app['twig']->addGlobal('piwik_tracker', $piwik_tracker);

$app['image_manager'] = new ImageManager(array('driver' => 'imagick'));

$app->get('/', function(Request $request) use ($app) {
    $query = $request->query->get('q', DEFAULT_SEARCH_QUERY);

    return $app['twig']->render('index.twig', [
        'query' => $query,
    ]);
});

$app->get('/ark:/{naan}/{name}.meme', function($naan, $name, Request $request) use ($app) {

    $identifier = $request->getPathInfo();

    $query = 'https://gallica.bnf.fr'.$identifier.'/f1.highres';

    return $app['twig']->render('create.twig', [
        'query' => $query,
    ]);
})
->assert('naan', '\d+')
->assert('name', '[a-z0-9]+');

$app->get('/proxy/ark:/{naan}/{name}/lowres', function($naan, $name, Request $request) use ($app) {

    $url = 'http://gallica.bnf.fr' . str_replace('/proxy', '', $request->getPathInfo());

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, 'GallicaLOL');

    $raw = curl_exec($ch);
    $image = $app['image_manager']->make($raw);

    $image->resize($image->getWidth() * 0.6, $image->getHeight() * 0.6);
    $data = $image->encode('jpg');

    $headers = [
        'Content-Type' => 'image/jpeg',
    ];
    if (false !== strpos($request->headers->get('Accept-Encoding'), 'gzip')) {
        $data = gzencode($data);
        $headers['Content-Encoding'] = 'gzip';
        $headers['Vary'] = 'Accept-Encoding';
    }

    $response = Response::create($data, 200, $headers);

    // @link https://coderwall.com/p/rl6v7a/http-caching-in-symfony2-max-age-etag-gzip
    $etagWithoutGzip = str_replace('-gzip"', '"', $request->headers->get('If-None-Match'));
    $request->headers->set('If-None-Match', $etagWithoutGzip);

    $response->setETag(md5($data));
    $response->setPublic(); // make sure the response is public/cacheable
    $response->setSharedMaxAge(60 * 60 * 24 * 30);
    $response->isNotModified($request);

    return $response;
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

    // Save image to filesystem
    $path = meme_image_path($meme_id);
    $filename = IMAGE_DIR.'/'.$path;
    if (!is_dir(dirname($filename))) {
        mkdir(dirname($filename), 0755, true);
    }
    file_put_contents($filename, base64_to_bin($request->request->get('download_hidden')));

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
            $meme['image_url'] = meme_image_uri($meme['id']);
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

    $stmt = $app['db']->prepare('SELECT * FROM memes WHERE id = :id');
    $stmt->bindValue(':id', $id);
    $result = $stmt->execute();
    $meme = $result->fetchArray();
    $meme['image_url'] = meme_image_uri($meme['id']);

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

    $access_token = $app['session']->get('access_token');
    $access_token_secret = $app['session']->get('access_token_secret');

    $is_authenticated = !empty($access_token) && !empty($access_token_secret);

    return $app['twig']->render('meme.twig', [
        'meme' => $meme,
        'source' => $source,
        'is_authenticated' => $is_authenticated,
    ]);
});

// @link http://symfony.com/doc/current/components/http_foundation.html#serving-files
$app->get('/memes/{id}/download', function($id, Request $request) use ($app) {

    $stmt = $app['db']->prepare('SELECT * FROM memes WHERE id = :id');
    $stmt->bindValue(':id', $id);
    $result = $stmt->execute();
    $meme = $result->fetchArray();

    $data = base64_to_bin($meme['image']);

    $response = new Response($data);

    $disposition = $response->headers->makeDisposition(
        ResponseHeaderBag::DISPOSITION_ATTACHMENT,
        'gallica.jpg'
    );

    $response->headers->set('Content-Disposition', $disposition);

    return $response;
});

$app->run();
