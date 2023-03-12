welcome back sir (-_-)
<?php
if(empty($_SESSION['name'])){
    session_start();
    #echo 'hello ' + $_SESSION['name'];
}else{
    die('are you admin but ask to login !!');
    die('Are you a hacker?!!');
}

?>