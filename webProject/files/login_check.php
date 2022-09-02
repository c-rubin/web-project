<?php
    //start session
    session_start();

    

    if(!isset($_POST["user"]) || $_POST["user"]=="" || !isset($_POST["pw"]) || $_POST["pw"]=="")echo "<h1 class='errorHeader'>Please enter username and password!</h1><br><a class='Btn' href='login.php'>Return</a>";
    else{
        $user = $_POST["user"];
        $pw = $_POST["pw"];

        $DBuser = "root";
        $DBpw = "";
        $DBname = "web_project_2022";
        $DBconn = new mysqli('localhost',$DBuser,$DBpw,$DBname);//sql connection

        if ($DBconn->connect_error){
            die("Connection failed: " . $conn->connect_error);
        }else{
            echo "Connected successfully to the database";
            $sql = "SELECT * FROM users WHERE username = '".$user."' AND password = '".$pw."';";
            try{
                $result = $DBconn -> query($sql);
                if($result->num_rows>0){//user exists
                    $row = $result->fetch_assoc();
                    $_SESSION["username"] = $user;
                    $_SESSION["password"] = $pw;
                    $_SESSION["sql"] = $row;
                    $_SESSION["photo"] = $row["photo"];

                    /*$logFile = fopen("logins.txt","r") or die("Unable to open file!");
                    $num = (int)fgets($logFile);
                    fclose($logFile);

                    $logFile = fopen("logins.txt","w") or die("Unable to open file!");
                    fwrite($logFile,$num+1);
                    fclose($logFile);*/

                    header("Location: homepage.php");
                    exit();
                }else{
                    echo "<h1 class='errorHeader'>This user doesn't exist!</h1><br><a class='Btn' href='login.php'>Return</a>";
                }
            }catch(Exception $ex){
                echo "<h1 class='errorHeader'>Error: ".$ex->getMessage()."</h1><br><a class='Btn' href='login.php'>Return</a>";
            }
        }
    }
?>

