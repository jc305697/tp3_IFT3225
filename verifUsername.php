<?php if(isset($_GET['source'])) die(highlight_file(__FILE__,1)); ?>

<?php
 require "opendb.php";

 $username = $_POST["username"];

    $res  = mysqli_query($connect,"SELECT * FROM Joueur WHERE username='$username'");
    if (mysqli_num_rows($res)==0){
        echo "true";
    }
    else{
        echo "false";
    }

 require "closedb.php";

?>