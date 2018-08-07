<?php
    require "opendb.php";

$username = $_POST["username"];
$password = $_POST["password"];
$nom =  $_POST["nom"];
$prenom = $_POST["prenom"];
$res  = mysqli_query($connect,"SELECT * FROM Joueur WHERE username='$username'");
if (mysqli_num_rows($res)==0){
    mysqli_free_result($res);
}
else{
    header('Location: inscription.html');//redirige vers les inscriptions
    mysqli_free_result($res);
    die();
}
mysqli_set_charset($connect,"utf8");
$nouv_username = mysqli_real_escape_string($connect,$username);
$nouv_password = mysqli_real_escape_string($connect,$password);
$nouv_prenom = mysqli_real_escape_string($connect,$prenom);
$nouv_nom = mysqli_real_escape_string($connect,$nom);
  $res =  mysqli_query($connect,"SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci");
  if (!$res){die("erreur setting de l'encodage ".mysqli_error());}
if( !mysqli_query($connect,"INSERT INTO Joueur VALUES ('$nouv_prenom','$nouv_nom','$nouv_username','$nouv_password',0)")){
    die("erreur insertion ".mysqli_error());
}
    header('Location: login.html');

    require "closedb.php";
?>