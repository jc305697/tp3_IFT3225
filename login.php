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

	function imprimeTable($query,$message){
        global $connect;
        $res = mysqli_query($connect,$query);
        if (mysqli_num_rows($res) > 0){
            $ligne = mysqli_fetch_assoc($res);
            echo '<table> <thead><tr><th>'.implode("</th><th>",array_keys($ligne)).'</th></tr></thead>';
            echo '<tbody><tr><td>'.array_values("</td><td>",$ligne).'</td></tr>';
            while($ligne = mysqli_fetch_assoc($res)){
                echo '<tr><td>'.array_values("</td><td>",$ligne).'</td></tr>';

            }
            echo '</tbody></table>';
        }
        else{
            echo ''.$message;
        }
    }

	$liste = isset($_POST["submitListe"]);
    $listeReserv = isset($_POST["submitListeTerrain"]);
    $listeTerrainDispo = isset($_POST["submitListeTerrDispo"]);

if( $listeTerrainDispo || $liste || $listeReserv || isset($_POST["submitFaitReserv"]) || isset($_POST["submitReserv"]) || isset($_POST["submitAnnul"]) || login()){//remplacer condition si vient du script decrit dans ligne suivante
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
            <tr><th></th>";
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
        echo '</table> <br /> <h2>Voir mes réservations pour une journée donné </h2>';

        echo"<form action=\"#\" name=\"\" id=\"\" method=\"post\" accept-charset=\"utf-8\">   ".
        "<label>Date <input type=\"date\" required name=\"dateReserv\" id=\"dateReservVoir\" ";

        if(isset($_POST["submitReserv"])){
            echo 'value='.$_POST["dateReserv"];//afin d'afficher la date donner par l'utilisateur
        }
       echo "/>  </label>   ".
        "<label>  <input type=\"submit\" name=\"submitReserv\" value='Voir les réservations' /> </label> ".
         "<input type='hidden' value=\"$username\" id='username' name='username'/>".
         "<input type='hidden' value=\"$password\" id='password' name='password'/>".
        "</form>";


        if(isset($_POST["submitReserv"])){//donc on a cliquer sur le submit pour voir les reservations
            $query = 'select terrain,date_reservation,heure_reservation from Reservation where nom='.$nom.' and prenom='.$prenom.' and date_reservation='.$_POST["dateReserv"];
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

        echo '<h2>Annulez une réservation</h2>
        <br /> <form action="#" name="" id="" method="post" accept-charset="utf-8"> 
        <label>Numéro de Terrain <input type="number" name="numTerrAnnul" id="numTerrAnnul" required min="1" max="5" value="1"/>  </label>   
        <label>Date <input type="date" name="dateAnnul" id="dateAnnul" required/>  </label>    
        <label> Heure:<input type="number" name="heureAnnul" id="heureAnnul" step=\'any\' min="06" max="21" value=\'06\' required/> </label> 
        <br />
        <label>   <input type="submit" name="submitAnnul" value="Annuler la réservation"/> </label> 
        <input type=\'hidden\' value=\'\''.$username.'id=\'usernameAnnul\' name=\'username\'/>
        <input type=\'hidden\' value=\'\''.$password.'id=\'passwordAnnul\' name=\'password\'/>
        </form> <br/>';

        echo '<h2>Faire une réservation</h2>
        <br /> <form action="#" name="" id="faitReserv" method="post" accept-charset="utf-8"> 
        <label>Numéro de Terrain <input type="number" name="numTerrReserv" id="numTerrReserv" required min="1" max="5" value="1"/>  </label>   
        <label>Date <input type="date" name="dateReserv" id="dateReserv"  required/>  </label>    
        <label> Heure:<input type="number" name="heureReserv" id="heureReserv" step=\'any\' min=\"06\" max=\"21\" value=\'06\' required/> </label> 
        <br /><label>   <input type="submit" name="submitFaitReserv" value="Faire la réservation"/> </label> 
        <input type=\'hidden\' value=\'\''.$username.'id=\'usernameReserv\' name=\'username\'/>
        <input type=\'hidden\' value=\'\''.$password.'id=\'passwordReserv\' name=\'password\'/>
        </form> <br/>';




        if ( $liste || $listeReserv||$listeTerrainDispo || estGerant()){
            echo "     <h2>Lister les joueurs du clubs</h2>
        <form action=\"#\" name=\"\" id='formListeJoueur' method=\"post\" accept-charset=\"utf-8\">
            <label>Lister les joueurs du club en ordre
            <select name='filtre'>
                <option value='croissantNom'>Croissant selon le nom</option>
                <option value='decroissantNom'>Decroissant selon le nom</option>
                <option value='croissantPrenom'>Croissant selon le prénom</option>
                <option value='decroissantPrenom'>Decroissant selon le prénom</option>
            </select>
            </label>
           
            <input type=\"submit\" id=\"submitListe\" name='submitListe' value=\"choisir le filtre\"/>
        </form> <br />";
            if ($liste){
                $query;
                if (strcmp($_POST["filtre"],"croissantNom")==0){
                    $query = "select * from Joueur order by nom asc";
                }
                else if (strcmp($_POST["filtre"],"decroissantNom")==0){
                    $query = "select * from Joueur order by nom desc";

                }
                else if (strcmp($_POST["filtre"],"croissantPrenom")==0){
                    $query = "select * from Joueur order by prenom asc";
                }
                else if (strcmp($_POST["filtre"],"decroissantPrenom")==0){
                    $query = "select * from Joueur order by prenom desc ";
                }

                imprimeTable($query,'aucun joueur');
            }

            echo '<h2>Lister les terrains réservés aujourd\'hui</h2>
            <form action="#" name="" id="formListeTerrain" method="post" accept-charset="utf-8">
                <select name=\'filtre\'>
                    <option value=\'croissantNom\'>Croissant selon le nom</option>
                    <option value=\'decroissantNom\'>Decroissant selon le nom</option>
                    <option value=\'croissantPrenom\'>Croissant selon le prénom</option>
                    <option value=\'decroissantPrenom\'>Decroissant selon le prénom</option>
                    <option value="croissantTerrain">Croissant selon le numéro du terrain</option>
                    <option value="decroissantTerrain">Decroissant selon le numéro du terrain</option>
                    <option value="croissantHeure">Croissant selon l\'heure</option>
                    <option value="decroissantHeure">Decroissant selon l\'heure</option>
                </select>
                <input type="submit" id="submitListeTerrain" name="submitListeTerrain" value="choisir le filtre"/>
            </form> <br />';
            if($listeReserv){
                $date = (new DateTime())->format("Y-m-d");
                $query;
                if (strcmp($_POST["filtre"],"croissantNom")==0){
                    $query = "select * from Reservation where date_reservation=$date order by nom asc";
                }
                else if (strcmp($_POST["filtre"],"decroissantNom")==0){
                    $query = "select * from Reservation where date_reservation=$date order by nom desc";

                }
                else if (strcmp($_POST["filtre"],"croissantPrenom")==0){
                    $query = "select * from Reservation where date_reservation=$date order by prenom asc";
                }
                else if (strcmp($_POST["filtre"],"decroissantPrenom")==0){
                    $query = "select * from Reservation where date_reservation=$date order by prenom desc ";
                }
                else if (strcmp($_POST["filtre"],"croissantTerrain")==0){
                    $query = "select * from Reservation where date_reservation=$date order by terrain asc ";
                }
                else if (strcmp($_POST["filtre"],"decroissantTerrain")==0){
                    $query = "select * from Reservation where date_reservation=$date order by terrain desc ";
                }
                else if (strcmp($_POST["filtre"],"croissantHeure")==0){
                    $query = "select * from Reservation where date_reservation=$date order by heure_reservation asc";
                }
                else if (strcmp($_POST["filtre"],"decroissantHeure")==0){
                    $query = "select * from Reservation where date_reservation=$date order by heure_reservation desc";
                }

                imprimeTable($query,'aucune reservation aujourd\'hui');
            }

            echo '<form action="# id="formListeTerrDispo" method="post" accept-charset="utf-8">
                <label>Heure de debut d\'intervalle
                    <input type="number" value="6" min="6" max="21" step="1" name="min" required/>h
                </label>
                <label> Heure de debut d\'intervalle
                    <input type="number" value="6" min="6" max="21" step="1" name="max" required />h
                </label>
                
                <input type="submit" name="submitListeTerrDispo" id="submitListeTerrDispo" value="">
                </form>';

            if ($listeTerrainDispo){
                $query = 'select numero,heure from Terrain,Heure where heure not in (select heure_reservation from Reservation where terrain = Terrain.numero and date_reservation= CURDATE()) and Heure.heure >='.$_POST["min"].' and Heure.heure <='.$_POST["max"].' order by Terrain.numero, Heure.heure';
                $res = mysqli_query($connect,$query);
                if (mysqli_num_rows($res) >0) {
                    echo '<table> <thead><tr><th>Terrain</th> <th>heure</th></tr></thead><tbody>';

                    while ($ligne = mysqli_fetch_assoc($res)) {
                        echo '<tr><td>' . $ligne["numero"] . '</td><td>' . $ligne["heure"] . '</td></tr>';
                    }
                    echo '</tbody>';
                }
                else{
                    echo 'aucun terrain disponible aujourd\'hui';
                }
                //imprimeTable($query,'aucun terrain de ')
            }
//select numero,heure from Terrain,Heure where heure not in (select heure_reservation from Reservation where terrain = Terrain.numero and date_reservation= CURDATE()) and Heure.heure >= 6 and Heure.heure <=21 order by Terrain.numero, Heure.heure;




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
