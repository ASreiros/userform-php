<?php

if(array_key_exists("PHPSESSID", $_COOKIE)){
    session_start();   
    session_destroy();
    setcookie("PHPSESSID", "", time()-3600);
}
header('Location: ./login.php');

?>