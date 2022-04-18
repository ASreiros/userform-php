<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/global.css">
    <link rel="stylesheet" href="./css/login.css">
    <title>Userform</title>
</head>
<body>
    <header>
        <nav>
            <a href="./html/login.php">Log in</a>
            <a href="./html/blog.php">Blog</a>
            <a href="./html/signin.php">Sign up</a>
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
                        <button><a href='./html/logout.php'>Logout</a></button>
                    </div>
                    ";
                }
            }
        ?>  

        <h1>Users</h1>
    </header>
    <main>
        
    </main>
</body>
</html>