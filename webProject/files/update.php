<?php
    session_start();//start session

    if(!isset($_SESSION["username"]) || $_SESSION["username"]=="" || !isset($_SESSION["password"]) || $_SESSION["password"]==""){
        header("Location: login.php?error=unauthorised");
		exit();
    }else{
        $user = $_SESSION["username"];
        $pw = $_SESSION["password"];

        $updateChosen = false;
        $sql = "UPDATE users";//update info

        updateSql("fname");
        updateSql("lname");
        updateSql("email");
        updateSql("username");
        updateSql("password");

        //if delete picture selected
        if(isset($_GET["photo"]) && $_GET["photo"]=="null"){
            $sql = "UPDATE users set photo='nullProfilePic.png' WHERE username='".$_SESSION["username"]."';";
            $DBuser = "root";
            $DBpw = "";
            $DBname = "web_project_2022";
            $DBconn = new mysqli('localhost',$DBuser,$DBpw,$DBname);//sql connection

            if ($DBconn->connect_error){
                die("Connection failed: " . $conn->connect_error);
            }else{
                if($DBconn->query($sql)===true){
                    $_SESSION["photo"] = "nullProfilePic.png";
                    header("Location: update.php");
                    exit();
                    //echo "B";
                }
                else{
                    header("Location: update.php?UpdateError=Error");
                    exit();
                    //echo "Baaa";
                }
            }
        }

        if($updateChosen==true){
            $sql = $sql." WHERE username='".write("username")."';";
            $DBuser = "root";
            $DBpw = "";
            $DBname = "web_project_2022";
            $DBconn = new mysqli('localhost',$DBuser,$DBpw,$DBname);//sql connection

            if ($DBconn->connect_error){
                die("Connection failed: " . $conn->connect_error);
            }else{
                if($DBconn->query($sql)===true){
                    $sql = "SELECT * FROM users WHERE username = '".$user."' AND password = '".$pw."';";

                    try{
                        $result = $DBconn -> query($sql);
                        if($result->num_rows>0){//user exists
                            $row = $result->fetch_assoc();
                            $_SESSION["username"] = $user;
                            $_SESSION["password"] = $pw;
                            $_SESSION["sql"] = $row;

                            header("Location: homepage.php");
                            exit();
                        }else{
                            echo "This user doesn't exist!";
                        }
                    }catch(Exception $ex){
                        echo "Error: ".$ex->getMessage();
                    }
                }
                else{
                    header("Location: update.php?UpdateError=Error");
                    exit();
                }
            }
        }else if(!isset($_GET["First"])){
            header("Location: profile.php");
            exit();
        }

        

    }
    
    function write($name){
        //if($name=="user")return $_SESSION["sql"]["username"];
        //if($name=="pw")return $_SESSION["sql"]["password"];
        return $_SESSION["sql"][$name];
    }

    function updateSql($name){
        if(isset($_GET[$name]) && $_GET[$name]!=""){
            global $updateChosen,$sql,$user,$pw;
            $updateChosen=true;
            if($sql!="UPDATE users")$sql = $sql." AND";
            $sql=$sql." SET ".$name."='".$_GET[$name]."'";

            if($name=="username")$user = $name;
            if($name=="password")$pw = $name;

            setcookie($name,$_GET[$name],time()+(60*5));//set cookie 5 minutes
        }
    }

    function checkUpdateError(){
        if(isset($_GET["UpdateError"]) && $_GET["UpdateError"]=="Error"){
            echo "There was an error during the update operation!";
        }
    }

    function writeCookie($name){
        if(isset($_COOKIE[$name]))return $_COOKIE[$name];
        else return "";
    }
    
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Update information</title>
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
        <h1>Update information</h1><?php checkUpdateError(); $sql; ?>
        <form action="update.php" method="get">
            <label for="fname">First name:</label><br>
            <input type="text" id ="fname" name="fname" value="<?php echo writeCookie("fname"); ?>"><br>
            <label for="lname">Last name:</label><br>
            <input type="text" id ="lname" name="lname" value="<?php echo writeCookie("lname"); ?>"><br>
            <!--<label for="bday">Birthdate:</label><br>
            <input type="text" id ="bday" name="bday"><br>

            <label for="title">Title</label>
            <select id="title" name="title">
                <option value="Miss">Miss</option>
                <option value="Mr">Mr</option>
                <option value="Dr">Dr</option>
            </select><br>-->

            <label for="email">E-Mail:</label><br>
            <input type="text" id ="email" name="email" value="<?php echo writeCookie("email"); ?>"><br>
            <label for="user">Username:</label><br>
            <input type="text" id ="user" name="username" value="<?php echo writeCookie("username"); ?>"><br>
            <label for="pw">Password:</label><br>
            <input type="text" id ="pw" name="password" value="<?php echo writeCookie("password"); ?>"><br>
            <!--<label for="cpw">Confirm Password:</label><br>
            <input type="text" id ="cpw" name="cpw"><br>-->
            <input type="submit" value="Save"><br>
            <a href="uploadPhoto.php?visit=yes"><input type="button" value="Change profile picture"></a>
            <a href="update.php?photo=null"><input type="button" value="Delete profile picture"></a><br>
            <a href="homepage.php"><input type="button" value="Back"></a><br>
            
        </form>


        
    </body>
</html>