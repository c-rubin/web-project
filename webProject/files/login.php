<?php
    session_start();
    //check if already logged in
    if(isset($_SESSION["username"]) && $_SESSION["username"]!="" && isset($_SESSION["password"]) && $_SESSION["password"]!=""){
        header("Location: homepage.php");
		exit();
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Login</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <div class="center">
            <h1>Login</h1>
            <p id="errorPara" class="errorHeader"></p>
            <form id="formi" action="login_check.php" method="post">
                <label for="user">Username:</label><br>
                <input type="text" id ="user" name="user" ><br>
                <label for="pw">Password:</label><br>
                <input type="password" id ="pw" name="pw" ><br>

                <input type="button" value="Login" onclick="validate()"><br>
            </form>
            <p><a href="registration.php">Create a new account.</a></p><br>
        </div>
    </body>
    <script>
        function validate(){
            var para = document.getElementById("errorPara")
            var user = document.getElementById("user").value;
            var pw = document.getElementById("pw").value;
            if(user == null || user == ""){
                para.innerHTML="Enter username!";
                return false;
            }else if(pw == null || pw == ""){
                para.innerHTML="Enter password!";
                return false;
            }else{
                document.getElementById("formi").submit();
            }
        }
    </script>
</html>