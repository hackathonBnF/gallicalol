<?php
header('Content-Type: application/json');

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

$q = $api_endpoint."?".http_build_query($parameters);

$xml = file_get_contents( $q , false, $context);
$xml = simplexml_load_string($xml);

$results = [];

foreach ($xml->xpath('//dc:identifier') as $identifier) {
  if ( preg_match( '/gallica.bnf.fr/', $identifier ) ) {

    $r = [
      "id" => $identifier[0],
      "thumb" => $identifier[0]."/lowres",
      "img" => $identifier[0]."/f1.highres"
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

echo(json_encode($response));

?>
