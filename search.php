<?php
  $context  = stream_context_create(array('http' => array('header' => 'Accept: application/xml')));
  $url_query = 'http://gallica.bnf.fr/SRU?operation=searchRetrieve&version=1.2&query=dc.type%20any%20image%20and%20gallica%20any%20"' . $_POST['query'] . '"';
	$xml = file_get_contents( $url_query , false, $context);
  $xml = simplexml_load_string($xml);

  foreach ($xml->xpath('//dc:identifier') as $identifier) {
	  if ( preg_match( '/gallica.bnf.fr/', $identifier ) ) {
      echo $identifier[0];
  		echo '<br />';
		}
  }
?>
