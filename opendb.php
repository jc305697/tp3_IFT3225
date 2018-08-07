<?php 
	require "config.php";
	$connect = mysqli_connect($db_host,$db_utilisateur,$db_password,$db_nom);
	if(!$connect){
		die("probleme lors de la connexion".mysqli_error());
	}
?>