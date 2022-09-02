<?php
    session_start();
    //if(!isset($_SESSION["photo"]))echo "AAAA";
    if(!isset($_SESSION["username"]) || $_SESSION["username"]=="" || !isset($_SESSION["password"]) || $_SESSION["password"]==""){
        header("Location: login.php?error=unauthorised");
		exit();
    }else{
        $user = $_SESSION["username"];
        $pw = $_SESSION["password"];
    }
    if(isset($_POST["postText"]) && $_POST["postText"]!=""){
        uploadPost($_POST["postText"]);
    }
    if(isset($_GET["LogOut"]) && $_GET["LogOut"]=="yes"){
        session_destroy();
        removeCookies();
        header("Location: login.php");
		exit();
    }
    function removeCookies(){
        setcookie("fname","",time()-3600);//set cookie to past time (1 hour before)
        setcookie("lname","",time()-3600);
        setcookie("email","",time()-3600);
        setcookie("username","",time()-3600);
        setcookie("password","",time()-3600);
    }
    function loadPosts(){
        $DBuser = "root";
        $DBpw = "";
        $DBname = "web_project_2022";
        $DBconn = new mysqli('localhost',$DBuser,$DBpw,$DBname);//sql connection

        if ($DBconn->connect_error){
            die("Connection failed: " . $conn->connect_error);
        }else{
            //echo "Connected successfully to the database";
            $sql = "SELECT * FROM posts ORDER BY date DESC;";
            try{
                $result = $DBconn -> query($sql);
                if($result->num_rows>0){//there exists at least 1 post
                    echo "<p class='info'>Posts from other users</p><br>";
                    while($row = $result->fetch_assoc()){
                        echo "<p class='info'>".$row["date"]." - <a href='profile.php?user=".$row["username"]."'>".$row["username"]."</a>: ".$row["body"]."</p><br>";
                        //if($row["username"]==$_SESSION["username"])
                    }

                }
                else{
                    echo "<p class='info'>There are no posts yet :(</p><br>";
                }
            }catch(Exception $ex){
                echo "<h1 class='errorHeader'>Error: ".$ex->getMessage()."</h1><br><a class='Btn' href='homepage.php'>Return</a>";
            }
        }
    }
    function uploadPost($body){
        $DBuser = "root";
        $DBpw = "";
        $DBname = "web_project_2022";
        $DBconn = new mysqli('localhost',$DBuser,$DBpw,$DBname);//sql connection

        $body = $DBconn -> real_escape_string($body);

        if ($DBconn->connect_error){
            die("Connection failed: " . $conn->connect_error);
        }else{
            //echo "Connected successfully to the database";
            $sql = "INSERT INTO posts VALUES(NULL,'".$body."','".$_SESSION["username"]."');";
            try{
                if($result = $DBconn -> query($sql)){
                    header("Location: homepage.php");
                    exit();
                }
                else echo $DBconn->error;
            }catch(Exception $ex){
                echo "<h1 class='errorHeader'>Error: ".$ex->getMessage()."</h1><br><a class='Btn' href='homepage.php'>Return</a>";
            }
        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Homepage</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <div class="menuDiv">
            <img width="50px" height="50px" src="<?php echo $_SESSION["photo"]; ?>">
            <a class="divLink" href="profile.php">Profile</a>
            <a class="divLink" href="homepage.php">Homepage</a>
            <a class="divLink" href="messages.php">Messages</a>
            <a class="divLink" href="members.php">Members</a>
            <a class="divLink" href="homepage.php?LogOut=yes">Log out</a>
            <a class="divLink" href="contact.php">Contact</a>
        </div><br>
        <?php echo "<h1> Welcome ".$_SESSION["username"]."!"; ?>
        <form method="POST">
            <label for="postText" class="info">Post something: </label>
            <input type="text" name="postText" id="postText">
            <input type="submit" value="Post" ><br>
        </form>

        <?php loadPosts(); ?>

    </body>
</html>