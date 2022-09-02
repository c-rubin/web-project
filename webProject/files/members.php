<?php
    session_start();
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
    function loadMembers(){
        $DBuser = "root";
        $DBpw = "";
        $DBname = "web_project_2022";
        $DBconn = new mysqli('localhost',$DBuser,$DBpw,$DBname);//sql connection

        if ($DBconn->connect_error){
            die("Connection failed: " . $conn->connect_error);
        }else{
            //echo "Connected successfully to the database";
            $sql = "SELECT * FROM users ORDER BY date DESC;";
            try{
                $result = $DBconn -> query($sql);
                echo "<table><tr><th>Profile Picture</th><th>Username</th><th>Posts</th><th>Date Joined</th></tr>";
                while($row = $result->fetch_assoc()){
                    $sql = "SELECT * FROM posts WHERE username = '".$row["username"]."'";
                    $result2 = $DBconn -> query($sql);
                    echo "<tr><td><a href='".$row["photo"]."' target='_blank'><img width='50px' height='50px' src='".$row["photo"]."'></a></td><td><a href='profile.php?user=".$row["username"]."'>".$row["username"]."</a></td><td>".$result2->num_rows."</td><td>".$row["date"]."</td><tr>";
                    //if($row["username"]==$_SESSION["username"])
                }
                echo"</table>";

            }catch(Exception $ex){
                echo "<h1 class='errorHeader'>Error: ".$ex->getMessage()."</h1><br><a class='Btn' href='homepage.php'>Return</a>";
            }
        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Members</title>
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

        <?php loadMembers(); ?>

    </body>
</html>