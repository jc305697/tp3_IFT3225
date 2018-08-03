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
	require "opendb.php";
	
	$username=$_POST["username"];
	echo "<h1>$username </h1>";
        $password=$_POST["password"];
        $resultat = mysqli_fetch_assoc(mysqli_query($connect,"SELECT prenom,nom FROM Joueur WHERE username=".$username));
        $nom = $resultat["nom"];
        $prenom = $resultat["prenom"];
        mysqli_free_result($resultat);
        function login(){
                global $username,$password,$connect;
                $resultat = mysqli_query($connect,"SELECT password FROM Joueur WHERE username=$username AND  password=$password");
                if(msqli_num_rows($resultat)>0){
                        mysqli_free_result($resultat);
                        return true;    
                }
                else{
                        mysqli_free_result($resultat);
                        return false;
                }
                
        }
        function estGerant(){
                global $username,$password,$connect;
                $resultat = mysqli_query($connect,"SELECT gerant FROM Joueur WHERE username=$username AND  password=$password");
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
                    <input type=\"radio\" name=\"dispoTerrain\" id=\"dispoTerrain\" value=\"1\"/>
                </label>
                <label>
                    Liste des réservations pour une journée donnée
                    <input type=\"radio\" name=\"listeReserv\" id=\"listeReserv\" value=\"1\" />
                </label> 
                <label>
                    Annuler une réservation
                    <input type=\"radio\" name=\"annuleReserv\" id=\"annuleReserv\" value=\"1\" />
                </label> 
                <label>
                    Faire une réservation
                    <input type=\"radio\" name=\"faitReserv\" id=\"faitReserv\" value=\"1\" />
                </label> 
                <input type=\"submit\" value=\"choisir l\'option\"/>
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

        else{
            echo '<h1>nom d\'utilisateur ou mot de passe incorect</h1> <br/>';
            echo '<a href="login.html">se connecter</a>';
        }
?>
        </body>
</html>
