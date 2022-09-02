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
    if(isset($_GET["return"]) && $_GET["return"]=="yes"){
        unset($_SESSION["msgUser"]);
        unset($_POST["msgUser"]);
        unset($_POST["msgText"]);
    }if(!isset($_POST["msgUser"]) && isset($_SESSION["msgUser"])){
        $_POST["msgUser"]=$_SESSION["msgUser"];
    }if(isset($_POST["msgText"]) && $_POST["msgText"]!=""){
        uploadMsg($_POST["msgText"]);
    }
    


    function userExists(){
        $DBuser = "root";
        $DBpw = "";
        $DBname = "web_project_2022";
        $DBconn = new mysqli('localhost',$DBuser,$DBpw,$DBname);//sql connection

        if ($DBconn->connect_error){
            die("Connection failed: " . $conn->connect_error);
        }else{
            //echo "Connected successfully to the database";
            $sql = "SELECT * FROM users WHERE username='".$_POST["msgUser"]."';";
            try{
                $result = $DBconn -> query($sql);
                if($result->num_rows==0)return false;//user doesnt exist
                else{//user exists
                    if(!isset($_SESSION["msgUser"]))$_SESSION["msgUser"] = $_POST["msgUser"];
                    return true;
                }
            }catch(Exception $ex){
                echo "<h1 class='errorHeader'>Error: ".$ex->getMessage()."</h1><br><a class='Btn' href='homepage.php'>Return</a>";
            }

        }
    }
    function loadMsgs(){
        $DBuser = "root";
        $DBpw = "";
        $DBname = "web_project_2022";
        $DBconn = new mysqli('localhost',$DBuser,$DBpw,$DBname);//sql connection

        if ($DBconn->connect_error){
            die("Connection failed: " . $conn->connect_error);
        }else{
            $user1 = $_SESSION["username"];
            $user2 = $_POST["msgUser"];
            $sql = "SELECT * FROM messages WHERE (sender='".$user1."' AND receiver='".$user2."') OR (sender = '".$user2."' AND receiver = '".$user1."') ORDER BY date DESC;";
            try{
                $result = $DBconn -> query($sql);
                if($result->num_rows>0){
                    echo "<p class='info'>Messages with ".$user2.":</p><br>";
                    while($row = $result->fetch_assoc()){
                        if($row["sender"]==$user1){//if user sent it
                            echo "<p class='sentMsg'>".$row["date"]."<br>".$user1."<br>".$row["body"]."</p><br>";
                        }else{
                            echo "<p class='receivedMsg'>".$row["date"]."<br><a href='profile.php?user=".$user2."'>".$user2."</a><br>".$row["body"]."</p><br>";
                        }
                        //if($row["username"]==$_SESSION["username"])
                    }
                }else{echo "<p class='info'>You haven't exchanged any messages with ".$user2." yet!</p><br>";}
                
            }catch(Exception $ex){
                echo "<h1 class='errorHeader'>Error: ".$ex->getMessage()."</h1><br><a class='Btn' href='messages.php'>Return</a>";
            }
        }
    }
    function userSelected(){
        return ((isset($_POST["msgUser"]) && $_POST["msgUser"]!="") || (isset($_SESSION["msgUser"]) && $_SESSION["msgUser"]!="") || (isset($_POST["msgText"]) && $_POST["msgText"]!=""));
    }
    function uploadMsg($body){
        $DBuser = "root";
        $DBpw = "";
        $DBname = "web_project_2022";
        $DBconn = new mysqli('localhost',$DBuser,$DBpw,$DBname);//sql connection

        $body = $DBconn -> real_escape_string($body);

        if ($DBconn->connect_error){
            die("Connection failed: " . $conn->connect_error);
        }else{
            //echo "Connected successfully to the database";
            $sql = "INSERT INTO messages VALUES(NULL, '".$_SESSION["username"]."', '".$_SESSION["msgUser"]."','".$body."');";//date,sender,receiver,body
            try{
                if($result = $DBconn -> query($sql)){
                    header("Location: messages.php");
                    exit();
                }
                else echo $DBconn->error;
            }catch(Exception $ex){
                echo "<h1 class='errorHeader'>Error: ".$ex->getMessage()."</h1><br><a class='Btn' href='messages.php?return=yes'>Return</a>";
            }
        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Messages</title>
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
        <p id="errorPara" class="errorHeader" ><?php if(isset($_GET["error"]) && $_GET["error"]=="userNotFound") echo "This user doesn't exist!";?></p>
        <?php
            if( userSelected()){
                if(userExists()){
                    echo '<form id="msgSend" method="POST">
                    <label for="msgText" class="info">Send a message to '.$_POST["msgUser"].': </label>
                    <input type="text" name="msgText" id="msgText">
                    <input type="button" value="Send" onclick="validateMsgSend()" >
                    <a class="Btn" href="messages.php?return=yes">Back</a><br>
                </form>';
                    loadMsgs();
                }else{
                    header("Location: messages.php?error=userNotFound");
		            exit();
                }
            }else echo "<form id='msgSearch' action='messages.php' method='POST'>
            <label for='msgUser' class='info'>Enter username: </label>
            <input type='text' name='msgUser' id='msgUser'>
            <input type='button' value='Search' onclick='validateMsgSearch()' ><br>
        </form>";
            //^^ if username not given, give form^^

        ?>

    </body>
    <?php
        if(userSelected()){
            echo '<script>
            function validateMsgSend(){
                var msg = document.getElementById("msgText").value;
                if(msg== null || msg==""){
                    document.getElementById("errorPara").innerHTML = "Please enter a message!";
                }else if(msg.length >500)document.getElementById("errorPara").innerHTML = "Message can\'t contain more than 500 characters! This message contains "+msg.length+" characters!";
                else document.getElementById("msgSend").submit();
            }
        </script>';
        }else{echo '<script>
            function validateMsgSearch(){
                var user = document.getElementById("msgUser").value;
                if(user== null || user==""){
                    document.getElementById("errorPara").innerHTML = "Please enter the username of the person whom you want to send a message!";
                }else document.getElementById("msgSearch").submit();
            }
        </script>';}
    ?>
    
</html>