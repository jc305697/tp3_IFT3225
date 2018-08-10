<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>Inscription</title>
    <link rel="stylesheet" href="style.css"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script type="text/javascript" src="afficheForm.js"></script>
</head>
<body>
<?php
    if (isset($_GET["erreur"])){
        echo '<h2 class="erreur">Le login est déja utilisé </h2>';
    }
?>
<form action="inscription.php" name="inscription" id="inscriptionForm" method="post" accept-charset="utf-8">
    <label for="usernameInscr"> Nom d'utilisateur: </label>
    <input type="text" name="username" id="usernameInscr" placeholder="nom d'utilisateur" required="required"/>
    <br />
    <label for="prenom">Prénom</label>
    <input type="text" name="prenom" id="prenom" placeholder="prénom" required="required"/>
    <br />
    <label for="nom">Nom de famille:</label>
    <input type="text" name="nom" id="nom" placeholder="nom de famille" required="required"/>
    <label for="password" >Mot de passe:</label>
    <input type="password" name="password" id="password" placeholder="mot de passe" required="required"/> <br />
    <label for="passwordConf" >Confirmation mot de passe:</label>
    <input type="password" name="password" id="passwordConf" placeholder="Confirmation mot de passe" required="required"/> <br />
    <input type="submit" name="submit" id="submit1" value="soumettre" />
    <br />
    <input type="hidden" name="sour"value="source"/>
</form>
</body>
</html>
