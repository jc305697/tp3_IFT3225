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

	include  "opendb.php";
    //if(!isset($_POST["submitReserv"])){
        $username=$_POST["username"];
        $password=$_POST["password"];

        $resultat = mysqli_query($connect,"SELECT prenom,nom FROM Joueur WHERE login='".$username."'");
        if(mysqli_error($resultat)){echo '<h1>erreur de query</h1>';}
        $resultat = mysqli_fetch_assoc($resultat);
        $nom = $resultat["nom"];
        $prenom = $resultat["prenom"];
        mysqli_free_result($resultat);
    /*}
    else{
        $nom = $_POST["nom"];
        $prenom = $_POST["prenom"];
    }*/
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
                $resultat = mysqli_query($connect,"SELECT gerant FROM Joueur WHERE login='$username'");
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

        if(isset($_POST["submitFaitReserv"]) || isset($_POST["submitReserv"]) || isset($_POST["submitAnnul"]) || login()){//remplacer condition si vient du script decrit dans ligne suivante
            //va appeller un script qui va selon le radio selectionner afficher le formulaire correspondant

            if(isset($_POST["submitAnnul"])){
                $query = 'delete from Reservation where date='.$_POST["dateAnnul"].' and terrain=\''.$_POST["numTerrAnnul"].'\' and heure=\''
                    .$_POST["heureAnnul"].'\' and nom=\''.$_POST["nom"].'\' and prenom=\''.$_POST["prenom"].'\'';
                if (mysqli_query($connect,$query)){
                    echo '<h2>Réservation annuler</h2>';
                }else{
                    echo 'erreur de query '.mysqli_error($connect);
                    require "closedb.php";
                    die();
                }
            }
            if(isset($_POST["submitFaitReserv"])){
                $query = 'insert into Reservation VALUES (\''.$prenom.'\',\''.$nom.'\',\''.$_POST["numTerrReserv"].'\',\''.$_POST["dateReserv"].'\',\''.$_POST["heureReserv"].'\')' ;
                if (mysqli_query($connect,$query)){
                    echo '<h2>Réservation faite</h2>';
                }else{
                    echo 'erreur de query '.mysqli_error($connect);
                    require "closedb.php";
                    die();
                }
            }

            //va afficher la disponibilite des terrains par heure
            echo "       
                <table> 
                <tr>";
            for ($i=6;$i<=21;$i++){
                echo '<th>'.$i.'h</th>';
            }
            echo '</tr>';

            $resultat = mysqli_query($connect,"select numero from Terrain");
            $dateDemain = (new DateTime())->add(new DateInterval("P1D"))->format("Y-m-d");
            //de https://stackoverflow.com/questions/2215354/php-date-format-when-inserting-into-datetime-in-mysql
            //et de https://stackoverflow.com/questions/14460518/php-get-tomorrows-date-from-date/14460546
            $query = "select nom from Reservation where date_reservation=? and heure_reservation=? and terrain=?";
            $demandePrep=   mysqli_prepare($connect,$query);
            if( !mysqli_stmt_bind_param($demandePrep,'sii',$dateDemain,$i,$tabResultat[0])){
                die('erreur de binding '.mysqli_error($connect));
            }
            while ($tabResultat = mysqli_fetch_row($resultat)){
                echo '<tr><th>terrain '.$tabResultat[0].'</th>';
                //$i;
                for ($i=6;$i<=21;$i++){

                   if(!mysqli_stmt_execute($demandePrep)){
                       die('erreur d\'execution '.mysqli_error($connect) );
                   }
                    $res = mysqli_fetch_array(mysqli_stmt_get_result($demandePrep));
                   if (is_null($res)){
                        echo '<td class="dispo">libre</td>';
                   }
                   else{
                       echo '<td class="occupe">réservé</td>';
                   }
                   mysqli_free_result($res);

                }
                echo '</tr>';

            }
            echo '</table> <br />';

            echo"<form action=\"#\" name=\"\" id=\"\" method=\"post\" accept-charset=\"utf-8\">   ".
            "<label>Date <input type=\"date\" required name=\"dateReserv\" id=\"dateReserv\" ";

            if(isset($_POST["submitReserv"])){
                echo 'value='.$_POST["dateReserv"];//afin d'afficher la date donner par l'utilisateur
            }
           echo "/>  </label>   ".
            "<label>  <input type=\"submit\" name=\"submitReserv\" /> </label> ".
             "<input type=\'hidden\' value=\"$username\" id='username' name='username'/>".
             "<input type=\'hidden\' value=\"$password\" id='password' name='password'/>".
            "</form>";


            if(isset($_POST["submitReserv"])){//donc on a cliquer sur le submit pour voir les reservations
                $query = "select terrain,date_reservation,heure_reservation from Reservation where nom='$nom' and prenom='$prenom'";
                $res = mysqli_query($connect,$query);
                if (mysqli_num_rows($res)==0){
                    echo '<p>aucune réservations pour cette journée</p>';
                }
                else{
                    echo '<table> <tr><th>Terrain</th><th>Date</th><th>Heure</th></tr>';
                    while ($resArray = mysqli_fetch_assoc($res)){
                        echo '<tr><td>'.$resArray["terrain"].'</td><td>'.$resArray["date_reservation"]
                            .'</td><td>'.$resArray["heure_reservation"].'</td></tr>';
                        mysqli_free_result($res);
                    }
                    echo '</table>';
                }
            }

            //pour l'annulation d'une resevation
            echo '<br /> <form action=\"#\" name=\"\" id=\"\" method=\"post\" accept-charset=\"utf-8\"> 
            <label>Numéro de Terrain <input type=\"number\" name=\"numTerrAnnul\" id=\"numTerrAnnul\" required min="1" max="5" value="1"/>  </label>   
            <label>Date <input type=\"date\" name=\"dateAnnul\" id=\"dateAnnul\" required/>  </label>    
            <label> Heure:<input type=\"number\" name=\"heureAnnul\" id=\"heureAnnul\" step=\'any\' min=\"06\" max=\"21\" value=\'06\' required/> </label> 
            <label>   <input type=\"submit\" name=\"submitAnnul\" value="Annuler la réservation"/> </label> 
            <input type=\'hidden\' value=\'\''.$username.'id=\'usernameAnnul\' name=\'username\'/>
            <input type=\'hidden\' value=\'\''.$password.'id=\'passwordAnnul\' name=\'password\'/>
            </form> <br/>';

            echo '<br /> <form action=\"#\" name=\"\" id=\"faitReserv\" method=\"post\" accept-charset=\"utf-8\"> 
            <label>Numéro de Terrain <input type=\"number\" name=\"numTerrReserv\" id=\"numTerrReserv\" required min="1" max="5" value="1"/>  </label>   
            <label>Date <input type=\"date\" name=\"dateReserv\" id=\"dateReserv\" required/>  </label>    
            <label> Heure:<input type=\"number\" name=\"heureReserv\" id=\"heureReserv\" step=\'any\' min=\"06\" max=\"21\" value=\'06\' required/> </label> 
            <label>   <input type=\"submit\" name=\"submitFaitReserv\" value="Faire la réservation"/> </label> 
            <input type=\'hidden\' value=\'\''.$username.'id=\'usernameReserv\' name=\'username\'/>
            <input type=\'hidden\' value=\'\''.$password.'id=\'passwordReserv\' name=\'password\'/>
            </form> <br/>';


            if (estGerant()){
                echo "      <br /> 
            <form action=\"actionGerant.php\" name=\"\" id='formGerant' method=\"post\" accept-charset=\"utf-8\">
                <label>Lister les joueurs du club
                    <input type=\"radio\" value=\"listeJoueur\" id='listeJoueur' name=\"choix\"/>
                </label>
                <label>
                    Lister les terrains réservés aujourd\'hui avec les infos des joueurs qui l\'ont réservés.
                    <input type=\"radio\" value=\"listeReserv\" id='listeReserv' name=\"choix\" />
                </label> 
                <label>
                    Lister les terrains disponibles dans un intervalle de temps donné dans la journée
                    <input type=\"radio\" value=\"ListeTerrainDispo\" id='ListeTerrainDispo' name=\"choix\" />
                </label> 
           
               
                <input type=\"submit\" id=\"submitGerant\" value=\"choisir l'option\"/>
            </form>
            <span id=\"spanGerant\"></span>";
            }
        }

        else //if(!$estLogger){
        {
           // echo '<h1>nom d\'utilisateur ou mot de passe incorect</h1> <br/>';
            //echo '<a href="ecranLogin.php">se connecter</a>';
            header("Location: ecranLogin.php?erreurLogin=1");
        }

        require "closedb.php";
?>
        </body>
</html>
