<?php

function heroku_connect(){
    $user='be6b6a79600a03';
    $pass='149695ef';
   return new PDO('mysql:host=us-cdbr-east-05.cleardb.net;dbname=heroku_88c28e41cd8a7ed', $user, $pass);
}