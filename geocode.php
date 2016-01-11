<?php


// Por enregistrer en bdd : 
// champ lat: DECIMAL(10,8)
// champ lat: DECIMAL(11s,8)
function geocode($address){

	// Url de l'api http://maps.google.com/maps/api/geocode/json?address=

	// encodage de l'adresse pour la soumettre sur l'url par la suite

	$address = urlencode($address);

	// Url de l'api pour geocoder
	// 'http://maps.google.com/maps/api/geocode/json?address='.$adress
	$urlApi = "http://maps.google.com/maps/api/geocode/json?address=$address";

	// Appel à l'Api gmap decode (en GET - réponse en JSON)
	$responseJson = file_get_contents($urlApi);

	// Decodage du json pour le transformer en array php associatif
	$responseArray = json_decode($responseJson, true);

/*	echo "<pre>";
	print_r($responseArray);
	echo "</pre>";*/

	// On prépare un tableau associatif comme réponse de cette fonction
	$response = [];

	// Je test le statut de réponse à OK (sinon cela signifie qu'il n'a pas de correspondant)
	if($responseArray['status'] == 'OK'){
		$lat = $responseArray['results']['0']['geometry']['location']['lat'];
		$lng = $responseArray['results']['0']['geometry']['location']['lng'];

	// Test les valeurs (pas indispensable)
	if() {
		$reponse['lat'] = $lat;
		$reponse['lng'] = $lng;
	}
	}

}

echo geocode('136, avenue pablo picasso 92000 nanterre');

?>