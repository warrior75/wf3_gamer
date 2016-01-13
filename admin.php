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
	$query = $pdo->query('SELECT count(id) as total FROM gamers');
	$resultCount = $query->fetch();
	$totalUser = $resultCount['total'];// affiche dans le page admin
	
?>
<h2>Statistiques</h2>

<p>Le site contient <?php echo $totalUser ?>utilisateur(s).</p>


<p>Cette session est visible que pour les administrateurs</p>

<h1>Localisation des utilisateurs</h1>

    <style type="text/css">
     
      #map { height: 200px; }
    </style>
  </head>
  <body>
    <div id="map"></div>
    <script type="text/javascript">

var map;

var myLatLng = {lat: 48.8909964, lng: 2.2345892};

function initMap() {
  map = new google.maps.Map(document.getElementById('map'), {
    center: {lat: -34.397, lng: 150.644},
    // on peut changer le zoom
    zoom: 12
  });

      var marker = new google.maps.Marker({
        position: myLatLng,
        map: map,
        title: 'hello'
	});
}



    </script>
    <script async defer
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyApFHyhOE1lniNGNo0yrkthO-wEUp4OOzM&callback=initMap">
    </script>
<h1>Les derniers jeux ajoutés par les inscrits</h1>



  </body>
</html>