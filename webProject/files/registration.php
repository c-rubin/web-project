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
        <title>Registration</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <h1>Register</h1>
        <p id="errorPara" class="errorHeader"></p>
        <form id="formi" action="reg_check.php" method="post" enctype="multipart/form-data">
            <label for="fname">First name:</label><br>
            <input type="text" id ="fname" name="fname" ><br>
            <label for="lname">Last name:</label><br>
            <input type="text" id ="lname" name="lname" ><br>
            <label for="photo">Profile picture</label>
            <input type="file" id="fileToUpload" name="fileToUpload" ><br>
            <label for="email">E-Mail:</label><br>
            <input type="text" id ="email" name="email" ><br>
            <label for="user">Username:</label><br>
            <input type="text" id ="user" name="user" ><br>
            <label for="pw">Password:</label><br>
            <input type="password" id ="pw" name="pw" ><br>
            <label for="cpw">Confirm Password:</label><br>
            <input type="password" id ="cpw" name="cpw" ><br>

            <input type="button" value="Register" onclick="validate()"><br>
            
        </form>


        
        <p><a href="login.php">Already have an account? Log in.</a></p><br>
    </body>
    <script>
        function validate(){
            var para = document.getElementById("errorPara")
            var user = document.getElementById("user").value;
            var pw = document.getElementById("pw").value;
            var cpw = document.getElementById("cpw").value;
            var email = document.getElementById("email").value;
            var fname = document.getElementById("fname").value;
            var lname = document.getElementById("lname").value;
            
            if(fname == null || fname == ""){
                para.innerHTML="Enter first name!";
                return false;
            }else if(fname.length > 40){
                para.innerHTML="First name is too long ("+fname.length+"/40 characters)!";
                return false;
            }
            
            else if(lname.length > 40){
                para.innerHTML="Last name is too long ("+lname.length+"/40 characters)!";
                return false;
            }else if(lname == null || lname == ""){
                para.innerHTML="Enter last name!";
                return false;
            }
            
            else if(email == null || email == ""){
                para.innerHTML="Enter email!";
                return false;
            }else if(email.length > 40){
                para.innerHTML="Email is too long ("+email.length+"/40 characters)!";
                return false;
            }
            
            else if(user == null || user == ""){
                para.innerHTML="Enter username!";
                return false;
            }else if(user.length > 20){
                para.innerHTML="Username is too long ("+user.length+"/20 characters)!";
                return false;
            }
            
            else if(pw == null || pw == ""){
                para.innerHTML="Enter password!";
                return false;
            }else if(pw.length > 20){
                para.innerHTML="Password is too long ("+pw.length+"/20 characters)!";
                return false;
            }else if(pw.length < 8){
                para.innerHTML="Password is too short! (Password must be at least 8 characters)!";
                return false;
            }
            else if(cpw == null || cpw == ""){
                para.innerHTML="Confirm password!";
                return false;
            }else if(pw != cpw){
                para.innerHTML="Passwords don't match!";
                return false;
            }else{
                document.getElementById("formi").submit();
            }
        }
    </script>    
</html>