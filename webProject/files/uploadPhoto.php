<?php
    session_start();
    if(!isset($_SESSION["username"]) || $_SESSION["username"]=="" || !isset($_SESSION["password"]) || $_SESSION["password"]==""){
        header("Location: login.php?error=unauthorised");
		exit();
    }
    if(!isset($_GET["visit"]) || $_GET["visit"]!="yes"){
        $imgDir = uploadImg();
        if($imgDir !==0 && $imgDir !=""){
            $sql = "UPDATE users set photo='".$imgDir."' WHERE username='".$_SESSION["username"]."';";
                $DBuser = "root";
                $DBpw = "";
                $DBname = "web_project_2022";
                $DBconn = new mysqli('localhost',$DBuser,$DBpw,$DBname);//sql connection

                if ($DBconn->connect_error){
                    die("Connection failed: " . $conn->connect_error);
                }else{
                    if($DBconn->query($sql)===true){
                        $_SESSION["photo"] = $imgDir;
                        header("Location: update.php");
                        exit();
                    }
                    else{
                        header("Location: update.php?UpdateError=Error");
                        exit();
                    }
                }
            }else{
                header("Location: update.php?UpdateError=Error");
                exit();
            }
        }
    

    function uploadImg(){
		$target_dir = "uploads/profilePics/";
		$name = $_FILES["fileToUpload"]["name"];
		$extt = explode(".", $name);
		$ext = end($extt);
		//echo $ext;
		$datee = date("d-m-y--h-i-s");
		$target_file = $target_dir .$_SESSION["username"]."-".$datee.".".$ext;
		$uploadOk = 1;
		$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

		try{

			// Check if image file is a actual image or fake image
			if(isset($_POST["submit"])) {
			$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
			if($check !== false) {
				echo "File is an image - " . $check["mime"] . ".";
				$uploadOk = 1;
			} else {
				echo "File is not an image.";
				$uploadOk = 0;
			}
			}

			// Check if file already exists
			if (file_exists($target_file)) {
			echo "Sorry, file already exists.";
			$uploadOk = 0;
			}

			// Check file size
			if ($_FILES["fileToUpload"]["size"] > 500000) {
			echo "Sorry, your file is too large.";
			$uploadOk = 0;
			}

			// Allow certain file formats
			if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" ) {
			echo "Sorry, only JPG, JPEG & PNG files are allowed.";
			$uploadOk = 0;
			}

			// Check if $uploadOk is set to 0 by an error
			if ($uploadOk == 0) {
			echo "Sorry, your file was not uploaded.";
			// if everything is ok, try to upload file
			} else {
				if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
					echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";
					return $target_file;
				} else {
					echo "Sorry, there was an error uploading your file.";
					return 0;
				}
			}
		}catch(Exception $ex){
			echo "<h1 class='errorHeader'>Error: ".$ex->getMessage()."</h1><br><a class='Btn' href='registration.php'>Return</a>";
			return 0;
		}
	}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Change profile picture</title>
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
        <h1>Change profile picture</h1>
        <form action="uploadPhoto.php" method="post" enctype="multipart/form-data">
            <label for="photo">Profile picture</label>
            <input type="file" id="fileToUpload" name="fileToUpload" ><br>
            <input type="submit" value="Set profile picture">
            <a href="update.php?First=1"><input type="button" value="Back"></a>
            
        </form>

    </body>
</html>