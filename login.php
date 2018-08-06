<?php if(isset($_POST['source'])) die(highlight_file(__FILE__,1)); ?>	
<!DOCTYPE html>
        <html>
        <head>
                <meta charset="utf-8"/>
                <title>page de selection </title>
                <link rel="stylesheet" href="" />
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
            <script src="afficheForm.js"></script>

        </head>
        <body>
        
<?php 
	//include  "opendb.php";
$db_utilisateur= "coulomje";
$db_password="sCkjeXxBt55pRD";
$db_host="www-ens";
$db_nom="coulomje_Tp3";
$connect = mysqli_connect($db_host,$db_utilisateur,$db_password,$db_nom);
if(!$connect){
	die("probleme lors de la connexion ".mysqli_connect_error());
}
	$username=$_POST["username"];
	//echo "<h1>$username </h1>";
	$password=$_POST["password"];
	//echo "$password";
	//echo " test";
	$resultat = mysqli_query($connect,"SELECT prenom,nom FROM Joueur WHERE login='".$username."'");
	if(mysqli_error($resultat)){echo '<h1>erreur de query</h1>';}
	$resultat = mysqli_fetch_assoc($resultat);
	//echo "$resultat";       
	$nom = $resultat["nom"];
	//echo "$nom <br/>";
        $prenom = $resultat["prenom"];
        mysqli_free_result($resultat);
	function login(){
		global $username,$password,$connect;
		//echo "commence login <br />";

		$resultat = mysqli_query($connect,"SELECT password FROM Joueur WHERE login='$username' AND  password='$password'");
		//echo "<h1> a passé la query </h1> <br />";
      		//echo "<h1>msqli_num_rows($resultat)</h1> <br />"; 
		if(mysqli_num_rows($resultat)>0){
			//	echo '<h1> login retourne true</h1> <br />';
			mysqli_free_result($resultat);
		
                        return true;    
                }
		else{
			//echo '<h1> login retourne false</h1> <br />';
                        mysqli_free_result($resultat);
                        return false;
                }
                
        }
        function estGerant(){
                global $username,$password,$connect;
                $resultat = mysqli_query($connect,"SELECT gerant FROM Joueur WHERE login='$username' AND  password='$password'");
                $arrayResult = mysqli_fetch_assoc($resultat);
                if($arrayResult["gerant"]>0){
                         mysqli_free_result($resultat);
                        return true;
                }
                else{
                        mysqli_free_result($resultat);
                        return false;
                }
        }

        if(login()){//remplacer condition si vient du script decrit dans ligne suivante
            //va appeller un script qui va selon le radio selectionner afficher le formulaire correspondant
            echo "       
            <form action=\"\" name=\"choixFormJoueur\" id=\"choixFormJoueur\" method=\"post\" accept-charset=\"utf-8\">
                <label>Disponibilité des terrains par date et par heure dans une journée
                    <input type=\"radio\" name=\"choix\" id=\"dispoTerrain\" value=\"dispoTerrain\"/>
                </label>
                <label>
                    Liste des réservations pour une journée donnée
                    <input type=\"radio\" name=\"choix\" id=\"listeReserv\" value=\"listeReserv\" />
                </label> 
                <label>
                    Annuler une réservation
                    <input type=\"radio\" name=\"choix\" id=\"annuleReserv\" value=\"annuleReserv\" />
                </label> 
                <label>
                    Faire une réservation
                    <input type=\"radio\" name=\"choix\" id=\"faitReserv\" value=\"faitReserv\" />
                </label> 
                <input type=\"button\" name=\"choisiTypePage\" id=\"submit\" value=\"choisir l'option\"/>
                <input type=\"hidden\" value=\"$nom\" id='nom' name='nom'/>
                <input type=\"hidden\" value=\"$prenom\" id='prenom' name='prenom'/>
            </form>
            <span id=\"spanForm\"></span>";

            if (estGerant()){
                echo '       
            <form action="" name="" method="post" accept-charset="utf-8">
                <label>Lister les joueurs du club
                    <input type="radio" name="listeJoueur" value="1"/>
                </label>
                <label>
                    Lister les terrains réservés aujourd\'hui avec les infos des joueurs qui l\'ont réservés.
                    <input type="radio" name="listeReserv" value="1" />
                </label> 
                <label>
                    Lister les terrains disponibles dans un intervalle de temps donné dans la journée
                    <input type="radio" name="ListeTerrainDispo" value="1" />
                </label> 
               
                <input type="submit" value="choisir l\'option"/>
            </form>';
            }
        }

        else if(!login()){
            echo '<h1>nom d\'utilisateur ou mot de passe incorect</h1> <br/>';
            echo '<a href="login.html">se connecter</a>';
        }
?>
        </body>
</html>
