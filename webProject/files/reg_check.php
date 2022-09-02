<?php
	session_start();

	$fname = $_POST["fname"];
    $lname = $_POST["lname"];
    $email = $_POST["email"];
    $user = $_POST["user"];
    $pw = $_POST["pw"];
    $cpw = $_POST["cpw"];

	/*if(!isset($_POST["photo"]) || $_POST["photo"]==""){
		$photo_name = "NULL";
	}else $photo_name = "uploads/".$_POST["photo"];*/
	

	if(!isset($fname))echo "<h1 class='errorHeader'>No first name has been given!</h1><br><a class='Btn' href='registration.php'>Return</a>";
	else if ($fname=="")echo "<h1 class='errorHeader'>No first name has been given!</h1><br><a class='Btn' href='registration.php'>Return</a>";
		
	else if(!isset($lname))echo "<h1 class='errorHeader'>No last name has been given!</h1><br><a class='Btn' href='registration.php'>Return</a>";
	else if($lname=="")echo "<h1 class='errorHeader'>No last name has been given!</h1><br><a class='Btn' href='registration.php'>Return</a>";
		
	else if(!isset($user))echo "<h1 class='errorHeader'>No username has been given!</h1><br><a class='Btn' href='registration.php'>Return</a>";
	else if ($user=="")echo "<h1 class='errorHeader'>No username has been given!</h1><br><a class='Btn' href='registration.php'>Return</a>";
	
	else if(!isset($email))echo "<h1 class='errorHeader'>No email has been given!</h1><br><a class='Btn' href='registration.php'>Return</a>";
	else if ($email=="")echo "<h1 class='errorHeader'>No email has been given!</h1><br><a class='Btn' href='registration.php'>Return</a>";
	else if (!filter_var($email,FILTER_VALIDATE_EMAIL))echo "<h1 class='errorHeader'>Enter a valid email!</h1><br><a class='Btn' href='registration.php'>Return</a>";
		
	else if(!isset($pw))echo "<h1 class='errorHeader'>No password has been given!</h1><br><a class='Btn' href='registration.php'>Return</a>";
	else if ($pw=="")echo "<h1 class='errorHeader'>No password has been given!</h1><br><a class='Btn' href='registration.php'>Return</a>";
	else if (strlen($pw)<8)echo "<h1 class='errorHeader'>Password must be at least 8 characters long!</h1><br><a class='Btn' href='registration.php'>Return</a>";
		
	else if(!isset($cpw))echo "<h1 class='errorHeader'>Please confirm your password!</h1><br><a class='Btn' href='registration.php'>Return</a>";
	else if ($cpw=="")echo "<h1 class='errorHeader'>Please confirm your password!</h1><br><a class='Btn' href='registration.php'>Return</a>";
	else if ($cpw!=$pw)echo "<h1 class='errorHeader'>Passwords do not match!</h1><br><a class='Btn' href='registration.php'>Return</a>";
		
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
            $sql = "SELECT * FROM users WHERE username = '".$user."';";
            try{
                $result = $DBconn -> query($sql);
                if($result->num_rows==0){//username not already used
					$sql = "SELECT * FROM users WHERE email = '".$_POST["email"]."';";
					$result = $DBconn -> query($sql);
                    if($result->num_rows==0){//email not already used
						$imgDir = uploadImg();
						//echo "imgDir = ".$imgDir;
						if($imgDir!==0 && $imgDir!="")$sql = "INSERT INTO users VALUES (NULL, '".$fname."', '".$lname."', '".$email."', '".$user."', '".$pw."', '".$imgDir."');";
						else $sql = "INSERT INTO users VALUES (NULL, '".$fname."', '".$lname."', '".$email."', '".$user."', '".$pw."', 'nullProfilePic.png');";
						if($DBconn -> query($sql) === TRUE){
							$_SESSION["username"]=$user;
							$_SESSION["password"]=$pw;
							if($imgDir!==0 && $imgDir!="")$_SESSION["photo"] = $imgDir;
							else $_SESSION["photo"]="nullProfilePic.png";

							$sql = "SELECT * FROM users WHERE username = '".$user."' AND password = '".$pw."';";
							$result = $DBconn -> query($sql);
							$row = $result->fetch_assoc();
							$_SESSION["sql"]=$row;

							/*$regFile = fopen("registrations.txt","r") or die("Unable to open file!");
							$num = (int)fgets($regFile);
							fclose($regFile);

							$regFile = fopen("registrations.txt","w") or die("Unable to open file!");
							fwrite($regFile,$num+1);
							fclose($regFile);*/

							header("Location: homepage.php");
                    		exit();
						}
						//echo "<h1 class='errorHeader'>ERROR!</h1><br><a class='Btn' href='registration.php'>Return</a>";

						//header("Location: homepage.php");
						//exit();
					}else{
						echo "<h1 class='errorHeader'>There already exists an account with this email!</h1><br><a class='Btn' href='registration.php'>Return</a>";
					}
                }else{
                    echo "<h1 class='errorHeader'>There already exists an account with this username!</h1><br><a class='Btn' href='registration.php'>Return</a>";
                }
            }catch(Exception $ex){
                echo "<h1 class='errorHeader'>Error: ".$ex->getMessage()."</h1><br><a class='Btn' href='registration.php'>Return</a>";
            }
        }
    }
	function uploadImg(){
		$target_dir = "uploads/profilePics/";
		$name = $_FILES["fileToUpload"]["name"];
		$extt = explode(".", $name);
		$ext = end($extt);
		//echo $ext;
		$datee = date("d-m-y--h-i-s");
		$target_file = $target_dir .$_POST["user"]."-".$datee.".".$ext;
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
	/*function photoSaver($user){
		$target_dir = "uploads/";
		$target_file = $target_dir . $user; //. basename($_FILES["fileToUpload"]["name"]);
		$uploadOk = 1;
		$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));//jpg,png, etc

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
		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
			echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
			$uploadOk = 0;
		}

		// Check if $uploadOk is set to 0 by an error
		if ($uploadOk == 0) {
			echo "Sorry, your file was not uploaded.";
			// if everything is ok, try to upload file
		} else {
			if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
				echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";
			} else {
				echo "Sorry, there was an error uploading your file.";
			}
		}
	}*/




?>