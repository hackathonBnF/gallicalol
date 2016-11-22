<?php
header('Content-Type: application/json');

if ( !empty($_GET['query']) ) {
    $query = $_GET['query'];
} else {
    $query = "";
}

$context  = stream_context_create(array('http' => array('header' => 'Accept: application/xml')));

$api_endpoint = "http://gallica.bnf.fr/SRU";

$parameters = [
  "operation" => "searchRetrieve",
  "version" => "1.2",
  "maximumRecords" => "50",
  "query" => "dc.type any image and gallica any ".urldecode($query)
];

$q = $api_endpoint."?".http_build_query($parameters);

$xml = file_get_contents( $q , false, $context);
$xml = simplexml_load_string($xml);

$results = [];

foreach ($xml->xpath('//dc:identifier') as $identifier) {
  if ( preg_match( '/gallica.bnf.fr/', $identifier ) ) {
    //$r["id"] = $identifier[0];
    $r["thumb"] = $identifier[0]."/lowres";
    $r["img"] = $identifier[0]."/f1.highres";

    array_push($results, $r);
	}
}

echo(json_encode($results));

?>
