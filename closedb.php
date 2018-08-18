<?php if(isset($_GET['source'])) die(highlight_file(__FILE__,1)); ?>

<?php
	mysqli_close($connect) or die("probleme lors de la connexion".mysqli_error);
?>