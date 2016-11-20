<?php
  header('Content-Type: application/json');
if ( !empty($_GET['query']) ) {
    $query = $_GET['query'];
} else {
    $query = "";
}
  $context  = stream_context_create(array('http' => array('header' => 'Accept: application/xml')));
$url_query = 'http://gallica.bnf.fr/SRU?operation=searchRetrieve&version=1.2&maximumRecords=50&query=dc.type%20any%20image%20and%20gallica%20any%20"' . urlencode(urldecode($query)) . '"';
	$xml = file_get_contents( $url_query , false, $context);
  $xml = simplexml_load_string($xml);

  $results = [];

  foreach ($xml->xpath('//dc:identifier') as $identifier) {
	  if ( preg_match( '/gallica.bnf.fr/', $identifier ) ) {
      //$r["id"] = $identifier[0];
      $r["thumb"] = $identifier[0].".thumbnail";
      $r["img"] = $identifier[0]."/f1.highres";

      array_push($results, $r);
		}
  }

echo(json_encode($results));

?>

