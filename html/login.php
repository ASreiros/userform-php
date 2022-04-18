<?php require './parts/head.php'; ?>
<?php require_once './parts/dblogin.php'; ?>


<body>
    <header>

    <nav>
        <a href="../index.php">Home</a>
        <a href="./blog.php">Blog</a>
        <a href="./signin.php">Sign up</a>
    </nav>

<?php

function loge($message){
    echo "<br> $message";
}

function logv($message){
    echo "<br>";
    var_dump ($message);
    echo "<br>";
}

$counter=0;

if ($_SERVER["REQUEST_METHOD"]==="POST"){
    

    $username=$_POST["username"];
    $userpassword=$_POST["passwrd"];

    $dbh=heroku_connect();
    $atsakymas =  $dbh->prepare("select Username, Passwrd from  users where Username = ? and Passwrd = ?");
    $atsakymas->bindParam(1,$username);
    $atsakymas->bindParam(2,$userpassword);
    $reply = $atsakymas->execute();
    //logv($reply);
    // reply duoda true or false ar sintaksiskai teisingai
    $info = $atsakymas->fetch();
    //info duoda arr arba false
    if($info){
        echo  "<p class='hello'>Sveiki $username</p>";
        session_start();
        $_SESSION['user'] = $username;
        $counter = 1;
    } else{
        echo "<p class='hello'>prisijungti nepavyko</p>";
    }
}    

?>


    <?php

        if((array_key_exists("PHPSESSID", $_COOKIE))||(session_status()=== PHP_SESSION_ACTIVE)){
           if(session_status()!== PHP_SESSION_ACTIVE){
                session_start();
           }
            if(array_key_exists("user", $_SESSION)){
                echo "
                <div class='curuser'>
                    <p>Logged in as ${_SESSION['user']}</p>
                    <button><a href='./logout.php'>Logout</a></button>
                </div>
                ";
            }
        }
     ?>     

        
    </header>
    <main>
        <form method="post">
            <h2>Log in</h2>
            <div>
                <label for="iduser"></label>
                <input required maxlength="50" id="iduser" name="username" class="userinput" type="text" placeholder="Username">
            </div>
            <div>
                <label for="idpassword"></label>
                <input required maxlength="50" id="idpassword" name="passwrd" class="userinput" type="password" placeholder="password">
            </div>
            <button type="submit">Submit</button>


        </form>
    </main>
    <footer>

    </footer>
</body>
</html>