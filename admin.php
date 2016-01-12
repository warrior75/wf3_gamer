<?php
	session_start();

	require(__DIR__.'/config/db.php');
	require(__DIR__.'/functions.php');

	// Cette fonction doit être mis de préférence dans le fichier functions.php

	checkLoggedIn();

	// L'utilisateur est connecté

	// On va vérifié que ce user a le role admin 

	if ($_SESSION['gamers']['role'] != 'admin') {
		header("HTTP/1.0 403 Forbidden");
		die();
	}

	// compter le nbr. de users en bdd
	$query = $pdo('');
	$resultCount = $query->bindValue



?>

Cette session est visible que pour les administrateurs

    <style type="text/css">
      html, body { height: 100%; margin: 0; padding: 0; }
      #map { height: 100%; }
    </style>
  </head>
  <body>
    <div id="map"></div>
    <script type="text/javascript">

var map;

var myLatLng = lat lng 

function initMap() {
  map = new google.maps.Map(document.getElementById('map'), {
    center: {lat: -34.397, lng: 150.644},
    // on peut changer le zoom
    zoom: 12
  });

  var marker = new google.maps.Marker({
	

	});
}



    </script>
    <script async defer
      src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&callback=initMap">
    </script>
  </body>
</html>