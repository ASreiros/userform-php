<?php require './parts/head.php'; ?>
<?php require_once './parts/dblogin.php'; ?>
<body>
    <header>
        <nav>
            <a href="../index.php">Home</a>
            <a href="./blog.php">Blog</a>
            <a href="./login.php">Log in</a>
        </nav>
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
        <h1>Sign up</h1>
    </header>
    <main>
        <form method="post">
            <h2>Sign up</h2>
            <div>
                <label for="iduser"></label>
                <input required id="iduser" maxlength="50" name="username" class="userinput" type="text" placeholder="Username">
            </div>
            <div>
                <label for="idemail"></label>
                <input required id="idemail" maxlength="50" name="email" class="userinput" type="email" placeholder="email">
            </div>
            <div>
                <label for="idpassword"></label>
                <input required id="idpassword" maxlength="50" name="passwrd" class="userinput" type="password" placeholder="password">
            </div>
            <div>
                <label for="idpasswordrepeat"></label>
                <input required id="idpasswordrepeat" maxlength="50" name="passwrdrep" class="userinput" type="password" placeholder="repeat password">
            </div>
            <button type="submit">Submit</button>

            <?php

                function loge($message){
                    echo "<br> $message";
                }

                function logv($message){
                    echo "<br>";
                    var_dump ($message);
                    echo "<br>";
                }
                    
                if ($_SERVER["REQUEST_METHOD"]==="POST"){
                    $username=$_POST["username"];
                    $userpassword=$_POST["passwrd"];
                    $usepasswordrep=$_POST["passwrdrep"];
                    $useremail=$_POST["email"];

                    if(($userpassword===$usepasswordrep)&&(strlen($username)>3)){
             
                        $dbh= heroku_connect();
                        $atsakymas =  $dbh->prepare("select Username from  users where Username = ?");
                        $atsakymas->bindParam(1,$username);
                        $reply = $atsakymas->execute();
                        //logv($reply);
                        // reply duoda true or false ar sintaksiskai teisingai
                        $info = $atsakymas->fetch();
                        //info duoda arr arba false
                        if($info !== false){
                            echo  "<p class='hello'>Toks Username jau egzistuoja</p>";
                        } else{
                            $atsakymas =  $dbh->prepare("select email from  users where email = ?");
                            $atsakymas->bindParam(1,$useremail);
                            $reply = $atsakymas->execute();
                            $info = $atsakymas->fetch();
                            if($info){
                                echo  "<p class='hello'>Vartuotojas su tokiu email jau egzistuoja</p>";
                            } else{
                                $atsakymas =  $dbh->prepare("insert into users (Username, email, Passwrd ) values (?, ?, ?)");
                                $atsakymas->bindParam(1,$username);
                                $atsakymas->bindParam(2,$useremail);
                                $atsakymas->bindParam(3,$userpassword);
                                $reply = $atsakymas->execute();
                                $info = $atsakymas->fetch();
                                 echo "<p class='hello'>Great success, vartuotojas  <span class='bluetext'>$username</span>  sukurtas</p>";
                                    
                                }

                        } 
                                

                    }   else {
                         echo "<p class='hello'>Username turi būti ilgesnis nei 3 simboliai, o slaptažodžiai turi atitikti</p>";
                        }
                    


                } 
            ?>    
        </form>
    </main>
    <footer>

    </footer>
</body>
</html>