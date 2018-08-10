<!DOCTYPE html> 
<html> 
        <head> 
                <meta charset="utf-8"/> 
                <title>Login</title> 
                <link rel="stylesheet" href="style.css"/>
        </head> 
        <body>
            <?php
            if (isset($_GET["erreurLogin"])){
                echo '<h2 class="erreur">Nom d\'utilisateur ou mot de passe incorect</h2>';
            }
            ?>
                <form action="login.php" name="login" method="post" accept-charset="utf-8">  
                        <label for="username"> Nom d'utilisateur: </label>
	     		 <input type="text" name="username" id="username" placeholder="nom d'utilisateur" required="required"/>
		       	<br /> 
                        <label for="password" >  Mot de passe:</label> 
			       	<input type="password" name="password" id="password" placeholder="mot de passe" required="required"/> <br /> 
                        <input type="submit" name="submit" id="submit1" value="soumettre" />
                        <br /> 
                        <a href="ecranInscription.php">s'inscrire</a>
			<input type="hidden" name="sour"value="source"/>	
                </form> 
        </body> 
</html>
