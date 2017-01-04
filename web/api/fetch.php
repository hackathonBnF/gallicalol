<?php

require __DIR__ . '/../../vendor/autoload.php';

use Doctrine\Common\Cache\FilesystemCache;

$cache = new FilesystemCache(__DIR__ .'/../../cache');

if ( !empty($_GET['query']) ) {
    $query = $_GET['query'];
} else {
    $query = "";
}

$context  = stream_context_create(array('http' => array('header' => 'Accept: application/xml')));

$api_endpoint = "http://gallica.bnf.fr/SRU";

$page = ( !empty($_GET['page']) ? $_GET['page'] : 1);

$parameters = [
  "operation" => "searchRetrieve",
  "version" => "1.2",
  "maximumRecords" => "50",
  "page" => $page,
  "startRecord" => ($page-1) * 50 + 1,
  "query" => "dc.type any image and gallica any ".urldecode($query)
];

$cacheKey = http_build_query($parameters);

if (!$response = $cache->fetch($cacheKey)) {

    $q = $api_endpoint."?".http_build_query($parameters);

    $xml = file_get_contents( $q , false, $context);
    $xml = simplexml_load_string($xml);

    $results = [];

    foreach ($xml->xpath('//dc:identifier') as $identifier) {
        $identifier = (string) $identifier;
        // @link http://www.bnf.fr/fr/professionnels/issn_isbn_autres_numeros/a.ark.html
        if ( preg_match( '#^https?://gallica.bnf.fr/(ark:.*)#', $identifier, $matches ) ) {

            $ark_id = $matches[1];

            $r = [
              "id" => $ark_id,
              "thumb" => str_replace('http://gallica.bnf.fr', '/proxy', $identifier."/lowres"),
              "img" => str_replace('http://gallica.bnf.fr', '/proxy', $identifier."/f1.highres")
            ];

            array_push($results, $r);
        }
    }

    $records = $xml->xpath("//srw:numberOfRecords[1]/text()")[0]->__toString();

    $response = [
      "page" => $parameters["page"],
      "records" => $records,
      "results" => $results
    ];

    // Cache for 10 minutes
    $cache->save($cacheKey, $response, 60 * 10);
}

header('Content-Type: application/json');
echo(json_encode($response));
