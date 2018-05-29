<?php
/**
 * Author: Saeed Ahmed - w16024782
 * Version: 29/04/2017
 * Description: The Log in script, contains functionality that logs in the user, establishes $_SESSION variables needed to run the site and records the login datetime.
 */

//Establish session, include dbconnection details and set the timezone.
session_start();
include '../other/dbconnect.php';
date_default_timezone_set('Europe/London');

//if the user had clicked the log in button from the log in page
if(isset($_POST['loginsubmit'])){
	
	//store the posted values in local variables, making sure to strip tags for security.
	$memberemail = htmlentities(strip_tags(trim($_POST["loginemail"])));
	$memberpassword = htmlentities(strip_tags(trim($_POST["loginpassword"])));
	
	//get date and store in datetime format
	$date = date_create('200-01-01');
	$now = date("Y-m-d h:i:s");
	
	//attempt to run a query against the database to find user
	try{
		//Establish PDO connection
		$conn = new PDO('mysql:host='.$hostname.';dbname='.$databaseName.';charset=utf8mb4', $username,$password, array(PDO::ATTR_EMULATE_PREPARES => true,PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		$query=$conn->prepare("SELECT * FROM members WHERE email =:email");
		$query->bindParam(":email", $memberemail);
		$query->execute();
		$row = $query->fetch(PDO::FETCH_ASSOC);
		
		//if the user is found, get their details, including encrypted password
		$memberName = $row['name'];
		$memberID = $row['memberID'];
		$hashed = $row['password'];
		
		//close connection
    $conn = null;
		}
	//if the query fails, display an error message
	catch(PDOException $e) {
			echo "Error: " . $e->getMessage();
	}
	
	try{
		//Establish PDO connection
		$conn = new PDO('mysql:host='.$hostname.';dbname='.$databaseName.';charset=utf8mb4', $username,$password, array(PDO::ATTR_EMULATE_PREPARES => true,PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		//if the password entered in form matches the one stored in the database
		if (password_verify($memberpassword, $hashed)){
			//create $_SESSION variables containing user's details such as ID, name, and set `logged in` to true
			$_SESSION['email'] = $memberemail;
			$_SESSION['loggedin'] = true;
			$_SESSION['memberID'] = $memberID;
			$_SESSION['memberName'] = $memberName;
			
			//Run a query to update the user's lastlogin datetime
			$query = "UPDATE members SET lastlogin = '$now' WHERE memberID = '$memberID'";

			$stmt = $stmt = $conn->prepare($query);
			$stmt->execute();
			
			//direct user to the homepage
			header("Location: ../index.php");
			exit();
  	}
		//if log in fails
		else{
			//Store error in $_SESSION variable
			$_SESSION['loginErrors'] = "<br>Username or password is incorrect.";
			//direct user back to login page
			header("Location: ../login.php");
			exit();
		}
		//close connection
    $conn = null;
	}
	//display error message if the query fails
	catch(PDOException $e) {
		echo "Error, please try again or contact the administrator.";
	}
}
//if user did not click on the login button, display an error message
else{
  echo 'Error, please go back to <a href="../login.php">Log in</a> and try again.';
}
//close connection
$conn = null;