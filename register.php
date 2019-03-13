<!DOCTYPE html>
<html lang="en">

	<head>
		<meta charset="utf-8">
		<title>Welcome to our dating site</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<meta name="description" content="" />
		<!-- css -->
		<link href="css/bootstrap.min.css" rel="stylesheet" />
		<link href="css/fancybox/jquery.fancybox.css" rel="stylesheet">
		<link href="css/jcarousel.css" rel="stylesheet" />
		<link href="css/flexslider.css" rel="stylesheet" />
		<link href="css/style.css" rel="stylesheet" />
		<link rel="stylesheet" href="css/font-awesome.css">
		<link rel="stylesheet" type="text/css" href="css/campus/normalize.css" />
		<link rel="stylesheet" type="text/css" href="css/campus/style.css" />
		<script src="js/campus/modernizr-custom.js"></script>
		<script src="js/jquery.js "></script>
		<link href="skins/default.css" rel="stylesheet" />
	</head>

    <body>
        <div id="wrapper">
        <!-- start header loaded from javascript at bottom of page-->
            <section id="content">
                <div class="container">
                    <div class="row">
                    </div>
                </div>
            </section>
            <section id="content">
                <!-- start slider -->
                <div class="container">
<?PHP




	$servername = "hive.csis.ul.ie";
	$username = "16231309";
	$password = "YIrCwd2p";
	$database = "group06";
	$interestsCheckboxes = "";
	//try load all the interests from the database for the user to choose from
	try{
		//use PDO to create DB connection.
		$connection = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
		$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		//Retrieve all the interests from the table
		$query = $connection->prepare("SELECT * FROM availableinterests");
        $query->execute();
		//FEtch only one record(FETCH_ASSOC will return only one item. use )
        $allInterests = $query->fetchAll(PDO::FETCH_ASSOC);
		//create same way as the search table
		foreach ($allInterests as $interests) {
				$interestsCheckboxes .=   "<div class='form-check form-check-inline'>"
										.	"<input type='checkbox' class='form-check-input' name='checkBoxes[]' id='{$interests["InterestID"]}' value='{$interests["InterestID"]}'>"
										.	"<label class='form-check-label' for='{$interests["InterestID"]}'>{$interests['InterestName']}</label>"
										. "</div>";
			}
		
	}catch (PDOException $e){
		$interestsCheckboxes = "<p>No interests in DB</p>";
	}


if(isset($_POST['submit']))

{
	try{



		//use PDO to create DB connection.
		$connection = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
		$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		//Get the posted variables from the form for the user table




		$username = $_POST['Handle'];
		$name = $_POST['Firstname'];
		$surname = $_POST['Surname'];
	        $email = $_POST['Email']; 
            	$pwd = $_POST['Password'];
		
		//using prepared statement to stop SQL injection and insert data into the User table
		$stmt = $connection->prepare("INSERT INTO user (Handle, Firstname, Surname, Email, Password)
		VALUES (:Handle, :Firstname, :Surname, :Email, :Password)");
		$stmt->bindParam(':Handle', $username);
		$stmt->bindParam(':Firstname', $name);
		$stmt->bindParam(':Surname', $surname);
		$stmt->bindParam(':Email', $email);
		$stmt->bindParam(':Password', $pwd);
		
		// do the insert into the User table
		$stmt->execute();
		
		//Get the rest of the posted variables for the profile table
		$age = $_POST['Age'];
		$seeking = $_POST['Seeking'];
		$gender = $_POST['Gender'];
		$smoker = $_POST['Smoker'];
		$drinker = $_POST['Drinker'];
		$description = $_POST['Description'];
		
		//Get the ID of the newly added user from the User table to use to add in profile table
		$newInsertID = $connection->lastInsertId();
		
		//create new statement for the 
		$stmt1 = $connection->prepare("INSERT INTO profile (UserID, Age, Gender, Seeking, Smoker, Drinker, Description)
		VALUES (:UserID, :Age, :Gender, :Seeking, :Smoker, :Drinker, :Description)");
		$stmt1->bindParam(':UserID', $newInsertID);
		$stmt1->bindParam(':Age', $age);
		$stmt1->bindParam(':Gender', $gender);
		$stmt1->bindParam(':Seeking', $seeking);
		$stmt1->bindParam(':Smoker', $smoker);
		$stmt1->bindParam(':Drinker', $drinker);
		$stmt1->bindParam(':Description', $description);		
		
		//Do the second half of the insert into the profile table.
		$stmt1->execute();
		
		//Add the registering users interests to the database table.
		if(!empty($_POST['checkBoxes'])) {
			foreach($_POST['checkBoxes'] as $check) {
					$stmt2 = $connection->prepare("INSERT INTO `interests`(`UserID`, `InterestID`) VALUES (:UserID,:interstID)");
					$stmt2->bindParam(':UserID', $newInsertID);
					$stmt2->bindParam(':interstID', $check);	
					
					//Do the insert of the userID and InterestID.
					$stmt2->execute();
			}
		}
		
		
		//Show a message to the user they are added correctly
		echo "Welcom $username to iHeartYou.com, Please <a href='Login.php'>Login</a> to view all the site has to offer!";
			
	}
	catch(PDOException $e){
		// catch and problems with the insert and show why it fails.
		echo "Connection failed: " . $e->getMessage();
	}    
}else{
	//normal text when user is on the page and form is not submitted.
    echo "<p></p>";
}
?>
                    <div class="row">
                        <form class="form-horizontal" action="<?php echo $_SERVER['PHP_SELF']; ?>" method='post'>
                            <fieldset>
                                <legend>Create Profile</legend>
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="Name (Full name)">Username</label>
                                    <div class="col-md-4">
                                        <div class="input-group">
                                            <input id="Handle" name="Handle" type="text" placeholder="Name (Full name)" class="form-control">
                                        </div>

                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="Date Of Birth">Firstname</label>
                                    <div class="col-md-4">
                                        <div class="input-group">
                                            <input id="Firstname" name="Firstname" type="text" placeholder="Firstname" class="form-control input-md">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="Surname">Surname</label>
                                    <div class="col-md-4">
                                        <div class="input-group">
                                            <input id="Surname" name="Surname" type="text" placeholder="Surname" class="form-control input-md">
                                        </div>

                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="Mother">Password</label>
                                    <div class="col-md-4">
                                        <div class="input-group">
                                            <input id="Password" name="Password" type="password" class="form-control input-md">
                                        </div>
                                    </div>
                                </div>
								<hr/>
								<div class="form-group">
                                    <label class="col-md-4 control-label" for="Age">Age</label>
                                    <div class="col-md-4">
                                        <div class="input-group">
                                            <input id="Age" name="Age" type="text" placeholder="Age" class="form-control input-md">
                                        </div>

                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="Gender">Gender</label>
                                    <div class="col-md-4">
                                        <label class="radio-inline" for="Gender-0">
                                            <input type="radio" name="Gender" id="Gender-0" value="Male" checked="checked">
                                            Male
                                        </label>
                                        <label class="radio-inline" for="Gender-1">
                                            <input type="radio" name="Gender" id="Gender-1" value="Female">
                                            Female
                                        </label>
										<label class="radio-inline" for="Gender-1">
                                            <input type="radio" name="Gender" id="Gender-2" value="other">
                                            Other
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="Seeking">Seeking</label>
                                    <div class="col-md-4">
                                        <label class="radio-inline" for="Seeking-0">
                                            <input type="radio" name="Seeking" id="Seeking-0" value="Male" checked="checked">
                                            Male
                                        </label>
                                        <label class="radio-inline" for="Seeking-1">
                                            <input type="radio" name="Seeking" id="Seeking-1" value="Female">
                                            Female
                                        </label>
										<label class="radio-inline" for="Seeking-1">
                                            <input type="radio" name="Seeking" id="Seeking-2" value="other">
                                            Other
                                        </label>
                                    </div>
                                </div>
                                <div>
                                <form>
                                    Hobbies <br>
                                    <input type="checkbox" name="vehicle1" value="Bike"> Surfing
                                    <input type="checkbox" name="vehicle2" value="Car"> Snowboarding <br>
                                    <input type="checkbox" name="vehicle1" value="Bike"> Skiing
                                    <input type="checkbox" name="vehicle2" value="Car"> Music <br>
                                    <input type="checkbox" name="vehicle1" value="Bike"> Kayak
                                    <input type="checkbox" name="vehicle2" value="Car"> Montain bike <br>
                                    <input type="checkbox" name="vehicle1" value="Bike"> Drawing
                                    <input type="checkbox" name="vehicle2" value="Car"> Tea appreciation <br>

                                    </div>
                                </form>

                                <div>
                                <form>
                                    Interests <br>
                                    <input type="checkbox" name="vehicle1" value="Bike"> Music
                                    <input type="checkbox" name="vehicle2" value="Car"> Books <br>
                                    <input type="checkbox" name="vehicle1" value="Bike"> Art
                                    <input type="checkbox" name="vehicle2" value="Car"> Architecture <br>
                                    <input type="checkbox" name="vehicle1" value="Bike"> Design
                                    <input type="checkbox" name="vehicle2" value="Car"> Movies <br>
                                    <input type="checkbox" name="vehicle1" value="Bike"> Shows
                                    <input type="checkbox" name="vehicle2" value="Car"> Tech <br>

                                    </div>
                                </form>

                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="Email">Email</label>
                                    <div class="col-md-4">
                                        <div class="input-group">
                                            <input id="Email" name="Email" type="text" placeholder="Email" class="form-control input-md">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="Description">Description</label>
                                    <div class="col-md-4">
                                        <textarea class="form-control" rows="10" id="Description" name="Description">Ready to tell us about yourself?</textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label"></label>
                                    <div class="col-md-4">
                                        <input type="submit" class="btn btn-submit" name="submit" value="Submit">
                                
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </section>
        </div>
        <footer id='iHeartFooter'></footer>
        <a href="#" class="scrollup"><i class="fa fa-angle-up active"></i></a>
        <!-- Placed at the end of the document so the pages load faster
        <script src="js/jquery.js"></script>
    -->
        <script src="js/jquery.easing.1.3.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/jquery.fancybox.pack.js"></script>
        <script src="js/jquery.fancybox-media.js"></script>
        <script src="js/google-code-prettify/prettify.js"></script>
        <script src="js/portfolio/jquery.quicksand.js"></script>
        <script src="js/portfolio/setting.js"></script>
        <script src="js/jquery.flexslider.js"></script>
        <script src="js/animate.js"></script>
        <script src="js/custom.js"></script>
        <script src="js/campus/modernizr-custom.js"></script>
        <script src="js/campus/classie.js"></script>
        <script src="js/campus/list.min.js"></script>
        <script src="js/campus/main.js"></script>
        <script>
            $(function () {
                $("#headerContent").load("header.php");
                $("#iHeartFooter").load("footer.php");
            });
        </script>
    </body>

</html>