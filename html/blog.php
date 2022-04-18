<?php require './parts/head.php'; ?>
<?php require_once './parts/dblogin.php'; ?>
<body>
    <header>
        <nav>
            <a href="../index.php">Home</a>
            <a href="./login.php">Sign in</a>
            <a href="./login.php">Log in</a>
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
        <h1>Blog</h1>
    </header>
    <main class="flex-container">

        <?php

            if((array_key_exists("PHPSESSID", $_COOKIE))||(session_status()=== PHP_SESSION_ACTIVE)){
            if(session_status()!== PHP_SESSION_ACTIVE){
                    session_start();
            }
                if(array_key_exists("user", $_SESSION)){
                        $writer=$_SESSION["user"];
                     $dbh=heroku_connect();
                     $atsakymas =  $dbh->prepare("select title, content, published_at, id from  blog where user_id = ?;");
                     $atsakymas->bindParam(1,$writer);
                     $reply = $atsakymas->execute();
                     //logv($reply);
                     // reply duoda true or false ar sintaksiskai teisingai
                     $info = $atsakymas->fetchAll();
                     //info duoda arr arba false
                     
                     $createcounter=0;
                     $sorter=1;

                    if(isset($_POST['create-btn'])){
                        $createcounter=1;
                    }

                    if(isset($_POST['Newontop'])){
                        $sorter=1;
                    }

                    if(isset($_POST['Oldontop'])){
                        $sorter=2;
                    }

                    if(isset($_POST['delete-btn'])){
                        if ($_SERVER["REQUEST_METHOD"]==="POST"){
                             $postid = $_POST["id"];
                             $atsakymas =  $dbh->prepare("delete  from blog where id=?");
                             $atsakymas->bindParam(1,$postid);
                             $reply = $atsakymas->execute();
                            

                             $writer=$_SESSION["user"];
                             $dbh=heroku_connect();
                             $atsakymas =  $dbh->prepare("select title, content, published_at, id from  blog where user_id = ?;");
                             $atsakymas->bindParam(1,$writer);
                             $reply = $atsakymas->execute();
                             $info = $atsakymas->fetchAll();
                        }    
                    }

                    if(isset($_POST['cancel-new-post-btn'])){
                        $createcounter=0;
                    }

                    if(isset($_POST['new-post-btn'])){
                        if ($_SERVER["REQUEST_METHOD"]==="POST"){
                            $posttitle=$_POST["posttitle"];
                            $postbody=$_POST["postbody"];
                            if((strlen($posttitle)>0)&&(strlen($postbody)>0)){
                                $date = date("Y-m-d");
                                $dbh= heroku_connect();
                                $atsakymas =  $dbh->prepare("insert into blog (user_id, title, content, published_at ) values (?, ?, ?, ?)");
                                $atsakymas->bindParam(1,$writer);
                                $atsakymas->bindParam(2,$posttitle);
                                $atsakymas->bindParam(3,$postbody);
                                $atsakymas->bindParam(4,$date);
                                $reply = $atsakymas->execute();

                                $writer=$_SESSION["user"];
                                $dbh=heroku_connect();
                                $atsakymas =  $dbh->prepare("select title, content, published_at, id from  blog where user_id = ?;");
                                $atsakymas->bindParam(1,$writer);
                                $reply = $atsakymas->execute();
                                $info = $atsakymas->fetchAll();
                                       

                            } else{
                                echo "
                                <p> You need to write title and body of post to create post</p>
                                ";
                            }

                        }
                        
                    }

                    if(isset($_POST['edit-btn'])){
                        if ($_SERVER["REQUEST_METHOD"]==="POST"){
                            $editid = $_POST["id"];
                            loge($editid);

                            $dbh=heroku_connect();
                            $atsakymas =  $dbh->prepare("select title, content from  blog where id = ?;");
                            $atsakymas->bindParam(1,$editid);
                            $reply = $atsakymas->execute();
                            $editinfo = $atsakymas->fetch();
                            $edittitle=$editinfo["title"];
                            $editcontent=$editinfo["content"];
                            

                            $createcounter=2;
                              echo "
                                    <div class='modalcreate'>
                                        <form method='post' class='create-form'>
                                            <input placeholder='title' class='create-title' type='text' name='editposttitle' value='$edittitle'>
                                            <textarea placeholder='your text here' class='create-text' rows='8' cols='50' name='editpostbody'>$editcontent</textarea>
                                            <input type='hidden' name='editid' value='$editid'>
                                            <div>
                                              <input type='submit' class='btn' name='edit-post-btn' value='Edit'>
                                              <input type='submit' class='btn' name='cancel-new-post-btn' value='Cancel'>
                                            </div>
                                        </form>
                                    </div>    
                              ";

                       }
                        

                    }


                    if(isset($_POST['edit-post-btn'])){
                        if ($_SERVER["REQUEST_METHOD"]==="POST"){
                             $editid = $_POST["editid"];
                             $edittitle = $_POST["editposttitle"];
                             $editcontent = $_POST["editpostbody"];


                              $atsakymas =  $dbh->prepare("update blog set title = ?, content = ? where id = ?");
                              $atsakymas->bindParam(1,$edittitle);
                              $atsakymas->bindParam(2,$editcontent);
                              $atsakymas->bindParam(3,$editid);
                              $reply = $atsakymas->execute();
                            

                              $writer=$_SESSION["user"];
                              $dbh=heroku_connect();
                              $atsakymas =  $dbh->prepare("select title, content, published_at, id from  blog where user_id = ?;");
                              $atsakymas->bindParam(1,$writer);
                              $reply = $atsakymas->execute();
                              $info = $atsakymas->fetchAll();
                        }    
                    }


                    if($createcounter===1){
                        echo '
                            <div class="modalcreate">
                                <form method="post" class="create-form">
                                    <input placeholder="title" class="create-title" type="text" name="posttitle">
                                    <textarea placeholder="your text here" class="create-text" rows="8" cols="50" name="postbody"></textarea>
                                    <div>
                                        <input type="submit" name="new-post-btn" value="Save">
                                        <input type="submit" name="cancel-new-post-btn" value="Cancel">
                                    </div>
                                </form>
                            </div>    
                        ';
                    }    


                    function sortdatenew($Arr){
                        $length = count($Arr);
                        
                        if($length>1){
                            $valueholder = $Arr[0];
                            for ($i=0; $i < $length; $i++) { 
                                for ($y=1; $y < $length; $y++) { 
                                    if($Arr[$y]["published_at"]>$Arr[$y-1]["published_at"]){
                                        $valueholder = $Arr[$y-1];
                                        $Arr[$y-1] = $Arr[$y];
                                        $Arr[$y] = $valueholder;
                                    }
                                }
                            }
                        }
                        return $Arr;
                    }

                    function sortdateold($Arr){
                        $length = count($Arr);
                        
                        if($length>1){
                            $valueholder = $Arr[0];
                            for ($i=0; $i < $length; $i++) { 
                                for ($y=1; $y < $length; $y++) { 
                                    if($Arr[$y]["published_at"]<$Arr[$y-1]["published_at"]){
                                        $valueholder = $Arr[$y-1];
                                        $Arr[$y-1] = $Arr[$y];
                                        $Arr[$y] = $valueholder;
                                    }
                                }
                            }
                        }
                        return $Arr;
                    }


                    function showblog($obj){
                        echo "
                        <form class='blog-post' method='post'>
                            <h3>$obj[title]</h3>
                            <p class='post-body'>$obj[content]</p>
                            <input type='hidden' name='id' value='$obj[id]'>
                            <div class='control-post'>
                                <div>
                                    <input type='submit' class='edit-btn' name='edit-btn' value='Edit post'>
                                    <input type='submit' class='delete-btn' name='delete-btn' value='Delete post'>
                                </div>
                                <p class='postdate'>Posted on $obj[published_at]</p>
                            </div>    
                        </form>
                        ";
                    }


                    echo "<div  class='blog'>";
                    echo    '<form method="post">
                                <input type="submit" class="create-btn" name="create-btn" value="Create new post"> 
                                <div>
                                    <input type="submit" class="create-btn" name="Newontop" value="New on top">
                                    <input type="submit" class="create-btn" name="Oldontop" value="Old on top">
                                </div>
                            </form>';
                    if($createcounter===0){
                        if ($sorter === 1) {
                            $info = sortdatenew($info);
                        }
                        if ($sorter === 2) {
                            $info = sortdateold($info);
                        }
                        
                        array_map('showblog',$info);
                    }
                    echo "</div>";    

                     if(!$info){
                         echo "<p class='hello'>There are no posts found</p>";
                     }


                } else{
                    echo "
                    <div>
                        <h2 class=`announcement`>Please login to read the blog</h2>  
                    </div>
                    ";
                }
            }
        ?> 

    </main>
    <footer>

    </footer>
</body>
</html>    