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
        <title>Welcome!</title>
    </head>
    <link rel="stylesheet" href="style.css">
    <body>
        <div class="center">
        <h1>Welcome to my website!</h1>
        <p class="info">Login or Register to continue.</p><br>
        <a class="Btn" href="login.php">
            Login
        </a>
        <a class="Btn" href="registration.php">
            Register
        </a>
        </div>
    </body>
</html>