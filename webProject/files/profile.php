<?php
    session_start();//start session

    if(!isset($_SESSION["username"]) || $_SESSION["username"]=="" || !isset($_SESSION["password"]) || $_SESSION["password"]==""){
        header("Location: login.php?error=unauthorised");
		exit();
    }else{
        $user = $_SESSION["username"];
        $pw = $_SESSION["password"];
    }
    function write($name){
        return $_SESSION["sql"][$name];
    }
    function isCurrentUser(){
        return ( !isset($_GET["user"]) || $_GET["user"]=="" || $_GET["user"]==$_SESSION["username"] );
    }
    function writeOther($name){
        $DBuser = "root";
        $DBpw = "";
        $DBname = "web_project_2022";
        $DBconn = new mysqli('localhost',$DBuser,$DBpw,$DBname);//sql connection

        if ($DBconn->connect_error){
            die("Connection failed: " . $conn->connect_error);
        }else{
            //echo "Connected successfully to the database";
            $sql = "SELECT * FROM users WHERE username = '".$_GET["user"]."';";
            try{
                $result = $DBconn -> query($sql);
                $row = $result->fetch_assoc();
                return $row[$name];

            }catch(Exception $ex){
                echo "<h1 class='errorHeader'>Error: ".$ex->getMessage()."</h1><br><a class='Btn' href='profile.php'>Return</a>";
            }
        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php if (isCurrentUser()) echo "My Profile";else echo $_GET["user"];?></title>
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
        <h1><?php if (isCurrentUser()) echo "My Profile";else echo $_GET["user"];?></h1><br>
        <form action="update.php?First=1">
            <a href="<?php if(isCurrentUser()) echo $_SESSION["photo"];else echo writeOther('photo'); ?>" target="_blank"><img width="200px" height="200px" src="<?php if(isCurrentUser()) echo $_SESSION["photo"];else echo writeOther('photo'); ?>"></a><br>
            <label for="date">Date Joined:</label><br>
            <input type="text" id ="date" name="date" value="<?php if(isCurrentUser()) echo write('date');else echo writeOther('date'); ?>" disabled ><br>
            <label for="fname">First name:</label><br>
            <input type="text" id ="fname" name="fname" value="<?php if(isCurrentUser()) echo write('fname');else echo writeOther('fname'); ?>" disabled ><br>
            <label for="lname">Last name:</label><br>
            <input type="text" id ="lname" name="lname" value="<?php if(isCurrentUser()) echo write('lname');else echo writeOther('lname'); ?>" disabled ><br>
            <!--<label for="bday">Birthdate:</label><br>
            <input type="text" id ="bday" name="bday" value="" disabled ><br>

            <label for="title">Title</label>
            <select id="title" disabled>
                <option></option>
            </select><br>-->

            <label for="email" >E-Mail:</label><br>
            <input type="text" id ="email" name="email" value="<?php if(isCurrentUser()) echo write('email');else echo writeOther('email'); ?>" disabled ><br>
            <label for="user">Username:</label><br>
            <input type="text" id ="user" name="user" value="<?php if(isCurrentUser()) echo $user;else echo writeOther('username'); ?>" disabled ><br>
            <?php if(isCurrentUser()){echo '<label for="pw">Password:</label><br>
            <input type="text" id ="pw" name="pw" value="'.$pw.'" disabled ><br>
            
            <a href="update.php?First=1"><input type="button" value="Update"></a><br>';}?>
            
            <!--<a href="profile.php?Return=yes"><input type="button" value="Return"></a><br>-->
            
        </form>
    </body>
</html>