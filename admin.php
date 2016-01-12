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

?>

Cette session est visible que pour les administrateurs